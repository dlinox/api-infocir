<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Repositories;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\Order;
use App\Models\Dairy\OrderItem;
use App\Models\Dairy\ProductPresentation;
use App\Models\Dairy\StockMovement;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function dataTable($request, ?int $plantId = null)
    {
        $query = Order::query()->with(['plant'])->withCount('items');

        if ($plantId !== null) {
            $query->where('plant_id', $plantId);
        }

        if (!empty($request->filters['status'])) {
            $query->where('status', $request->filters['status']);
        }

        if (!empty($request->filters['plant_id'])) {
            $query->where('plant_id', $request->filters['plant_id']);
        }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        $handledFilters = ['status', 'plant_id'];
        $remainingFilters = array_diff_key($request->filters ?? [], array_flip($handledFilters));
        $request->merge(['filters' => $remainingFilters]);

        return $query->dataTable($request);
    }

    public function findById(string $id, ?int $plantId = null)
    {
        $query = Order::with(['plant', 'items']);
        if ($plantId !== null) {
            $query->where('plant_id', $plantId);
        }
        return $query->findOrFail($id);
    }

    public function updateStatus(string $id, string $status, ?int $plantId = null)
    {
        return DB::transaction(function () use ($id, $status, $plantId) {
            $order = $this->findById($id, $plantId)->load('items');
            $stockApplied = $this->syncStock($order, $status);
            $closedAt = $status === 'closed' ? ($order->closed_at ?? now()) : null;
            $order->update(['status' => $status, 'stock_applied' => $stockApplied, 'closed_at' => $closedAt]);
            return $order->fresh(['plant', 'items']);
        });
    }

    /**
     * Crea un pedido por cada planta involucrada en el carrito.
     * @return Order[]
     */
    public function createForCart(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $groups = [];

            foreach ($data['items'] as $line) {
                $presentation = ProductPresentation::with('plantProduct.product')->find($line['presentation_id']);
                if (!$presentation || !$presentation->plantProduct) {
                    continue;
                }
                $plantId = (int) $presentation->plantProduct->plant_id;
                $unitPrice = $this->getCurrentPrice((int) $presentation->id);
                $quantity = (int) $line['quantity'];

                $groups[$plantId][] = [
                    'presentation_id'   => $presentation->id,
                    'product_name'      => $presentation->plantProduct->product?->name ?? $presentation->name,
                    'presentation_name' => $presentation->name,
                    'unit_price'        => $unitPrice,
                    'quantity'          => $quantity,
                    'subtotal'          => $unitPrice * $quantity,
                ];
            }

            $orders = [];
            foreach ($groups as $plantId => $lines) {
                $subtotal = array_sum(array_column($lines, 'subtotal'));

                $order = Order::create([
                    'code'              => 'TMP-' . uniqid(),
                    'status'            => 'pending',
                    'customer_name'     => $data['customer_name'],
                    'customer_phone'    => $data['customer_phone'],
                    'customer_email'    => $data['customer_email'] ?? null,
                    'customer_document' => $data['customer_document'] ?? null,
                    'address'           => $data['address'] ?? null,
                    'district'          => $data['district'] ?? null,
                    'city'              => $data['city'] ?? null,
                    'reference'         => $data['reference'] ?? null,
                    'inquiry'           => $data['inquiry'] ?? null,
                    'plant_id'          => $plantId,
                    'subtotal'          => $subtotal,
                    'total'             => $subtotal,
                ]);

                $order->update(['code' => 'VL-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT)]);
                $order->items()->createMany($lines);
                $orders[] = $order->fresh(['items', 'plant']);
            }

            return $orders;
        });
    }

    /* ─── Edición de ítems ─────────────────────────────────────── */

    public function addItem(string $orderId, int $presentationId, int $quantity, ?int $plantId = null): Order
    {
        return DB::transaction(function () use ($orderId, $presentationId, $quantity, $plantId) {
            $order = $this->findById($orderId, $plantId);
            $this->assertEditable($order);

            $presentation = ProductPresentation::with('plantProduct.product')->findOrFail($presentationId);
            if ((int) $presentation->plantProduct?->plant_id !== (int) $order->plant_id) {
                throw new ApiException('La presentación no pertenece a la planta del pedido.', 422);
            }

            $existing = $order->items()->where('presentation_id', $presentationId)->first();
            $unitPrice = $this->getCurrentPrice($presentationId);

            if ($existing) {
                $newQty = $existing->quantity + $quantity;
                $existing->update([
                    'quantity' => $newQty,
                    'subtotal' => $existing->unit_price * $newQty,
                ]);
            } else {
                $order->items()->create([
                    'presentation_id'   => $presentation->id,
                    'product_name'      => $presentation->plantProduct->product?->name ?? $presentation->name,
                    'presentation_name' => $presentation->name,
                    'unit_price'        => $unitPrice,
                    'quantity'          => $quantity,
                    'subtotal'          => $unitPrice * $quantity,
                ]);
            }

            return $this->recalc($order);
        });
    }

    public function updateItemQuantity(string $orderId, int $itemId, int $quantity, ?int $plantId = null): Order
    {
        return DB::transaction(function () use ($orderId, $itemId, $quantity, $plantId) {
            $order = $this->findById($orderId, $plantId);
            $this->assertEditable($order);

            $item = $order->items()->findOrFail($itemId);
            if ($quantity < 1) {
                $item->delete();
            } else {
                $item->update(['quantity' => $quantity, 'subtotal' => $item->unit_price * $quantity]);
            }

            return $this->recalc($order);
        });
    }

    public function removeItem(string $orderId, int $itemId, ?int $plantId = null): Order
    {
        return DB::transaction(function () use ($orderId, $itemId, $plantId) {
            $order = $this->findById($orderId, $plantId);
            $this->assertEditable($order);
            $order->items()->where('id', $itemId)->delete();
            return $this->recalc($order);
        });
    }

    /* ─── Helpers ──────────────────────────────────────────────── */

    private function assertEditable(Order $order): void
    {
        if ($order->stock_applied || $order->status === 'closed') {
            throw new ApiException('No se puede editar un pedido cerrado. Reábrelo para modificarlo.', 422);
        }
    }

    private function recalc(Order $order): Order
    {
        $order->load('items');
        $subtotal = (float) $order->items->sum('subtotal');
        $order->update(['subtotal' => $subtotal, 'total' => $subtotal]);
        return $order->fresh(['plant', 'items']);
    }

    /**
     * Descuenta o repone stock según el cambio de estado. Devuelve el nuevo valor de stock_applied.
     */
    private function syncStock(Order $order, string $newStatus): bool
    {
        $isClosed = $order->stock_applied;
        $willClose = $newStatus === 'closed';

        if ($willClose && !$isClosed) {
            foreach ($order->items as $item) {
                if ($item->presentation_id) {
                    StockMovement::create([
                        'presentation_id' => $item->presentation_id,
                        'plant_id'        => $order->plant_id,
                        'type'            => 'exit',
                        'quantity'        => $item->quantity,
                        'reason'          => 'Venta — Pedido ' . $order->code,
                    ]);
                }
            }
            return true;
        }

        if (!$willClose && $isClosed) {
            foreach ($order->items as $item) {
                if ($item->presentation_id) {
                    StockMovement::create([
                        'presentation_id' => $item->presentation_id,
                        'plant_id'        => $order->plant_id,
                        'type'            => 'entry',
                        'quantity'        => $item->quantity,
                        'reason'          => 'Reposición — Pedido reabierto ' . $order->code,
                    ]);
                }
            }
            return false;
        }

        return $isClosed;
    }

    /**
     * Presentaciones disponibles para agregar al pedido (de la planta del pedido).
     */
    public function plantPresentations(string $orderId, ?int $plantId = null): array
    {
        $order = $this->findById($orderId, $plantId);

        $presentations = ProductPresentation::query()
            ->where('is_active', true)
            ->whereHas('plantProduct', fn ($q) => $q->where('plant_id', $order->plant_id)->where('is_active', true))
            ->with(['plantProduct.product', 'unitMeasure'])
            ->orderBy('name')
            ->get();

        return $presentations->map(fn ($p) => [
            'value'     => $p->id,
            'title'     => trim(($p->plantProduct->product?->name ?? '') . ' — ' . $p->name),
            'price'     => $this->getCurrentPrice((int) $p->id),
            'available' => StockMovement::available((int) $p->id),
        ])->all();
    }

    public function getCurrentPrice(int $presentationId): float
    {
        $today = now()->toDateString();

        $price = \App\Models\Dairy\ProductPrice::where('presentation_id', $presentationId)
            ->whereDate('effective_from', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->whereNull('effective_until')->orWhereDate('effective_until', '>=', $today);
            })
            ->orderBy('effective_from', 'desc')
            ->first();

        return (float) ($price->price ?? 0);
    }
}

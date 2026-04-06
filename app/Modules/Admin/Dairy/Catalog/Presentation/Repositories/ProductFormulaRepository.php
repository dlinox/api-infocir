<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Repositories;

use App\Models\Dairy\ProductFormula;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductFormulaRepository
{
    public function getByPresentation(int $presentationId, ?int $version = null): array
    {
        if ($version) {
            $items = ProductFormula::where('presentation_id', $presentationId)
                ->where('version', $version)
                ->with(['supply.unitMeasure'])
                ->get();
            $isCurrent = $items->first()?->is_current ?? false;
        } else {
            $items = ProductFormula::where('presentation_id', $presentationId)
                ->where('is_current', true)
                ->with(['supply.unitMeasure'])
                ->get();
            $version = $items->first()?->version ?? 0;
            $isCurrent = true;
        }

        $totalCost = $items->sum(fn($item) => $item->quantity * $item->unit_price);

        $previousItems = collect();
        if ($version > 1) {
            $previousItems = ProductFormula::where('presentation_id', $presentationId)
                ->where('version', $version - 1)
                ->get()
                ->keyBy('supply_id');
        }

        $currentSupplyIds = $items->pluck('supply_id')->toArray();

        $resultItems = $items->map(function ($item) use ($previousItems, $totalCost) {
            $subtotal = $item->quantity * $item->unit_price;
            $change = null;

            if ($previousItems->isNotEmpty()) {
                $prev = $previousItems->get($item->supply_id);
                if (!$prev) {
                    $change = 'new';
                } elseif (
                    bccomp((string) $prev->quantity, (string) $item->quantity, 3) !== 0 ||
                    bccomp((string) $prev->unit_price, (string) $item->unit_price, 3) !== 0
                ) {
                    $change = 'modified';
                }
            }

            $item->subtotal = round($subtotal, 3);
            $item->percentage = $totalCost > 0 ? round(($subtotal / $totalCost) * 100, 1) : 0;
            $item->change = $change;

            return $item;
        });

        if ($previousItems->isNotEmpty()) {
            $removedItems = $previousItems->filter(fn($prev) => !in_array($prev->supply_id, $currentSupplyIds));
            foreach ($removedItems as $prev) {
                $prev->load('supply.unitMeasure');
                $prevSubtotal = $prev->quantity * $prev->unit_price;
                $prev->subtotal = round($prevSubtotal, 3);
                $prev->percentage = 0;
                $prev->change = 'removed';
                $prev->id = null;
                $resultItems->push($prev);
            }
        }

        return [
            'items' => $resultItems->values(),
            'version' => $version,
            'isCurrent' => $isCurrent,
        ];
    }

    public function getVersions(int $presentationId): array
    {
        $versions = DB::table('dairy_product_formulas')
            ->where('presentation_id', $presentationId)
            ->groupBy('version', 'is_current')
            ->orderBy('version')
            ->select([
                'version',
                'is_current',
                DB::raw('SUM(quantity * unit_price) as total_cost'),
                DB::raw('COUNT(*) as supply_count'),
                DB::raw('MAX(updated_at) as updated_at'),
            ])
            ->get();

        $result = [];
        $prevCost = null;

        foreach ($versions as $v) {
            $totalCost = round((float) $v->total_cost, 3);
            $delta = null;
            if ($prevCost !== null && $prevCost > 0) {
                $delta = round((($totalCost - $prevCost) / $prevCost) * 100, 1);
            }

            $result[] = [
                'version' => $v->version,
                'totalCost' => $totalCost,
                'supplyCount' => $v->supply_count,
                'isCurrent' => (bool) $v->is_current,
                'updatedAt' => $v->updated_at,
                'delta' => $delta,
            ];

            $prevCost = $totalCost;
        }

        return $result;
    }

    public function saveItem(array $data): ProductFormula
    {
        if (isset($data['id'])) {
            $item = ProductFormula::findOrFail($data['id']);

            if (!$item->is_current) {
                throw ValidationException::withMessages([
                    'id' => ['No se puede editar un item de una versión histórica.'],
                ]);
            }

            $item->update([
                'supply_id' => $data['supply_id'],
                'quantity' => $data['quantity'],
                'unit_price' => $data['unit_price'],
            ]);

            return $item;
        }

        $currentVersion = ProductFormula::where('presentation_id', $data['presentation_id'])
            ->where('is_current', true)
            ->max('version');

        $data['version'] = $currentVersion ?? 1;
        $data['is_current'] = true;

        return ProductFormula::create($data);
    }

    public function createVersion(int $presentationId): int
    {
        return DB::transaction(function () use ($presentationId) {
            $currentVersion = ProductFormula::where('presentation_id', $presentationId)
                ->where('is_current', true)
                ->max('version');

            if (!$currentVersion) {
                throw ValidationException::withMessages([
                    'presentation_id' => ['No existe una fórmula para crear una nueva versión.'],
                ]);
            }

            $currentItems = ProductFormula::where('presentation_id', $presentationId)
                ->where('version', $currentVersion)
                ->get();

            ProductFormula::where('presentation_id', $presentationId)
                ->where('version', $currentVersion)
                ->update(['is_current' => false]);

            $newVersion = $currentVersion + 1;

            foreach ($currentItems as $item) {
                ProductFormula::create([
                    'presentation_id' => $presentationId,
                    'supply_id' => $item->supply_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'version' => $newVersion,
                    'is_current' => true,
                ]);
            }

            return $newVersion;
        });
    }

    public function deleteItem(int $id): void
    {
        $item = ProductFormula::findOrFail($id);

        if (!$item->is_current) {
            throw ValidationException::withMessages([
                'id' => ['No se puede eliminar un item de una versión histórica.'],
            ]);
        }

        $item->delete();
    }
}

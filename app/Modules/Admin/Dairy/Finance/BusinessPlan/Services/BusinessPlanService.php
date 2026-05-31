<?php

namespace App\Modules\Admin\Dairy\Finance\BusinessPlan\Services;

use App\Models\Dairy\BusinessPlan;
use App\Models\Dairy\FixedAsset;
use App\Models\Dairy\InvestmentPlan;
use App\Models\Dairy\Plant;
use App\Models\Dairy\PreOperativeExpense;
use App\Models\Dairy\ProductPresentation;
use App\Models\Dairy\ProductPrice;
use App\Modules\Admin\Dairy\Finance\BusinessPlan\Support\BusinessPlanCalculator;

class BusinessPlanService
{
    public function __construct(
        private BusinessPlanCalculator $calculator
    ) {}

    public function getForPlant(int $plantId): array
    {
        $plant = Plant::with('entity')->findOrFail($plantId);
        $entityId = $plant->entity?->id;

        $stored = BusinessPlan::where('plant_id', $plantId)->first();
        $params = $stored->data['parametros'] ?? ['wacc' => 0.1, 'tasaCrecimiento' => 0.05, 'horizonte' => 12];
        $storedDemanda = $stored->data['demanda'] ?? [];

        // El Cuadro de Inversión NO es editable aquí: se arma con lo que ya cargó la planta
        // (activos fijos, gastos pre-operativos y capital de trabajo).
        $inversiones = $this->buildInversiones($entityId);

        // Productos del catálogo de la planta. Precio y costo vienen de dairy_product_prices.
        // Lo único editable es la demanda mensual (cantidades por mes).
        $productos = $this->buildProductos($plantId, $storedDemanda);

        $inputs = ['parametros' => $params, 'inversiones' => $inversiones, 'productos' => $productos];

        return [
            'inputs'   => $inputs,
            'computed' => $this->calculator->compute($inputs),
            'isDefault' => $stored === null,
        ];
    }

    public function saveForPlant(int $plantId, array $payload): array
    {
        $data = [
            'parametros' => $payload['parametros'] ?? ['wacc' => 0.1, 'tasaCrecimiento' => 0.05, 'horizonte' => 12],
            'demanda'    => $payload['demanda'] ?? [], // mapa presentationId => [12]
        ];
        BusinessPlan::updateOrCreate(['plant_id' => $plantId], ['data' => $data]);
        return $this->getForPlant($plantId);
    }

    /* ── Cuadro de Inversión desde datos reales ─────────────────── */

    private function buildInversiones(?int $entityId): array
    {
        return [
            'grupos' => [
                $this->grupoActivoFijo($entityId),
                $this->grupoPreOperativos($entityId),
                $this->grupoCapitalTrabajo($entityId),
            ],
        ];
    }

    private function grupoActivoFijo(?int $entityId): array
    {
        $assets = $entityId
            ? FixedAsset::where('entity_id', $entityId)->with('category')->get()
            : collect();
        return $this->agrupaPorCategoria('Activo Fijo', $assets, fn ($a) => [
            'rubro'     => $a->name ?? 'Activo',
            'valorUnit' => (float) $a->purchase_cost,
            'unidades'  => 1,
        ]);
    }

    private function grupoPreOperativos(?int $entityId): array
    {
        $items = $entityId
            ? PreOperativeExpense::where('entity_id', $entityId)->with('category')->get()
            : collect();
        return $this->agrupaPorCategoria('Gastos Pre-operativos', $items, fn ($e) => [
            'rubro'     => $e->name,
            'valorUnit' => (float) $e->amount,
            'unidades'  => 1,
        ]);
    }

    private function grupoCapitalTrabajo(?int $entityId): array
    {
        $items = collect();
        if ($entityId) {
            $plans = InvestmentPlan::where('entity_id', $entityId)
                ->where('plan_type', 'working_capital')
                ->with('items.category')
                ->get();
            $items = $plans->flatMap(fn ($p) => $p->items);
        }
        return $this->agrupaPorCategoria('Capital de Trabajo', $items, fn ($i) => [
            'rubro'     => $i->name,
            'valorUnit' => (float) $i->unit_value,
            'unidades'  => (float) $i->quantity,
        ]);
    }

    /**
     * Agrupa una colección por su categoría (sección) y mapea cada registro a un rubro.
     */
    private function agrupaPorCategoria(string $nombreGrupo, $rows, callable $toRubro): array
    {
        $secciones = [];
        foreach ($rows as $row) {
            $cat = $row->category?->name ?? 'Otros';
            if (!isset($secciones[$cat])) {
                $secciones[$cat] = ['nombre' => $cat, 'rubros' => []];
            }
            $secciones[$cat]['rubros'][] = $toRubro($row);
        }
        return ['nombre' => $nombreGrupo, 'secciones' => array_values($secciones)];
    }

    /* ── Productos / proyección ─────────────────────────────────── */

    private function buildProductos(int $plantId, array $storedDemanda): array
    {
        $presentations = ProductPresentation::query()
            ->where('is_active', true)
            ->whereHas('plantProduct', fn ($q) => $q->where('plant_id', $plantId)->where('is_active', true))
            ->with(['plantProduct.product'])
            ->orderBy('id')
            ->get();

        $today = now()->toDateString();
        $productos = [];
        foreach ($presentations as $p) {
            [$price, $cost] = $this->currentPriceCost((int) $p->id, $today);
            $demanda = $storedDemanda[(string) $p->id] ?? array_fill(0, 12, 0);
            $demanda = array_slice(array_map('floatval', array_pad((array) $demanda, 12, 0)), 0, 12);
            $productos[] = [
                'presentationId' => $p->id,
                'nombre'         => trim(($p->plantProduct->product?->name ?? '') . ' — ' . $p->name),
                'precioVenta'    => $price,
                'costoUnit'      => $cost,
                'capacidad'      => 0,
                'demanda'        => $demanda,
            ];
        }
        return $productos;
    }

    private function currentPriceCost(int $presentationId, string $today): array
    {
        $price = ProductPrice::where('presentation_id', $presentationId)
            ->whereDate('effective_from', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('effective_until')->orWhereDate('effective_until', '>=', $today);
            })
            ->orderBy('effective_from', 'desc')
            ->first();

        return [(float) ($price->price ?? 0), (float) ($price->cost ?? 0)];
    }
}

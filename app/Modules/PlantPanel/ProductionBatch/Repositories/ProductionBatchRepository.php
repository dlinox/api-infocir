<?php

namespace App\Modules\PlantPanel\ProductionBatch\Repositories;

use App\Models\Dairy\ProductionBatch;
use App\Models\Dairy\StockMovement;
use Illuminate\Support\Facades\DB;

class ProductionBatchRepository
{
    public function dataTable($request, int $plantId)
    {
        $query = ProductionBatch::query()
            ->leftJoin('dairy_product_presentations', 'dairy_product_presentations.id', '=', 'dairy_production_batches.presentation_id')
            ->where('dairy_production_batches.plant_id', $plantId)
            ->select([
                'dairy_production_batches.*',
                'dairy_product_presentations.id as presentation_id_alias',
                'dairy_product_presentations.name as presentation_name',
                'dairy_product_presentations.sku as presentation_sku',
            ]);

        if (empty($request->sortBy)) {
            $query->orderBy('dairy_production_batches.production_date', 'desc')
                  ->orderBy('dairy_production_batches.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByIdForPlant(int $id, int $plantId): ?ProductionBatch
    {
        return ProductionBatch::with('suppliers')
            ->where('id', $id)
            ->where('plant_id', $plantId)
            ->first();
    }

    public function createOrUpdate(array $data, int $plantId): ProductionBatch
    {
        $data['plant_id'] = $plantId;
        if (isset($data['quantity_liters_used']) && isset($data['quantity_kg']) && $data['quantity_liters_used'] > 0) {
            $data['yield_ratio'] = round(((float) $data['quantity_kg'] / (float) $data['quantity_liters_used']) * 100, 2);
        }

        $suppliers = $data['suppliers'] ?? [];
        unset($data['suppliers']);

        return DB::transaction(function () use ($data, $plantId, $suppliers) {
            if (!empty($data['id'])) {
                $batch = ProductionBatch::where('id', $data['id'])->where('plant_id', $plantId)->firstOrFail();
                $batch->update($data);
            } else {
                $batch = ProductionBatch::create($data);
            }

            $sync = collect($suppliers)
                ->mapWithKeys(fn ($s) => [
                    (int) $s['supplier_id'] => ['quantity_liters' => (float) $s['quantity_liters']],
                ])
                ->all();
            $batch->suppliers()->sync($sync);

            return $batch;
        });
    }

    public function delete(int $id, int $plantId): void
    {
        $batch = ProductionBatch::where('id', $id)->where('plant_id', $plantId)->firstOrFail();
        $batch->delete();
    }

    public function markReady(int $id, int $plantId): ProductionBatch
    {
        return DB::transaction(function () use ($id, $plantId) {
            $batch = ProductionBatch::with('presentation')
                ->where('id', $id)
                ->where('plant_id', $plantId)
                ->whereIn('status', ['in_production', 'maturing'])
                ->firstOrFail();

            if (!$batch->presentation_id) {
                abort(422, 'El lote no tiene una presentación asignada.');
            }

            $content = (float) ($batch->presentation->content ?? 0);
            $units   = $content > 0
                ? (int) floor((float) $batch->quantity_kg / $content)
                : (int) $batch->quantity_kg;

            StockMovement::create([
                'presentation_id' => $batch->presentation_id,
                'plant_id'        => $plantId,
                'type'            => 'entry',
                'quantity'        => $units,
                'batch_code'      => $batch->batch_code,
                'expiration_date' => $batch->maturation_end_date,
                'reason'          => 'Ingreso por producción — Lote ' . $batch->batch_code,
            ]);

            $batch->update(['status' => 'ready']);

            return $batch->fresh();
        });
    }
}

<?php

namespace App\Modules\PlantPanel\ProductionBatch\Repositories;

use App\Models\Dairy\Plant;
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

        return DB::transaction(function () use ($data, $plantId) {
            if (!empty($data['id'])) {
                $batch = ProductionBatch::where('id', $data['id'])->where('plant_id', $plantId)->firstOrFail();
                $batch->update($data);
            } else {
                $data['batch_code'] = $this->generateBatchCode($plantId, $data['production_date']);
                $batch = ProductionBatch::create($data);
            }

            return $batch;
        });
    }

    private function generateBatchCode(int $plantId, string $productionDate): string
    {
        $plant = Plant::findOrFail($plantId);
        $type  = $plant->type; // 'A', 'B' o 'C'
        $date  = str_replace('-', '', $productionDate); // YYYYMMDD

        $prefix = sprintf('%s%03d-%s-', $type, $plantId, $date);

        $last = ProductionBatch::where('batch_code', 'like', $prefix . '%')
            ->lockForUpdate()
            ->max('batch_code');

        $seq = $last ? ((int) substr($last, -3)) + 1 : 1;

        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    public function delete(int $id, int $plantId): void
    {
        $batch = ProductionBatch::where('id', $id)->where('plant_id', $plantId)->firstOrFail();
        $batch->delete();
    }

    public function markReady(int $id, int $plantId, ?int $finalQuantity = null, ?string $notes = null): ProductionBatch
    {
        return DB::transaction(function () use ($id, $plantId, $finalQuantity, $notes) {
            $batch = ProductionBatch::with('presentation')
                ->where('id', $id)
                ->where('plant_id', $plantId)
                ->whereIn('status', ['in_production', 'maturing'])
                ->firstOrFail();

            if (!$batch->presentation_id) {
                abort(422, 'El lote no tiene una presentación asignada.');
            }

            $update = ['status' => 'ready'];
            if ($finalQuantity !== null) $update['quantity_units'] = $finalQuantity;
            if ($notes) $update['observations'] = $notes;
            $batch->update($update);
            $batch->refresh();

            StockMovement::create([
                'presentation_id' => $batch->presentation_id,
                'plant_id'        => $plantId,
                'type'            => 'entry',
                'quantity'        => (int) $batch->quantity_units,
                'batch_code'      => $batch->batch_code,
                'expiration_date' => $batch->maturation_end_date,
                'reason'          => 'Ingreso por producción — Lote ' . $batch->batch_code,
            ]);

            return $batch->fresh();
        });
    }
}

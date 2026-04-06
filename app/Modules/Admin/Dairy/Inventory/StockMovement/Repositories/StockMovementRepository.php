<?php

namespace App\Modules\Admin\Dairy\Inventory\StockMovement\Repositories;

use App\Models\Dairy\StockMovement;
use Illuminate\Support\Facades\DB;

class StockMovementRepository
{
    public function dataTable($request)
    {
        $query = StockMovement::query()
            ->with(['presentation', 'plant', 'creator']);

        if (!empty($request->filters['plant_id'])) {
            $query->where('plant_id', $request->filters['plant_id']);
        }

        if (!empty($request->filters['presentation_id'])) {
            $query->where('presentation_id', $request->filters['presentation_id']);
        }

        if (!empty($request->filters['type'])) {
            $query->where('type', $request->filters['type']);
        }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function summary(int $presentationId, int $plantId): array
    {
        $summary = StockMovement::where('presentation_id', $presentationId)
            ->where('plant_id', $plantId)
            ->select([
                DB::raw('SUM(quantity) as stock'),
                DB::raw("SUM(CASE WHEN type = 'entry' THEN quantity ELSE 0 END) as total_entries"),
                DB::raw("SUM(CASE WHEN type = 'exit' THEN quantity ELSE 0 END) as total_exits"),
                DB::raw("SUM(CASE WHEN type = 'adjustment' THEN quantity ELSE 0 END) as total_adjustments"),
                DB::raw("SUM(CASE WHEN type = 'loss' THEN quantity ELSE 0 END) as total_losses"),
                DB::raw("SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND type = 'entry' THEN quantity ELSE 0 END) as month_entries"),
                DB::raw("SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND type = 'exit' THEN quantity ELSE 0 END) as month_exits"),
                DB::raw("SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND type = 'loss' THEN quantity ELSE 0 END) as month_losses"),
            ])
            ->first();

        return [
            'stock'        => (int) ($summary->stock ?? 0),
            'monthEntries' => (int) ($summary->month_entries ?? 0),
            'monthExits'   => (int) ($summary->month_exits ?? 0),
            'monthLosses'  => (int) ($summary->month_losses ?? 0),
        ];
    }

    public function create(array $data): StockMovement
    {
        if (in_array($data['type'], ['exit', 'loss'])) {
            $data['quantity'] = -abs($data['quantity']);
        } else {
            $data['quantity'] = abs($data['quantity']);
        }

        return StockMovement::create($data);
    }

    public function delete(int $id): void
    {
        $movement = StockMovement::findOrFail($id);
        $movement->delete();
    }
}

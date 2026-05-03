<?php

namespace App\Modules\PlantPanel\Supplier\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlantPanelSupplierRepository
{
    public function getForPlant(int $plantId): Collection
    {
        return DB::table('dairy_plant_suppliers as ps')
            ->join('dairy_suppliers as s', 's.id', '=', 'ps.supplier_id')
            ->where('ps.plant_id', $plantId)
            ->orderBy('s.name')
            ->select([
                'ps.id as pivot_id',
                's.id as supplier_id',
                's.name',
                's.trade_name',
                's.document_type',
                's.document_number',
                's.cellphone',
                's.email',
                's.address',
                's.community',
                's.latitude',
                's.longitude',
                's.total_cows',
                's.cows_in_production',
                'ps.is_active',
                'ps.price_per_liter',
            ])
            ->get();
    }

    public function getPivot(int $plantId, int $supplierId): ?object
    {
        return DB::table('dairy_plant_suppliers')
            ->where('plant_id', $plantId)
            ->where('supplier_id', $supplierId)
            ->first();
    }

    public function toggleActive(int $plantId, int $supplierId): void
    {
        DB::table('dairy_plant_suppliers')
            ->where('plant_id', $plantId)
            ->where('supplier_id', $supplierId)
            ->update([
                'is_active' => DB::raw('NOT is_active'),
                'updated_at' => now(),
            ]);
    }

    public function updatePrice(int $plantId, int $supplierId, ?float $price): void
    {
        DB::table('dairy_plant_suppliers')
            ->where('plant_id', $plantId)
            ->where('supplier_id', $supplierId)
            ->update([
                'price_per_liter' => $price,
                'updated_at' => now(),
            ]);
    }
}

<?php

namespace App\Modules\PlantPanel\Supplier\Repositories;

use App\Models\Dairy\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlantPanelSupplierRepository
{
    public function createForPlant(int $plantId, array $data): void
    {
        DB::transaction(function () use ($plantId, $data) {
            $supplier = Supplier::create([
                'supplier_type' => $data['supplier_type'],
                'document_type' => $data['document_type'],
                'document_number' => $data['document_number'],
                'name' => $data['name'],
                'trade_name' => $data['trade_name'] ?? null,
                'cellphone' => $data['cellphone'] ?? null,
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
                'community' => $data['community'] ?? null,
                'total_cows' => 0,
                'cows_in_production' => 0,
                'dry_cows' => 0,
                'is_active' => $data['is_active'],
            ]);

            DB::table('dairy_plant_suppliers')->insert([
                'plant_id' => $plantId,
                'supplier_id' => $supplier->id,
                'is_active' => $data['is_active'],
                'price_per_liter' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function getForPlant(int $plantId): Collection
    {
        $avgLitersBySupplier = DB::table('dairy_milk_collections as mc')
            ->where('mc.plant_id', $plantId)
            ->select([
                'mc.supplier_id',
                DB::raw('ROUND(COALESCE(SUM(mc.quantity_liters) / NULLIF(COUNT(DISTINCT mc.collection_date), 0), 0), 2) as avg_liters_per_day'),
            ])
            ->groupBy('mc.supplier_id');

        return DB::table('dairy_plant_suppliers as ps')
            ->join('dairy_suppliers as s', 's.id', '=', 'ps.supplier_id')
            ->leftJoinSub($avgLitersBySupplier, 'avg_mc', function ($join) {
                $join->on('avg_mc.supplier_id', '=', 'ps.supplier_id');
            })
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
                'avg_mc.avg_liters_per_day',
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

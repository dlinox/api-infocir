<?php

namespace App\Modules\SupplierPanel\MilkDelivery\Repositories;

use App\Models\Dairy\MilkCollection;

class MilkDeliveryRepository
{
    public function dataTable($request, int $supplierId)
    {
        $query = MilkCollection::query()
            ->leftJoin('dairy_plants', 'dairy_plants.id', '=', 'dairy_milk_collections.plant_id')
            ->where('dairy_milk_collections.supplier_id', $supplierId)
            ->select([
                'dairy_milk_collections.*',
                'dairy_plants.id as plant_id_alias',
                'dairy_plants.name as plant_name',
                'dairy_plants.trade_name as plant_trade_name',
            ]);

        if (empty($request->sortBy)) {
            $query->orderBy('dairy_milk_collections.collection_date', 'desc')
                  ->orderBy('dairy_milk_collections.id', 'desc');
        }

        return $query->dataTable($request, [
            'dairy_milk_collections.observations',
            'dairy_plants.name',
            'dairy_plants.trade_name',
        ]);
    }
}

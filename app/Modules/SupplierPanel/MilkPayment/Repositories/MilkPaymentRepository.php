<?php

namespace App\Modules\SupplierPanel\MilkPayment\Repositories;

use App\Models\Dairy\SupplierPayment;

class MilkPaymentRepository
{
    public function dataTable($request, int $supplierId)
    {
        $query = SupplierPayment::query()
            ->leftJoin('dairy_plants', 'dairy_plants.id', '=', 'dairy_supplier_payments.plant_id')
            ->where('dairy_supplier_payments.supplier_id', $supplierId)
            ->select([
                'dairy_supplier_payments.*',
                'dairy_plants.id as plant_id_alias',
                'dairy_plants.name as plant_name',
                'dairy_plants.trade_name as plant_trade_name',
            ]);

        if (empty($request->sortBy)) {
            $query->orderBy('dairy_supplier_payments.period_end', 'desc')
                  ->orderBy('dairy_supplier_payments.id', 'desc');
        }

        return $query->dataTable($request, [
            'dairy_supplier_payments.observations',
            'dairy_plants.name',
            'dairy_plants.trade_name',
        ]);
    }
}

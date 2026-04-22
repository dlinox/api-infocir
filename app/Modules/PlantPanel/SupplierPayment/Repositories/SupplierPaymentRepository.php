<?php

namespace App\Modules\PlantPanel\SupplierPayment\Repositories;

use App\Models\Dairy\MilkCollection;
use App\Models\Dairy\SupplierPayment;

class SupplierPaymentRepository
{
    public function dataTable($request, int $plantId)
    {
        $query = SupplierPayment::query()
            ->leftJoin('dairy_suppliers', 'dairy_suppliers.id', '=', 'dairy_supplier_payments.supplier_id')
            ->where('dairy_supplier_payments.plant_id', $plantId)
            ->select([
                'dairy_supplier_payments.*',
                'dairy_suppliers.id as supplier_id_alias',
                'dairy_suppliers.name as supplier_name',
                'dairy_suppliers.trade_name as supplier_trade_name',
                'dairy_suppliers.document_number as supplier_document_number',
            ]);

        if (empty($request->sortBy)) {
            $query->orderBy('dairy_supplier_payments.period_end', 'desc')
                  ->orderBy('dairy_supplier_payments.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByIdForPlant(int $id, int $plantId): ?SupplierPayment
    {
        return SupplierPayment::where('id', $id)
            ->where('plant_id', $plantId)
            ->first();
    }

    public function createOrUpdate(array $data, int $plantId): SupplierPayment
    {
        $data['plant_id'] = $plantId;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_amount'] = round((float) $data['total_amount'] - (float) $data['deductions'], 2);

        if (!empty($data['id'])) {
            $payment = SupplierPayment::where('id', $data['id'])->where('plant_id', $plantId)->firstOrFail();
            $payment->update($data);
            return $payment;
        }

        return SupplierPayment::create($data);
    }

    public function delete(int $id, int $plantId): void
    {
        $payment = SupplierPayment::where('id', $id)->where('plant_id', $plantId)->firstOrFail();
        $payment->delete();
    }

    /**
     * Sum the deliveries of a supplier in the given range. Used to pre-fill the form.
     */
    public function summarizePeriod(int $plantId, int $supplierId, string $from, string $to): array
    {
        $row = MilkCollection::query()
            ->where('plant_id', $plantId)
            ->where('supplier_id', $supplierId)
            ->whereBetween('collection_date', [$from, $to])
            ->selectRaw('COALESCE(SUM(quantity_liters),0) as total_liters, COALESCE(SUM(total_amount),0) as total_amount')
            ->first();

        return [
            'totalLiters' => (float) ($row->total_liters ?? 0),
            'totalAmount' => (float) ($row->total_amount ?? 0),
        ];
    }
}

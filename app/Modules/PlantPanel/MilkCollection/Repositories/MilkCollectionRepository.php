<?php

namespace App\Modules\PlantPanel\MilkCollection\Repositories;

use App\Models\Dairy\MilkCollection;
use App\Models\Dairy\Supplier;

class MilkCollectionRepository
{
    public function dailySummary(string $date, int $plantId): array
    {
        $collections = MilkCollection::query()
            ->join('dairy_suppliers', 'dairy_suppliers.id', '=', 'dairy_milk_collections.supplier_id')
            ->where('dairy_milk_collections.plant_id', $plantId)
            ->where('dairy_milk_collections.collection_date', $date)
            ->select([
                'dairy_suppliers.id as supplier_id',
                'dairy_suppliers.name as supplier_name',
                'dairy_suppliers.trade_name as supplier_trade_name',
                'dairy_milk_collections.shift',
                'dairy_milk_collections.quantity_liters',
            ])
            ->orderBy('dairy_suppliers.name')
            ->get();

        return [
            'date'        => $date,
            'totalLiters' => round((float) $collections->sum('quantity_liters'), 2),
            'suppliers'   => $collections->map(fn ($c) => [
                'supplierId'   => $c->supplier_id,
                'supplierName' => $c->supplier_trade_name ?: $c->supplier_name,
                'shift'        => $c->shift,
                'liters'       => (float) $c->quantity_liters,
            ])->values()->toArray(),
        ];
    }

    public function dataTable($request, int $plantId)
    {
        $query = MilkCollection::query()
            ->with('file')
            ->leftJoin('dairy_suppliers', 'dairy_suppliers.id', '=', 'dairy_milk_collections.supplier_id')
            ->where('dairy_milk_collections.plant_id', $plantId)
            ->select([
                'dairy_milk_collections.*',
                'dairy_suppliers.id as supplier_id_alias',
                'dairy_suppliers.name as supplier_name',
                'dairy_suppliers.trade_name as supplier_trade_name',
                'dairy_suppliers.document_number as supplier_document_number',
            ]);

        if (empty($request->sortBy)) {
            $query->orderBy('dairy_milk_collections.collection_date', 'desc')
                  ->orderBy('dairy_milk_collections.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByIdForPlant(int $id, int $plantId): ?MilkCollection
    {
        return MilkCollection::with(['qualityTest', 'file'])
            ->where('id', $id)
            ->where('plant_id', $plantId)
            ->first();
    }

    public function createOrUpdate(array $data, int $plantId): MilkCollection
    {
        $data['plant_id'] = $plantId;
        $supplier = Supplier::findOrFail($data['supplier_id']);
        $pricePerLiter = (float) ($supplier->reference_price_per_liter ?? 0);
        $data['price_per_liter'] = $pricePerLiter;
        $data['total_amount'] = round((float) $data['quantity_liters'] * $pricePerLiter, 2);

        if (!empty($data['collection_route_id'])) {
            $data['collection_route_id'] = $data['collection_route_id'];
        }

        if (!empty($data['id'])) {
            $collection = MilkCollection::where('id', $data['id'])->where('plant_id', $plantId)->firstOrFail();
            $collection->update($data);
            return $collection;
        }

        return MilkCollection::create($data);
    }

    public function delete(int $id, int $plantId): void
    {
        $collection = MilkCollection::where('id', $id)->where('plant_id', $plantId)->firstOrFail();
        $collection->delete();
    }

    public function saveQualityTest(int $collectionId, ?array $data): void
    {
        if ($data === null) return;

        $collection = MilkCollection::findOrFail($collectionId);
        $collection->qualityTest()->updateOrCreate(
            ['milk_collection_id' => $collectionId],
            $data,
        );
    }
}

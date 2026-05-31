<?php

namespace Database\Seeders;

use App\Models\Core\UnitMeasure;
use App\Models\Dairy\Plant;
use App\Models\Dairy\PlantProduct;
use App\Models\Dairy\Product;
use App\Models\Dairy\ProductionBatch;
use App\Models\Dairy\ProductPresentation;
use App\Models\Dairy\ProductPrice;
use App\Models\Dairy\StockMovement;
use App\Models\Dairy\Supplier;
use Illuminate\Database\Seeder;

class StorefrontSeeder extends Seeder
{
    private int $skuCounter = 0;
    private int $batchCounter = 0;

    public function run(): void
    {
        $this->seedSuppliers();

        $plants = Plant::orderBy('id')->get();
        $products = Product::with('productType')->orderBy('id')->get();

        if ($plants->isEmpty() || $products->isEmpty()) {
            return;
        }

        $units = [
            'g'  => UnitMeasure::where('abbreviation', 'g')->first()?->id,
            'mL' => UnitMeasure::where('abbreviation', 'mL')->first()?->id,
            'kg' => UnitMeasure::where('abbreviation', 'kg')->first()?->id,
            'L'  => UnitMeasure::where('abbreviation', 'L')->first()?->id,
        ];

        $today = now()->toDateString();

        foreach ($products as $index => $product) {
            $plant = $plants[$index % $plants->count()];

            $plantProduct = PlantProduct::firstOrCreate(
                ['plant_id' => $plant->id, 'product_id' => $product->id],
                ['is_active' => true],
            );

            foreach ($this->presentationsFor($product->name) as $spec) {
                $presentation = ProductPresentation::create([
                    'plant_product_id' => $plantProduct->id,
                    'sku'              => 'SKU-' . str_pad((string) (++$this->skuCounter), 6, '0', STR_PAD_LEFT),
                    'name'             => $spec['name'],
                    'unit_measure_id'  => $units[$spec['unit']] ?? null,
                    'content'          => $spec['content'],
                    'is_active'        => true,
                ]);

                ProductPrice::create([
                    'presentation_id' => $presentation->id,
                    'price'           => $spec['price'],
                    'cost'            => round($spec['price'] * 0.6, 2),
                    'effective_from'  => $today,
                ]);

                // Stock inicial: lote de producción "listo" → entrada de inventario
                $quantity = 40;
                $batchCode = 'LOTE-' . str_pad((string) (++$this->batchCounter), 5, '0', STR_PAD_LEFT);

                ProductionBatch::create([
                    'plant_id'        => $plant->id,
                    'batch_code'      => $batchCode,
                    'production_date' => $today,
                    'quantity_units'  => $quantity,
                    'status'          => 'ready',
                    'presentation_id' => $presentation->id,
                ]);

                StockMovement::create([
                    'presentation_id' => $presentation->id,
                    'plant_id'        => $plant->id,
                    'type'            => 'entry',
                    'quantity'        => $quantity,
                    'batch_code'      => $batchCode,
                    'reason'          => 'Stock inicial — Lote ' . $batchCode,
                ]);
            }
        }
    }

    private function presentationsFor(string $productName): array
    {
        $name = mb_strtolower($productName);

        return match (true) {
            str_contains($name, 'queso') || str_contains($name, 'mozzarella') => [
                ['name' => 'Bloque 500 g', 'content' => 500, 'unit' => 'g',  'price' => 18.00],
                ['name' => 'Bloque 1 kg',  'content' => 1,   'unit' => 'kg', 'price' => 34.00],
            ],
            str_contains($name, 'yogurt') => [
                ['name' => 'Botella 1 L', 'content' => 1, 'unit' => 'L', 'price' => 9.00],
                ['name' => 'Bidón 2 L',   'content' => 2, 'unit' => 'L', 'price' => 17.00],
            ],
            str_contains($name, 'crema') => [
                ['name' => 'Pote 250 mL', 'content' => 250, 'unit' => 'mL', 'price' => 8.00],
                ['name' => 'Pote 500 mL', 'content' => 500, 'unit' => 'mL', 'price' => 15.00],
            ],
            str_contains($name, 'mantequilla') => [
                ['name' => 'Pote 200 g', 'content' => 200, 'unit' => 'g', 'price' => 10.00],
                ['name' => 'Pote 500 g', 'content' => 500, 'unit' => 'g', 'price' => 22.00],
            ],
            str_contains($name, 'leche') => [
                ['name' => 'Botella 1 L', 'content' => 1, 'unit' => 'L', 'price' => 5.00],
                ['name' => 'Bidón 2 L',   'content' => 2, 'unit' => 'L', 'price' => 9.00],
            ],
            default => [
                ['name' => 'Pote 250 g', 'content' => 250, 'unit' => 'g', 'price' => 9.00],
                ['name' => 'Pote 500 g', 'content' => 500, 'unit' => 'g', 'price' => 16.00],
            ],
        };
    }

    /**
     * Proveedores (ganaderos) reales de la región Puno para el directorio.
     */
    private function seedSuppliers(): void
    {
        $items = [
            ['supplier_type' => 'company',    'document_type' => '6', 'document_number' => '20448900011', 'name' => 'Ganaderos Unidos Azángaro',     'trade_name' => 'Ganaderos Azángaro',     'cellphone' => '951432100', 'community' => 'Azángaro', 'latitude' => -14.9210, 'longitude' => -70.1758, 'total_cows' => 340, 'tank_capacity_liters' => 8500, 'reference_price_per_liter' => 1.50, 'description' => 'Asociación de familias ganaderas del distrito de Azángaro.'],
            ['supplier_type' => 'individual', 'document_type' => '1', 'document_number' => '40887112',    'name' => 'Familia Quispe Mamani',          'trade_name' => null,                     'cellphone' => '958221087', 'community' => 'Chucuito', 'latitude' => -16.0046, 'longitude' => -69.8403, 'total_cows' => 45,  'tank_capacity_liters' => 180,  'reference_price_per_liter' => 1.45, 'description' => 'Proveedor familiar con ganado Brown Swiss.'],
            ['supplier_type' => 'company',    'document_type' => '6', 'document_number' => '20448900022', 'name' => 'Cooperativa Lago Titicaca',      'trade_name' => 'Coop. Titicaca',         'cellphone' => '952110334', 'community' => 'Puno',         'latitude' => -15.8650, 'longitude' => -69.9900, 'total_cows' => 210, 'tank_capacity_liters' => 5200, 'reference_price_per_liter' => 1.55, 'description' => 'Cooperativa de productores en las orillas del Lago Titicaca.'],
            ['supplier_type' => 'individual', 'document_type' => '1', 'document_number' => '41552009',    'name' => 'Estancia Vilque',                'trade_name' => null,                     'cellphone' => '953778210', 'community' => 'Vilque',     'latitude' => -15.7667, 'longitude' => -70.2333, 'total_cows' => 80,  'tank_capacity_liters' => 600,  'reference_price_per_liter' => 1.48, 'description' => 'Establo de altura en el distrito de Vilque.'],
        ];

        foreach ($items as $item) {
            Supplier::firstOrCreate(['document_number' => $item['document_number']], $item);
        }
    }
}

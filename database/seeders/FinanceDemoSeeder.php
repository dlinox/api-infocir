<?php

namespace Database\Seeders;

use App\Models\Dairy\FixedAsset;
use App\Models\Dairy\InvestmentCategory;
use App\Models\Dairy\InvestmentItem;
use App\Models\Dairy\InvestmentPlan;
use App\Models\Dairy\Order;
use App\Models\Dairy\Plant;
use App\Models\Dairy\PreOperativeExpense;
use App\Models\Dairy\ProductPresentation;
use App\Models\Dairy\StockMovement;
use App\Models\Dairy\Worker;
use App\Models\Dairy\WorkerPayment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FinanceDemoSeeder extends Seeder
{
    public function run(): void
    {
        $months = [Carbon::now(), Carbon::now()->subMonth(), Carbon::now()->subMonths(2)];

        foreach (Plant::with('entity')->orderBy('id')->get() as $plant) {
            $this->seedSales($plant, $months);
            $this->seedPayroll($plant, $months);
            $this->seedInvestment($plant);
        }
    }

    private function categoryId(string $group, string $like): ?int
    {
        return InvestmentCategory::where('group', $group)->where('name', 'like', "%{$like}%")->value('id')
            ?? InvestmentCategory::where('group', $group)->value('id');
    }

    private function seedInvestment(Plant $plant): void
    {
        $entityId = $plant->entity?->id;
        if (!$entityId || FixedAsset::where('entity_id', $entityId)->exists()) {
            return;
        }
        $today = now()->toDateString();

        // Activo fijo
        foreach ([['Maquinaria', 'Tanque de enfriamiento 500 L', 18000], ['Herramientas', 'Kit de utensilios inox', 3500]] as [$cat, $name, $cost]) {
            FixedAsset::create([
                'entity_id' => $entityId, 'investment_category_id' => $this->categoryId('fixed_asset', $cat),
                'name' => $name, 'purchase_date' => $today, 'purchase_cost' => $cost, 'quantity' => 1,
                'depreciation_method' => 'straight_line', 'status' => 'active',
            ]);
        }

        // Gastos pre-operativos
        foreach ([['Licencia de funcionamiento', 2800], ['Certificación DIGESA + HACCP', 6500]] as [$name, $amount]) {
            PreOperativeExpense::create([
                'entity_id' => $entityId, 'investment_category_id' => $this->categoryId('pre_operative', 'Pre'),
                'name' => $name, 'payment_date' => $today, 'amount' => $amount, 'recurrence_type' => 'one_time',
            ]);
        }

        // Capital de trabajo (plan + items, con relación a mano de obra y materia prima)
        $plan = InvestmentPlan::create([
            'entity_id' => $entityId, 'plan_type' => 'working_capital',
            'period_year' => now()->year, 'period_month' => now()->month, 'status' => 'draft', 'total_amount' => 0,
        ]);
        $total = 0;
        foreach ([['Materia Prima', 'Leche fresca (2 meses)', 18000, 2], ['Mano de Obra Directa', 'Operarios de planta (2 meses)', 2800, 2]] as [$cat, $name, $uv, $q]) {
            $t = $uv * $q;
            $total += $t;
            InvestmentItem::create([
                'plan_id' => $plan->id, 'investment_category_id' => $this->categoryId('working_capital', $cat),
                'name' => $name, 'recurrence_type' => 'one_time', 'unit_value' => $uv, 'quantity' => $q, 'total' => $t,
            ]);
        }
        $plan->update(['total_amount' => $total]);
    }

    private function seedSales(Plant $plant, array $months): void
    {
        $presentations = ProductPresentation::whereHas(
            'plantProduct',
            fn ($q) => $q->where('plant_id', $plant->id)->where('is_active', true),
        )->with('plantProduct.product')->limit(2)->get();

        if ($presentations->isEmpty()) {
            return;
        }

        foreach ($months as $month) {
            // 1 venta cerrada por mes y planta
            $closedAt = $month->copy()->day(min(15, $month->daysInMonth));
            $lines = [];
            $subtotal = 0;

            foreach ($presentations as $i => $pres) {
                $unitPrice = 20 + $i * 5;
                $qty = 3 + $i;
                $lineSubtotal = $unitPrice * $qty;
                $subtotal += $lineSubtotal;
                $lines[] = [
                    'presentation_id'   => $pres->id,
                    'product_name'      => $pres->plantProduct->product?->name ?? $pres->name,
                    'presentation_name' => $pres->name,
                    'unit_price'        => $unitPrice,
                    'quantity'          => $qty,
                    'subtotal'          => $lineSubtotal,
                ];
            }

            $order = Order::create([
                'code'           => 'TMP-' . uniqid(),
                'status'         => 'closed',
                'customer_name'  => 'Cliente Demo',
                'customer_phone' => '950000000',
                'plant_id'       => $plant->id,
                'subtotal'       => $subtotal,
                'total'          => $subtotal,
                'stock_applied'  => true,
                'closed_at'      => $closedAt,
                'created_at'     => $closedAt,
                'updated_at'     => $closedAt,
            ]);
            $order->update(['code' => 'VL-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT)]);
            $order->items()->createMany($lines);

            foreach ($lines as $line) {
                StockMovement::create([
                    'presentation_id' => $line['presentation_id'],
                    'plant_id'        => $plant->id,
                    'type'            => 'exit',
                    'quantity'        => $line['quantity'],
                    'reason'          => 'Venta demo — Pedido ' . $order->code,
                ]);
            }
        }
    }

    private function seedPayroll(Plant $plant, array $months): void
    {
        $entityId = $plant->entity?->id;
        if (!$entityId) {
            return;
        }

        $workers = Worker::where('entity_id', $entityId)->get();

        foreach ($workers as $worker) {
            foreach ($months as $month) {
                $exists = WorkerPayment::where('plant_id', $plant->id)
                    ->where('worker_person_id', $worker->person_id)
                    ->where('period_year', $month->year)
                    ->where('period_month', $month->month)
                    ->exists();
                if ($exists) {
                    continue;
                }

                $salary = (float) ($worker->monthly_salary ?: 1200);
                WorkerPayment::create([
                    'plant_id'         => $plant->id,
                    'worker_person_id' => $worker->person_id,
                    'period_year'      => $month->year,
                    'period_month'     => $month->month,
                    'base_salary'      => $salary,
                    'bonuses'          => 0,
                    'deductions'       => 0,
                    'net_amount'       => $salary,
                    'status'           => 'paid',
                    'paid_at'          => $month->copy()->day(min(28, $month->daysInMonth)),
                ]);
            }
        }
    }
}

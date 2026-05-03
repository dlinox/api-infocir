$user = App\Models\Auth\User::first();
echo 'User: ' . $user->id . PHP_EOL;
$repo = app(App\Modules\PlantPanel\Investment\Repositories\InvestmentPlanRepository::class);
$plan = $repo->save($user->id, ['plan_type'=>'fixed_assets','period_year'=>2026,'period_month'=>null,'notes'=>'test','items'=>[['investment_category_id'=>1,'name'=>'Terreno X','recurrence_type'=>'one_time','unit_value'=>5000.5,'quantity'=>2]]]);
echo 'Plan id: ' . $plan->id . ' status: ' . $plan->status . ' total: ' . $plan->total_amount . PHP_EOL;
foreach ($plan->items as $it) {
    echo '  - ' . $it->name . ' uv=' . $it->unit_value . ' qty=' . $it->quantity . ' total=' . $it->total . PHP_EOL;
}
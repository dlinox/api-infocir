<?php

namespace App\Modules\PlantPanel\Investment\Support;

use Carbon\Carbon;

class DepreciationCalculator
{
    /**
     * Calcula la depreciación de un activo a la fecha dada.
     *
     * @return array{monthlyDepreciation: float, accumulated: float, bookValue: float}
     */
    public static function compute(
        float    $purchaseCost,
        float    $residualValue,
        ?int     $usefulLifeYears,
        ?string  $purchaseDate,
        ?string  $method = 'straight_line',
        ?Carbon  $asOf = null,
    ): array {
        $asOf          = $asOf ?? Carbon::today();
        $usefulMonths  = $usefulLifeYears ? $usefulLifeYears * 12 : null;
        $depreciable   = max(0, $purchaseCost - $residualValue);
        $monthsInUse   = $purchaseDate
            ? max(0, (int) Carbon::parse($purchaseDate)->diffInMonths($asOf))
            : 0;

        if (!$usefulMonths || $usefulMonths <= 0) {
            return [
                'monthlyDepreciation' => 0.0,
                'accumulated'         => 0.0,
                'bookValue'           => round($purchaseCost, 2),
            ];
        }

        if ($method === 'declining_balance') {
            // Doble saldo decreciente (DDB): tasa anual = 2 / vidaÚtilAños
            // Aplicada mensualmente sobre el valor en libros.
            $monthlyRate = (2 / $usefulLifeYears) / 12;
            $bookValue   = $purchaseCost * pow(1 - $monthlyRate, $monthsInUse);
            $bookValue   = max($residualValue, $bookValue);
            $accumulated = $purchaseCost - $bookValue;
            // Promedio mensual aproximado para mostrar al usuario
            $monthly = $monthsInUse > 0
                ? $accumulated / $monthsInUse
                : $purchaseCost * $monthlyRate;
            return [
                'monthlyDepreciation' => round($monthly, 2),
                'accumulated'         => round($accumulated, 2),
                'bookValue'           => round($bookValue, 2),
            ];
        }

        // Lineal por defecto
        $monthly     = $depreciable / $usefulMonths;
        $accumulated = min($depreciable, round($monthly * $monthsInUse, 2));
        $bookValue   = $purchaseCost - $accumulated;

        return [
            'monthlyDepreciation' => round($monthly, 2),
            'accumulated'         => round($accumulated, 2),
            'bookValue'           => round($bookValue, 2),
        ];
    }
}

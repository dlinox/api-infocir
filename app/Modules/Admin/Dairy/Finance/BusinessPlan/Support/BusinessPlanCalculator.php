<?php

namespace App\Modules\Admin\Dairy\Finance\BusinessPlan\Support;

class BusinessPlanCalculator
{
    private const MESES = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    /**
     * A partir de los inputs editables, calcula el plan completo (para mostrar).
     */
    public function compute(array $inputs): array
    {
        $params = $inputs['parametros'] ?? ['wacc' => 0.1, 'tasaCrecimiento' => 0.05, 'horizonte' => 12];
        $wacc = (float) ($params['wacc'] ?? 0.1);

        $inversiones = $this->computeInversiones($inputs['inversiones'] ?? ['grupos' => []]);
        $productos = $inputs['productos'] ?? [];

        $demanda = $this->computeDemanda($productos);
        $ventas = $this->computeVentas($productos, (float) ($params['tasaCrecimiento'] ?? 0));
        $flujo = $this->computeFlujo($inversiones, $demanda, $ventas);
        $vanTir = $this->computeVanTir($flujo, $inversiones['total'], $wacc);

        return [
            'parametros'  => $params,
            'inversiones' => $inversiones,
            'demanda'     => $demanda,
            'ventas'      => $ventas,
            'flujoCaja'   => $flujo,
            'vanTir'      => $vanTir,
        ];
    }

    private function computeInversiones(array $inv): array
    {
        $total = 0;
        $grupos = [];
        foreach ($inv['grupos'] ?? [] as $g) {
            $gsub = 0;
            $secciones = [];
            foreach ($g['secciones'] ?? [] as $s) {
                $ssub = 0;
                $rubros = [];
                foreach ($s['rubros'] ?? [] as $r) {
                    $t = round((float) ($r['valorUnit'] ?? 0) * (float) ($r['unidades'] ?? 0), 2);
                    $ssub += $t;
                    $rubros[] = ['rubro' => $r['rubro'] ?? '', 'valorUnit' => (float) ($r['valorUnit'] ?? 0), 'unidades' => (float) ($r['unidades'] ?? 0), 'total' => $t];
                }
                $gsub += $ssub;
                $secciones[] = ['nombre' => $s['nombre'] ?? '', 'subtotal' => round($ssub, 2), 'rubros' => $rubros];
            }
            $total += $gsub;
            $grupos[] = ['nombre' => $g['nombre'] ?? '', 'subtotal' => round($gsub, 2), 'secciones' => $secciones];
        }
        return ['total' => round($total, 2), 'grupos' => $grupos];
    }

    private function sumCols(array $rows): array
    {
        $acc = array_fill(0, 12, 0.0);
        foreach ($rows as $row) {
            foreach ($row as $i => $v) {
                $acc[$i] += (float) $v;
            }
        }
        return array_map(fn ($v) => round($v, 2), $acc);
    }

    private function computeDemanda(array $productos): array
    {
        $dem = [];
        $costo = [];
        $demRows = [];
        $costoRows = [];
        $capTotal = 0;
        foreach ($productos as $p) {
            $mensual = array_map('floatval', array_pad($p['demanda'] ?? [], 12, 0));
            $mensual = array_slice($mensual, 0, 12);
            $dem[] = ['nombre' => $p['nombre'] ?? '', 'mensual' => $mensual, 'total' => round(array_sum($mensual), 2), 'capacidad' => (float) ($p['capacidad'] ?? 0)];
            $demRows[] = $mensual;
            $capTotal += (float) ($p['capacidad'] ?? 0);
            $cu = (float) ($p['costoUnit'] ?? 0);
            $cMensual = array_map(fn ($u) => round($u * $cu, 2), $mensual);
            $costo[] = ['nombre' => $p['nombre'] ?? '', 'costoUnit' => $cu, 'mensual' => $cMensual, 'total' => round(array_sum($cMensual), 2)];
            $costoRows[] = $cMensual;
        }
        $demTotMensual = $this->sumCols($demRows);
        $costoTotMensual = $this->sumCols($costoRows);
        return [
            'meses' => self::MESES,
            'productos' => $dem,
            'totales' => ['mensual' => $demTotMensual, 'total' => round(array_sum($demTotMensual), 2), 'capacidad' => round($capTotal, 2)],
            'costos' => ['productos' => $costo, 'totales' => ['mensual' => $costoTotMensual, 'total' => round(array_sum($costoTotMensual), 2)]],
            'bom' => [],
        ];
    }

    private function computeVentas(array $productos, float $tasa): array
    {
        $units = [];
        $money = [];
        $uRows = [];
        $mRows = [];
        foreach ($productos as $p) {
            $mensual = array_map('floatval', array_slice(array_pad($p['demanda'] ?? [], 12, 0), 0, 12));
            $units[] = ['nombre' => $p['nombre'] ?? '', 'mensual' => $mensual, 'total' => round(array_sum($mensual), 2)];
            $uRows[] = $mensual;
            $pv = (float) ($p['precioVenta'] ?? 0);
            $mMensual = array_map(fn ($u) => round($u * $pv, 2), $mensual);
            $money[] = ['nombre' => $p['nombre'] ?? '', 'valorVenta' => $pv, 'mensual' => $mMensual, 'total' => round(array_sum($mMensual), 2)];
            $mRows[] = $mMensual;
        }
        $uTot = $this->sumCols($uRows);
        $mTot = $this->sumCols($mRows);
        return [
            'tasaCrecimiento' => $tasa,
            'meses' => self::MESES,
            'unidades' => ['productos' => $units, 'totalesMensual' => $uTot, 'total' => round(array_sum($uTot), 2)],
            'montos' => ['productos' => $money, 'totalesMensual' => $mTot, 'total' => round(array_sum($mTot), 2)],
        ];
    }

    private function computeFlujo(array $inversiones, array $demanda, array $ventas): array
    {
        $meses = array_map(fn ($i) => 'Mes ' . $i, range(1, 12));
        $ventasMensual = $ventas['montos']['totalesMensual'];
        $costosMensual = $demanda['costos']['totales']['mensual'];

        $ingresos = [
            ['concepto' => 'Ventas', 'anio0' => 0, 'meses' => $ventasMensual],
            ['concepto' => 'Otros ingresos', 'anio0' => 0, 'meses' => array_fill(0, 12, 0)],
        ];
        $totalIngresos = ['concepto' => 'Total ingresos', 'anio0' => 0, 'meses' => $ventasMensual];

        $egresosInversion = [];
        foreach ($inversiones['grupos'] as $g) {
            $egresosInversion[] = ['concepto' => $g['nombre'], 'anio0' => $g['subtotal'], 'meses' => array_fill(0, 12, 0)];
        }
        $egresosOperativos = [
            ['concepto' => 'Costos de producción', 'anio0' => 0, 'meses' => $costosMensual],
        ];
        $totalEgresos = ['concepto' => 'Total egresos', 'anio0' => $inversiones['total'], 'meses' => $costosMensual];

        $netoMeses = [];
        for ($i = 0; $i < 12; $i++) {
            $netoMeses[] = round($ventasMensual[$i] - $costosMensual[$i], 2);
        }
        $flujoNeto = ['concepto' => 'Flujo neto', 'anio0' => round(-$inversiones['total'], 2), 'meses' => $netoMeses];

        $acum = [];
        $run = -$inversiones['total'];
        foreach ($netoMeses as $n) {
            $run += $n;
            $acum[] = round($run, 2);
        }
        $flujoAcumulado = ['concepto' => 'Flujo acumulado', 'anio0' => round(-$inversiones['total'], 2), 'meses' => $acum];

        return compact('meses') + [
            'ingresos' => $ingresos,
            'totalIngresos' => $totalIngresos,
            'egresosInversion' => $egresosInversion,
            'egresosOperativos' => $egresosOperativos,
            'totalEgresos' => $totalEgresos,
            'flujoNeto' => $flujoNeto,
            'flujoAcumulado' => $flujoAcumulado,
        ];
    }

    private function computeVanTir(array $flujo, float $inversion, float $wacc): array
    {
        $bna = $flujo['flujoNeto']['meses'];
        $factor = [];
        $desc = [];
        $acumDesc = [];
        $run = -$inversion;
        for ($t = 1; $t <= 12; $t++) {
            $f = 1 / pow(1 + $wacc, $t);
            $factor[] = round($f, 4);
            $d = round($bna[$t - 1] * $f, 2);
            $desc[] = $d;
            $run += $d;
            $acumDesc[] = round($run, 2);
        }
        $van = round(-$inversion + array_sum($desc), 2);
        $tir = $this->irr($inversion, $bna);
        $payback = $this->payback($flujo['flujoAcumulado']['meses']);
        $ir = $inversion > 0 ? round(($van + $inversion) / $inversion, 2) : 0;
        $vanInvPct = $inversion > 0 ? round(($van / $inversion) * 100, 1) : 0;

        $viable = $van > 0;
        return [
            'parametros' => ['inversionInicial' => round($inversion, 2), 'wacc' => $wacc, 'horizonte' => 12],
            'serie' => ['meses' => $flujo['meses'], 'bna' => $bna, 'factor' => $factor, 'bnaDescontado' => $desc, 'acumuladoDescontado' => $acumDesc],
            'indicadores' => [
                ['nombre' => 'VAN (Valor Actual Neto)', 'valor' => $van, 'estado' => $viable ? '✅ VIABLE' : '⚠ No viable', 'nota' => 'VAN > 0 → Proyecto viable'],
                ['nombre' => 'TIR (Tasa Interna Retorno)', 'valor' => $tir === null ? 'N/D' : round($tir * 100, 2), 'estado' => $tir === null ? 'Revisar flujos' : ($tir > $wacc ? '✅ Rentable' : '⚠ Bajo WACC'), 'nota' => 'TIR mensual > WACC → Rentable'],
                ['nombre' => 'Payback (meses)', 'valor' => $payback ?? 'No recupera', 'estado' => $payback ? '✅' : '⚠', 'nota' => 'Meses para recuperar la inversión'],
                ['nombre' => 'Índice Rentabilidad (B/C)', 'valor' => $ir, 'estado' => $ir >= 1 ? '✅ Viable (B/C≥1)' : '⚠ B/C<1', 'nota' => 'Beneficio por S/ invertido'],
                ['nombre' => 'VAN / Inversión (%)', 'valor' => $vanInvPct, 'estado' => '', 'nota' => '% de retorno sobre la inversión'],
            ],
            'conclusion' => $viable
                ? '✅ CONCLUSIÓN: El proyecto es viable con los supuestos actuales (VAN positivo).'
                : '⚠ CONCLUSIÓN: Revisar el proyecto. Ajusta costos, precio o inversión para mejorar la viabilidad.',
        ];
    }

    private function irr(float $inversion, array $bna): ?float
    {
        $npv = function (float $r) use ($inversion, $bna): float {
            $v = -$inversion;
            foreach ($bna as $t => $cf) {
                $v += $cf / pow(1 + $r, $t + 1);
            }
            return $v;
        };
        $lo = -0.9;
        $hi = 1.0;
        $flo = $npv($lo);
        $fhi = $npv($hi);
        if ($flo * $fhi > 0) {
            return null; // sin cambio de signo → no se puede resolver
        }
        for ($i = 0; $i < 100; $i++) {
            $mid = ($lo + $hi) / 2;
            $fm = $npv($mid);
            if (abs($fm) < 0.01) {
                return $mid;
            }
            if ($flo * $fm < 0) {
                $hi = $mid;
            } else {
                $lo = $mid;
                $flo = $fm;
            }
        }
        return ($lo + $hi) / 2;
    }

    private function payback(array $acumulado): ?int
    {
        foreach ($acumulado as $i => $v) {
            if ($v >= 0) {
                return $i + 1;
            }
        }
        return null;
    }
}

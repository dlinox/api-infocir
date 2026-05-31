<?php

namespace App\Modules\PlantPanel\WorkerPayment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerPaymentFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workerPersonId' => (int) $this->worker_person_id,
            'periodYear' => (int) $this->period_year,
            'periodMonth' => (int) $this->period_month,
            'baseSalary' => (float) $this->base_salary,
            'bonuses' => (float) $this->bonuses,
            'deductions' => (float) $this->deductions,
            'netAmount' => (float) $this->net_amount,
            'status' => $this->status,
            'paidAt' => optional($this->paid_at)->format('Y-m-d H:i:s'),
            'observations' => $this->observations,
        ];
    }
}

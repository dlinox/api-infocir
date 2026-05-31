<?php

namespace App\Modules\PlantPanel\WorkerPayment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerPaymentDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'periodYear' => (int) $this->period_year,
            'periodMonth' => (int) $this->period_month,
            'baseSalary' => (float) $this->base_salary,
            'bonuses' => (float) $this->bonuses,
            'deductions' => (float) $this->deductions,
            'netAmount' => (float) $this->net_amount,
            'status' => $this->status,
            'paidAt' => optional($this->paid_at)->format('Y-m-d H:i:s'),
            'worker' => $this->worker_person_id_alias ? [
                'personId' => (int) $this->worker_person_id_alias,
                'fullName' => collect([
                    $this->worker_name,
                    $this->worker_paternal_surname,
                    $this->worker_maternal_surname,
                ])->filter()->implode(' '),
                'documentType' => $this->worker_document_type,
                'documentNumber' => $this->worker_document_number,
                'monthlySalary' => $this->worker_monthly_salary !== null
                    ? (float) $this->worker_monthly_salary
                    : null,
            ] : null,
        ];
    }
}

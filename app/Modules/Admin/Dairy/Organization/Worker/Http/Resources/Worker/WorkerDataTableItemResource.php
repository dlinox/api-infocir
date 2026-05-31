<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Http\Resources\Worker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'personId' => $this->person_id,
            'person'   => [
                'fullName'       => collect([$this->person_name, $this->person_paternal_surname, $this->person_maternal_surname])->filter()->implode(' '),
                'documentType'   => $this->person_document_type,
                'documentNumber' => $this->person_document_number,
                'cellphone'      => $this->person_cellphone,
                'email'          => $this->person_email,
            ],
            'entity' => $this->entity ? [
                'id'   => $this->entity->id,
                'name' => $this->entity->entityable?->name ?? '',
                'type' => $this->entity->entityable_type,
            ] : null,
            'position' => $this->position ? [
                'id'   => $this->position->id,
                'name' => $this->position->name,
            ] : null,
            'instructionDegree' => $this->instructionDegree ? [
                'id'   => $this->instructionDegree->id,
                'name' => $this->instructionDegree->name,
            ] : null,
            'profession' => $this->profession ? [
                'id'   => $this->profession->id,
                'name' => $this->profession->name,
            ] : null,
            'monthlySalary' => (float) $this->monthly_salary,
            'currentPayment' => $this->current_payment_id ? [
                'id' => (int) $this->current_payment_id,
                'status' => $this->current_payment_status,
                'netAmount' => $this->current_payment_net_amount !== null
                    ? (float) $this->current_payment_net_amount
                    : null,
                'paidAt' => $this->current_payment_paid_at,
            ] : null,
            'isActive' => $this->is_active,
        ];
    }
}

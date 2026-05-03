<?php

namespace App\Modules\PlantPanel\Investment\Http\Requests\PreOperativeExpense;

use App\Common\Http\Requests\ApiFormRequest;

class PreOperativeExpenseRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'                     => 'nullable|integer',
            'investment_category_id' => 'required|integer|exists:dairy_investment_categories,id',
            'name'                   => 'required|string|max:150',
            'payment_date'           => 'required|date',
            'amount'                 => 'required|numeric|min:0',
            'recurrence_type'        => 'required|in:one_time,periodic',
            'validity_years'         => 'nullable|integer|min:1|max:100|required_if:recurrence_type,periodic',
            'notes'                  => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'investment_category_id' => 'Categoría',
            'name'                   => 'Nombre',
            'payment_date'           => 'Fecha de pago',
            'amount'                 => 'Monto',
            'recurrence_type'        => 'Tipo',
            'validity_years'         => 'Vigencia (años)',
            'notes'                  => 'Notas',
        ];
    }

    public function messages(): array
    {
        return [
            'validity_years.required_if' => 'La vigencia (años) es requerida cuando el tipo es periódico.',
        ];
    }
}

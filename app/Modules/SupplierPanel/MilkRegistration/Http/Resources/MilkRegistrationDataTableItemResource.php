<?php

namespace App\Modules\SupplierPanel\MilkRegistration\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkRegistrationDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'registrationDate' => optional($this->registration_date)->format('Y-m-d'),
            'shift'            => $this->shift,
            'quantityLiters'   => (float) $this->quantity_liters,
            'numberOfCows'     => $this->number_of_cows,
            'observations'     => $this->observations,
        ];
    }
}

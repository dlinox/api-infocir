<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Http\Resources\Plant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'ruc'                     => $this->ruc,
            'name'                    => $this->name,
            'tradeName'               => $this->trade_name,
            'type'                    => $this->type,
            'brand'                   => $this->brand,
            'country'                 => $this->country,
            'city'                    => $this->city,
            'address'                 => $this->address,
            'cellphone'               => $this->cellphone,
            'email'                   => $this->email,
            'latitude'                => (float) $this->latitude,
            'longitude'               => (float) $this->longitude,
            'productQuality'          => $this->product_quality,
            'hasSanitaryRegistration' => $this->has_sanitary_registration,
            'hasTechnification'       => $this->has_technification,
            'hasProductionParameters' => $this->has_production_parameters,
            'hasDigesaParameters'     => $this->has_digesa_parameters,
            'hasTddTraining'          => $this->has_tdd_training,
            'description'             => $this->description,
            'isActive'                => $this->is_active,
            'companyTypeId'           => $this->company_type_id,
            'trainingLevelId'         => $this->training_level_id,
            'institutionTypeId'       => $this->institution_type_id,
        ];
    }
}

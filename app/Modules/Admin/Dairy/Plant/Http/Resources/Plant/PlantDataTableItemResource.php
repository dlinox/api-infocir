<?php

namespace App\Modules\Admin\Dairy\Plant\Http\Resources\Plant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantDataTableItemResource extends JsonResource
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
            'latitude'                => $this->latitude,
            'longitude'               => $this->longitude,
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
            'companyType'             => $this->whenLoaded('companyType', fn() => [
                'id'   => $this->companyType->id,
                'name' => $this->companyType->name,
            ]),
            'trainingLevel'           => $this->whenLoaded('trainingLevel', fn() => [
                'id'   => $this->trainingLevel->id,
                'name' => $this->trainingLevel->name,
            ]),
            'institutionType'         => $this->whenLoaded('institutionType', fn() => [
                'id'   => $this->institutionType->id,
                'name' => $this->institutionType->name,
            ]),
            'countryData'             => $this->whenLoaded('country', fn() => [
                'code' => $this->country->code,
                'name' => $this->country->name,
            ]),
            'cityData'                => $this->whenLoaded('city', fn() => [
                'code' => $this->city->code,
                'name' => $this->city->name,
            ]),
        ];
    }
}

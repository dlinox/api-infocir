<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Http\Resources\Plant;

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
            'companyType'             => $this->companyType ? [
                'id'   => $this->companyType->id,
                'name' => $this->companyType->name,
            ] : null,
            'trainingLevel'           => $this->trainingLevel ? [
                'id'   => $this->trainingLevel->id,
                'name' => $this->trainingLevel->name,
            ] : null,
            'institutionType'         => $this->institutionType ? [
                'id'   => $this->institutionType->id,
                'name' => $this->institutionType->name,
            ] : null,
            'countryInfo'             => ($country = $this->countryRelation) ? [
                'code' => $country->code,
                'name' => $country->name,
            ] : null,
            'cityInfo'                => ($city = $this->cityRelation) ? [
                'code' => $city->code,
                'name' => $city->name,
            ] : null,
        ];
    }
}

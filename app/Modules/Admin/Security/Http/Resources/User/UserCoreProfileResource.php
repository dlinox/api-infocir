<?php

namespace App\Modules\Admin\Security\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCoreProfileResource extends JsonResource
{
    private const TYPE_LABELS = [
        'core_admins'     => 'Administrador',
        'dairy_workers'   => 'Trabajador',
        'dairy_suppliers' => 'Proveedor',
        'dairy_plants'    => 'Planta',
    ];

    public function toArray($request): array
    {
        $type  = $this->profileable_type;
        $label = self::TYPE_LABELS[$type] ?? class_basename((string) $type);

        return [
            'value' => $this->id,
            'title' => $label . ' #' . $this->profileable_id,
            'type'  => $type,
        ];
    }
}

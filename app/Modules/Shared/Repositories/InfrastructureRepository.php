<?php

namespace App\Modules\Shared\Repositories;

use App\Models\Core\Entity;

class InfrastructureRepository
{
    private const TYPE_MAP = [
        'plant'    => 'dairy_plants',
        'supplier' => 'dairy_suppliers',
    ];

    public function getSelectItems(?string $type = null)
    {
        $query = Entity::with('entityable');

        if ($type && isset(self::TYPE_MAP[$type])) {
            $query->where('entityable_type', self::TYPE_MAP[$type]);
        }

        return $query->get();
    }
}

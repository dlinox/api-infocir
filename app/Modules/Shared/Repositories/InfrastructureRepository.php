<?php

namespace App\Modules\Shared\Repositories;

use App\Models\Core\Infrastructure;
use App\Common\Traits\HasInfrastructureScope;

class InfrastructureRepository
{
    use HasInfrastructureScope;

    public function getSelectItems()
    {
        $query = Infrastructure::with('infrastructurable');

        $this->scopeByInfrastructure($query, 'id');

        return $query->get();
    }
}

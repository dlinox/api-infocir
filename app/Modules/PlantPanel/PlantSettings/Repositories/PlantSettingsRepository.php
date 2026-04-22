<?php

namespace App\Modules\PlantPanel\PlantSettings\Repositories;

use App\Models\Dairy\Plant;

class PlantSettingsRepository
{
    public function findById(int $id): ?Plant
    {
        return Plant::find($id);
    }
}

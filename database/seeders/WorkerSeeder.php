<?php

namespace Database\Seeders;

use App\Models\Dairy\Plant;
use App\Models\Dairy\Position;
use App\Models\Dairy\Worker;
use App\Modules\Admin\Dairy\Organization\Worker\Repositories\Actions\CreateOrUpdateWorkerAction;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    public function run(): void
    {
        $action = app(CreateOrUpdateWorkerAction::class);

        $jefe   = Position::where('name', 'Jefe de planta')->first();
        $acopio = Position::where('name', 'Encargado de acopio')->first();

        if (!$jefe || !$acopio) {
            return;
        }

        $plants = Plant::with('entity')->orderBy('id')->get();
        $dni = 40000000; // base para DNIs únicos (usuario = contraseña = DNI)

        foreach ($plants as $index => $plant) {
            $entityId = $plant->entity?->id;
            if (!$entityId) {
                continue;
            }

            $n = $index + 1;
            $cityCode = $plant->city ?? 'planta';

            // 1) Administrador de planta (rol plant_manager → app-managers)
            $this->createWorker($action, [
                'entity_id'      => $entityId,
                'position_id'    => $jefe->id,
                'monthly_salary' => 3000,
                'document'       => (string) (++$dni),
                'name'           => 'Administrador',
                'paternal'       => 'Planta ' . $n,
                'email'          => "admin.planta{$n}@vialactea.test",
            ]);

            // 2) Acopiador de planta (rol plant_collector → panel de acopio)
            $this->createWorker($action, [
                'entity_id'      => $entityId,
                'position_id'    => $acopio->id,
                'monthly_salary' => 1500,
                'document'       => (string) (++$dni),
                'name'           => 'Acopiador',
                'paternal'       => 'Planta ' . $n,
                'email'          => "acopio.planta{$n}@vialactea.test",
            ]);
        }
    }

    private function createWorker(CreateOrUpdateWorkerAction $action, array $d): void
    {
        // Idempotencia: no recrear si el documento ya existe como trabajador
        if (Worker::whereHas('person', fn ($q) => $q->where('document_number', $d['document']))->exists()) {
            return;
        }

        $action->execute([
            'entity_id'      => $d['entity_id'],
            'position_id'    => $d['position_id'],
            'monthly_salary' => $d['monthly_salary'],
            'is_active'      => true,
            'person'         => [
                'document_type'    => '1', // DNI
                'document_number'  => $d['document'],
                'name'             => $d['name'],
                'paternal_surname' => $d['paternal'],
                'maternal_surname' => 'Vía Láctea',
                'email'            => $d['email'],
            ],
        ]);
    }
}

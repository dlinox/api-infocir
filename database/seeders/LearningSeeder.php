<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class LearningSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedAreas();
        $this->seedTrainingTypes();
    }

    private function seedAreas(): void
    {
        $items = [
            ['name' => 'Buenas Prácticas de Manufactura',    'description' => 'Normas y procedimientos para garantizar la calidad e inocuidad en la producción láctea'],
            ['name' => 'Inocuidad y Calidad',                'description' => 'Control de calidad, análisis microbiológico y fisicoquímico de productos lácteos'],
            ['name' => 'Seguridad y Salud en el Trabajo',    'description' => 'Prevención de riesgos laborales, uso de EPP y protocolos de seguridad en planta'],
            ['name' => 'Operaciones de Planta',              'description' => 'Manejo de equipos, líneas de producción, pasteurización y procesamiento de leche'],
            ['name' => 'Gestión Administrativa',             'description' => 'Procesos de logística, gestión de proveedores, inventarios y documentación operativa'],
        ];

        foreach ($items as $item) {
            DB::table('learning_areas')->insertOrIgnore([
                ...$item,
                'is_active'  => true,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedTrainingTypes(): void
    {
        $items = [
            ['name' => 'Taller práctico',  'description' => 'Capacitación con actividades prácticas y trabajo en grupos reducidos'],
            ['name' => 'Charla informativa', 'description' => 'Sesión expositiva de corta duración sobre un tema puntual'],
            ['name' => 'Curso e-learning',  'description' => 'Capacitación autogestionada a través de la plataforma virtual'],
            ['name' => 'Inducción',         'description' => 'Capacitación inicial para personal nuevo ingresante a la planta'],
            ['name' => 'Simulacro',         'description' => 'Ejercicio práctico de respuesta ante emergencias o situaciones de riesgo'],
        ];

        foreach ($items as $item) {
            DB::table('learning_training_types')->insertOrIgnore([
                ...$item,
                'is_active'  => true,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

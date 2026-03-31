<?php

namespace App\Common\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Este método se llama automáticamente por Laravel al bootear.
     */
    public function boot(): void
    {
        $this->routes(function () {
            $this->mapModuleRoutes(); // << Aquí usamos nuestro método personalizado
        });
    }

    /**
     * Registra automáticamente los archivos de rutas de cada módulo.
     * Busca archivos *.api.php dentro de cualquier carpeta Http/
     * sin importar el nivel de anidamiento dentro de Modules/.
     *
     * Ejemplos que detecta:
     *   Modules/Auth/Http/auth.api.php
     *   Modules/Administrator/Security/Http/security.api.php
     *   Modules/Administrator/Security/SubModule/Http/sub.api.php
     */
    protected function mapModuleRoutes(): void
    {
        $modulesPath = app_path('Modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($modulesPath, \FilesystemIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            // Solo archivos que terminen en .api.php y estén dentro de una carpeta Http/
            if (
                $file->isFile()
                && str_ends_with($file->getFilename(), '.api.php')
                && basename($file->getPath()) === 'Http'
            ) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($file->getRealPath());
            }
        }
    }
}

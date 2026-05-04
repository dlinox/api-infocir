# Plan: Renombrar PlantWorker → Worker (Backend + Frontend)

## Contexto

El frontend ya usa la carpeta `organization/worker/` y `Organization/Worker/` pero internamente sigue usando nombres como `PlantWorkerService`, `PlantWorker`, `PlantWorkerRequestDto`, etc. El backend usa `PlantWorker` en todo (modelo, módulo, rutas, clases). Se pide coherencia renombrando a solo "Worker".

**Nota importante**: La tabla de base de datos se queda como `dairy_plant_workers` (no se toca la migración). Solo cambian los nombres de clases, archivos, rutas y namespaces.

---

## BACKEND (api-infocir)

### 1. Renombrar modelo `PlantWorker.php` → `Worker.php`
- **Archivo**: `app/Models/Dairy/PlantWorker.php` → `app/Models/Dairy/Worker.php`
- Cambiar: `class PlantWorker` → `class Worker`
- Mantener: `$table = 'dairy_plant_workers'` (la tabla no cambia)

### 2. Renombrar carpeta del módulo `PlantWorker/` → `Worker/`
- **De**: `app/Modules/Admin/Dairy/Organization/PlantWorker/`
- **A**: `app/Modules/Admin/Dairy/Organization/Worker/`

### 3. Renombrar archivos y clases dentro del módulo
Todos cambian `PlantWorker` → `Worker` en nombre de archivo, clase y namespace:

| Antes | Después |
|-------|---------|
| `PlantWorkerController.php` | `WorkerController.php` |
| `PlantWorkerRequest.php` (+ carpeta `PlantWorker/` → `Worker/`) | `WorkerRequest.php` |
| `PlantWorkerDataTableItemResource.php` (+ carpeta) | `WorkerDataTableItemResource.php` |
| `PlantWorkerFormResource.php` (+ carpeta) | `WorkerFormResource.php` |
| `CreateOrUpdatePlantWorkerAction.php` | `CreateOrUpdateWorkerAction.php` |
| `PlantWorkerRepository.php` | `WorkerRepository.php` |
| `PlantWorkerService.php` | `WorkerService.php` |

### 4. Actualizar rutas backend
- **Archivo**: `plant-worker.api.php` → `worker.api.php`
- Prefijo: `/plant-workers` → `/workers`
- Nombres de ruta: `plant-workers.*` → `workers.*`
- Import del controller actualizado

### 5. Actualizar AppServiceProvider (morph map)
- **Archivo**: `app/Common/Providers/AppServiceProvider.php`
- Import: `use App\Models\Dairy\PlantWorker` → `use App\Models\Dairy\Worker`
- Morph map: `'dairy_plant_workers' => PlantWorker::class` → `'dairy_plant_workers' => Worker::class`

---

## FRONTEND (app-infocir)

### 6. Renombrar tipos/interfaces
- **`worker.model.ts`**: `PlantWorker` → `Worker`
- **`worker-request.dto.ts`**: `PlantWorkerRequestSchema` → `WorkerRequestSchema`, `PlantWorkerRequestDto` → `WorkerRequestDto`, `plantWorkerRequestDefaults` → `workerRequestDefaults`

### 7. Renombrar servicio
- **`worker.service.ts`**: `PlantWorkerService` → `WorkerService`, `BASE_URL` = `/plant-workers` → `/workers`

### 8. Actualizar composables que usan los tipos renombrados
- **`useWorkerTable.ts`**: Actualizar imports y uso de `PlantWorkerService` → `WorkerService`
- **`useWorkerForm.ts`**: Actualizar todos los imports y uso de `PlantWorkerService`, `PlantWorkerRequestSchema`, `plantWorkerRequestDefaults`, `PlantWorkerRequestDto`, `PlantWorker`

### 9. Actualizar rutas frontend
- **`organization.routes.ts`**: path `plant-workers` → `workers`, nombres `admin-plant-workers-*` → `admin-workers-*`

### 10. Actualizar sidebar
- **`Admin.layout.vue`**: Cambiar referencia `admin-plant-workers-view` → `admin-workers-view`

---

## Resumen de impacto

| Área | Archivos afectados |
|------|-------------------|
| Backend - Modelo | 1 archivo (renombrar + editar) |
| Backend - Módulo | 8 archivos (renombrar + editar namespaces/clases) |
| Backend - Rutas | 1 archivo (renombrar + editar) |
| Backend - Provider | 1 archivo (editar) |
| Frontend - App layer | 3 archivos (editar) |
| Frontend - UI layer | 2 composables + 1 ruta + 1 layout (editar) |
| **Total** | ~17 archivos |

No se tocan migraciones, seeders, ni la tabla de base de datos.

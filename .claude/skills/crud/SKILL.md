---
name: crud
description: Genera un CRUD completo de API para una entidad del proyecto. Crea Model, Migration, Repository, Service, Controller, Request, Resource y Routes siguiendo la arquitectura modular existente. Soporta dos variantes: Setting (catálogos simples) y Dairy (entidades complejas). Usar cuando el usuario pida crear un nuevo CRUD, módulo, entidad o catálogo.
---

# Skill: CRUD Generator

Genera todos los archivos necesarios para un CRUD de API REST siguiendo la arquitectura del proyecto.

## Uso

El usuario indica la entidad y sus campos. Ejemplo:
- "Crea un CRUD para Nationality"
- "Nuevo módulo para MaritalStatus con code string PK"
- "/crud dairy_suppliers" (entidad Dairy compleja)

## Parámetros a determinar

Antes de generar código, determina:

1. **Nombre de la entidad** (singular, PascalCase): ej. `Nationality`
   - IMPORTANTE: Si la tabla tiene prefijo de módulo (`dairy_`, `core_`, etc.), el nombre de la clase NO debe incluir ese prefijo
   - Ejemplo: `dairy_product_types` → Clase: `ProductType` (NO `DairyProductType`)
   - Ejemplo: `core_genders` → Clase: `Gender`
2. **Tabla** (plural, snake_case, con prefijo según módulo): ej. `core_nationalities`, `dairy_product_types`
3. **Tipo de PK**: `id` (auto-increment) o `code` (string)
4. **Campos adicionales** además de `name` e `is_active`
5. **Variante del módulo**:
   - **Setting** (default): catálogos simples → archivos en `Admin/Setting/`, ruta compartida en `setting.api.php`
   - **Dairy**: entidades complejas con lógica de negocio propia → archivos en `Admin/Dairy/{Area}/{Entity}/`, ruta propia `{entity}.api.php`
     - `{Area}` = `Organization` (Plant, PlantWorker, Supplier), `Catalog` (Product, Presentation), `Inventory` (StockMovement)
6. **Namespace del modelo**:
   - Tablas `core_*` → `App\Models\Core`
   - Tablas `dairy_*` → `App\Models\Dairy`
7. **Prefijo de ruta**: ej. `/nationalities`, `/product-types` (sin el prefijo de la tabla)
8. **Mensaje en español** para save/delete: ej. "Nacionalidad guardada correctamente"
9. **Endpoints adicionales** (preguntar si necesita):
   - `GET /get/{id}` — solo si el formulario es complejo y la tabla no envía todos los datos necesarios para edición
   - `GET /select-items` — si la entidad será referenciada como FK por otras
10. **Verificar si la tabla ya existe**:
    - Buscar por nombre de archivo: `Glob database/migrations/*{table_name}*.php`
    - Buscar en contenido de migraciones: `Grep "Schema::create('{table}'` en `database/migrations/`
    - Si existe, leer la migración para conocer la estructura real (campos, timestamps, etc.)

Si el usuario no especifica, asumir:
- PK tipo `id` (auto-increment)
- Campos: `name` (string 100, unique) + `is_active` (boolean, default true)
- Variante: `Setting`
- Sin timestamps (salvo que la tabla existente los tenga)

## Archivos a generar

**IMPORTANTE**: Si la tabla ya existe en una migración, NO crear una nueva migración. Solo generar los archivos de código + rutas.

**Verificación obligatoria**:
1. `Glob database/migrations/*{nombre_tabla}*.php`
2. `Grep "Schema::create('{nombre_tabla}'" database/migrations/`
3. Si existe, leer la migración para adaptar Model/Request/Resource a la estructura real

---

## Variante SETTING (catálogos simples)

Genera 7 archivos + 1 edición de ruta existente.

### 1. Migration — `database/migrations/{timestamp}_create_{table}_table.php`

**SOLO crear si la tabla NO existe**. Verificar primero.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{table}', function (Blueprint $table) {
            // PK id:
            $table->id();
            // PK code:
            // $table->char('code', {length})->unique();
            // $table->primary('code');

            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->index('name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{table}');
    }
};
```

### 2. Model — `app/Models/{Core|Dairy}/{Entity}.php`

Namespace según prefijo de tabla: `core_*` → `App\Models\Core`, `dairy_*` → `App\Models\Dairy`.

```php
<?php

namespace App\Models\{Core|Dairy};

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class {Entity} extends Model
{
    use HasDataTable;

    protected $table = '{table}';
    public $timestamps = false;

    // Solo para PK string:
    // protected $primaryKey = 'code';
    // protected $keyType = 'string';
    // public $incrementing = false;

    protected $fillable = [
        // 'code', ← solo PK string
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
    ];
}
```

### 3. Repository — `app/Modules/Admin/Setting/Repositories/{Entity}Repository.php`

#### Variante PK `id` (auto-increment):

```php
<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\{Core|Dairy}\{Entity};

class {Entity}Repository
{
    public function dataTable($request)
    {
        $query = {Entity}::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            ${entity} = {Entity}::findOrFail($data['id']);
            ${entity}->update($data);
            return ${entity};
        }

        return {Entity}::create($data);
    }

    public function delete(string $id)
    {
        ${entity} = {Entity}::findOrFail($id);
        ${entity}->delete();
        return ${entity};
    }

    // Solo si necesita select-items:
    // public function getSelectItems()
    // {
    //     return {Entity}::where('is_active', true)->orderBy('name')->get();
    // }
}
```

#### Variante PK `code` (string):

```php
public function createOrUpdate(array $data)
{
    return {Entity}::updateOrCreate(['code' => $data['code']], $data);
}

public function delete(string $code)
{
    ${entity} = {Entity}::where('code', $code)->firstOrFail();
    ${entity}->delete();
    return ${entity};
}
```

### 4. Service — `app/Modules/Admin/Setting/Services/{Entity}Service.php`

```php
<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\{Entity}Repository;

class {Entity}Service
{
    public function __construct(
        private {Entity}Repository ${entity}Repository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->{entity}Repository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->{entity}Repository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->{entity}Repository->delete($id);
    }

    // Solo si necesita select-items:
    // public function getSelectItems()
    // {
    //     return $this->{entity}Repository->getSelectItems();
    // }
}
```

### 5. Controller — `app/Modules/Admin/Setting/Http/Controllers/{Entity}Controller.php`

```php
<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\{Entity}\{Entity}Request;
use App\Modules\Admin\Setting\Http\Resources\{Entity}\{Entity}DataTableItemResource;
use App\Modules\Admin\Setting\Services\{Entity}Service;

class {Entity}Controller
{
    public function __construct(
        private {Entity}Service ${entity}Service
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->{entity}Service->dataTable($request);
        $items['data'] = {Entity}DataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save({Entity}Request $request)
    {
        $data = $request->validated();
        $this->{entity}Service->save($data);
        return ApiResponse::success($data, '{EntityLabel} guardado/a correctamente');
    }

    public function delete(string $id)
    {
        $this->{entity}Service->delete($id);
        return ApiResponse::success(null, '{EntityLabel} eliminado/a correctamente');
    }

    // Solo si necesita select-items:
    // public function getSelectItems()
    // {
    //     $items = $this->{entity}Service->getSelectItems();
    //     $items = {Entity}SelectItemResource::collection($items);
    //     return ApiResponse::success($items);
    // }
}
```

### 6. Request — `app/Modules/Admin/Setting/Http/Requests/{Entity}/{Entity}Request.php`

```php
<?php

namespace App\Modules\Admin\Setting\Http\Requests\{Entity};

use App\Common\Http\Requests\ApiFormRequest;

class {Entity}Request extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'        => 'nullable|integer',
            'name'      => 'required|string|max:100|unique:{table},name,' . $id . ',id',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El :attribute es requerido.',
            'name.string'        => 'El :attribute debe ser una cadena de texto.',
            'name.max'           => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'        => 'El :attribute ya existe.',
            'is_active.required' => 'El :attribute es requerido.',
            'is_active.boolean'  => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'        => 'ID',
            'name'      => 'Nombre',
            'is_active' => 'Estado',
        ];
    }
}
```

### 7. Resource — `app/Modules/Admin/Setting/Http/Resources/{Entity}/{Entity}DataTableItemResource.php`

```php
<?php

namespace App\Modules\Admin\Setting\Http\Resources\{Entity};

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {Entity}DataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,       ← PK id
            // 'code' => $this->code,    ← PK code
            'name'     => $this->name,
            'isActive' => $this->is_active,
        ];
    }
}
```

### 8. Routes — editar `app/Modules/Admin/Setting/Http/setting.api.php`

Agregar al archivo existente:

```php
use App\Modules\Admin\Setting\Http\Controllers\{Entity}Controller;

Route::middleware(['auth:api'])->prefix('/{route-prefix}')->group(function () {
    Route::post('/data-table', [{Entity}Controller::class, 'dataTable']);
    Route::post('/save', [{Entity}Controller::class, 'save']);
    Route::delete('/delete/{id}', [{Entity}Controller::class, 'delete']);
    // Solo si necesita select-items:
    // Route::get('/select-items', [{Entity}Controller::class, 'getSelectItems']);
});
```

---

## Variante DAIRY (entidades complejas)

Genera 7+ archivos con ruta propia. Cada entidad tiene su propio submódulo bajo `Admin/Dairy/{Area}/{Entity}/`.

### Estructura de directorios

```
app/Modules/Admin/Dairy/{Area}/{Entity}/
├── Http/
│   ├── Controllers/{Entity}Controller.php
│   ├── Requests/{Entity}/{Entity}Request.php
│   ├── Resources/{Entity}/
│   │   ├── {Entity}DataTableItemResource.php
│   │   └── {Entity}FormResource.php          ← si tiene GET /get/{id}
│   └── {entity}.api.php                      ← ruta propia
├── Repositories/
│   └── {Entity}Repository.php
└── Services/
    └── {Entity}Service.php
```

### Diferencias clave vs Setting:

| Aspecto | Setting | Dairy |
|---------|---------|-------|
| Namespace | `App\Modules\Admin\Setting\...` | `App\Modules\Admin\Dairy\{Area}\{Entity}\...` |
| Modelo | `App\Models\Core\{Entity}` o `App\Models\Dairy\{Entity}` | `App\Models\Dairy\{Entity}` |
| Rutas | Se agregan a `setting.api.php` compartido | Archivo propio `{entity}.api.php` |
| Endpoints | 3 base + select-items opcional | 3 base + `GET /get/{id}` + `select-items` opcionales |
| Resources | Solo `DataTableItemResource` | `DataTableItemResource` + `FormResource` (para getById) |

### Model — `app/Models/Dairy/{Entity}.php`

```php
<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class {Entity} extends Model
{
    use HasDataTable;

    protected $table = '{table}';

    protected $fillable = [
        // campos según migración
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
    ];

    // Relaciones según necesidad
}
```

### Repository — `app/Modules/Admin/Dairy/{Area}/{Entity}/Repositories/{Entity}Repository.php`

```php
<?php

namespace App\Modules\Admin\Dairy\{Area}\{Entity}\Repositories;

use App\Models\Dairy\{Entity};

class {Entity}Repository
{
    public function dataTable($request)
    {
        $query = {Entity}::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById($id)
    {
        return {Entity}::findOrFail($id);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            ${entity} = {Entity}::findOrFail($data['id']);
            ${entity}->update($data);
            return ${entity};
        }

        return {Entity}::create($data);
    }

    public function delete(string $id)
    {
        ${entity} = {Entity}::findOrFail($id);
        ${entity}->delete();
        return ${entity};
    }

    // Solo si necesita select-items:
    // public function getSelectItems()
    // {
    //     return {Entity}::where('is_active', true)->orderBy('name')->get();
    // }
}
```

### Service — `app/Modules/Admin/Dairy/{Area}/{Entity}/Services/{Entity}Service.php`

```php
<?php

namespace App\Modules\Admin\Dairy\{Area}\{Entity}\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\{Area}\{Entity}\Repositories\{Entity}Repository;

class {Entity}Service
{
    public function __construct(
        private {Entity}Repository ${entity}Repository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->{entity}Repository->dataTable($request);
    }

    public function findById($id)
    {
        return $this->{entity}Repository->findById($id);
    }

    public function save(array $data)
    {
        return $this->{entity}Repository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->{entity}Repository->delete($id);
    }
}
```

### Controller — `app/Modules/Admin/Dairy/{Area}/{Entity}/Http/Controllers/{Entity}Controller.php`

```php
<?php

namespace App\Modules\Admin\Dairy\{Area}\{Entity}\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\{Area}\{Entity}\Http\Requests\{Entity}\{Entity}Request;
use App\Modules\Admin\Dairy\{Area}\{Entity}\Http\Resources\{Entity}\{Entity}DataTableItemResource;
use App\Modules\Admin\Dairy\{Area}\{Entity}\Http\Resources\{Entity}\{Entity}FormResource;
use App\Modules\Admin\Dairy\{Area}\{Entity}\Services\{Entity}Service;

class {Entity}Controller
{
    public function __construct(
        private {Entity}Service ${entity}Service
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->{entity}Service->dataTable($request);
        $items['data'] = {Entity}DataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById($id)
    {
        ${entity} = $this->{entity}Service->findById($id);
        return ApiResponse::success(new {Entity}FormResource(${entity}));
    }

    public function save({Entity}Request $request)
    {
        $data = $request->validated();
        $this->{entity}Service->save($data);
        return ApiResponse::success($data, '{EntityLabel} guardado/a correctamente');
    }

    public function delete(string $id)
    {
        $this->{entity}Service->delete($id);
        return ApiResponse::success(null, '{EntityLabel} eliminado/a correctamente');
    }
}
```

### Routes — `app/Modules/Admin/Dairy/{Area}/{Entity}/Http/{entity}.api.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\{Area}\{Entity}\Http\Controllers\{Entity}Controller;

Route::middleware(['auth:api'])->prefix('/{route-prefix}')->group(function () {
    Route::post('/data-table', [{Entity}Controller::class, 'dataTable']);
    Route::get('/get/{id}', [{Entity}Controller::class, 'getById']);
    Route::post('/save', [{Entity}Controller::class, 'save']);
    Route::delete('/delete/{id}', [{Entity}Controller::class, 'delete']);
    // Solo si necesita select-items:
    // Route::get('/select-items', [{Entity}Controller::class, 'getSelectItems']);
});
```

---

## Checklist post-generación

1. Si generó migración, ejecutar: `php artisan migrate`
2. Si la entidad tiene relación con `core_profiles` (MorphOne), registrar en `AppServiceProvider::boot()` morph map: `'nombre_tabla' => Model::class`
3. Verificar rutas: `php artisan route:list --path={route-prefix}`
4. Probar endpoints con auth JWT

## Placeholders

| Placeholder | Ejemplo | Ejemplo 2 | Descripción |
|---|---|---|---|
| `{Entity}` | `Nationality` | `ProductType` | PascalCase singular (SIN prefijo de tabla) |
| `{entity}` | `nationality` | `productType` | camelCase singular (para variables) |
| `{table}` | `core_nationalities` | `dairy_product_types` | snake_case plural con prefijo según módulo |
| `{route-prefix}` | `nationalities` | `product-types` | kebab-case plural para URLs (sin prefijo de tabla) |
| `{EntityLabel}` | `Nacionalidad` | `Tipo de producto` | Nombre en español para mensajes |
| `{Core\|Dairy}` | `Core` | `Dairy` | Namespace del modelo según prefijo de tabla |
| `{Area}` | `Organization` | `Catalog` | Área funcional para variante Dairy: `Organization`, `Catalog`, `Inventory` |

### Regla de prefijos

- Si tabla = `dairy_product_types` → Entity = `ProductType`, route-prefix = `product-types`, Model ns = `Dairy`
- Si tabla = `core_genders` → Entity = `Gender`, route-prefix = `genders`, Model ns = `Core`
- **Los nombres de clase NUNCA incluyen el prefijo de la tabla** (`dairy_`, `core_`, etc.)

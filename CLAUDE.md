# CLAUDE.md — api-infocir

## Proyecto

API REST para sistema INFOCIR, construida con Laravel 13.x y PHP 8.3+.

## Frontend asociado

Cuando se pida implementar código frontend junto con (o además de) backend, **SIEMPRE leer primero** los CLAUDE.md del monorepo frontend antes de escribir cualquier componente, composable o servicio:

- `c:\lnx\projects\infocir\workspace-infocir\CLAUDE.md` — reglas universales (imports, naming, Vuetify, utils, etc.)
- `c:\lnx\projects\infocir\workspace-infocir\app-managers\CLAUDE.md` — si el trabajo es para `app-managers` (usuarios finales)

Leer ambos archivos con `read_file` **antes** de generar cualquier archivo `.vue`, `.ts` de componente, composable o servicio frontend.

## Arquitectura

- **Estructura modular**: `app/Modules/Admin/{Module}/`
- **Patrón por módulo**: Model → Repository → Service → Controller → Request → Resource → Routes
- **Sin clases base** para Repository, Service ni Controller. Todos son plain PHP classes con inyección por constructor.
- **Organización de modelos**:
  - Modelos core/settings: `app/Models/Core/` (Gender, DocumentType, etc.)
  - Modelos del módulo Dairy: `app/Models/Dairy/` (Plant, CompanyType, Position, etc.)
- **Organización de módulos**:
  - Settings simples: `app/Modules/Admin/Setting/` (Gender, DocumentType, Profession, CompanyType, Position, ProductType, etc.)
  - Entidades Dairy complejas: `app/Modules/Admin/Dairy/{Area}/{Entity}/` — agrupadas por área funcional:
    - **Organization** (plantas, trabajadores y proveedores): `Dairy/Organization/Plant/`, `Dairy/Organization/Worker/`, `Dairy/Organization/Supplier/`
    - **Catalog** (productos y presentaciones): `Dairy/Catalog/Product/`, `Dairy/Catalog/Presentation/` (consolida ProductPresentation, ProductFormula, ProductPrice y PlantProduct)
    - **Inventory** (movimientos de stock): `Dairy/Inventory/StockMovement/`
- **Clases compartidas** en `app/Common/`:
  - `ApiResponse` — envelope JSON estándar para todas las respuestas
  - `ApiFormRequest` — convierte camelCase↔snake_case automáticamente, errores de validación en español
  - `HasDataTable` — trait Eloquent para paginación, filtro, búsqueda y ordenamiento server-side
- **Morph map**: Entidades con relaciones polimórficas se registran en `AppServiceProvider::boot()` usando nombre de tabla como clave

## Reglas obligatorias

### Estructura de archivos
- NO crear carpetas nuevas si ya existe una para ese propósito
- **Modelos**:
  - Core/Settings: `app/Models/Core/` (Gender, DocumentType, UnitMeasure, InstructionDegree, Profession, etc.)
  - Dairy: `app/Models/Dairy/` (Plant, CompanyType, Position, TrainingLevel, InstitutionType, ProductType, Worker, etc.)
- **Módulos**: todos bajo `app/Modules/Admin/`
  - Settings simples (catálogos): `app/Modules/Admin/Setting/` — archivos organizados por tipo (Repositories/, Services/, Http/)
    - Incluye tanto catálogos Core como Dairy: Gender, DocumentType, CompanyType, Position, ProductType, etc.
  - Entidades Dairy complejas (con lógica de negocio propia): agrupadas por área funcional bajo `app/Modules/Admin/Dairy/`
    - **Organization** (plantas, trabajadores y proveedores): `Dairy/Organization/Plant/`, `Dairy/Organization/Worker/`, `Dairy/Organization/Supplier/`
    - **Catalog** (productos y presentaciones): `Dairy/Catalog/Product/` y `Dairy/Catalog/Presentation/`
      - `Presentation/` es un módulo consolidado que agrupa: ProductPresentation, ProductFormula, ProductPrice y PlantProduct (comparten Controllers/, Repositories/, Services/ y Http/)
    - **Inventory** (movimientos de stock): `Dairy/Inventory/StockMovement/`
- Las rutas van en archivos `*.api.php` dentro de carpetas `Http/` (se auto-descubren recursivamente)

### Naming conventions
- **DB columns**: `snake_case` → `is_active`, `document_type`, `paternal_surname`
- **JSON responses**: `camelCase` → `isActive`, `documentType`, `paternalSurname`
- **Tablas**: plural snake_case con prefijo según módulo:
  - Core: `core_genders`, `core_persons`
  - Dairy: `dairy_product_types`, `dairy_plants`
- **Modelos**: singular PascalCase **SIN el prefijo de la tabla**
  - `core_genders` → `Gender`
  - `dairy_product_types` → `ProductType` (NO `DairyProductType`)
  - `core_instruction_degrees` → `InstructionDegree`
  - **Excepción**: `dairy_plant_workers` → `Worker` (NO `PlantWorker`). Modelo, módulo y ruta usan `Worker`/`workers`
- **Rutas**: kebab-case plural **sin el prefijo de la tabla**
  - `core_genders` → `/genders`
  - `dairy_product_types` → `/product-types`
  - **Excepción**: `dairy_plant_workers` → `/workers` (NO `/plant-workers`)
- **Controllers, Services, Repositories**: `{Entity}Controller`, `{Entity}Service`, `{Entity}Repository`
  - Donde `{Entity}` es el nombre del modelo sin prefijo
- **Requests**: `app/.../Requests/{Entity}/{Entity}Request.php`
- **Resources**: `app/.../Resources/{Entity}/{Entity}DataTableItemResource.php`

### Código
- Usar `ApiResponse::success($data, $message)` para TODAS las respuestas exitosas
- Mensajes de respuesta SIEMPRE en español: "Género guardado correctamente"
- FormRequests SIEMPRE extienden `ApiFormRequest` (no `FormRequest` directamente)
- **Claves en `rules()`, `messages()` y `attributes()` SIEMPRE en `snake_case`**: `ApiFormRequest` convierte automáticamente el JSON camelCase entrante a snake_case antes de la validación. Por lo tanto usar `course_id`, `is_active`, `duration_min` — NUNCA `courseId`, `isActive`, `durationMin`
- Modelos de catálogo: `public $timestamps = false` solo si la migración NO tiene `$table->timestamps()`, usar trait `HasDataTable`
- Controllers NO extienden ninguna clase base
- Inyección de dependencias vía constructor con PHP 8 promoted properties
- Validación: `messages()` y `attributes()` en español
- En Resources, para concatenar nombres usar `collect([$name, $paternal, $maternal])->filter()->implode(' ')` (nunca `trim()` con interpolación)

### Resources (DataTableItemResource)
- **Relaciones como objetos**: NUNCA exponer IDs planos de FKs si ya existe el objeto de la relación. Usar acceso directo nullable:
  ```php
  'companyType' => $this->companyType ? ['id' => $this->companyType->id, 'name' => $this->companyType->name] : null,
  ```
- **NO usar `whenLoaded()`**: acceder a las relaciones directamente con null-check (`$this->relation ? [...] : null`)
- **Entidades con `person` (Worker, Supplier)**: NO usar `with('person')` en el Repository. Usar `join` + `select` con columnas aliasadas (`person_name`, `person_paternal_surname`, etc.) y acceder como `$this->person_name` en el Resource. Agrupar los datos de persona en un objeto `person`:
  ```php
  'person' => [
      'fullName'       => collect([$this->person_name, $this->person_paternal_surname, $this->person_maternal_surname])->filter()->implode(' '),
      'documentType'   => $this->person_document_type,
      'documentNumber' => $this->person_document_number,
  ],
  ```
- **`$searchColumns`**: entidades con join deben declarar `public array $searchColumns` en el modelo con columnas prefijadas por tabla (ej: `'core_persons.name'`, `'dairy_suppliers.trade_name'`). El trait `HasDataTable` las usa automáticamente
- **FormResources**: mantienen IDs planos (`plantId`, `positionId`) porque son para binding de formularios — NO aplican las reglas de objetos
- **Colisión columna/relación**: si una columna y una relación tienen el mismo nombre (ej: `country` string + `country()` BelongsTo), renombrar la relación con sufijo `Relation` (`countryRelation()`) y acceder con `$this->countryRelation`

### Rutas

Cada entidad tiene un **conjunto base** de 3 endpoints:
- `POST /{prefix}/data-table` — listar con paginación
- `POST /{prefix}/save` — crear o actualizar (no usar PUT/PATCH separados)
- `DELETE /{prefix}/delete/{id}` — eliminar

**Endpoints opcionales** según necesidad:
- `GET /{prefix}/get/{id}` — obtener por ID. Solo para formularios complejos donde la tabla no envía todos los datos necesarios para el formulario de edición
- `GET /{prefix}/select-items` — items para dropdowns/selects (entidades referenciadas como FK en otras)

SIEMPRE protegidas con middleware `auth:api`.

### Base de datos
- No usar timestamps en tablas de catálogo/setting
- Campos `is_active` con `default(true)`
- Índices en columnas frecuentemente consultadas (`name`, `is_active`)
- Migraciones como anonymous classes (`return new class extends Migration`)

### Seeders
- Datos semilla en `database/seeders/`
- `CoreSeeder` → datos core (countries, document_types, genders, unit_measures)
- `DairySeeder` → datos dairy (company_types, training_levels, positions, product_types, instruction_degrees, professions, institution_types)
- Registrar nuevos seeders en `DatabaseSeeder.php` respetando el orden de dependencias

### Morph map
- Entidades con `core_profiles` (relaciones polimórficas) deben registrarse en `AppServiceProvider::boot()`
- Formato: `'nombre_tabla' => Model::class` (ej: `'dairy_plant_workers' => Worker::class`)

### Iconos (Frontend)
- **Librería**: Phosphor Icons vía UnoCSS — prefijo `i-ph-*` (ej: `i-ph-house-fill`, `i-ph-video-camera`, `i-ph-calendar-check-fill`)
- Se usan como clases CSS directamente en props de Vuetify (`prepend-icon`, `prepend-inner-icon`, `append-icon`) y como clases en etiquetas `<i>` o `<span>`
- **NUNCA** usar iconos Material Design (`mdi-*`) ni Font Awesome (`fa-*`)
- **`v-icon` en Vuetify**: SIEMPRE usar la prop `icon`, NUNCA el nombre del ícono como texto/slot:
  - ✅ `<v-icon icon="i-ph-x-circle" />` / `<v-icon start icon="i-ph-check-circle" />`
  - ❌ `<v-icon>i-ph-x-circle</v-icon>` — el ícono NO se renderiza

### Lo que NO hacer
- NO crear archivos de test a menos que se pida explícitamente
- NO agregar docblocks, PHPDoc ni comentarios innecesarios
- NO refactorizar código existente a menos que se pida
- NO crear helpers, traits o abstracciones para operaciones que se usan una sola vez
- NO usar `resource` routes de Laravel (el proyecto usa rutas explícitas)
- NO modificar archivos en `app/Common/` sin autorización explícita
- NO agregar paquetes al composer.json sin consultar primero
- NO duplicar servicios/repositorios en módulos que no corresponden (ej: no poner PlantService en Setting)

### Menú de navegación (OBLIGATORIO en implementaciones frontend)

Cada vez que se agrega una nueva vista con ruta de nivel superior o una nueva sección de Settings, **SIEMPRE** actualizar el menú de navegación en:

- `app-infocir`: `src/ui/modules/Admin/layouts/AdminLayout/Admin.layout.vue`

**Reglas del menú**:
- Vistas principales → `v-list-item` con `prepend-icon` en el bloque principal del drawer
- Sub-secciones de Ajustes → `v-list-item` dentro del `v-list-group value="settings"` al final del drawer
- Icono: SIEMPRE `i-ph-*` (Phosphor Icons)
- Propiedad de navegación: `:to="{ name: 'nombre-de-la-ruta' }"` + `value="identificador-unico"`

### Edición de archivos sin dejar código duplicado
- **SIEMPRE** leer el contenido completo del archivo antes de editarlo
- `oldString` en `replace_string_in_file` debe incluir TODO el bloque a reemplazar (función completa, clase completa, sección completa), no solo las primeras líneas
- Si el cambio afecta más del 50% del archivo, reescribir el archivo completo en lugar de hacer reemplazos parciales
- Después de editar, releer la sección modificada para verificar que no quedó código duplicado (nuevo + antiguo coexistiendo)

## Skills disponibles

- `/crud` — Genera un CRUD completo para una entidad siguiendo el patrón del proyecto. Soporta variantes:
  - **Setting** (catálogos simples): archivos bajo `Admin/Setting/`, modelo en `Models/Core/` o `Models/Dairy/`
  - **Dairy** (entidades complejas): archivos bajo `Admin/Dairy/{Dominio}/{Entity}/`, modelo en `Models/Dairy/`

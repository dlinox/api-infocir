# Tareas pendientes — Módulo Setting

## [PENDIENTE] Crear SelectItemResource para 3 entidades

Las siguientes entidades usan `DataTableItemResource` en `getSelectItems()` cuando deberían tener su propio `SelectItemResource` dedicado.

### Archivos a crear

#### `AssetCatalogSelectItemResource`
```
app/Modules/Admin/Setting/Http/Resources/AssetCatalog/AssetCatalogSelectItemResource.php
```
```php
'title'              => name
'value'              => id
'investmentCategory' => ['id', 'name', 'group']
'usefulLifeYears'    => useful_life_years
'depreciationMethod' => depreciation_method
```

#### `PreOperativeCatalogSelectItemResource`
```
app/Modules/Admin/Setting/Http/Resources/PreOperativeCatalog/PreOperativeCatalogSelectItemResource.php
```
```php
'title'              => name
'value'              => id
'investmentCategory' => ['id', 'name', 'group']
'validityYears'      => validity_years
'recurrenceType'     => recurrence_type
```

#### `WorkingCapitalCatalogSelectItemResource`
```
app/Modules/Admin/Setting/Http/Resources/WorkingCapitalCatalog/WorkingCapitalCatalogSelectItemResource.php
```
```php
'title'              => name
'value'              => id
'investmentCategory' => ['id', 'name', 'group']
'unitMeasure'        => ['id', 'name', 'abbreviation']
```

### Archivos a actualizar

Reemplazar `DataTableItemResource` por el nuevo `SelectItemResource` en `getSelectItems()`:

- `app/Modules/Admin/Setting/Http/Controllers/AssetCatalogController.php` — línea 40
- `app/Modules/Admin/Setting/Http/Controllers/PreOperativeCatalogController.php` — línea 38
- `app/Modules/Admin/Setting/Http/Controllers/WorkingCapitalCatalogController.php` — línea 38

### Notas
- Los repositorios ya cargan las relaciones correctamente con `with()`, no necesitan cambios.
- Confirmar con el frontend si los campos extra propuestos son suficientes o se necesitan más.

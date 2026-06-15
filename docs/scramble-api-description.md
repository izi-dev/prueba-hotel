# API REST — Sistema de Gestión Hotelera

API versionada bajo **`/api/v1`** para administrar hoteles, consultar catálogos maestros y configurar la distribución de habitaciones por tipo y acomodación.

## Arquitectura

- **Backend:** Laravel 13 · PHP 8.4 · arquitectura hexagonal (Domain / Application / Infrastructure).
- **Persistencia:** PostgreSQL.
- **Contrato:** JSON sobre HTTP; colecciones envueltas en `{ "data": [...] }` (Laravel API Resources).
- **Autenticación:** no requerida en esta prueba técnica (endpoints públicos).

## Convenciones

| Aspecto | Detalle |
|--------|---------|
| **Base URL** | `{APP_URL}/api/v1` |
| **Content-Type** | `application/json` en peticiones con cuerpo |
| **Identificadores** | Enteros autoincrementales |
| **Errores de validación (422)** | `{ "message": "...", "errors": { "campo": ["..."] } }` |
| **Errores de dominio (404/422)** | `{ "message": "Descripción legible del error" }` |
| **Eliminaciones exitosas** | HTTP `204` sin cuerpo |

## Catálogos

Datos sembrados al desplegar (`CatalogSeeder`). Son de solo lectura vía API.

### Tipos de habitación

| Slug | Nombre |
|------|--------|
| `estandar` | Estándar |
| `junior` | Junior |
| `suite` | Suite |

### Reglas tipo ↔ acomodación

| Tipo | Acomodaciones permitidas |
|------|--------------------------|
| **Estándar** | Sencilla, Doble |
| **Junior** | Triple, Cuádruple |
| **Suite** | Sencilla, Doble, Triple |

Use `GET /room-types/{roomTypeId}/accommodations` para obtener solo las acomodaciones válidas de un tipo antes de crear o editar configuraciones.

## Hoteles

Un hotel tiene: nombre, dirección, ciudad (`city_id`), **NIT único** (formato `12345678-9`) y **máximo de habitaciones** (`max_rooms`).

Al consultar un hotel se incluyen:

- `configured_rooms` — suma de cantidades en configuraciones.
- `available_rooms` — `max_rooms - configured_rooms`.
- `room_configurations` — detalle de cada combinación tipo/acomodación.

## Configuraciones de habitación

Cada configuración asocia a un hotel:

- `room_type_id` — tipo de habitación.
- `accommodation_id` — acomodación (debe ser compatible con el tipo).
- `quantity` — número de habitaciones de esa combinación.

### Reglas de negocio

1. **Unicidad:** no puede repetirse la misma tripleta `(hotel_id, room_type_id, accommodation_id)`.
2. **Capacidad:** la suma de `quantity` de todas las configuraciones del hotel no puede superar `max_rooms`.
3. **Compatibilidad:** la acomodación debe estar permitida para el tipo seleccionado.
4. **NIT único:** dos hoteles no pueden compartir el mismo NIT.
5. **Reducción de cupo:** no se puede bajar `max_rooms` por debajo de las habitaciones ya configuradas.

## Flujo recomendado (SPA / integración)

1. `GET /cities` — poblar selector de ciudad.
2. `GET /room-types` — tipos disponibles.
3. `POST /hotels` — crear hotel.
4. `GET /room-types/{id}/accommodations` — acomodaciones válidas al agregar habitaciones.
5. `POST /hotels/{hotelId}/room-configurations` — agregar configuraciones.
6. `GET /hotels/{id}` — ver resumen con totales.

## Documentación adicional

- Especificación OpenAPI manual: `/documentacion/openapi`
- Diagramas UML: `/documentacion/uml`
- Código fuente: [github.com/izi-dev/prueba-hotel](https://github.com/izi-dev/prueba-hotel)

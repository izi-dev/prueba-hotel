# Sistema de Gestión Hotelera

API REST en Laravel 13 con arquitectura hexagonal (Domain, Application, Infrastructure) y frontend React desacoplado.

## Requisitos

- PHP 8.3+
- Composer
- Node.js 20+
- PostgreSQL 15+

## Instalación local

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configurar DB_* en .env para PostgreSQL
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

La aplicación estará en `http://localhost:8000` y la API en `http://localhost:8000/api/v1`.

Desarrollo con hot-reload:

```bash
composer dev
```

## Tests y cobertura

```bash
php artisan test --coverage
```

## Arquitectura

```
src/
├── Domain/           # Entidades, interfaces de repositorio, reglas de negocio
├── Application/      # Casos de uso (un handler por operación)
└── Infrastructure/   # Implementaciones Eloquent

app/Http/
├── Controllers/Api/  # Un controlador por endpoint (SRP)
├── Requests/         # Validación de entrada
└── Resources/        # Transformación de respuesta JSON
```

## Documentación

- [API REST (OpenAPI manual)](docs/openapi.yaml)
- [Documentación interactiva (Scramble)](/docs/api) — generada automáticamente en `/docs/api` y `/docs/api.json`
- [Diagramas UML](docs/uml.md)
- [Despliegue en la nube](docs/deployment.md)

## Criterios de negocio implementados

| Regla | Implementación |
|-------|----------------|
| Estándar → Sencilla, Doble | `room_type_accommodation` + `RoomTypeAccommodationRules` |
| Junior → Triple, Cuádruple | Idem |
| Suite → Sencilla, Doble, Triple | Idem |
| Máximo de habitaciones | Validado en handlers de configuración |
| Sin hoteles duplicados | NIT único |
| Sin configuraciones duplicadas | Unique `(hotel_id, room_type_id, accommodation_id)` |

## Endpoints principales

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/v1/cities` | Catálogo de ciudades |
| GET | `/api/v1/room-types` | Tipos de habitación |
| GET | `/api/v1/room-types/{id}/accommodations` | Acomodaciones válidas por tipo |
| GET/POST | `/api/v1/hotels` | Listar / crear hoteles |
| GET/PUT/DELETE | `/api/v1/hotels/{id}` | Detalle / actualizar / eliminar |
| GET/POST | `/api/v1/hotels/{id}/room-configurations` | Configuraciones de habitación |

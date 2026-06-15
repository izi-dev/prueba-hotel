# Despliegue en Dokploy

## 1. Repositorio y CI

Sube el código a GitHub. El workflow `.github/workflows/ci.yml` valida backend (Pint, PHPStan, Rector, Pest) y frontend (build Vite).

## 2. Base de datos PostgreSQL en Dokploy

Crea un servicio **PostgreSQL** en Dokploy y anótalo en la red `dokploy-network`.

## 3. Aplicación (Docker Compose)

En Dokploy, crea un proyecto tipo **Docker Compose** apuntando a este repositorio.

Archivos relevantes:
- `Dockerfile` — build multi-stage (Composer + Vite → FrankenPHP)
- `docker-compose.yml` — servicio `app` en puerto 8080

## 4. Variables de entorno

```env
APP_NAME="Gestión Hotelera"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
# APP_URL debe usar https:// con TLS; si queda en http://, el navegador bloqueará CSS/JS (mixed content).
APP_KEY=base64:...

DB_CONNECTION=pgsql
DB_HOST=<host-postgres-dokploy>
DB_PORT=5432
DB_DATABASE=prueba_hoteles
DB_USERNAME=postgres
DB_PASSWORD=...

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## 5. Red Docker

El `docker-compose.yml` usa la red externa `dokploy-network`. Dokploy la crea automáticamente; si despliegas manualmente:

```bash
docker network create dokploy-network
```

## 6. Primer deploy

El contenedor ejecuta automáticamente (vía `AUTORUN_*`):
- Migraciones (`--force`)
- Seed de catálogos (`AUTORUN_LARAVEL_MIGRATION_SEED=true`)
- `storage:link`
- Optimización de eventos

## 7. Documentación API

Con la app en marcha, Scramble expone la documentación en:

```
/docs/api
```

## 8. Build local (prueba)

```bash
docker compose build
docker compose up -d
```

Asegúrate de tener `.env` configurado y la red `dokploy-network` creada.

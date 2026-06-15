<?php

declare(strict_types=1);

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Tag;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Proveedor de servicios principal de la aplicación.
 *
 * Configura aspectos globales de la API, como la documentación OpenAPI
 * generada con Scramble y el acceso a la misma.
 */
final class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra servicios en el contenedor de dependencias.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializa servicios tras el arranque de la aplicación.
     *
     * Limita la documentación Scramble a las rutas bajo `api/v1`
     * y define el permiso `viewApiDocs` para consultar la documentación.
     */
    public function boot(): void
    {
        if (
            $this->app->environment('production')
            || str_starts_with((string) config('app.url'), 'https://')
            || (! $this->app->runningInConsole() && request()->isSecure())
        ) {
            URL::forceScheme('https');
        }

        Scramble::configure()
            ->routes(fn (Route $route): bool => Str::startsWith(haystack: $route->uri, needles: 'api/v1'));

        Scramble::afterOpenApiGenerated(function (OpenApi $openApi): void {
            $tagDescriptions = [
                'Catálogos' => 'Consulta de datos maestros: ciudades, tipos de habitación y acomodaciones. Incluye el filtrado de acomodaciones válidas por tipo.',
                'Hoteles' => 'CRUD de hoteles con validación de NIT único, ciudad existente y control del cupo máximo de habitaciones.',
                'Configuraciones' => 'Gestión de la distribución de habitaciones por hotel según tipo, acomodación y cantidad, respetando reglas de negocio.',
            ];

            $documentedTags = collect($openApi->tags)->keyBy(fn (Tag $tag): string => $tag->name);

            foreach ($tagDescriptions as $name => $description) {
                if ($documentedTags->has($name)) {
                    $documentedTags->get($name)->description = $description;

                    continue;
                }

                $openApi->tags[] = new Tag(name: $name, description: $description);
            }
        });

        Gate::define('viewApiDocs', static fn ($user = null): bool => true);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Colección JSON de ciudades.
 *
 * Agrupa múltiples {@see CityResource} en las respuestas de catálogo.
 */
final class CityCollection extends ResourceCollection
{
    /** @var class-string<CityResource> */
    public $collects = CityResource::class;
}

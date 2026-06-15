<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Colección JSON de acomodaciones.
 *
 * Agrupa múltiples {@see AccommodationResource} en las respuestas de catálogo.
 */
final class AccommodationCollection extends ResourceCollection
{
    /** @var class-string<AccommodationResource> */
    public $collects = AccommodationResource::class;
}

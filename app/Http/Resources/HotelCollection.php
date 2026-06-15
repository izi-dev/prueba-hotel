<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Colección JSON de hoteles.
 *
 * Agrupa múltiples {@see HotelResource} en las respuestas de listado.
 */
final class HotelCollection extends ResourceCollection
{
    /** @var class-string<HotelResource> */
    public $collects = HotelResource::class;
}

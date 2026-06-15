<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Colección JSON de configuraciones de habitación.
 *
 * Agrupa múltiples {@see HotelRoomConfigurationResource}
 * en las respuestas de listado por hotel.
 */
final class HotelRoomConfigurationCollection extends ResourceCollection
{
    /** @var class-string<HotelRoomConfigurationResource> */
    public $collects = HotelRoomConfigurationResource::class;
}

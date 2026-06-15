<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Colección JSON de tipos de habitación.
 *
 * Agrupa múltiples {@see RoomTypeResource} en las respuestas de catálogo.
 */
final class RoomTypeCollection extends ResourceCollection
{
    /** @var class-string<RoomTypeResource> */
    public $collects = RoomTypeResource::class;
}

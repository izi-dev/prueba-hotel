<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeCollection;
use Src\Application\Catalogs\ListRoomTypes\ListRoomTypesHandler;

/**
 * Controlador del catálogo de tipos de habitación.
 *
 * Expone el endpoint `GET /api/v1/room-types` para listar los tipos de
 * habitación disponibles al configurar las habitaciones de un hotel.
 */
final class ListRoomTypesController extends Controller
{
    /**
     * @param  ListRoomTypesHandler  $handler  Caso de uso que obtiene el listado de tipos de habitación.
     */
    public function __construct(
        private readonly ListRoomTypesHandler $handler,
    ) {}

    /**
     * Devuelve el catálogo completo de tipos de habitación.
     */
    public function __invoke(): RoomTypeCollection
    {
        return new RoomTypeCollection($this->handler->handle());
    }
}

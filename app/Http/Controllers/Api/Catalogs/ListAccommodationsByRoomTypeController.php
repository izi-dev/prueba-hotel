<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccommodationCollection;
use Src\Application\Catalogs\ListAccommodationsByRoomType\ListAccommodationsByRoomTypeHandler;

/**
 * Controlador del catálogo de acomodaciones por tipo de habitación.
 *
 * Expone el endpoint `GET /api/v1/room-types/{roomTypeId}/accommodations`
 * para listar las acomodaciones compatibles con un tipo de habitación dado.
 */
final class ListAccommodationsByRoomTypeController extends Controller
{
    /**
     * @param  ListAccommodationsByRoomTypeHandler  $handler  Caso de uso que filtra acomodaciones por tipo de habitación.
     */
    public function __construct(
        private readonly ListAccommodationsByRoomTypeHandler $handler,
    ) {}

    /**
     * Devuelve las acomodaciones asociadas al tipo de habitación indicado.
     *
     * @param  int  $roomTypeId  Identificador del tipo de habitación.
     */
    public function __invoke(int $roomTypeId): AccommodationCollection
    {
        return new AccommodationCollection($this->handler->handle(roomTypeId: $roomTypeId));
    }
}

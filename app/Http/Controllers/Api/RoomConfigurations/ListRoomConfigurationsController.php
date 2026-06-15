<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\RoomConfigurations;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelRoomConfigurationCollection;
use Src\Application\RoomConfigurations\ListRoomConfigurations\ListRoomConfigurationsHandler;

/**
 * Controlador de listado de configuraciones de habitación.
 *
 * Expone el endpoint `GET /api/v1/hotels/{hotelId}/room-configurations`
 * para obtener todas las configuraciones de habitación de un hotel.
 */
final class ListRoomConfigurationsController extends Controller
{
    /**
     * @param  ListRoomConfigurationsHandler  $handler  Caso de uso que lista configuraciones por hotel.
     */
    public function __construct(
        private readonly ListRoomConfigurationsHandler $handler,
    ) {}

    /**
     * Devuelve las configuraciones de habitación del hotel indicado.
     *
     * @param  int  $hotelId  Identificador del hotel.
     */
    public function __invoke(int $hotelId): HotelRoomConfigurationCollection
    {
        return new HotelRoomConfigurationCollection($this->handler->handle(hotelId: $hotelId));
    }
}

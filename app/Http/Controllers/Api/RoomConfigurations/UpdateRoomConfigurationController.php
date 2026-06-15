<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\RoomConfigurations;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomConfigurations\UpdateRoomConfigurationRequest;
use App\Http\Resources\HotelRoomConfigurationResource;
use Src\Application\RoomConfigurations\UpdateRoomConfiguration\UpdateRoomConfigurationCommand;
use Src\Application\RoomConfigurations\UpdateRoomConfiguration\UpdateRoomConfigurationHandler;

/**
 * Controlador de actualización de configuraciones de habitación.
 *
 * Expone el endpoint `PUT /api/v1/hotels/{hotelId}/room-configurations/{configurationId}`
 * para modificar una configuración de habitación existente de un hotel.
 */
final class UpdateRoomConfigurationController extends Controller
{
    /**
     * @param  UpdateRoomConfigurationHandler  $handler  Caso de uso que actualiza una configuración de habitación.
     */
    public function __construct(
        private readonly UpdateRoomConfigurationHandler $handler,
    ) {}

    /**
     * Actualiza la configuración indicada y devuelve su representación actualizada.
     *
     * @param  int  $hotelId  Identificador del hotel propietario.
     * @param  int  $configurationId  Identificador de la configuración a modificar.
     * @param  UpdateRoomConfigurationRequest  $request  Datos validados de la configuración.
     */
    public function __invoke(
        int $hotelId,
        int $configurationId,
        UpdateRoomConfigurationRequest $request,
    ): HotelRoomConfigurationResource {
        $configuration = $this->handler->handle(new UpdateRoomConfigurationCommand(
            id: $configurationId,
            hotelId: $hotelId,
            roomTypeId: (int) $request->validated(key: 'room_type_id'),
            accommodationId: (int) $request->validated(key: 'accommodation_id'),
            quantity: (int) $request->validated(key: 'quantity'),
        ));

        return new HotelRoomConfigurationResource($configuration);
    }
}

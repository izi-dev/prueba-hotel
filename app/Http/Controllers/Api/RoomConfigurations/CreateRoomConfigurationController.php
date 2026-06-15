<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\RoomConfigurations;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomConfigurations\StoreRoomConfigurationRequest;
use App\Http\Resources\HotelRoomConfigurationResource;
use Illuminate\Http\JsonResponse;
use Src\Application\RoomConfigurations\CreateRoomConfiguration\CreateRoomConfigurationCommand;
use Src\Application\RoomConfigurations\CreateRoomConfiguration\CreateRoomConfigurationHandler;

/**
 * Controlador de creación de configuraciones de habitación.
 *
 * Expone el endpoint `POST /api/v1/hotels/{hotelId}/room-configurations`
 * para asignar un tipo de habitación, acomodación y cantidad a un hotel,
 * respetando las reglas de negocio del dominio.
 */
final class CreateRoomConfigurationController extends Controller
{
    /**
     * @param  CreateRoomConfigurationHandler  $handler  Caso de uso que crea una configuración de habitación.
     */
    public function __construct(
        private readonly CreateRoomConfigurationHandler $handler,
    ) {}

    /**
     * Crea una configuración de habitación y responde con código HTTP 201.
     *
     * @param  int  $hotelId  Identificador del hotel al que pertenece la configuración.
     * @param  StoreRoomConfigurationRequest  $request  Datos validados de la configuración.
     */
    public function __invoke(int $hotelId, StoreRoomConfigurationRequest $request): JsonResponse
    {
        $configuration = $this->handler->handle(new CreateRoomConfigurationCommand(
            hotelId: $hotelId,
            roomTypeId: (int) $request->validated(key: 'room_type_id'),
            accommodationId: (int) $request->validated(key: 'accommodation_id'),
            quantity: (int) $request->validated(key: 'quantity'),
        ));

        return (new HotelRoomConfigurationResource($configuration))->response()->setStatusCode(code: 201);
    }
}

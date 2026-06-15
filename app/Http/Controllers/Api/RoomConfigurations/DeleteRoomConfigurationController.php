<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\RoomConfigurations;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Application\RoomConfigurations\DeleteRoomConfiguration\DeleteRoomConfigurationHandler;

/**
 * Controlador de eliminación de configuraciones de habitación.
 *
 * Expone el endpoint `DELETE /api/v1/hotels/{hotelId}/room-configurations/{configurationId}`
 * para quitar una configuración de habitación de un hotel.
 */
final class DeleteRoomConfigurationController extends Controller
{
    /**
     * @param  DeleteRoomConfigurationHandler  $handler  Caso de uso que elimina una configuración de habitación.
     */
    public function __construct(
        private readonly DeleteRoomConfigurationHandler $handler,
    ) {}

    /**
     * Elimina la configuración indicada y responde con código HTTP 204 sin contenido.
     *
     * @param  int  $hotelId  Identificador del hotel propietario.
     * @param  int  $configurationId  Identificador de la configuración a eliminar.
     */
    public function __invoke(int $hotelId, int $configurationId): JsonResponse
    {
        $this->handler->handle(hotelId: $hotelId, configurationId: $configurationId);

        return response()->json(data: null, status: 204);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\RoomConfigurations;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Src\Application\RoomConfigurations\DeleteRoomConfiguration\DeleteRoomConfigurationHandler;
use Src\Domain\Exceptions\RoomConfigurationNotFoundException;

#[Group(name: 'Configuraciones', description: 'Configuración de habitaciones por hotel.', weight: 30)]
final class DeleteRoomConfigurationController extends Controller
{
    public function __construct(
        private readonly DeleteRoomConfigurationHandler $handler,
    ) {}

    /**
     * @throws RoomConfigurationNotFoundException
     */
    #[Endpoint(
        operationId: 'hotels.roomConfigurations.destroy',
        title: 'Eliminar configuración de habitación',
        description: 'Elimina una configuración del hotel. La configuración debe existir y pertenecer al hotel indicado en la URL.',
    )]
    #[Response(204, 'Configuración eliminada correctamente.')]
    public function __invoke(
        #[PathParameter('hotelId', description: 'Identificador del hotel propietario.', example: 1)]
        int $hotelId,
        #[PathParameter('configurationId', description: 'Identificador de la configuración.', example: 5)]
        int $configurationId,
    ): JsonResponse {
        $this->handler->handle(hotelId: $hotelId, configurationId: $configurationId);

        return response()->json(data: null, status: 204);
    }
}

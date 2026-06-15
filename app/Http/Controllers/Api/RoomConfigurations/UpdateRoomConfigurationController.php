<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\RoomConfigurations;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomConfigurations\UpdateRoomConfigurationRequest;
use App\Http\Resources\HotelRoomConfigurationResource;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Src\Application\RoomConfigurations\UpdateRoomConfiguration\UpdateRoomConfigurationCommand;
use Src\Application\RoomConfigurations\UpdateRoomConfiguration\UpdateRoomConfigurationHandler;
use Src\Domain\Exceptions\DuplicateRoomConfigurationException;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Exceptions\InvalidRoomTypeAccommodationException;
use Src\Domain\Exceptions\RoomConfigurationNotFoundException;
use Src\Domain\Exceptions\RoomCountExceededException;

#[Group(name: 'Configuraciones', description: 'Configuración de habitaciones por hotel.', weight: 30)]
final class UpdateRoomConfigurationController extends Controller
{
    public function __construct(
        private readonly UpdateRoomConfigurationHandler $handler,
    ) {}

    /**
     * @throws RoomConfigurationNotFoundException
     * @throws HotelNotFoundException
     * @throws InvalidRoomTypeAccommodationException
     * @throws DuplicateRoomConfigurationException
     * @throws RoomCountExceededException
     */
    #[Endpoint(
        operationId: 'hotels.roomConfigurations.update',
        title: 'Actualizar configuración de habitación',
        description: 'Modifica tipo, acomodación o cantidad de una configuración existente, aplicando las mismas reglas de negocio que en la creación.',
    )]
    public function __invoke(
        #[PathParameter('hotelId', description: 'Identificador del hotel propietario.', example: 1)]
        int $hotelId,
        #[PathParameter('configurationId', description: 'Identificador de la configuración.', example: 5)]
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

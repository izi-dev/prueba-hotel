<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\RoomConfigurations;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomConfigurations\StoreRoomConfigurationRequest;
use App\Http\Resources\HotelRoomConfigurationResource;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Src\Application\RoomConfigurations\CreateRoomConfiguration\CreateRoomConfigurationCommand;
use Src\Application\RoomConfigurations\CreateRoomConfiguration\CreateRoomConfigurationHandler;
use Src\Domain\Exceptions\DuplicateRoomConfigurationException;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Exceptions\InvalidRoomTypeAccommodationException;
use Src\Domain\Exceptions\RoomCountExceededException;

#[Group(name: 'Configuraciones', description: 'Configuración de habitaciones por hotel.', weight: 30)]
final class CreateRoomConfigurationController extends Controller
{
    public function __construct(
        private readonly CreateRoomConfigurationHandler $handler,
    ) {}

    /**
     * @throws HotelNotFoundException
     * @throws InvalidRoomTypeAccommodationException
     * @throws DuplicateRoomConfigurationException
     * @throws RoomCountExceededException
     */
    #[Endpoint(
        operationId: 'hotels.roomConfigurations.store',
        title: 'Crear configuración de habitación',
        description: 'Asigna un tipo de habitación, acomodación y cantidad a un hotel. Valida compatibilidad tipo-acomodación, unicidad de la combinación y cupo máximo del hotel.',
    )]
    #[Response(201, 'Configuración creada correctamente.', type: HotelRoomConfigurationResource::class)]
    public function __invoke(
        #[PathParameter('hotelId', description: 'Identificador del hotel.', example: 1)]
        int $hotelId,
        StoreRoomConfigurationRequest $request,
    ): JsonResponse {
        $configuration = $this->handler->handle(new CreateRoomConfigurationCommand(
            hotelId: $hotelId,
            roomTypeId: (int) $request->validated(key: 'room_type_id'),
            accommodationId: (int) $request->validated(key: 'accommodation_id'),
            quantity: (int) $request->validated(key: 'quantity'),
        ));

        return (new HotelRoomConfigurationResource($configuration))->response()->setStatusCode(code: 201);
    }
}

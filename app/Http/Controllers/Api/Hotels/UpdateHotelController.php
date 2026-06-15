<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotels\UpdateHotelRequest;
use App\Http\Resources\HotelResource;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Src\Application\Hotels\UpdateHotel\UpdateHotelCommand;
use Src\Application\Hotels\UpdateHotel\UpdateHotelHandler;
use Src\Domain\Exceptions\DuplicateHotelException;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Exceptions\RoomCountExceededException;

#[Group(name: 'Hoteles', description: 'Gestión de hoteles.', weight: 20)]
final class UpdateHotelController extends Controller
{
    public function __construct(
        private readonly UpdateHotelHandler $handler,
    ) {}

    /**
     * @throws HotelNotFoundException
     * @throws DuplicateHotelException
     * @throws RoomCountExceededException
     */
    #[Endpoint(
        operationId: 'hotels.update',
        title: 'Actualizar hotel',
        description: 'Modifica los datos del hotel. No se puede reducir `max_rooms` por debajo del total de habitaciones ya configuradas ni reutilizar un NIT de otro hotel.',
    )]
    public function __invoke(
        #[PathParameter('id', description: 'Identificador del hotel.', example: 1)]
        int $id,
        UpdateHotelRequest $request,
    ): HotelResource {
        $hotel = $this->handler->handle(new UpdateHotelCommand(
            id: $id,
            name: $request->validated(key: 'name'),
            address: $request->validated(key: 'address'),
            cityId: (int) $request->validated(key: 'city_id'),
            nit: $request->validated(key: 'nit'),
            maxRooms: (int) $request->validated(key: 'max_rooms'),
        ));

        return new HotelResource($hotel);
    }
}

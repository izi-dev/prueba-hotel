<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotels\StoreHotelRequest;
use App\Http\Resources\HotelResource;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Src\Application\Hotels\CreateHotel\CreateHotelCommand;
use Src\Application\Hotels\CreateHotel\CreateHotelHandler;
use Src\Domain\Exceptions\DuplicateHotelException;

#[Group(name: 'Hoteles', description: 'Gestión de hoteles.', weight: 20)]
final class CreateHotelController extends Controller
{
    public function __construct(
        private readonly CreateHotelHandler $handler,
    ) {}

    /**
     * @throws DuplicateHotelException
     */
    #[Endpoint(
        operationId: 'hotels.store',
        title: 'Crear hotel',
        description: 'Registra un hotel nuevo. El NIT debe ser único en todo el sistema y tener el formato `12345678-9`.',
    )]
    #[Response(201, 'Hotel creado correctamente.', type: HotelResource::class)]
    public function __invoke(StoreHotelRequest $request): JsonResponse
    {
        $hotel = $this->handler->handle(new CreateHotelCommand(
            name: $request->validated(key: 'name'),
            address: $request->validated(key: 'address'),
            cityId: (int) $request->validated(key: 'city_id'),
            nit: $request->validated(key: 'nit'),
            maxRooms: (int) $request->validated(key: 'max_rooms'),
        ));

        return (new HotelResource($hotel))->response()->setStatusCode(code: 201);
    }
}

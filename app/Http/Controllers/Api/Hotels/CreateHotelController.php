<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotels\StoreHotelRequest;
use App\Http\Resources\HotelResource;
use Illuminate\Http\JsonResponse;
use Src\Application\Hotels\CreateHotel\CreateHotelCommand;
use Src\Application\Hotels\CreateHotel\CreateHotelHandler;

/**
 * Controlador de creación de hoteles.
 *
 * Expone el endpoint `POST /api/v1/hotels` para registrar un nuevo hotel
 * validando sus datos de negocio (nombre, dirección, ciudad, NIT y cupo máximo).
 */
final class CreateHotelController extends Controller
{
    /**
     * @param  CreateHotelHandler  $handler  Caso de uso que persiste un hotel nuevo.
     */
    public function __construct(
        private readonly CreateHotelHandler $handler,
    ) {}

    /**
     * Crea un hotel y devuelve su representación con código HTTP 201.
     *
     * @param  StoreHotelRequest  $request  Datos validados del hotel a crear.
     */
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

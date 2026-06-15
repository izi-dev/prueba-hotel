<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotels\UpdateHotelRequest;
use App\Http\Resources\HotelResource;
use Src\Application\Hotels\UpdateHotel\UpdateHotelCommand;
use Src\Application\Hotels\UpdateHotel\UpdateHotelHandler;

/**
 * Controlador de actualización de hoteles.
 *
 * Expone el endpoint `PUT /api/v1/hotels/{id}` para modificar los datos
 * de un hotel existente.
 */
final class UpdateHotelController extends Controller
{
    /**
     * @param  UpdateHotelHandler  $handler  Caso de uso que actualiza un hotel.
     */
    public function __construct(
        private readonly UpdateHotelHandler $handler,
    ) {}

    /**
     * Actualiza el hotel indicado y devuelve su representación actualizada.
     *
     * @param  int  $id  Identificador del hotel a modificar.
     * @param  UpdateHotelRequest  $request  Datos validados del hotel.
     */
    public function __invoke(int $id, UpdateHotelRequest $request): HotelResource
    {
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

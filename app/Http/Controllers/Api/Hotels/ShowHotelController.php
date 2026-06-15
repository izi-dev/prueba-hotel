<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use Src\Application\Hotels\GetHotel\GetHotelHandler;

/**
 * Controlador de consulta de un hotel.
 *
 * Expone el endpoint `GET /api/v1/hotels/{id}` para obtener el detalle
 * de un hotel, incluidas sus configuraciones de habitación.
 */
final class ShowHotelController extends Controller
{
    /**
     * @param  GetHotelHandler  $handler  Caso de uso que obtiene un hotel por identificador.
     */
    public function __construct(
        private readonly GetHotelHandler $handler,
    ) {}

    /**
     * Devuelve el detalle del hotel solicitado.
     *
     * @param  int  $id  Identificador del hotel.
     */
    public function __invoke(int $id): HotelResource
    {
        return new HotelResource($this->handler->handle(id: $id));
    }
}

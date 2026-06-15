<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelCollection;
use Src\Application\Hotels\ListHotels\ListHotelsHandler;

/**
 * Controlador de listado de hoteles.
 *
 * Expone el endpoint `GET /api/v1/hotels` para obtener todos los hoteles
 * registrados con su información resumida.
 */
final class ListHotelsController extends Controller
{
    /**
     * @param  ListHotelsHandler  $handler  Caso de uso que obtiene el listado de hoteles.
     */
    public function __construct(
        private readonly ListHotelsHandler $handler,
    ) {}

    /**
     * Devuelve la colección de hoteles existentes.
     */
    public function __invoke(): HotelCollection
    {
        return new HotelCollection($this->handler->handle());
    }
}

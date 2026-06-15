<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Application\Hotels\DeleteHotel\DeleteHotelHandler;

/**
 * Controlador de eliminación de hoteles.
 *
 * Expone el endpoint `DELETE /api/v1/hotels/{id}` para eliminar un hotel
 * y sus configuraciones asociadas.
 */
final class DeleteHotelController extends Controller
{
    /**
     * @param  DeleteHotelHandler  $handler  Caso de uso que elimina un hotel.
     */
    public function __construct(
        private readonly DeleteHotelHandler $handler,
    ) {}

    /**
     * Elimina el hotel indicado y responde con código HTTP 204 sin contenido.
     *
     * @param  int  $id  Identificador del hotel a eliminar.
     */
    public function __invoke(int $id): JsonResponse
    {
        $this->handler->handle(id: $id);

        return response()->json(data: null, status: 204);
    }
}

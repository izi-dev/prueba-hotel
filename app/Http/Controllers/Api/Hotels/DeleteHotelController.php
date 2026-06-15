<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Src\Application\Hotels\DeleteHotel\DeleteHotelHandler;
use Src\Domain\Exceptions\HotelNotFoundException;

#[Group(name: 'Hoteles', description: 'Gestión de hoteles.', weight: 20)]
final class DeleteHotelController extends Controller
{
    public function __construct(
        private readonly DeleteHotelHandler $handler,
    ) {}

    /**
     * @throws HotelNotFoundException
     */
    #[Endpoint(
        operationId: 'hotels.destroy',
        title: 'Eliminar hotel',
        description: 'Elimina el hotel y todas sus configuraciones de habitación asociadas.',
    )]
    #[Response(204, 'Hotel eliminado correctamente.')]
    public function __invoke(
        #[PathParameter('id', description: 'Identificador del hotel.', example: 1)]
        int $id,
    ): JsonResponse {
        $this->handler->handle(id: $id);

        return response()->json(data: null, status: 204);
    }
}

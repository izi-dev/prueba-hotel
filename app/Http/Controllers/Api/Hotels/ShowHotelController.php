<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Src\Application\Hotels\GetHotel\GetHotelHandler;
use Src\Domain\Exceptions\HotelNotFoundException;

#[Group(name: 'Hoteles', description: 'Gestión de hoteles.', weight: 20)]
final class ShowHotelController extends Controller
{
    public function __construct(
        private readonly GetHotelHandler $handler,
    ) {}

    /**
     * @throws HotelNotFoundException
     */
    #[Endpoint(
        operationId: 'hotels.show',
        title: 'Obtener hotel',
        description: 'Devuelve el detalle del hotel, incluyendo configuraciones de habitación y totales calculados.',
    )]
    public function __invoke(
        #[PathParameter('id', description: 'Identificador del hotel.', example: 1)]
        int $id,
    ): HotelResource {
        return new HotelResource($this->handler->handle(id: $id));
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Hotels;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelCollection;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Src\Application\Hotels\ListHotels\ListHotelsHandler;

#[Group(name: 'Hoteles', description: 'Gestión de hoteles.', weight: 20)]
final class ListHotelsController extends Controller
{
    public function __construct(
        private readonly ListHotelsHandler $handler,
    ) {}

    #[Endpoint(
        operationId: 'hotels.index',
        title: 'Listar hoteles',
        description: 'Devuelve todos los hoteles registrados con resumen de habitaciones configuradas y disponibles.',
    )]
    public function __invoke(): HotelCollection
    {
        return new HotelCollection($this->handler->handle());
    }
}

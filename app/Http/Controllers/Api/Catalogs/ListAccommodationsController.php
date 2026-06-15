<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccommodationCollection;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Src\Application\Catalogs\ListAccommodations\ListAccommodationsHandler;

#[Group(name: 'Catálogos', description: 'Datos maestros de solo lectura.', weight: 10)]
final class ListAccommodationsController extends Controller
{
    public function __construct(
        private readonly ListAccommodationsHandler $handler,
    ) {}

    #[Endpoint(
        operationId: 'catalogs.accommodations.index',
        title: 'Listar acomodaciones',
        description: 'Devuelve todas las acomodaciones (Sencilla, Doble, Triple, Cuádruple). Para filtrar por tipo use el endpoint anidado bajo `room-types`.',
    )]
    public function __invoke(): AccommodationCollection
    {
        return new AccommodationCollection($this->handler->handle());
    }
}

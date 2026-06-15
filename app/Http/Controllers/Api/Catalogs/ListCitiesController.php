<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityCollection;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Src\Application\Catalogs\ListCities\ListCitiesHandler;

#[Group(name: 'Catálogos', description: 'Datos maestros de solo lectura.', weight: 10)]
final class ListCitiesController extends Controller
{
    public function __construct(
        private readonly ListCitiesHandler $handler,
    ) {}

    #[Endpoint(
        operationId: 'catalogs.cities.index',
        title: 'Listar ciudades',
        description: 'Devuelve el catálogo completo de ciudades disponibles para asociar a un hotel.',
    )]
    public function __invoke(): CityCollection
    {
        return new CityCollection($this->handler->handle());
    }
}

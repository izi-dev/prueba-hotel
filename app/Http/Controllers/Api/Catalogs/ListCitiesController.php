<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityCollection;
use Src\Application\Catalogs\ListCities\ListCitiesHandler;

/**
 * Controlador del catálogo de ciudades.
 *
 * Expone el endpoint `GET /api/v1/cities` para listar todas las ciudades
 * disponibles al registrar o editar hoteles.
 */
final class ListCitiesController extends Controller
{
    /**
     * @param  ListCitiesHandler  $handler  Caso de uso que obtiene el listado de ciudades.
     */
    public function __construct(
        private readonly ListCitiesHandler $handler,
    ) {}

    /**
     * Devuelve el catálogo completo de ciudades.
     */
    public function __invoke(): CityCollection
    {
        return new CityCollection($this->handler->handle());
    }
}

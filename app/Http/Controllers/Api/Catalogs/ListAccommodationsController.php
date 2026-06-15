<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccommodationCollection;
use Src\Application\Catalogs\ListAccommodations\ListAccommodationsHandler;

/**
 * Controlador del catálogo general de acomodaciones.
 *
 * Expone el endpoint `GET /api/v1/accommodations` para listar todas las
 * acomodaciones disponibles en el sistema.
 */
final class ListAccommodationsController extends Controller
{
    /**
     * @param  ListAccommodationsHandler  $handler  Caso de uso que obtiene el listado de acomodaciones.
     */
    public function __construct(
        private readonly ListAccommodationsHandler $handler,
    ) {}

    /**
     * Devuelve el catálogo completo de acomodaciones.
     */
    public function __invoke(): AccommodationCollection
    {
        return new AccommodationCollection($this->handler->handle());
    }
}

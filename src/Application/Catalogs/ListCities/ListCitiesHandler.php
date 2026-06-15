<?php

declare(strict_types=1);

namespace Src\Application\Catalogs\ListCities;

use Src\Domain\Entities\City;
use Src\Domain\Repositories\CityRepositoryInterface;

/**
 * Caso de uso para listar todas las ciudades del catálogo.
 */
final readonly class ListCitiesHandler
{
    /**
     * @param  CityRepositoryInterface  $cityRepository  Repositorio de acceso a ciudades.
     */
    public function __construct(
        private CityRepositoryInterface $cityRepository,
    ) {}

    /**
     * Obtiene el listado completo de ciudades disponibles.
     *
     * @return list<City> Colección de entidades de ciudad.
     */
    public function handle(): array
    {
        return $this->cityRepository->all();
    }
}

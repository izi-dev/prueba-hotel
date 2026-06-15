<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\City;

/**
 * Contrato de acceso a datos para el catálogo de ciudades.
 *
 * Puerto del dominio que abstrae la persistencia de {@see City}, permitiendo
 * a la capa de aplicación consultar localidades sin depender de Eloquent ni
 * de ningún mecanismo concreto de almacenamiento. Las implementaciones
 * residen en la capa de infraestructura.
 */
interface CityRepositoryInterface
{
    /**
     * Obtiene todas las ciudades disponibles en el catálogo.
     *
     * @return list<City> Lista ordenada de entidades de ciudad; puede estar vacía si no hay registros.
     */
    public function all(): array;

    /**
     * Busca una ciudad por su identificador único.
     *
     * @param  int  $id  Identificador de la ciudad a recuperar.
     * @return City|null La entidad encontrada, o null si no existe un registro con ese id.
     */
    public function findById(int $id): ?City;
}

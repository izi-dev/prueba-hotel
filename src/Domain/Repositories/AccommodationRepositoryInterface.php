<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\Accommodation;

/**
 * Contrato de acceso a datos para el catálogo de acomodaciones.
 *
 * Puerto del dominio que abstrae la persistencia de {@see Accommodation} y
 * expone consultas filtradas por tipo de habitación. Permite a la capa de
 * aplicación validar compatibilidad y poblar formularios sin acoplarse a
 * la infraestructura de base de datos.
 */
interface AccommodationRepositoryInterface
{
    /**
     * Obtiene todas las acomodaciones disponibles en el catálogo.
     *
     * @return list<Accommodation> Lista completa de entidades de acomodación; puede estar vacía.
     */
    public function all(): array;

    /**
     * Busca una acomodación por su identificador único.
     *
     * @param  int  $id  Identificador de la acomodación a recuperar.
     * @return Accommodation|null La entidad encontrada, o null si no existe un registro con ese id.
     */
    public function findById(int $id): ?Accommodation;

    /**
     * Obtiene las acomodaciones compatibles con un tipo de habitación dado.
     *
     * @param  int  $roomTypeId  Identificador del tipo de habitación usado como filtro.
     * @return list<Accommodation> Acomodaciones asociadas al tipo de habitación; puede estar vacía si no hay compatibilidades definidas.
     */
    public function findByRoomTypeId(int $roomTypeId): array;
}

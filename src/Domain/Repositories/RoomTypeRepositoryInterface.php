<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\RoomType;

/**
 * Contrato de acceso a datos para el catálogo de tipos de habitación.
 *
 * Puerto del dominio que abstrae la persistencia de {@see RoomType}, usado
 * por casos de uso de catálogos y de configuración de habitaciones. Las
 * implementaciones concretas se registran en la capa de infraestructura.
 */
interface RoomTypeRepositoryInterface
{
    /**
     * Obtiene todos los tipos de habitación disponibles en el catálogo.
     *
     * @return list<RoomType> Lista de entidades de tipo de habitación; puede estar vacía.
     */
    public function all(): array;

    /**
     * Busca un tipo de habitación por su identificador único.
     *
     * @param  int  $id  Identificador del tipo de habitación a recuperar.
     * @return RoomType|null La entidad encontrada, o null si no existe un registro con ese id.
     */
    public function findById(int $id): ?RoomType;
}

<?php

declare(strict_types=1);

namespace Src\Application\Catalogs\ListRoomTypes;

use Src\Domain\Entities\RoomType;
use Src\Domain\Repositories\RoomTypeRepositoryInterface;

/**
 * Caso de uso para listar todos los tipos de habitación del catálogo.
 */
final readonly class ListRoomTypesHandler
{
    /**
     * @param  RoomTypeRepositoryInterface  $roomTypeRepository  Repositorio de acceso a tipos de habitación.
     */
    public function __construct(
        private RoomTypeRepositoryInterface $roomTypeRepository,
    ) {}

    /**
     * Obtiene el listado completo de tipos de habitación disponibles.
     *
     * @return list<RoomType> Colección de entidades de tipo de habitación.
     */
    public function handle(): array
    {
        return $this->roomTypeRepository->all();
    }
}

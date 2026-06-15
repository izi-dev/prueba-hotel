<?php

declare(strict_types=1);

namespace Src\Application\Catalogs\ListAccommodationsByRoomType;

use Src\Domain\Entities\Accommodation;
use Src\Domain\Repositories\AccommodationRepositoryInterface;

/**
 * Caso de uso para listar las acomodaciones compatibles con un tipo de habitación.
 *
 * Consulta el catálogo filtrando por las relaciones definidas entre tipos y acomodaciones.
 */
final readonly class ListAccommodationsByRoomTypeHandler
{
    /**
     * @param  AccommodationRepositoryInterface  $accommodationRepository  Repositorio de acceso a acomodaciones.
     */
    public function __construct(
        private AccommodationRepositoryInterface $accommodationRepository,
    ) {}

    /**
     * Obtiene las acomodaciones permitidas para un tipo de habitación dado.
     *
     * @param  int  $roomTypeId  Identificador del tipo de habitación.
     * @return list<Accommodation> Colección de acomodaciones compatibles con el tipo indicado.
     */
    public function handle(int $roomTypeId): array
    {
        return $this->accommodationRepository->findByRoomTypeId(roomTypeId: $roomTypeId);
    }
}

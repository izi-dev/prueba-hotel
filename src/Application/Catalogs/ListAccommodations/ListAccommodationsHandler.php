<?php

declare(strict_types=1);

namespace Src\Application\Catalogs\ListAccommodations;

use Src\Domain\Entities\Accommodation;
use Src\Domain\Repositories\AccommodationRepositoryInterface;

/**
 * Caso de uso para listar todas las acomodaciones del catálogo.
 */
final readonly class ListAccommodationsHandler
{
    /**
     * @param  AccommodationRepositoryInterface  $accommodationRepository  Repositorio de acceso a acomodaciones.
     */
    public function __construct(
        private AccommodationRepositoryInterface $accommodationRepository,
    ) {}

    /**
     * Obtiene el listado completo de acomodaciones disponibles.
     *
     * @return list<Accommodation> Colección de entidades de acomodación.
     */
    public function handle(): array
    {
        return $this->accommodationRepository->all();
    }
}

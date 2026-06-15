<?php

declare(strict_types=1);

namespace Src\Application\Hotels\ListHotels;

use Src\Domain\Entities\Hotel;
use Src\Domain\Repositories\HotelRepositoryInterface;

/**
 * Caso de uso para listar todos los hoteles registrados en el sistema.
 */
final readonly class ListHotelsHandler
{
    /**
     * @param  HotelRepositoryInterface  $hotelRepository  Repositorio de acceso a hoteles.
     */
    public function __construct(
        private HotelRepositoryInterface $hotelRepository,
    ) {}

    /**
     * Obtiene el listado completo de hoteles.
     *
     * @return list<Hotel> Colección de entidades de hotel.
     */
    public function handle(): array
    {
        return $this->hotelRepository->all();
    }
}

<?php

declare(strict_types=1);

namespace Src\Application\Hotels\DeleteHotel;

use Src\Domain\Entities\Hotel;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Repositories\HotelRepositoryInterface;

/**
 * Caso de uso para la eliminación de un hotel.
 *
 * Verifica que el hotel exista antes de delegar su borrado al repositorio.
 */
final readonly class DeleteHotelHandler
{
    /**
     * @param  HotelRepositoryInterface  $hotelRepository  Repositorio de acceso a hoteles.
     */
    public function __construct(
        private HotelRepositoryInterface $hotelRepository,
    ) {}

    /**
     * Elimina un hotel por su identificador.
     *
     * @param  int  $id  Identificador del hotel a eliminar.
     *
     * @throws HotelNotFoundException Si no existe un hotel con el identificador indicado.
     */
    public function handle(int $id): void
    {
        $hotel = $this->hotelRepository->findById(id: $id);

        throw_if(! $hotel instanceof Hotel, HotelNotFoundException::class, hotelId: $id);

        $this->hotelRepository->delete(id: $id);
    }
}

<?php

declare(strict_types=1);

namespace Src\Application\Hotels\CreateHotel;

use Src\Domain\Entities\Hotel;
use Src\Domain\Exceptions\DuplicateHotelException;
use Src\Domain\Repositories\HotelRepositoryInterface;

/**
 * Caso de uso para la creación de un hotel.
 *
 * Valida la unicidad del NIT y persiste la nueva entidad de dominio.
 */
final readonly class CreateHotelHandler
{
    /**
     * @param  HotelRepositoryInterface  $hotelRepository  Repositorio de acceso a hoteles.
     */
    public function __construct(
        private HotelRepositoryInterface $hotelRepository,
    ) {}

    /**
     * Ejecuta el flujo de creación de un hotel.
     *
     * @param  CreateHotelCommand  $command  Datos del hotel a registrar.
     * @return Hotel Entidad persistida con su identificador asignado.
     *
     * @throws DuplicateHotelException Si ya existe un hotel con el mismo NIT.
     */
    public function handle(CreateHotelCommand $command): Hotel
    {
        if ($this->hotelRepository->existsByNit(nit: $command->nit)) {
            throw new DuplicateHotelException(nit: $command->nit);
        }

        $hotel = new Hotel(
            id: null,
            name: $command->name,
            address: $command->address,
            cityId: $command->cityId,
            nit: $command->nit,
            maxRooms: $command->maxRooms,
        );

        return $this->hotelRepository->save(hotel: $hotel);
    }
}

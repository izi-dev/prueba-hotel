<?php

declare(strict_types=1);

namespace Src\Application\Hotels\UpdateHotel;

use Src\Domain\Entities\Hotel;
use Src\Domain\Exceptions\DuplicateHotelException;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Exceptions\RoomCountExceededException;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Src\Domain\Repositories\HotelRoomConfigurationRepositoryInterface;

/**
 * Caso de uso para la actualización de un hotel existente.
 *
 * Verifica existencia, unicidad del NIT y que el nuevo límite de habitaciones
 * no sea inferior a la cantidad ya configurada.
 */
final readonly class UpdateHotelHandler
{
    /**
     * @param  HotelRepositoryInterface  $hotelRepository  Repositorio de acceso a hoteles.
     * @param  HotelRoomConfigurationRepositoryInterface  $configurationRepository  Repositorio de configuraciones de habitación.
     */
    public function __construct(
        private HotelRepositoryInterface $hotelRepository,
        private HotelRoomConfigurationRepositoryInterface $configurationRepository,
    ) {}

    /**
     * Ejecuta el flujo de actualización de un hotel.
     *
     * @param  UpdateHotelCommand  $command  Datos actualizados del hotel.
     * @return Hotel Entidad persistida con los cambios aplicados.
     *
     * @throws HotelNotFoundException Si no existe un hotel con el identificador indicado.
     * @throws DuplicateHotelException Si el NIT ya pertenece a otro hotel.
     * @throws RoomCountExceededException Si el nuevo máximo de habitaciones es menor que el total ya configurado.
     */
    public function handle(UpdateHotelCommand $command): Hotel
    {
        // 1. Recuperar el hotel actual para validar existencia y conservar datos relacionados.
        $existing = $this->hotelRepository->findById(id: $command->id);

        if (! $existing instanceof Hotel) {
            throw new HotelNotFoundException(hotelId: $command->id);
        }

        // 2. Garantizar que el NIT no esté en uso por otro hotel distinto al que se edita.
        if ($this->hotelRepository->existsByNit(nit: $command->nit, excludeId: $command->id)) {
            throw new DuplicateHotelException(nit: $command->nit);
        }

        // 3. Comprobar que reducir maxRooms no deje por debajo las habitaciones ya asignadas.
        $currentTotal = $this->configurationRepository->totalQuantityByHotelId(hotelId: $command->id);

        if ($currentTotal > $command->maxRooms) {
            throw new RoomCountExceededException(maxRooms: $command->maxRooms, requestedTotal: $currentTotal);
        }

        // 4. Reconstruir la entidad conservando nombre de ciudad y configuraciones cargadas previamente.
        $hotel = new Hotel(
            id: $command->id,
            name: $command->name,
            address: $command->address,
            cityId: $command->cityId,
            nit: $command->nit,
            maxRooms: $command->maxRooms,
            cityName: $existing->cityName,
            roomConfigurations: $existing->roomConfigurations,
        );

        return $this->hotelRepository->save(hotel: $hotel);
    }
}

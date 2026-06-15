<?php

declare(strict_types=1);

namespace Src\Application\RoomConfigurations\CreateRoomConfiguration;

use Src\Application\Shared\RoomTypeAccommodationRules;
use Src\Domain\Entities\Hotel;
use Src\Domain\Entities\HotelRoomConfiguration;
use Src\Domain\Exceptions\DuplicateRoomConfigurationException;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Exceptions\InvalidRoomTypeAccommodationException;
use Src\Domain\Exceptions\RoomCountExceededException;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Src\Domain\Repositories\HotelRoomConfigurationRepositoryInterface;

/**
 * Caso de uso para crear una configuración de habitación en un hotel.
 *
 * Valida existencia del hotel, compatibilidad tipo-acomodación, unicidad de la combinación
 * y que la cantidad total de habitaciones no supere el límite del hotel.
 */
final readonly class CreateRoomConfigurationHandler
{
    /**
     * @param  HotelRepositoryInterface  $hotelRepository  Repositorio de acceso a hoteles.
     * @param  HotelRoomConfigurationRepositoryInterface  $configurationRepository  Repositorio de configuraciones de habitación.
     * @param  RoomTypeAccommodationRules  $roomTypeAccommodationRules  Reglas de compatibilidad entre tipo y acomodación.
     */
    public function __construct(
        private HotelRepositoryInterface $hotelRepository,
        private HotelRoomConfigurationRepositoryInterface $configurationRepository,
        private RoomTypeAccommodationRules $roomTypeAccommodationRules,
    ) {}

    /**
     * Ejecuta el flujo de creación de una configuración de habitación.
     *
     * @param  CreateRoomConfigurationCommand  $command  Datos de la configuración a registrar.
     * @return HotelRoomConfiguration Entidad persistida con su identificador asignado.
     *
     * @throws HotelNotFoundException Si el hotel indicado no existe.
     * @throws InvalidRoomTypeAccommodationException Si la acomodación no es válida para el tipo de habitación.
     * @throws DuplicateRoomConfigurationException Si ya existe la misma combinación hotel-tipo-acomodación.
     * @throws RoomCountExceededException Si la cantidad solicitada supera el máximo de habitaciones del hotel.
     */
    public function handle(CreateRoomConfigurationCommand $command): HotelRoomConfiguration
    {
        // 1. Verificar que el hotel destino exista y recuperar su límite de habitaciones.
        $hotel = $this->hotelRepository->findById(id: $command->hotelId);

        if (! $hotel instanceof Hotel) {
            throw new HotelNotFoundException(hotelId: $command->hotelId);
        }

        // 2. Validar que el tipo de habitación y la acomodación sean compatibles según el catálogo.
        $this->roomTypeAccommodationRules->assertValid(
            roomTypeId: $command->roomTypeId,
            accommodationId: $command->accommodationId,
        );

        // 3. Impedir duplicar la misma combinación hotel + tipo + acomodación.
        throw_if($this->configurationRepository->existsByHotelRoomTypeAndAccommodation(
            hotelId: $command->hotelId,
            roomTypeId: $command->roomTypeId,
            accommodationId: $command->accommodationId,
        ), DuplicateRoomConfigurationException::class);

        // 4. Comprobar que el total de habitaciones (actual + nueva) no exceda maxRooms del hotel.
        $currentTotal = $this->configurationRepository->totalQuantityByHotelId(hotelId: $command->hotelId);
        $newTotal = $currentTotal + $command->quantity;

        if ($newTotal > $hotel->maxRooms) {
            throw new RoomCountExceededException(maxRooms: $hotel->maxRooms, requestedTotal: $newTotal);
        }

        // 5. Construir la entidad y persistirla.
        $configuration = new HotelRoomConfiguration(
            id: 0,
            hotelId: $command->hotelId,
            roomTypeId: $command->roomTypeId,
            accommodationId: $command->accommodationId,
            quantity: $command->quantity,
        );

        return $this->configurationRepository->save(configuration: $configuration);
    }
}

<?php

declare(strict_types=1);

namespace Src\Application\RoomConfigurations\UpdateRoomConfiguration;

use Src\Application\Shared\RoomTypeAccommodationRules;
use Src\Domain\Entities\Hotel;
use Src\Domain\Entities\HotelRoomConfiguration;
use Src\Domain\Exceptions\DuplicateRoomConfigurationException;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Exceptions\InvalidRoomTypeAccommodationException;
use Src\Domain\Exceptions\RoomConfigurationNotFoundException;
use Src\Domain\Exceptions\RoomCountExceededException;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Src\Domain\Repositories\HotelRoomConfigurationRepositoryInterface;

/**
 * Caso de uso para actualizar una configuración de habitación existente.
 *
 * Valida pertenencia al hotel, compatibilidad tipo-acomodación, unicidad de la combinación
 * y que el nuevo total de habitaciones respete el límite del hotel.
 */
final readonly class UpdateRoomConfigurationHandler
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
     * Ejecuta el flujo de actualización de una configuración de habitación.
     *
     * @param  UpdateRoomConfigurationCommand  $command  Datos actualizados de la configuración.
     * @return HotelRoomConfiguration Entidad persistida con los cambios aplicados.
     *
     * @throws RoomConfigurationNotFoundException Si la configuración no existe o no pertenece al hotel indicado.
     * @throws HotelNotFoundException Si el hotel indicado no existe.
     * @throws InvalidRoomTypeAccommodationException Si la acomodación no es válida para el tipo de habitación.
     * @throws DuplicateRoomConfigurationException Si otra configuración ya usa la misma combinación hotel-tipo-acomodación.
     * @throws RoomCountExceededException Si la nueva cantidad haría superar el máximo de habitaciones del hotel.
     */
    public function handle(UpdateRoomConfigurationCommand $command): HotelRoomConfiguration
    {
        // 1. Recuperar la configuración y verificar que exista y pertenezca al hotel solicitado.
        $existing = $this->configurationRepository->findById(id: $command->id);

        if (! $existing instanceof HotelRoomConfiguration || $existing->hotelId !== $command->hotelId) {
            throw new RoomConfigurationNotFoundException(configurationId: $command->id);
        }

        // 2. Confirmar que el hotel sigue existiendo para obtener su límite de habitaciones.
        $hotel = $this->hotelRepository->findById(id: $command->hotelId);

        if (! $hotel instanceof Hotel) {
            throw new HotelNotFoundException(hotelId: $command->hotelId);
        }

        // 3. Validar compatibilidad entre el nuevo tipo de habitación y la nueva acomodación.
        $this->roomTypeAccommodationRules->assertValid(
            roomTypeId: $command->roomTypeId,
            accommodationId: $command->accommodationId,
        );

        // 4. Evitar colisión con otra configuración distinta que ya use la misma combinación.
        throw_if($this->configurationRepository->existsByHotelRoomTypeAndAccommodation(
            hotelId: $command->hotelId,
            roomTypeId: $command->roomTypeId,
            accommodationId: $command->accommodationId,
            excludeId: $command->id,
        ), DuplicateRoomConfigurationException::class);

        // 5. Calcular el total excluyendo la configuración actual y sumar la nueva cantidad.
        $currentTotal = $this->configurationRepository->totalQuantityByHotelId(
            hotelId: $command->hotelId,
            excludeConfigurationId: $command->id,
        );
        $newTotal = $currentTotal + $command->quantity;

        if ($newTotal > $hotel->maxRooms) {
            throw new RoomCountExceededException(maxRooms: $hotel->maxRooms, requestedTotal: $newTotal);
        }

        // 6. Reconstruir la entidad con los datos actualizados y persistirla.
        $configuration = new HotelRoomConfiguration(
            id: $command->id,
            hotelId: $command->hotelId,
            roomTypeId: $command->roomTypeId,
            accommodationId: $command->accommodationId,
            quantity: $command->quantity,
        );

        return $this->configurationRepository->save(configuration: $configuration);
    }
}

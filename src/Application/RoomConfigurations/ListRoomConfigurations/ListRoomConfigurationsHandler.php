<?php

declare(strict_types=1);

namespace Src\Application\RoomConfigurations\ListRoomConfigurations;

use Src\Domain\Entities\Hotel;
use Src\Domain\Entities\HotelRoomConfiguration;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Src\Domain\Repositories\HotelRoomConfigurationRepositoryInterface;

/**
 * Caso de uso para listar las configuraciones de habitación de un hotel.
 */
final readonly class ListRoomConfigurationsHandler
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
     * Obtiene todas las configuraciones de habitación asociadas a un hotel.
     *
     * @param  int  $hotelId  Identificador del hotel cuyas configuraciones se consultan.
     * @return list<HotelRoomConfiguration> Colección de configuraciones del hotel.
     *
     * @throws HotelNotFoundException Si no existe un hotel con el identificador indicado.
     */
    public function handle(int $hotelId): array
    {
        $hotel = $this->hotelRepository->findById(id: $hotelId);

        throw_if(! $hotel instanceof Hotel, HotelNotFoundException::class, hotelId: $hotelId);

        return $this->configurationRepository->findByHotelId(hotelId: $hotelId);
    }
}

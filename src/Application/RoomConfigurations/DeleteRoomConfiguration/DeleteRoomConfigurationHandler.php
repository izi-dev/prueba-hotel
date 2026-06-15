<?php

declare(strict_types=1);

namespace Src\Application\RoomConfigurations\DeleteRoomConfiguration;

use Src\Domain\Entities\HotelRoomConfiguration;
use Src\Domain\Exceptions\RoomConfigurationNotFoundException;
use Src\Domain\Repositories\HotelRoomConfigurationRepositoryInterface;

/**
 * Caso de uso para eliminar una configuración de habitación de un hotel.
 *
 * Verifica que la configuración exista y pertenezca al hotel indicado antes de borrarla.
 */
final readonly class DeleteRoomConfigurationHandler
{
    /**
     * @param  HotelRoomConfigurationRepositoryInterface  $configurationRepository  Repositorio de configuraciones de habitación.
     */
    public function __construct(
        private HotelRoomConfigurationRepositoryInterface $configurationRepository,
    ) {}

    /**
     * Elimina una configuración de habitación.
     *
     * @param  int  $hotelId  Identificador del hotel propietario de la configuración.
     * @param  int  $configurationId  Identificador de la configuración a eliminar.
     *
     * @throws RoomConfigurationNotFoundException Si la configuración no existe o no pertenece al hotel indicado.
     */
    public function handle(int $hotelId, int $configurationId): void
    {
        $existing = $this->configurationRepository->findById(id: $configurationId);

        throw_if(! $existing instanceof HotelRoomConfiguration || $existing->hotelId !== $hotelId, RoomConfigurationNotFoundException::class, configurationId: $configurationId);

        $this->configurationRepository->delete(id: $configurationId);
    }
}

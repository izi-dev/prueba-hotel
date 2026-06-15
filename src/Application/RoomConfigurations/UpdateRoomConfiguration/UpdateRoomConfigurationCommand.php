<?php

declare(strict_types=1);

namespace Src\Application\RoomConfigurations\UpdateRoomConfiguration;

/**
 * Comando de aplicación para actualizar una configuración de habitación existente.
 *
 * Identifica la configuración por su id y el hotel al que pertenece, junto con los nuevos valores.
 */
final readonly class UpdateRoomConfigurationCommand
{
    /**
     * @param  int  $id  Identificador de la configuración a modificar.
     * @param  int  $hotelId  Identificador del hotel propietario de la configuración.
     * @param  int  $roomTypeId  Nuevo identificador de tipo de habitación.
     * @param  int  $accommodationId  Nuevo identificador de acomodación.
     * @param  int  $quantity  Nueva cantidad de habitaciones para esta combinación.
     */
    public function __construct(
        public int $id,
        public int $hotelId,
        public int $roomTypeId,
        public int $accommodationId,
        public int $quantity,
    ) {}
}

<?php

declare(strict_types=1);

namespace Src\Application\RoomConfigurations\CreateRoomConfiguration;

/**
 * Comando de aplicación para registrar una nueva configuración de habitación en un hotel.
 *
 * Define la combinación tipo de habitación, acomodación y cantidad a asignar.
 */
final readonly class CreateRoomConfigurationCommand
{
    /**
     * @param  int  $hotelId  Identificador del hotel al que pertenece la configuración.
     * @param  int  $roomTypeId  Identificador del tipo de habitación (catálogo).
     * @param  int  $accommodationId  Identificador de la acomodación (catálogo).
     * @param  int  $quantity  Número de habitaciones de esta combinación a asignar al hotel.
     */
    public function __construct(
        public int $hotelId,
        public int $roomTypeId,
        public int $accommodationId,
        public int $quantity,
    ) {}
}

<?php

declare(strict_types=1);

namespace Src\Application\Hotels\UpdateHotel;

/**
 * Comando de aplicación para actualizar los datos de un hotel existente.
 *
 * Incluye el identificador del hotel y los nuevos valores de sus atributos editables.
 */
final readonly class UpdateHotelCommand
{
    /**
     * @param  int  $id  Identificador del hotel a modificar.
     * @param  string  $name  Nuevo nombre del hotel.
     * @param  string  $address  Nueva dirección del hotel.
     * @param  int  $cityId  Nuevo identificador de ciudad.
     * @param  string  $nit  Nuevo NIT del hotel.
     * @param  int  $maxRooms  Nuevo límite máximo de habitaciones configurables.
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $address,
        public int $cityId,
        public string $nit,
        public int $maxRooms,
    ) {}
}

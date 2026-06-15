<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

/**
 * Representa una línea de inventario de habitaciones dentro de un hotel.
 *
 * Entidad de dominio inmutable que asocia un {@see Hotel} con una combinación
 * concreta de {@see RoomType} y {@see Accommodation}, indicando cuántas
 * habitaciones de esa configuración existen. Pertenece al agregado
 * {@see Hotel} y se gestiona a través de casos de uso de configuración
 * de habitaciones en la capa de aplicación.
 */
final readonly class HotelRoomConfiguration
{
    /**
     * @param  int  $id  Identificador único de la configuración de habitación.
     * @param  int  $hotelId  Identificador del hotel al que pertenece esta configuración.
     * @param  int  $roomTypeId  Identificador del tipo de habitación configurado.
     * @param  int  $accommodationId  Identificador de la acomodación asociada al tipo de habitación.
     * @param  int  $quantity  Número de habitaciones con esta combinación tipo/acomodación.
     * @param  ?string  $roomTypeName  Nombre del tipo de habitación enriquecido para lectura; opcional.
     * @param  ?string  $accommodationName  Nombre de la acomodación enriquecido para lectura; opcional.
     */
    public function __construct(
        public int $id,
        public int $hotelId,
        public int $roomTypeId,
        public int $accommodationId,
        public int $quantity,
        public ?string $roomTypeName = null,
        public ?string $accommodationName = null,
    ) {}
}

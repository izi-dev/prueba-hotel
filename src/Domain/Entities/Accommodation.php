<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

/**
 * Representa una modalidad de acomodación del catálogo hotelero.
 *
 * Entidad de dominio inmutable que describe cómo se distribuyen los huéspedes
 * en una habitación (por ejemplo, cama doble, dos camas sencillas). Su
 * compatibilidad con un {@see RoomType} está regida por reglas de negocio
 * externas a esta entidad y se materializa en {@see HotelRoomConfiguration}.
 */
final readonly class Accommodation
{
    /**
     * @param  int  $id  Identificador único de la acomodación.
     * @param  string  $name  Nombre legible de la acomodación.
     * @param  string  $slug  Identificador textual estable usado en reglas y catálogos (por ejemplo, "double-bed").
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
    ) {}
}

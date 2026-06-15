<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

/**
 * Representa un tipo de habitación del catálogo hotelero.
 *
 * Entidad de dominio inmutable que define las categorías de habitación disponibles
 * (por ejemplo, estándar, suite). Se relaciona con {@see Accommodation} mediante
 * reglas de negocio en la capa de aplicación y se referencia en
 * {@see HotelRoomConfiguration} al describir el inventario de un hotel.
 */
final readonly class RoomType
{
    /**
     * @param  int  $id  Identificador único del tipo de habitación.
     * @param  string  $name  Nombre legible del tipo de habitación.
     * @param  string  $slug  Identificador textual estable usado en reglas y catálogos (por ejemplo, "standard").
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
    ) {}
}

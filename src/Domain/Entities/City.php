<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

/**
 * Representa una ciudad del catálogo geográfico del sistema.
 *
 * Entidad de dominio inmutable que modela una localidad donde puede ubicarse un hotel.
 * Forma parte de la capa de dominio y se utiliza como valor de referencia en la
 * agregación {@see Hotel}, sin contener lógica de persistencia ni de presentación.
 */
final readonly class City
{
    /**
     * @param  int  $id  Identificador único de la ciudad en el sistema.
     * @param  string  $name  Nombre descriptivo de la ciudad (por ejemplo, "Bogotá").
     */
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}

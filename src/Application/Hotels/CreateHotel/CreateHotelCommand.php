<?php

declare(strict_types=1);

namespace Src\Application\Hotels\CreateHotel;

/**
 * Comando de aplicación para registrar un nuevo hotel en el sistema.
 *
 * Transporta los datos de entrada necesarios para la creación de un hotel,
 * sin contener lógica de negocio ni acceso a persistencia.
 */
final readonly class CreateHotelCommand
{
    /**
     * @param  string  $name  Nombre comercial o razón social del hotel.
     * @param  string  $address  Dirección física del establecimiento.
     * @param  int  $cityId  Identificador de la ciudad donde opera el hotel.
     * @param  string  $nit  Número de identificación tributaria (NIT), único en el sistema.
     * @param  int  $maxRooms  Capacidad máxima de habitaciones que el hotel puede configurar.
     */
    public function __construct(
        public string $name,
        public string $address,
        public int $cityId,
        public string $nit,
        public int $maxRooms,
    ) {}
}

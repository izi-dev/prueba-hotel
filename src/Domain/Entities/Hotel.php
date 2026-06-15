<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

/**
 * Representa un hotel y su inventario de habitaciones configuradas.
 *
 * Agregado raíz del dominio que encapsula los datos identificativos de un
 * establecimiento hotelero y la colección de {@see HotelRoomConfiguration}
 * asociadas. La capa de aplicación valida invariantes sobre este agregado
 * (unicidad de NIT, límite de habitaciones, combinaciones tipo/acomodación)
 * antes de delegar la persistencia a la infraestructura.
 */
final readonly class Hotel
{
    /**
     * @param  ?int  $id  Identificador del hotel; null cuando la entidad aún no ha sido persistida.
     * @param  string  $name  Nombre comercial del hotel.
     * @param  string  $address  Dirección física del establecimiento.
     * @param  int  $cityId  Identificador de la ciudad donde opera el hotel.
     * @param  string  $nit  Número de identificación tributaria; debe ser único en el sistema.
     * @param  int  $maxRooms  Cantidad máxima de habitaciones que el hotel puede registrar en total.
     * @param  ?string  $cityName  Nombre de la ciudad enriquecido para lectura; opcional según el origen de los datos.
     * @param  list<HotelRoomConfiguration>  $roomConfigurations  Configuraciones de habitación que componen el inventario del hotel.
     */
    public function __construct(
        public ?int $id,
        public string $name,
        public string $address,
        public int $cityId,
        public string $nit,
        public int $maxRooms,
        public ?string $cityName = null,
        public array $roomConfigurations = [],
    ) {}
}

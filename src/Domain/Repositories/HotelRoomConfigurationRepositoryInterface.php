<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\HotelRoomConfiguration;

/**
 * Contrato de acceso a datos para configuraciones de habitación de un hotel.
 *
 * Puerto del dominio que gestiona la persistencia de
 * {@see HotelRoomConfiguration} como parte del inventario del agregado
 * {@see \Src\Domain\Entities\Hotel}. Expone consultas de agregación y
 * comprobaciones de unicidad por combinación hotel/tipo/acomodación.
 */
interface HotelRoomConfigurationRepositoryInterface
{
    /**
     * Obtiene todas las configuraciones de habitación asociadas a un hotel.
     *
     * @param  int  $hotelId  Identificador del hotel cuyo inventario se consulta.
     * @return list<HotelRoomConfiguration> Configuraciones del hotel; puede estar vacía si no tiene habitaciones registradas.
     */
    public function findByHotelId(int $hotelId): array;

    /**
     * Busca una configuración de habitación por su identificador único.
     *
     * @param  int  $id  Identificador de la configuración a recuperar.
     * @return HotelRoomConfiguration|null La entidad encontrada, o null si no existe un registro con ese id.
     */
    public function findById(int $id): ?HotelRoomConfiguration;

    /**
     * Persiste una configuración de habitación nueva o actualiza una existente.
     *
     * @param  HotelRoomConfiguration  $configuration  Entidad a guardar.
     * @return HotelRoomConfiguration Configuración persistida con datos actualizados según la implementación.
     */
    public function save(HotelRoomConfiguration $configuration): HotelRoomConfiguration;

    /**
     * Elimina una configuración de habitación del almacenamiento permanente.
     *
     * @param  int  $id  Identificador de la configuración a eliminar.
     */
    public function delete(int $id): void;

    /**
     * Comprueba si ya existe una configuración con la misma combinación hotel, tipo y acomodación.
     *
     * @param  int  $hotelId  Identificador del hotel al que pertenece la configuración.
     * @param  int  $roomTypeId  Identificador del tipo de habitación de la combinación.
     * @param  int  $accommodationId  Identificador de la acomodación de la combinación.
     * @param  ?int  $excludeId  Identificador de configuración a excluir de la búsqueda (útil en actualizaciones).
     * @return bool true si ya existe otra configuración con la misma combinación; false en caso contrario.
     */
    public function existsByHotelRoomTypeAndAccommodation(
        int $hotelId,
        int $roomTypeId,
        int $accommodationId,
        ?int $excludeId = null,
    ): bool;

    /**
     * Calcula la suma de cantidades de habitaciones configuradas para un hotel.
     *
     * @param  int  $hotelId  Identificador del hotel cuyo inventario se totaliza.
     * @param  ?int  $excludeConfigurationId  Identificador de configuración a excluir del cálculo (útil al validar actualizaciones).
     * @return int Suma total de habitaciones; cero si el hotel no tiene configuraciones.
     */
    public function totalQuantityByHotelId(int $hotelId, ?int $excludeConfigurationId = null): int;
}

<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\Hotel;

/**
 * Contrato de acceso a datos para el agregado Hotel.
 *
 * Puerto del dominio que define las operaciones de lectura y escritura sobre
 * {@see Hotel}, incluyendo comprobaciones de unicidad de NIT. La capa de
 * aplicación delega en este contrato la persistencia del agregado, mientras
 * que las implementaciones Eloquent residen en infraestructura.
 */
interface HotelRepositoryInterface
{
    /**
     * Obtiene todos los hoteles registrados en el sistema.
     *
     * @return list<Hotel> Lista de agregados de hotel; puede incluir configuraciones de habitación según la implementación.
     */
    public function all(): array;

    /**
     * Busca un hotel por su identificador único.
     *
     * @param  int  $id  Identificador del hotel a recuperar.
     * @return Hotel|null El agregado encontrado, o null si no existe un registro con ese id.
     */
    public function findById(int $id): ?Hotel;

    /**
     * Busca un hotel por su número de identificación tributaria (NIT).
     *
     * @param  string  $nit  NIT exacto del hotel a localizar.
     * @return Hotel|null El agregado encontrado, o null si no hay ningún hotel con ese NIT.
     */
    public function findByNit(string $nit): ?Hotel;

    /**
     * Persiste un hotel nuevo o actualiza uno existente.
     *
     * @param  Hotel  $hotel  Agregado a guardar; si {@see Hotel::$id} es null, se crea un registro nuevo.
     * @return Hotel Entidad persistida con identificador y datos actualizados según la implementación.
     */
    public function save(Hotel $hotel): Hotel;

    /**
     * Elimina un hotel del almacenamiento permanente.
     *
     * @param  int  $id  Identificador del hotel a eliminar.
     */
    public function delete(int $id): void;

    /**
     * Comprueba si ya existe un hotel registrado con el NIT indicado.
     *
     * @param  string  $nit  NIT a verificar por unicidad.
     * @param  ?int  $excludeId  Identificador de hotel a excluir de la búsqueda (útil en actualizaciones).
     * @return bool true si existe otro hotel con el mismo NIT; false en caso contrario.
     */
    public function existsByNit(string $nit, ?int $excludeId = null): bool;
}

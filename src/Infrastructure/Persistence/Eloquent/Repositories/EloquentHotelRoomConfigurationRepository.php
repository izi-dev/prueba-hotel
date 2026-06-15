<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Entities\HotelRoomConfiguration;
use Src\Domain\Repositories\HotelRoomConfigurationRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\HotelRoomConfigurationModel;

/**
 * Implementación del repositorio de configuraciones de habitación usando Eloquent.
 *
 * Adaptador de infraestructura que persiste y consulta la tabla
 * `hotel_room_configurations`, traduciendo modelos Eloquent a entidades
 * `HotelRoomConfiguration` del dominio.
 */
final class EloquentHotelRoomConfigurationRepository implements HotelRoomConfigurationRepositoryInterface
{
    /**
     * Obtiene todas las configuraciones de un hotel, ordenadas por identificador.
     *
     * @param  int  $hotelId  Identificador del hotel.
     * @return list<HotelRoomConfiguration> Lista de configuraciones del hotel.
     */
    public function findByHotelId(int $hotelId): array
    {
        return HotelRoomConfigurationModel::query()
            ->with(['roomType', 'accommodation'])
            ->where('hotel_id', $hotelId)
            ->orderBy('id')
            ->get()
            ->map(fn (HotelRoomConfigurationModel $model): HotelRoomConfiguration => $this->toEntity($model))
            ->all();
    }

    /**
     * Busca una configuración por su identificador.
     *
     * @param  int  $id  Identificador de la configuración.
     * @return HotelRoomConfiguration|null Entidad encontrada o `null` si no existe.
     */
    public function findById(int $id): ?HotelRoomConfiguration
    {
        $model = HotelRoomConfigurationModel::query()
            ->with(['roomType', 'accommodation'])
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    /**
     * Crea o actualiza una configuración de habitación.
     *
     * @param  HotelRoomConfiguration  $configuration  Entidad de dominio a persistir.
     * @return HotelRoomConfiguration Entidad actualizada con identificador y relaciones cargadas.
     */
    public function save(HotelRoomConfiguration $configuration): HotelRoomConfiguration
    {
        if ($configuration->id === 0) {
            $model = new HotelRoomConfigurationModel;
        } else {
            $model = HotelRoomConfigurationModel::query()->findOrFail($configuration->id);
        }

        $model->fill([
            'hotel_id' => $configuration->hotelId,
            'room_type_id' => $configuration->roomTypeId,
            'accommodation_id' => $configuration->accommodationId,
            'quantity' => $configuration->quantity,
        ]);
        $model->save();
        $model->load(['roomType', 'accommodation']);

        return $this->toEntity($model);
    }

    /**
     * Elimina una configuración por su identificador.
     *
     * @param  int  $id  Identificador de la configuración a eliminar.
     */
    public function delete(int $id): void
    {
        HotelRoomConfigurationModel::query()->whereKey($id)->delete();
    }

    /**
     * Comprueba si ya existe la combinación hotel–tipo de habitación–acomodación.
     *
     * @param  int  $hotelId  Identificador del hotel.
     * @param  int  $roomTypeId  Identificador del tipo de habitación.
     * @param  int  $accommodationId  Identificador de la acomodación.
     * @param  int|null  $excludeId  Identificador a excluir de la búsqueda (útil en actualizaciones).
     * @return bool `true` si ya existe una configuración con esa combinación.
     */
    public function existsByHotelRoomTypeAndAccommodation(
        int $hotelId,
        int $roomTypeId,
        int $accommodationId,
        ?int $excludeId = null,
    ): bool {
        $query = HotelRoomConfigurationModel::query()
            ->where('hotel_id', $hotelId)
            ->where('room_type_id', $roomTypeId)
            ->where('accommodation_id', $accommodationId);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Calcula la suma de cantidades de habitaciones configuradas para un hotel.
     *
     * @param  int  $hotelId  Identificador del hotel.
     * @param  int|null  $excludeConfigurationId  Configuración a excluir del total (útil al validar actualizaciones).
     * @return int Suma de las cantidades (`quantity`) de las configuraciones restantes.
     */
    public function totalQuantityByHotelId(int $hotelId, ?int $excludeConfigurationId = null): int
    {
        $query = HotelRoomConfigurationModel::query()->where('hotel_id', $hotelId);

        if ($excludeConfigurationId !== null) {
            $query->where('id', '!=', $excludeConfigurationId);
        }

        return (int) $query->sum('quantity');
    }

    /**
     * Convierte un modelo Eloquent en entidad de dominio `HotelRoomConfiguration`.
     *
     * Los nombres de tipo de habitación y acomodación se incluyen solo si las
     * relaciones fueron cargadas previamente con `with` o `load`.
     *
     * @param  HotelRoomConfigurationModel  $model  Modelo persistido en base de datos.
     * @return HotelRoomConfiguration Entidad de dominio equivalente.
     */
    private function toEntity(HotelRoomConfigurationModel $model): HotelRoomConfiguration
    {
        return new HotelRoomConfiguration(
            id: $model->id,
            hotelId: $model->hotel_id,
            roomTypeId: $model->room_type_id,
            accommodationId: $model->accommodation_id,
            quantity: $model->quantity,
            roomTypeName: $model->relationLoaded('roomType') ? $model->roomType->name : null,
            accommodationName: $model->relationLoaded('accommodation') ? $model->accommodation->name : null,
        );
    }
}

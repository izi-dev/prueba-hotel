<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Entities\Hotel;
use Src\Domain\Entities\HotelRoomConfiguration;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\HotelModel;
use Src\Infrastructure\Persistence\Eloquent\Models\HotelRoomConfigurationModel;

/**
 * Implementación del repositorio de hoteles usando Eloquent.
 *
 * Adaptador de infraestructura que traduce operaciones del contrato de dominio
 * (`HotelRepositoryInterface`) a consultas y persistencia sobre `HotelModel`,
 * mapeando modelos de base de datos a entidades `Hotel`.
 */
final class EloquentHotelRepository implements HotelRepositoryInterface
{
    /**
     * Obtiene todos los hoteles ordenados por nombre, con ciudad y configuraciones cargadas.
     *
     * @return list<Hotel> Lista de entidades de dominio.
     */
    public function all(): array
    {
        return HotelModel::query()
            ->with(['city', 'roomConfigurations.roomType', 'roomConfigurations.accommodation'])
            ->orderBy('name')
            ->get()
            ->map(fn (HotelModel $model): Hotel => $this->toEntity($model))
            ->all();
    }

    /**
     * Busca un hotel por su identificador.
     *
     * @param  int  $id  Identificador del hotel.
     * @return Hotel|null Entidad encontrada o `null` si no existe.
     */
    public function findById(int $id): ?Hotel
    {
        $model = HotelModel::query()
            ->with(['city', 'roomConfigurations.roomType', 'roomConfigurations.accommodation'])
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    /**
     * Busca un hotel por su NIT.
     *
     * @param  string  $nit  Número de identificación tributaria del hotel.
     * @return Hotel|null Entidad encontrada o `null` si no existe.
     */
    public function findByNit(string $nit): ?Hotel
    {
        $model = HotelModel::query()
            ->with(['city', 'roomConfigurations.roomType', 'roomConfigurations.accommodation'])
            ->where('nit', $nit)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    /**
     * Crea o actualiza un hotel en la base de datos.
     *
     * @param  Hotel  $hotel  Entidad de dominio a persistir.
     * @return Hotel Entidad actualizada con identificador y relaciones cargadas.
     */
    public function save(Hotel $hotel): Hotel
    {
        if ($hotel->id === null) {
            $model = new HotelModel;
        } else {
            $model = HotelModel::query()->findOrFail($hotel->id);
        }

        $model->fill([
            'name' => $hotel->name,
            'address' => $hotel->address,
            'city_id' => $hotel->cityId,
            'nit' => $hotel->nit,
            'max_rooms' => $hotel->maxRooms,
        ]);
        $model->save();
        $model->load(['city', 'roomConfigurations.roomType', 'roomConfigurations.accommodation']);

        return $this->toEntity($model);
    }

    /**
     * Elimina un hotel por su identificador.
     *
     * @param  int  $id  Identificador del hotel a eliminar.
     */
    public function delete(int $id): void
    {
        HotelModel::query()->whereKey($id)->delete();
    }

    /**
     * Comprueba si ya existe un hotel con el NIT indicado.
     *
     * @param  string  $nit  NIT a verificar.
     * @param  int|null  $excludeId  Identificador a excluir de la búsqueda (útil en actualizaciones).
     * @return bool `true` si existe otro hotel con ese NIT.
     */
    public function existsByNit(string $nit, ?int $excludeId = null): bool
    {
        $query = HotelModel::query()->where('nit', $nit);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Convierte un modelo Eloquent en entidad de dominio `Hotel`.
     *
     * Incluye el nombre de la ciudad y las configuraciones de habitación solo si
     * las relaciones correspondientes fueron cargadas previamente con `with` o `load`.
     *
     * @param  HotelModel  $model  Modelo persistido en base de datos.
     * @return Hotel Entidad de dominio equivalente.
     */
    private function toEntity(HotelModel $model): Hotel
    {
        $configurations = $model->relationLoaded('roomConfigurations')
            ? $model->roomConfigurations
                ->map(fn (HotelRoomConfigurationModel $config): HotelRoomConfiguration => new HotelRoomConfiguration(
                    id: $config->id,
                    hotelId: $config->hotel_id,
                    roomTypeId: $config->room_type_id,
                    accommodationId: $config->accommodation_id,
                    quantity: $config->quantity,
                    roomTypeName: $config->relationLoaded('roomType') ? $config->roomType->name : null,
                    accommodationName: $config->relationLoaded('accommodation') ? $config->accommodation->name : null,
                ))
                ->all()
            : [];

        return new Hotel(
            id: $model->id,
            name: $model->name,
            address: $model->address,
            cityId: $model->city_id,
            nit: $model->nit,
            maxRooms: $model->max_rooms,
            cityName: $model->relationLoaded('city') ? $model->city->name : null,
            roomConfigurations: $configurations,
        );
    }
}

<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Entities\RoomType;
use Src\Domain\Repositories\RoomTypeRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\RoomTypeModel;

/**
 * Implementación del repositorio de tipos de habitación usando Eloquent.
 *
 * Adaptador de infraestructura que consulta el catálogo `room_types` y
 * transforma los modelos persistidos en entidades `RoomType` del dominio.
 */
final class EloquentRoomTypeRepository implements RoomTypeRepositoryInterface
{
    /**
     * Obtiene todos los tipos de habitación ordenados por nombre.
     *
     * @return list<RoomType> Lista de entidades del catálogo.
     */
    public function all(): array
    {
        return RoomTypeModel::query()
            ->orderBy('name')
            ->get()
            ->map(fn (RoomTypeModel $model): RoomType => $this->toEntity($model))
            ->all();
    }

    /**
     * Busca un tipo de habitación por su identificador.
     *
     * @param  int  $id  Identificador del tipo de habitación.
     * @return RoomType|null Entidad encontrada o `null` si no existe.
     */
    public function findById(int $id): ?RoomType
    {
        $model = RoomTypeModel::query()->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    /**
     * Convierte un modelo Eloquent en entidad de dominio `RoomType`.
     *
     * @param  RoomTypeModel  $model  Modelo persistido en base de datos.
     * @return RoomType Entidad de dominio equivalente.
     */
    private function toEntity(RoomTypeModel $model): RoomType
    {
        return new RoomType(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
        );
    }
}

<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Entities\Accommodation;
use Src\Domain\Repositories\AccommodationRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\AccommodationModel;

/**
 * Implementación del repositorio de acomodaciones usando Eloquent.
 *
 * Adaptador de infraestructura que consulta el catálogo `accommodations`,
 * incluyendo el filtrado por tipo de habitación, y mapea los resultados
 * a entidades `Accommodation` del dominio.
 */
final class EloquentAccommodationRepository implements AccommodationRepositoryInterface
{
    /**
     * Obtiene todas las acomodaciones ordenadas por nombre.
     *
     * @return list<Accommodation> Lista de entidades del catálogo.
     */
    public function all(): array
    {
        return AccommodationModel::query()
            ->orderBy('name')
            ->get()
            ->map(fn (AccommodationModel $model): Accommodation => $this->toEntity($model))
            ->all();
    }

    /**
     * Busca una acomodación por su identificador.
     *
     * @param  int  $id  Identificador de la acomodación.
     * @return Accommodation|null Entidad encontrada o `null` si no existe.
     */
    public function findById(int $id): ?Accommodation
    {
        $model = AccommodationModel::query()->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    /**
     * Obtiene las acomodaciones compatibles con un tipo de habitación dado.
     *
     * @param  int  $roomTypeId  Identificador del tipo de habitación.
     * @return list<Accommodation> Acomodaciones asociadas al tipo mediante la tabla pivote.
     */
    public function findByRoomTypeId(int $roomTypeId): array
    {
        return AccommodationModel::query()
            ->whereHas('roomTypes', fn ($query) => $query->where('room_types.id', $roomTypeId))
            ->orderBy('name')
            ->get()
            ->map(fn (AccommodationModel $model): Accommodation => $this->toEntity($model))
            ->all();
    }

    /**
     * Convierte un modelo Eloquent en entidad de dominio `Accommodation`.
     *
     * @param  AccommodationModel  $model  Modelo persistido en base de datos.
     * @return Accommodation Entidad de dominio equivalente.
     */
    private function toEntity(AccommodationModel $model): Accommodation
    {
        return new Accommodation(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
        );
    }
}

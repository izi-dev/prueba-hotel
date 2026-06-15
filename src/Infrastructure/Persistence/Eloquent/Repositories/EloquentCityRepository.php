<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Entities\City;
use Src\Domain\Repositories\CityRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\CityModel;

/**
 * Implementación del repositorio de ciudades usando Eloquent.
 *
 * Adaptador de infraestructura que consulta el catálogo `cities` y traduce
 * los modelos de persistencia en entidades `City` del dominio.
 */
final class EloquentCityRepository implements CityRepositoryInterface
{
    /**
     * Obtiene todas las ciudades ordenadas por nombre.
     *
     * @return list<City> Lista de entidades del catálogo.
     */
    public function all(): array
    {
        return CityModel::query()
            ->orderBy('name')
            ->get()
            ->map(fn (CityModel $model): City => $this->toEntity($model))
            ->all();
    }

    /**
     * Busca una ciudad por su identificador.
     *
     * @param  int  $id  Identificador de la ciudad.
     * @return City|null Entidad encontrada o `null` si no existe.
     */
    public function findById(int $id): ?City
    {
        $model = CityModel::query()->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    /**
     * Convierte un modelo Eloquent en entidad de dominio `City`.
     *
     * @param  CityModel  $model  Modelo persistido en base de datos.
     * @return City Entidad de dominio equivalente.
     */
    private function toEntity(CityModel $model): City
    {
        return new City(
            id: $model->id,
            name: $model->name,
        );
    }
}

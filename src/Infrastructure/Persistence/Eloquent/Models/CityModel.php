<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo Eloquent que mapea la tabla `cities` a la capa de persistencia.
 *
 * Proporciona acceso relacional a las ciudades del catálogo y a los hoteles
 * registrados en cada una, sin exponer detalles de SQL a la capa de aplicación.
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, HotelModel> $hotels
 */
#[Fillable(['name'])]
#[Table(name: 'cities')]
final class CityModel extends Model
{
    use HasFactory;

    /**
     * Relación uno-a-muchos con los hoteles ubicados en esta ciudad.
     *
     * @return HasMany<HotelModel, $this> Colección de hoteles vinculados por `city_id`.
     */
    public function hotels(): HasMany
    {
        return $this->hasMany(HotelModel::class, 'city_id');
    }
}

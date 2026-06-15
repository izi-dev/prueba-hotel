<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo Eloquent que mapea la tabla `hotels` a la capa de persistencia.
 *
 * Actúa como adaptador de infraestructura entre la base de datos relacional
 * y las entidades de dominio, encapsulando atributos, relaciones y reglas
 * de asignación masiva propias de Laravel/Eloquent.
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $city_id
 * @property string $nit
 * @property int $max_rooms
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CityModel $city
 * @property-read Collection<int, HotelRoomConfigurationModel> $roomConfigurations
 */
#[Fillable([
    'name',
    'address',
    'city_id',
    'nit',
    'max_rooms',
])]
#[Table(name: 'hotels')]
final class HotelModel extends Model
{
    use HasFactory;

    /**
     * Relación de pertenencia con la ciudad del hotel.
     *
     * @return BelongsTo<CityModel, $this> Instancia de la ciudad asociada mediante `city_id`.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(related: CityModel::class, foreignKey: 'city_id');
    }

    /**
     * Relación uno-a-muchos con las configuraciones de habitación del hotel.
     *
     * @return HasMany<HotelRoomConfigurationModel, $this> Colección de configuraciones vinculadas por `hotel_id`.
     */
    public function roomConfigurations(): HasMany
    {
        return $this->hasMany(related: HotelRoomConfigurationModel::class, foreignKey: 'hotel_id');
    }
}

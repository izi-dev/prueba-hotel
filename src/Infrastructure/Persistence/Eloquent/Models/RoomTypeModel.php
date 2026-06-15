<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Modelo Eloquent que mapea la tabla `room_types` a la capa de persistencia.
 *
 * Representa los tipos de habitación del catálogo y su vínculo con las
 * acomodaciones permitidas a través de la tabla pivote `room_type_accommodation`.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, AccommodationModel> $accommodations
 */
#[Fillable(['name', 'slug'])]
#[Table(name: 'room_types')]
final class RoomTypeModel extends Model
{
    use HasFactory;

    /**
     * Relación muchos-a-muchos con las acomodaciones compatibles con este tipo de habitación.
     *
     * @return BelongsToMany<AccommodationModel, $this> Colección de acomodaciones asociadas vía `room_type_accommodation`.
     */
    public function accommodations(): BelongsToMany
    {
        return $this->belongsToMany(
            AccommodationModel::class,
            'room_type_accommodation',
            'room_type_id',
            'accommodation_id',
        );
    }
}

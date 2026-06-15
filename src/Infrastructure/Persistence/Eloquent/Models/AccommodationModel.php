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
 * Modelo Eloquent que mapea la tabla `accommodations` a la capa de persistencia.
 *
 * Representa las modalidades de acomodación del catálogo y su asociación
 * con los tipos de habitación que las admiten.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, RoomTypeModel> $roomTypes
 */
#[Fillable(['name', 'slug'])]
#[Table(name: 'accommodations')]
final class AccommodationModel extends Model
{
    use HasFactory;

    /**
     * Relación muchos-a-muchos con los tipos de habitación que permiten esta acomodación.
     *
     * @return BelongsToMany<RoomTypeModel, $this> Colección de tipos de habitación asociados vía `room_type_accommodation`.
     */
    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            RoomTypeModel::class,
            'room_type_accommodation',
            'accommodation_id',
            'room_type_id',
        );
    }
}

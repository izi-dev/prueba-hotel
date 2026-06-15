<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo Eloquent que mapea la tabla `hotel_room_configurations` a la capa de persistencia.
 *
 * Persiste la combinación hotel–tipo de habitación–acomodación con su cantidad,
 * enlazando el agregado de hotel con los catálogos de tipología y acomodación.
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $room_type_id
 * @property int $accommodation_id
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read HotelModel $hotel
 * @property-read RoomTypeModel $roomType
 * @property-read AccommodationModel $accommodation
 */
#[Fillable([
    'hotel_id',
    'room_type_id',
    'accommodation_id',
    'quantity',
])]
#[Table(name: 'hotel_room_configurations')]
final class HotelRoomConfigurationModel extends Model
{
    use HasFactory;

    /**
     * Relación de pertenencia con el hotel al que pertenece la configuración.
     *
     * @return BelongsTo<HotelModel, $this> Instancia del hotel asociado mediante `hotel_id`.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(related: HotelModel::class, foreignKey: 'hotel_id');
    }

    /**
     * Relación de pertenencia con el tipo de habitación de la configuración.
     *
     * @return BelongsTo<RoomTypeModel, $this> Instancia del tipo de habitación asociado mediante `room_type_id`.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(related: RoomTypeModel::class, foreignKey: 'room_type_id');
    }

    /**
     * Relación de pertenencia con la acomodación de la configuración.
     *
     * @return BelongsTo<AccommodationModel, $this> Instancia de la acomodación asociada mediante `accommodation_id`.
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(related: AccommodationModel::class, foreignKey: 'accommodation_id');
    }
}

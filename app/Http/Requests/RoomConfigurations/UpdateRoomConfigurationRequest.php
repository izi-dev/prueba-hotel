<?php

declare(strict_types=1);

namespace App\Http\Requests\RoomConfigurations;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Solicitud HTTP para actualizar una configuración de habitación.
 */
final class UpdateRoomConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            /** Tipo de habitación (`GET /api/v1/room-types`). @example 1 */
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            /** Acomodación compatible con el tipo. @example 2 */
            'accommodation_id' => ['required', 'integer', 'exists:accommodations,id'],
            /** Nueva cantidad de habitaciones para esta combinación. @example 15 */
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}

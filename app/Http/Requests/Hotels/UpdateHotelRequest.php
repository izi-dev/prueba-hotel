<?php

declare(strict_types=1);

namespace App\Http\Requests\Hotels;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Solicitud HTTP para actualizar un hotel.
 */
final class UpdateHotelRequest extends FormRequest
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
            /** Nombre comercial del hotel. @example Hotel Bogotá Plaza */
            'name' => ['required', 'string', 'max:255'],
            /** Dirección física del establecimiento. @example Carrera 7 # 71-21 */
            'address' => ['required', 'string', 'max:500'],
            /** Identificador de la ciudad (`GET /api/v1/cities`). @example 1 */
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            /** NIT único con formato dígitos-dígito verificador. @example 900123456-7 */
            'nit' => ['required', 'string', 'max:20', 'regex:/^\d+-\d$/'],
            /** Cupo máximo; no puede ser menor que las habitaciones ya configuradas. @example 120 */
            'max_rooms' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nit.regex' => 'El NIT debe tener el formato 12345678-9.',
        ];
    }
}

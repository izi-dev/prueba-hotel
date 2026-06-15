<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Infrastructure\Persistence\Eloquent\Models\AccommodationModel;
use Src\Infrastructure\Persistence\Eloquent\Models\CityModel;
use Src\Infrastructure\Persistence\Eloquent\Models\RoomTypeModel;

/**
 * Seeder de catálogos del sistema hotelero.
 *
 * Carga datos de referencia inmutables del negocio:
 * - 10 ciudades colombianas
 * - 3 tipos de habitación (estándar, junior, suite)
 * - 4 acomodaciones (sencilla, doble, triple, cuádruple)
 * - Reglas de compatibilidad tipo ↔ acomodación en la tabla pivote
 *
 * Estos datos alimentan validaciones de dominio y formularios del frontend.
 */
final class CatalogSeeder extends Seeder
{
    /**
     * Inserta o actualiza catálogos y sincroniza las reglas de compatibilidad.
     */
    public function run(): void
    {
        // Ciudades disponibles para asociar a hoteles.
        $cities = [
            'BOGOTÁ', 'MEDELLÍN', 'CALI', 'BARRANQUILLA', 'CARTAGENA',
            'BUCARAMANGA', 'PEREIRA', 'MANIZALES', 'SANTA MARTA', 'IBAGUÉ',
        ];

        foreach ($cities as $city) {
            CityModel::query()->firstOrCreate(['name' => $city]);
        }

        // Tipos de habitación con slug estable para reglas y API.
        $roomTypes = [
            ['name' => 'ESTÁNDAR', 'slug' => 'estandar'],
            ['name' => 'JUNIOR', 'slug' => 'junior'],
            ['name' => 'SUITE', 'slug' => 'suite'],
        ];

        foreach ($roomTypes as $roomType) {
            RoomTypeModel::query()->firstOrCreate(['slug' => $roomType['slug']], $roomType);
        }

        // Acomodaciones posibles por habitación.
        $accommodations = [
            ['name' => 'SENCILLA', 'slug' => 'sencilla'],
            ['name' => 'DOBLE', 'slug' => 'doble'],
            ['name' => 'TRIPLE', 'slug' => 'triple'],
            ['name' => 'CUÁDRUPLE', 'slug' => 'cuadruple'],
        ];

        foreach ($accommodations as $accommodation) {
            AccommodationModel::query()->firstOrCreate(['slug' => $accommodation['slug']], $accommodation);
        }

        // Matriz de negocio: qué acomodaciones admite cada tipo de habitación.
        $rules = [
            'estandar' => ['sencilla', 'doble'],
            'junior' => ['triple', 'cuadruple'],
            'suite' => ['sencilla', 'doble', 'triple'],
        ];

        foreach ($rules as $roomTypeSlug => $accommodationSlugs) {
            $roomType = RoomTypeModel::query()->where('slug', $roomTypeSlug)->firstOrFail();
            $accommodationIds = AccommodationModel::query()
                ->whereIn('slug', $accommodationSlugs)
                ->pluck('id');

            $roomType->accommodations()->syncWithoutDetaching($accommodationIds);
        }
    }
}

<?php

declare(strict_types=1);

/**
 * Funciones auxiliares globales para tests de la API y casos de uso.
 *
 * Centralizan la obtención de identificadores de catálogo y la construcción
 * de payloads válidos de hotel, evitando duplicar datos de prueba.
 */

use Illuminate\Support\Facades\DB;
use Src\Infrastructure\Persistence\Eloquent\Models\CityModel;

/**
 * Siembra catálogos y devuelve identificadores fijos usados en las pruebas.
 *
 * @return array{
 *     city_id: int,
 *     estandar_id: int,
 *     junior_id: int,
 *     suite_id: int,
 *     sencilla_id: int,
 *     doble_id: int,
 *     triple_id: int,
 *     cuadruple_id: int
 * }
 */
function catalogIds(): array
{
    test()->seedCatalogs();

    return [
        'city_id' => CityModel::query()->where('name', 'CARTAGENA')->value('id'),
        'estandar_id' => DB::table('room_types')->where('slug', 'estandar')->value('id'),
        'junior_id' => DB::table('room_types')->where('slug', 'junior')->value('id'),
        'suite_id' => DB::table('room_types')->where('slug', 'suite')->value('id'),
        'sencilla_id' => DB::table('accommodations')->where('slug', 'sencilla')->value('id'),
        'doble_id' => DB::table('accommodations')->where('slug', 'doble')->value('id'),
        'triple_id' => DB::table('accommodations')->where('slug', 'triple')->value('id'),
        'cuadruple_id' => DB::table('accommodations')->where('slug', 'cuadruple')->value('id'),
    ];
}

/**
 * Construye un payload válido para crear o actualizar un hotel vía API.
 *
 * @param  array<string, mixed>  $overrides  Campos que sobrescriben los valores por defecto.
 * @return array<string, mixed>
 */
function createHotelPayload(array $overrides = []): array
{
    $ids = catalogIds();

    return array_merge([
        'name' => 'DECAMERON CARTAGENA',
        'address' => 'CALLE 23 58-25',
        'city_id' => $ids['city_id'],
        'nit' => '12345678-9',
        'max_rooms' => 42,
    ], $overrides);
}

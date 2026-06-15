<?php

declare(strict_types=1);

/**
 * Tests de integración HTTP para los endpoints de catálogos.
 *
 * Verifica que la API exponga correctamente ciudades, tipos de habitación,
 * acomodaciones y el filtrado de acomodaciones por tipo.
 */
use Tests\SeedsCatalogs;

uses(SeedsCatalogs::class);

/** Caso: GET /api/v1/cities devuelve el listado de ciudades sembradas. */
it('lists cities', function (): void {
    $this->seedCatalogs();

    $response = $this->getJson('/api/v1/cities');

    $response->assertOk()
        ->assertJsonStructure(['data' => [['id', 'name']]]);
});

/** Caso: GET /api/v1/room-types incluye los tipos estándar, junior y suite. */
it('lists room types', function (): void {
    $this->seedCatalogs();

    $response = $this->getJson('/api/v1/room-types');

    $response->assertOk()
        ->assertJsonFragment(['slug' => 'estandar']);
});

/** Caso: GET /api/v1/accommodations devuelve las cuatro acomodaciones del catálogo. */
it('lists accommodations', function (): void {
    $this->seedCatalogs();

    $response = $this->getJson('/api/v1/accommodations');

    $response->assertOk()
        ->assertJsonCount(4, 'data');
});

/** Caso: GET /api/v1/room-types/{id}/accommodations filtra por reglas de compatibilidad. */
it('lists accommodations by room type', function (): void {
    $ids = catalogIds();

    $response = $this->getJson(sprintf('/api/v1/room-types/%s/accommodations', $ids['estandar_id']));

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

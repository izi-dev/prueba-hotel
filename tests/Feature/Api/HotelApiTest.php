<?php

declare(strict_types=1);

/**
 * Tests de integración HTTP para el CRUD de hoteles.
 *
 * Cubre creación, listado, detalle, actualización, eliminación,
 * validación de entrada y reglas de negocio (NIT único, límite de habitaciones).
 */
use Tests\SeedsCatalogs;

uses(SeedsCatalogs::class);

/** Caso: POST /api/v1/hotels crea un hotel con datos válidos y responde 201. */
it('creates a hotel', function (): void {
    $payload = createHotelPayload();

    $response = $this->postJson('/api/v1/hotels', $payload);

    $response->assertCreated()
        ->assertJsonPath('data.name', $payload['name'])
        ->assertJsonPath('data.nit', $payload['nit']);
});

/** Caso: no se permite registrar dos hoteles con el mismo NIT. */
it('rejects duplicate hotel nit', function (): void {
    $payload = createHotelPayload();
    $this->postJson('/api/v1/hotels', $payload)->assertCreated();

    $this->postJson('/api/v1/hotels', $payload)
        ->assertStatus(422)
        ->assertJsonPath('message', 'Ya existe un hotel registrado con el NIT 12345678-9.');
});

/** Caso: GET /api/v1/hotels devuelve todos los hoteles registrados. */
it('lists hotels', function (): void {
    $this->postJson('/api/v1/hotels', createHotelPayload())->assertCreated();

    $response = $this->getJson('/api/v1/hotels');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

/** Caso: GET /api/v1/hotels/{id} devuelve el detalle de un hotel existente. */
it('shows a hotel', function (): void {
    $created = $this->postJson('/api/v1/hotels', createHotelPayload())->json('data');

    $response = $this->getJson('/api/v1/hotels/'.$created['id']);

    $response->assertOk()
        ->assertJsonPath('data.id', $created['id']);
});

/** Caso: GET /api/v1/hotels/{id} responde 404 si el hotel no existe. */
it('returns 404 when hotel does not exist', function (): void {
    $this->seedCatalogs();

    $this->getJson('/api/v1/hotels/999')->assertNotFound();
});

/** Caso: PUT /api/v1/hotels/{id} actualiza los datos del hotel. */
it('updates a hotel', function (): void {
    $created = $this->postJson('/api/v1/hotels', createHotelPayload())->json('data');

    $response = $this->putJson('/api/v1/hotels/'.$created['id'], [
        ...createHotelPayload(),
        'name' => 'HOTEL ACTUALIZADO',
        'nit' => '87654321-0',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.name', 'HOTEL ACTUALIZADO');
});

/** Caso: no se puede reducir max_rooms por debajo del total ya configurado. */
it('rejects reducing max rooms below configured total', function (): void {
    $ids = catalogIds();
    $created = $this->postJson('/api/v1/hotels', createHotelPayload())->json('data');

    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $created['id']), [
        'room_type_id' => $ids['estandar_id'],
        'accommodation_id' => $ids['sencilla_id'],
        'quantity' => 30,
    ])->assertCreated();

    $this->putJson('/api/v1/hotels/'.$created['id'], [
        ...createHotelPayload(),
        'max_rooms' => 20,
    ])->assertStatus(422);
});

/** Caso: DELETE /api/v1/hotels/{id} elimina el hotel y deja de estar disponible. */
it('deletes a hotel', function (): void {
    $created = $this->postJson('/api/v1/hotels', createHotelPayload())->json('data');

    $this->deleteJson('/api/v1/hotels/'.$created['id'])
        ->assertNoContent();

    $this->getJson('/api/v1/hotels/'.$created['id'])->assertNotFound();
});

/** Caso: la validación de Form Request rechaza payloads incompletos con 422. */
it('validates hotel request payload', function (): void {
    $this->seedCatalogs();

    $this->postJson('/api/v1/hotels', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'address', 'city_id', 'nit', 'max_rooms']);
});

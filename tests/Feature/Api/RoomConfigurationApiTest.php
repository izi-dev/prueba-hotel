<?php

declare(strict_types=1);

/**
 * Tests de integración HTTP para configuraciones de habitación.
 *
 * Cubre CRUD anidado bajo hotel, reglas de compatibilidad tipo-acomodación,
 * unicidad de combinaciones, límite de cupo y validaciones por tipo.
 */
use Tests\SeedsCatalogs;

uses(SeedsCatalogs::class);

beforeEach(function (): void {
    $this->ids = catalogIds();
    $this->hotel = $this->postJson('/api/v1/hotels', createHotelPayload())->json('data');
});

/** Caso: POST crea una configuración válida para el hotel. */
it('creates room configuration', function (): void {
    $response = $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['estandar_id'],
        'accommodation_id' => $this->ids['sencilla_id'],
        'quantity' => 25,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.quantity', 25);
});

/** Caso: rechaza combinaciones no permitidas (estándar + triple). */
it('rejects invalid room type and accommodation combination', function (): void {
    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['estandar_id'],
        'accommodation_id' => $this->ids['triple_id'],
        'quantity' => 5,
    ])->assertStatus(422)
        ->assertJsonPath('message', 'La acomodación TRIPLE no es válida para el tipo de habitación ESTÁNDAR.');
});

/** Caso: no permite duplicar hotel + tipo + acomodación. */
it('rejects duplicate room configuration', function (): void {
    $payload = [
        'room_type_id' => $this->ids['estandar_id'],
        'accommodation_id' => $this->ids['sencilla_id'],
        'quantity' => 5,
    ];

    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), $payload)->assertCreated();
    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), $payload)
        ->assertStatus(422);
});

/** Caso: la suma de cantidades no puede superar max_rooms del hotel. */
it('rejects exceeding max rooms', function (): void {
    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['estandar_id'],
        'accommodation_id' => $this->ids['sencilla_id'],
        'quantity' => 30,
    ])->assertCreated();

    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['junior_id'],
        'accommodation_id' => $this->ids['triple_id'],
        'quantity' => 15,
    ])->assertStatus(422);
});

/** Caso: GET lista las configuraciones del hotel. */
it('lists room configurations', function (): void {
    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['junior_id'],
        'accommodation_id' => $this->ids['triple_id'],
        'quantity' => 12,
    ])->assertCreated();

    $response = $this->getJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']));

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

/** Caso: PUT actualiza tipo, acomodación o cantidad de una configuración. */
it('updates room configuration', function (): void {
    $config = $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['estandar_id'],
        'accommodation_id' => $this->ids['sencilla_id'],
        'quantity' => 5,
    ])->json('data');

    $response = $this->putJson(
        sprintf('/api/v1/hotels/%s/room-configurations/%s', $this->hotel['id'], $config['id']),
        [
            'room_type_id' => $this->ids['estandar_id'],
            'accommodation_id' => $this->ids['doble_id'],
            'quantity' => 8,
        ],
    );

    $response->assertOk()
        ->assertJsonPath('data.quantity', 8)
        ->assertJsonPath('data.accommodation_id', $this->ids['doble_id']);
});

/** Caso: DELETE elimina la configuración del inventario del hotel. */
it('deletes room configuration', function (): void {
    $config = $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['suite_id'],
        'accommodation_id' => $this->ids['triple_id'],
        'quantity' => 3,
    ])->json('data');

    $this->deleteJson(sprintf('/api/v1/hotels/%s/room-configurations/%s', $this->hotel['id'], $config['id']))
        ->assertNoContent();

    $this->getJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']))
        ->assertJsonCount(0, 'data');
});

/** Caso: DELETE responde 404 si la configuración no pertenece al hotel. */
it('returns 404 for configuration of another hotel', function (): void {
    $this->deleteJson(sprintf('/api/v1/hotels/%s/room-configurations/999', $this->hotel['id']))
        ->assertNotFound();
});

/** Caso: suite no admite acomodación cuádruple según reglas del catálogo. */
it('validates suite accommodations', function (): void {
    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['suite_id'],
        'accommodation_id' => $this->ids['cuadruple_id'],
        'quantity' => 2,
    ])->assertStatus(422);
});

/** Caso: junior no admite acomodación doble según reglas del catálogo. */
it('validates junior accommodations', function (): void {
    $this->postJson(sprintf('/api/v1/hotels/%s/room-configurations', $this->hotel['id']), [
        'room_type_id' => $this->ids['junior_id'],
        'accommodation_id' => $this->ids['doble_id'],
        'quantity' => 2,
    ])->assertStatus(422);
});

<?php

declare(strict_types=1);

/**
 * Tests unitarios de RoomTypeAccommodationRules y códigos HTTP de excepciones.
 *
 * Valida combinaciones permitidas/prohibidas y el contrato statusCode()
 * de cada excepción de dominio.
 */
use Src\Application\Shared\RoomTypeAccommodationRules;
use Src\Domain\Exceptions\DuplicateHotelException;
use Src\Domain\Exceptions\DuplicateRoomConfigurationException;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Exceptions\InvalidRoomTypeAccommodationException;
use Src\Domain\Exceptions\RoomConfigurationNotFoundException;
use Src\Domain\Exceptions\RoomCountExceededException;
use Tests\SeedsCatalogs;

uses(SeedsCatalogs::class);

/** Caso: combinación estándar + sencilla es válida según el catálogo. */
it('validates allowed combinations', function (): void {
    $this->seedCatalogs();
    $rules = resolve(RoomTypeAccommodationRules::class);
    $ids = catalogIds();

    $rules->assertValid(roomTypeId: $ids['estandar_id'], accommodationId: $ids['sencilla_id']);
    expect(true)->toBeTrue();
});

/** Caso: combinación estándar + cuádruple lanza InvalidRoomTypeAccommodationException. */
it('throws invalid combination exception', function (): void {
    $this->seedCatalogs();
    $rules = resolve(RoomTypeAccommodationRules::class);
    $ids = catalogIds();

    expect(fn () => $rules->assertValid(roomTypeId: $ids['estandar_id'], accommodationId: $ids['cuadruple_id']))
        ->toThrow(InvalidRoomTypeAccommodationException::class);
});

/** Caso: cada excepción de dominio expone el código HTTP esperado (404 o 422). */
it('exposes domain exception status codes', function (): void {
    expect((new HotelNotFoundException(hotelId: 1))->statusCode())->toBe(404);
    expect((new DuplicateHotelException(nit: '1'))->statusCode())->toBe(422);
    expect((new RoomConfigurationNotFoundException(configurationId: 1))->statusCode())->toBe(404);
    expect((new InvalidRoomTypeAccommodationException(roomType: 'A', accommodation: 'B'))->statusCode())->toBe(422);
    expect((new RoomCountExceededException(maxRooms: 10, requestedTotal: 20))->statusCode())->toBe(422);
    expect((new DuplicateRoomConfigurationException)->statusCode())->toBe(422);
});

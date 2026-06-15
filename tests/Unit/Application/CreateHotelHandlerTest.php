<?php

declare(strict_types=1);

/**
 * Tests unitarios del caso de uso CreateHotelHandler.
 *
 * Verifica persistencia exitosa, detección de NIT duplicado
 * y comportamiento del repositorio de hoteles.
 */
use Src\Application\Hotels\CreateHotel\CreateHotelCommand;
use Src\Application\Hotels\CreateHotel\CreateHotelHandler;
use Src\Domain\Entities\Hotel;
use Src\Domain\Exceptions\DuplicateHotelException;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Tests\SeedsCatalogs;

uses(SeedsCatalogs::class);

/** Caso: el handler persiste un hotel y asigna identificador. */
it('creates hotel through handler', function (): void {
    $this->seedCatalogs();
    $handler = resolve(CreateHotelHandler::class);
    $ids = catalogIds();

    $hotel = $handler->handle(new CreateHotelCommand(
        name: 'TEST HOTEL',
        address: 'CALLE 1',
        cityId: $ids['city_id'],
        nit: '11111111-1',
        maxRooms: 10,
    ));

    expect($hotel->id)->not->toBeNull();
});

/** Caso: segundo registro con mismo NIT lanza DuplicateHotelException. */
it('throws duplicate hotel exception in handler', function (): void {
    $this->seedCatalogs();
    $handler = resolve(CreateHotelHandler::class);
    $ids = catalogIds();

    $command = new CreateHotelCommand(
        name: 'TEST HOTEL',
        address: 'CALLE 1',
        cityId: $ids['city_id'],
        nit: '11111111-1',
        maxRooms: 10,
    );

    $handler->handle($command);

    expect(fn () => $handler->handle($command))
        ->toThrow(DuplicateHotelException::class);
});

/** Caso: existsByNit refleja correctamente la presencia del hotel en BD. */
it('checks hotel repository exists by nit', function (): void {
    $this->seedCatalogs();
    $repository = resolve(HotelRepositoryInterface::class);
    $ids = catalogIds();

    expect($repository->existsByNit('00000000-0'))->toBeFalse();

    $repository->save(hotel: new Hotel(
        id: null,
        name: 'REPO HOTEL',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '00000000-0',
        maxRooms: 5,
    ));

    expect($repository->existsByNit('00000000-0'))->toBeTrue();
});

<?php

declare(strict_types=1);

/**
 * Tests unitarios de los handlers de hoteles y lectura de catálogos.
 *
 * Cubre GetHotel, ListHotels, DeleteHotel, UpdateHotel y consultas
 * auxiliares de repositorios (findByNit, findById).
 */
use Src\Application\Hotels\CreateHotel\CreateHotelCommand;
use Src\Application\Hotels\CreateHotel\CreateHotelHandler;
use Src\Application\Hotels\DeleteHotel\DeleteHotelHandler;
use Src\Application\Hotels\GetHotel\GetHotelHandler;
use Src\Application\Hotels\ListHotels\ListHotelsHandler;
use Src\Application\Hotels\UpdateHotel\UpdateHotelCommand;
use Src\Application\Hotels\UpdateHotel\UpdateHotelHandler;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Repositories\CityRepositoryInterface;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Src\Domain\Repositories\RoomTypeRepositoryInterface;
use Tests\SeedsCatalogs;

uses(SeedsCatalogs::class);

/** Caso: GetHotelHandler devuelve el hotel por identificador. */
it('gets hotel by id', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();
    $created = resolve(CreateHotelHandler::class)->handle(new CreateHotelCommand(
        name: 'GET HOTEL',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '33333333-3',
        maxRooms: 5,
    ));

    $hotel = resolve(GetHotelHandler::class)->handle($created->id);

    expect($hotel->name)->toBe('GET HOTEL');
});

/** Caso: GetHotelHandler lanza excepción si el hotel no existe. */
it('throws when getting missing hotel', function (): void {
    $this->seedCatalogs();

    expect(fn () => resolve(GetHotelHandler::class)->handle(999))
        ->toThrow(HotelNotFoundException::class);
});

/** Caso: ListHotelsHandler devuelve todos los hoteles persistidos. */
it('lists hotels through handler', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();

    resolve(CreateHotelHandler::class)->handle(new CreateHotelCommand(
        name: 'LIST HOTEL',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '44444444-4',
        maxRooms: 5,
    ));

    expect(resolve(ListHotelsHandler::class)->handle())->toHaveCount(1);
});

/** Caso: DeleteHotelHandler elimina el registro de la base de datos. */
it('deletes hotel through handler', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();
    $created = resolve(CreateHotelHandler::class)->handle(new CreateHotelCommand(
        name: 'DELETE HOTEL',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '55555555-5',
        maxRooms: 5,
    ));

    resolve(DeleteHotelHandler::class)->handle($created->id);

    expect(resolve(HotelRepositoryInterface::class)->findById($created->id))->toBeNull();
});

/** Caso: UpdateHotelHandler modifica nombre, dirección y cupo máximo. */
it('updates hotel through handler', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();
    $created = resolve(CreateHotelHandler::class)->handle(new CreateHotelCommand(
        name: 'UPDATE HOTEL',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '66666666-6',
        maxRooms: 5,
    ));

    $updated = resolve(UpdateHotelHandler::class)->handle(new UpdateHotelCommand(
        id: $created->id,
        name: 'UPDATED',
        address: 'NEW ADDR',
        cityId: $ids['city_id'],
        nit: '66666666-6',
        maxRooms: 8,
    ));

    expect($updated->name)->toBe('UPDATED')
        ->and($updated->maxRooms)->toBe(8);
});

/** Caso: repositorios de catálogo resuelven entidades por id o null. */
it('reads catalog entities by id', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();

    expect(resolve(CityRepositoryInterface::class)->findById($ids['city_id'])?->name)->toBe('CARTAGENA');
    expect(resolve(RoomTypeRepositoryInterface::class)->findById(999))->toBeNull();
    expect(resolve(CityRepositoryInterface::class)->findById(999))->toBeNull();
});

/** Caso: HotelRepository.findByNit localiza hotel por número de identificación tributaria. */
it('finds hotel by nit', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();

    resolve(CreateHotelHandler::class)->handle(new CreateHotelCommand(
        name: 'NIT HOTEL',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '77777777-7',
        maxRooms: 5,
    ));

    expect(resolve(HotelRepositoryInterface::class)->findByNit('77777777-7')?->name)->toBe('NIT HOTEL');
});

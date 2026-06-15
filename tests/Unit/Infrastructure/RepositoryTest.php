<?php

declare(strict_types=1);

/**
 * Tests unitarios de repositorios Eloquent (capa Infrastructure).
 *
 * Verifica lectura de catálogos, filtrado por tipo de habitación
 * y persistencia completa de configuraciones de habitación.
 */
use Src\Domain\Entities\Hotel;
use Src\Domain\Entities\HotelRoomConfiguration;
use Src\Domain\Repositories\AccommodationRepositoryInterface;
use Src\Domain\Repositories\CityRepositoryInterface;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Src\Domain\Repositories\HotelRoomConfigurationRepositoryInterface;
use Src\Domain\Repositories\RoomTypeRepositoryInterface;
use Tests\SeedsCatalogs;

uses(SeedsCatalogs::class);

/** Caso: repositorios de catálogo devuelven listas y filtros por room_type_id. */
it('reads catalog repositories', function (): void {
    $this->seedCatalogs();

    expect(resolve(CityRepositoryInterface::class)->all())->not->toBeEmpty();
    expect(resolve(RoomTypeRepositoryInterface::class)->all())->toHaveCount(3);
    expect(resolve(AccommodationRepositoryInterface::class)->all())->toHaveCount(4);

    $ids = catalogIds();
    expect(resolve(AccommodationRepositoryInterface::class)->findByRoomTypeId($ids['junior_id']))
        ->toHaveCount(2);
});

/** Caso: repositorio de configuraciones persiste, totaliza, detecta duplicados y elimina. */
it('manages hotel room configurations in repository', function (): void {
    $this->seedCatalogs();
    $hotelRepository = resolve(HotelRepositoryInterface::class);
    $configRepository = resolve(HotelRoomConfigurationRepositoryInterface::class);
    $ids = catalogIds();

    $hotel = $hotelRepository->save(hotel: new Hotel(
        id: null,
        name: 'REPO CONFIG HOTEL',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '22222222-2',
        maxRooms: 20,
    ));

    $config = $configRepository->save(configuration: new HotelRoomConfiguration(
        id: 0,
        hotelId: $hotel->id,
        roomTypeId: $ids['estandar_id'],
        accommodationId: $ids['doble_id'],
        quantity: 4,
    ));

    expect($configRepository->findByHotelId($hotel->id))->toHaveCount(1);
    expect($configRepository->totalQuantityByHotelId($hotel->id))->toBe(4);
    expect($configRepository->existsByHotelRoomTypeAndAccommodation(
        hotelId: $hotel->id,
        roomTypeId: $ids['estandar_id'],
        accommodationId: $ids['doble_id'],
    ))->toBeTrue();

    $configRepository->delete($config->id);
    expect($configRepository->findById($config->id))->toBeNull();
});

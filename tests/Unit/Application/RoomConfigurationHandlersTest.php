<?php

declare(strict_types=1);

/**
 * Tests unitarios de handlers de catálogos y configuraciones de habitación.
 *
 * Verifica listados de catálogo y el ciclo completo CRUD de configuraciones
 * a nivel de casos de uso (sin HTTP).
 */
use Src\Application\Catalogs\ListAccommodations\ListAccommodationsHandler;
use Src\Application\Catalogs\ListAccommodationsByRoomType\ListAccommodationsByRoomTypeHandler;
use Src\Application\Catalogs\ListCities\ListCitiesHandler;
use Src\Application\Catalogs\ListRoomTypes\ListRoomTypesHandler;
use Src\Application\Hotels\CreateHotel\CreateHotelCommand;
use Src\Application\Hotels\CreateHotel\CreateHotelHandler;
use Src\Application\RoomConfigurations\CreateRoomConfiguration\CreateRoomConfigurationCommand;
use Src\Application\RoomConfigurations\CreateRoomConfiguration\CreateRoomConfigurationHandler;
use Src\Application\RoomConfigurations\DeleteRoomConfiguration\DeleteRoomConfigurationHandler;
use Src\Application\RoomConfigurations\ListRoomConfigurations\ListRoomConfigurationsHandler;
use Src\Application\RoomConfigurations\UpdateRoomConfiguration\UpdateRoomConfigurationCommand;
use Src\Application\RoomConfigurations\UpdateRoomConfiguration\UpdateRoomConfigurationHandler;
use Src\Domain\Exceptions\RoomConfigurationNotFoundException;
use Tests\SeedsCatalogs;

uses(SeedsCatalogs::class);

/** Caso: los cuatro handlers de catálogo devuelven datos sembrados. */
it('lists catalog handlers', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();

    expect(resolve(ListCitiesHandler::class)->handle())->not->toBeEmpty();
    expect(resolve(ListRoomTypesHandler::class)->handle())->toHaveCount(3);
    expect(resolve(ListAccommodationsHandler::class)->handle())->toHaveCount(4);
    expect(resolve(ListAccommodationsByRoomTypeHandler::class)->handle($ids['suite_id']))->toHaveCount(3);
});

/** Caso: flujo crear → listar → actualizar → eliminar configuración de habitación. */
it('manages room configurations through handlers', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();

    $hotel = resolve(CreateHotelHandler::class)->handle(new CreateHotelCommand(
        name: 'CONFIG HOTEL',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '88888888-8',
        maxRooms: 20,
    ));

    $created = resolve(CreateRoomConfigurationHandler::class)->handle(new CreateRoomConfigurationCommand(
        hotelId: $hotel->id,
        roomTypeId: $ids['estandar_id'],
        accommodationId: $ids['sencilla_id'],
        quantity: 5,
    ));

    expect(resolve(ListRoomConfigurationsHandler::class)->handle($hotel->id))->toHaveCount(1);

    $updated = resolve(UpdateRoomConfigurationHandler::class)->handle(new UpdateRoomConfigurationCommand(
        id: $created->id,
        hotelId: $hotel->id,
        roomTypeId: $ids['estandar_id'],
        accommodationId: $ids['doble_id'],
        quantity: 7,
    ));

    expect($updated->quantity)->toBe(7);

    resolve(DeleteRoomConfigurationHandler::class)->handle($hotel->id, $created->id);

    expect(resolve(ListRoomConfigurationsHandler::class)->handle($hotel->id))->toBeEmpty();
});

/** Caso: DeleteRoomConfigurationHandler lanza excepción si el id no existe. */
it('throws when deleting missing room configuration', function (): void {
    $this->seedCatalogs();
    $ids = catalogIds();

    $hotel = resolve(CreateHotelHandler::class)->handle(new CreateHotelCommand(
        name: 'MISSING CONFIG',
        address: 'ADDR',
        cityId: $ids['city_id'],
        nit: '99999999-9',
        maxRooms: 5,
    ));

    expect(fn () => resolve(DeleteRoomConfigurationHandler::class)->handle($hotel->id, 999))
        ->toThrow(RoomConfigurationNotFoundException::class);
});

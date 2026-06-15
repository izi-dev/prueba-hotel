<?php

declare(strict_types=1);

/**
 * Definición de rutas HTTP de la API REST del sistema de gestión hotelera.
 *
 * Todas las rutas se registran bajo el prefijo `/api` (configuración de Laravel)
 * más el grupo `v1` definido aquí, resultando en URLs como `/api/v1/hotels`.
 *
 * Cada ruta delega en un controlador de acción única (invokable) que orquesta
 * el caso de uso correspondiente de la capa Application.
 *
 * Catálogos:
 * - GET  /cities
 * - GET  /room-types
 * - GET  /room-types/{roomTypeId}/accommodations
 * - GET  /accommodations
 *
 * Hoteles:
 * - GET    /hotels
 * - POST   /hotels
 * - GET    /hotels/{id}
 * - PUT    /hotels/{id}
 * - DELETE /hotels/{id}
 *
 * Configuraciones de habitación (anidadas bajo hotel):
 * - GET    /hotels/{hotelId}/room-configurations
 * - POST   /hotels/{hotelId}/room-configurations
 * - PUT    /hotels/{hotelId}/room-configurations/{configurationId}
 * - DELETE /hotels/{hotelId}/room-configurations/{configurationId}
 */
use App\Http\Controllers\Api\Catalogs\ListAccommodationsByRoomTypeController;
use App\Http\Controllers\Api\Catalogs\ListAccommodationsController;
use App\Http\Controllers\Api\Catalogs\ListCitiesController;
use App\Http\Controllers\Api\Catalogs\ListRoomTypesController;
use App\Http\Controllers\Api\Hotels\CreateHotelController;
use App\Http\Controllers\Api\Hotels\DeleteHotelController;
use App\Http\Controllers\Api\Hotels\ListHotelsController;
use App\Http\Controllers\Api\Hotels\ShowHotelController;
use App\Http\Controllers\Api\Hotels\UpdateHotelController;
use App\Http\Controllers\Api\RoomConfigurations\CreateRoomConfigurationController;
use App\Http\Controllers\Api\RoomConfigurations\DeleteRoomConfigurationController;
use App\Http\Controllers\Api\RoomConfigurations\ListRoomConfigurationsController;
use App\Http\Controllers\Api\RoomConfigurations\UpdateRoomConfigurationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/cities', ListCitiesController::class);
    Route::get('/room-types', ListRoomTypesController::class);
    Route::get('/room-types/{roomTypeId}/accommodations', ListAccommodationsByRoomTypeController::class);
    Route::get('/accommodations', ListAccommodationsController::class);

    Route::get('/hotels', ListHotelsController::class);
    Route::post('/hotels', CreateHotelController::class);
    Route::get('/hotels/{id}', ShowHotelController::class);
    Route::put('/hotels/{id}', UpdateHotelController::class);
    Route::delete('/hotels/{id}', DeleteHotelController::class);

    Route::get('/hotels/{hotelId}/room-configurations', ListRoomConfigurationsController::class);
    Route::post('/hotels/{hotelId}/room-configurations', CreateRoomConfigurationController::class);
    Route::put('/hotels/{hotelId}/room-configurations/{configurationId}', UpdateRoomConfigurationController::class);
    Route::delete('/hotels/{hotelId}/room-configurations/{configurationId}', DeleteRoomConfigurationController::class);
});

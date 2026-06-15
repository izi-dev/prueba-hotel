<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Repositories\AccommodationRepositoryInterface;
use Src\Domain\Repositories\CityRepositoryInterface;
use Src\Domain\Repositories\HotelRepositoryInterface;
use Src\Domain\Repositories\HotelRoomConfigurationRepositoryInterface;
use Src\Domain\Repositories\RoomTypeRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentAccommodationRepository;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentCityRepository;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentHotelRepository;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentHotelRoomConfigurationRepository;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentRoomTypeRepository;

/**
 * Proveedor de servicios para el enlace de repositorios del dominio.
 *
 * Asocia cada interfaz de repositorio del dominio hotelero con su
 * implementación Eloquent en la capa de infraestructura.
 */
final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Registra las implementaciones concretas de los repositorios del dominio.
     */
    public function register(): void
    {
        $this->app->bind(CityRepositoryInterface::class, EloquentCityRepository::class);
        $this->app->bind(RoomTypeRepositoryInterface::class, EloquentRoomTypeRepository::class);
        $this->app->bind(AccommodationRepositoryInterface::class, EloquentAccommodationRepository::class);
        $this->app->bind(HotelRepositoryInterface::class, EloquentHotelRepository::class);
        $this->app->bind(HotelRoomConfigurationRepositoryInterface::class, EloquentHotelRoomConfigurationRepository::class);
    }
}

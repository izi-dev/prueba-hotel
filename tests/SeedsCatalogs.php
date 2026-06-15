<?php

declare(strict_types=1);

namespace Tests;

use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Trait de soporte para tests que requieren catálogos precargados.
 *
 * Combina `RefreshDatabase` con un helper que ejecuta `CatalogSeeder`,
 * dejando disponibles ciudades, tipos de habitación, acomodaciones
 * y las reglas de compatibilidad entre ellos.
 */
trait SeedsCatalogs
{
    use RefreshDatabase;

    /**
     * Siembra los catálogos de referencia del dominio hotelero.
     */
    protected function seedCatalogs(): void
    {
        $this->seed(CatalogSeeder::class);
    }
}

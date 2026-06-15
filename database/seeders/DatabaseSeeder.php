<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder principal de la aplicación.
 *
 * Orquesta la ejecución de seeders secundarios necesarios para el entorno
 * inicial. Actualmente delega en `CatalogSeeder` los datos de referencia
 * del dominio hotelero.
 */
final class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders registrados en orden.
     */
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
        ]);
    }
}

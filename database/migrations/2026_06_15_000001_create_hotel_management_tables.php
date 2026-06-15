<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración del esquema de gestión hotelera.
 *
 * Crea las tablas del dominio:
 * - cities: catálogo de ciudades
 * - room_types / accommodations: catálogos de inventario
 * - room_type_accommodation: reglas de compatibilidad (pivote)
 * - hotels: agregado raíz con NIT único y cupo máximo de habitaciones
 * - hotel_room_configurations: líneas de inventario por hotel
 */
return new class extends Migration
{
    /**
     * Aplica el esquema en orden de dependencias de claves foráneas.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('room_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('accommodations', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('room_type_accommodation', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('accommodation_id')->constrained()->cascadeOnDelete();
            $table->unique(['room_type_id', 'accommodation_id']);
        });

        Schema::create('hotels', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->foreignId('city_id')->constrained();
            $table->string('nit')->unique();
            $table->unsignedInteger('max_rooms');
            $table->timestamps();
        });

        Schema::create('hotel_room_configurations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_type_id')->constrained();
            $table->foreignId('accommodation_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->unique(['hotel_id', 'room_type_id', 'accommodation_id'], 'hotel_room_type_accommodation_unique');
            $table->timestamps();
        });
    }

    /**
     * Revierte el esquema en orden inverso para respetar restricciones FK.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_room_configurations');
        Schema::dropIfExists('hotels');
        Schema::dropIfExists('room_type_accommodation');
        Schema::dropIfExists('accommodations');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('cities');
    }
};

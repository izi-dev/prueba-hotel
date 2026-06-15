<?php

declare(strict_types=1);

use App\Http\Controllers\DocumentacionController;
use Illuminate\Support\Facades\Route;

/**
 * Rutas web públicas: landing, documentación y SPA de gestión hotelera.
 */
Route::view('/', 'welcome');

Route::get('/documentacion', [DocumentacionController::class, 'index']);
Route::get('/documentacion/openapi', [DocumentacionController::class, 'openapi']);
Route::get('/documentacion/{page}', [DocumentacionController::class, 'show'])
    ->where('page', 'proyecto|uml|despliegue');

Route::view('/app/{any?}', 'app')->where('any', '.*');

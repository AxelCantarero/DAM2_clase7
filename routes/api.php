<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DetalleFacturaController;

Route::apiResource('facturas', FacturaController::class);
Route::apiResource('detalle-facturas', DetalleFacturaController::class);

Route::get('/facturas/{id}/detalles', [DetalleFacturaController::class, 'detallesPorFactura']);
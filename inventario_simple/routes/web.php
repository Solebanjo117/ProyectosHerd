<?php

use App\Models\Producto;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Bienvenido' );
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*Route::get('productos', function () {
    return Inertia::render('Productos/Productos', [
        'productos' => Producto::orderBy('activo', 'desc')->paginate(5),
    ]);
})->middleware(['auth', 'verified'])->name('productos');*/
Route::middleware(['auth', 'verified'])->group(function () {
    //Productos
  Route::resource('productos', \App\Http\Controllers\ProductoController::class)
  ->names('productos');
Route::post('/productos/{producto}/update', [\App\Http\Controllers\ProductoController::class, 'updatePost'])->name('productos.update-post');
//Clientes
Route::resource('clientes', \App\Http\Controllers\Cliente::class)
    ->names('clientes');
//Ventas
    Route::resource('ventas', \App\Http\Controllers\VentasController::class)
    ->names('ventas');
    
//Salidas
    Route::resource('salidas', \App\Http\Controllers\SalidasController::class)
    ->names('salidas');
//Entradas
    Route::resource('entradas', \App\Http\Controllers\EntradasController::class)
    ->names('entradas');
//Salidas Detalles
   Route::post('salidasDetalle', [\App\Http\Controllers\SalidasDetalleController::class, 'store'])
    ->name('salidasDetalle.store');
    Route::put('salidasDetalle/{id}', [\App\Http\Controllers\SalidasDetalleController::class, 'update'])
    ->name('salidasDetalle.update');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

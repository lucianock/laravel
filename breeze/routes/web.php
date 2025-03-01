<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*##### crud de marcas #####*/
Route::get('/marcas', [MarcaController::class, 'index'])
    ->middleware('auth')->name('marcas');

Route::get('/marca/create', [MarcaController::class, 'create'])
    ->middleware('auth')->name('marca.create');

Route::post('/marca/store', [MarcaController::class, 'store'])
    ->middleware('auth')->name('marca.store');

Route::get('/marca/edit/{marca}', [MarcaController::class, 'edit'])
    ->middleware('auth')->name('marca.edit');

Route::put('/marca/update/{marca}', [MarcaController::class, 'update'])
    ->middleware('auth')->name('marca.update');

Route::get('/marca/delete/{marca}', [MarcaController::class, 'delete'])
    ->middleware('auth')->name('marca.delete');

Route::delete('/marca/{marca}', [MarcaController::class, 'destroy'])
    ->middleware('auth')->name('marca.destroy');

/*##### crud de productos #####*/
Route::get('/productos', [ProductoController::class, 'index'])
    ->middleware('auth')->name('productos');

Route::get('/producto/create', [ProductoController::class, 'create'])
    ->middleware('auth')->name('producto.create');

Route::post('/producto/store', [ProductoController::class, 'store'])
    ->middleware('auth')->name('producto.store');

Route::get('/producto/edit/{producto}', [ProductoController::class, 'edit'])
    ->middleware('auth')->name('producto.edit');

Route::put('/producto/update/{producto}', [ProductoController::class, 'update'])
    ->middleware('auth')->name('producto.update');

Route::get('/producto/delete/{producto}', [ProductoController::class, 'delete'])
    ->middleware('auth')->name('producto.delete');

Route::delete('/producto/destroy/{producto}', [ProductoController::class, 'destroy'])
    ->middleware('auth')->name('producto.destroy');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

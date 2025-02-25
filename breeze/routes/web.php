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
    ->middleware('auth');
Route::post('/marca/store', [MarcaController::class, 'store'])
    ->middleware('auth');
Route::get('/marca/edit/{marca}', [MarcaController::class, 'edit'])
    ->middleware('auth');
Route::put('/marca/update/{marca}', [MarcaController::class, 'update'])
    ->middleware('auth');

Route::get('/marca/delete/{id}', [ MarcaController::class, 'delete' ])
    ->middleware('auth');
Route::delete('/marca/destroy/{marca}', [ MarcaController::class, 'destroy' ])
    ->middleware('auth');



/*##### crud de productos #####*/
Route::get('/productos', [ProductoController::class, 'index'])
    ->middleware('auth')->name('productos');
Route::get('/producto/create', [ProductoController::class, 'create'])
    ->middleware('auth');
Route::post('/producto/store', [ProductoController::class, 'store'])
    ->middleware('auth');
Route::get('/producto/edit/{producto}', [ProductoController::class, 'edit'])
    ->middleware('auth');
Route::put('/producto/update/{producto}', [ProductoController::class, 'update'])
    ->middleware('auth');

Route::get('/producto/delete/{id}', [ ProductoController::class, 'delete' ])
    ->middleware('auth');
Route::delete('/producto/destroy/{producto}', [ ProductoController::class, 'destroy' ])
    ->middleware('auth');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
            

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
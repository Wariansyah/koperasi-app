<?php

use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{PermissionController, ProdukController, UserController, RoleController, LedgerController, AnggotaController};
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\CompanyController;
use App\Http\Middleware\UpdateKasNextDay;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->Middleware('UpdateKasNextDay');

Route::post('/postlogin', [LoginController::class, 'postlogin'])->name('postlogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::put('/permissions/{id}', 'PermissionController@update')->name('permissions.update');
    Route::delete('permissions/{id}', 'PermissionController@destroy')->name('permissions.destroy');
    Route::put('/roles/{id}', 'RoleController@update')->name('roles.update');
    Route::post('/roles', 'RoleController@store')->name('roles.store');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::resource('/users', UserController::class);
    Route::delete('roles/{id}', 'RoleController@destroy')->name('roles.destroy');
    Route::post('/users', 'UserController@store')->name('users.store');
    Route::resource('users', UserController::class);
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', 'UserController@update')->name('users.update');
    Route::delete('users/{id}', 'UserController@destroy')->name('users.destroy');
    Route::get('/kas', [KasController::class, 'index'])->name('kas.index');
    Route::resource('kas', KasController::class);
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::resource('/produk', ProdukController::class);
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::put('/produk/{id}', 'ProdukController@update')->name('produk.update');
    Route::delete('produk/{id}', 'ProdukController@destroy')->name('produk.destroy');
    Route::get('/ledgers', [LedgerController::class, 'index'])->name('ledgers.index');
    Route::resource('/ledgers', LedgerController::class);
    Route::post('/ledgers', [LedgerController::class, 'store'])->name('ledgers.store');
    Route::put('/ledgers/{id}', [LedgerController::class, 'update'])->name('ledgers.update');
    Route::delete('/ledgers/{id}', [LedgerController::class, 'destroy'])->name('ledgers.destroy');
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::resource('/companies', CompanyController::class);
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::resource('/anggota', AnggotaController::class);
    Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
    Route::get('anggota/simpanan', 'AnggotaController@lihatSimpanan')->name('anggota.lihat_simpanan');



})->middleware('web');

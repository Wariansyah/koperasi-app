<?php

use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{PermissionController, UserController, RoleController};
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KasController;
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
    
})->middleware('web');

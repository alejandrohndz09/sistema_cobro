<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\SucursalController;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'home']);
Route::get('inicio', function () {
    return view('dashboard');
})->name('inicio');

//Pantalla categorias
Route::resource('/activos/categorias', 'App\Http\Controllers\CategoriaController');
Route::get('/obtener-categorias', 'App\Http\Controllers\CategoriaController@getCategorias');
Route::get('/activos/categorias/baja/{id}', 'App\Http\Controllers\CategoriaController@baja');
Route::get('/activos/categorias/alta/{id}', 'App\Http\Controllers\CategoriaController@alta');

//Pantalla bienes
Route::resource('/activos/{idActivo}/bienes', 'App\Http\Controllers\BienController');
Route::get('/activos/{idActivo}/obtener-bienes', 'App\Http\Controllers\BienController@getBienes');
Route::post('/activos/bienes/baja/{id}', 'App\Http\Controllers\BienController@baja');
Route::get('/activos/bienes/alta/{id}', 'App\Http\Controllers\BienController@alta');

//Pantalla activos
Route::get('/activos/pdf', 'App\Http\Controllers\ActivoController@pdf');
Route::get('/obtener-activos', 'App\Http\Controllers\ActivoController@getActivos');
Route::get('/activos/baja/{id}', 'App\Http\Controllers\ActivoController@baja');
Route::get('/activos/alta/{id}', 'App\Http\Controllers\ActivoController@alta');
Route::get('/activos/obtener-categorias', 'App\Http\Controllers\ActivoController@getCategorias');
Route::get('/activos/obtener-sucursales', 'App\Http\Controllers\ActivoController@getSucursales');
Route::get('/activos/obtener-departamentos/{idSucursal}', 'App\Http\Controllers\ActivoController@getDepartamentos');
Route::resource('/activos', 'App\Http\Controllers\ActivoController');


Route::get('opciones', function () {
    return view('opciones.index');
})->name('opciones');

//Pantalla de departamentos
Route::resource('/empresa/departamentos', 'App\Http\Controllers\DepartamentoController');
Route::get('/obtener-departamentos', 'App\Http\Controllers\DepartamentoController@getDepartamentos');
Route::get('/empresa/departamentos/baja/{id}', 'App\Http\Controllers\DepartamentoController@baja');
Route::get('/empresa/departamentos/alta/{id}', 'App\Http\Controllers\DepartamentoController@alta');

//Pantalla empresa
Route::resource('/opciones/empresa', 'App\Http\Controllers\EmpresaController');
Route::get('/obtener-empresa', 'App\Http\Controllers\EmpresaController@getEmpresa');
Route::get('/opciones/empresa/{id}/editEmpresa', 'App\Http\Controllers\EmpresaController@editEmpresa')->name('empresa.edit');
Route::put('/opciones/empresa/{id}/updateEmpresa', 'App\Http\Controllers\EmpresaController@updateEmpresa')->name('empresa.update');

//Sucursales
Route::resource('/opciones/sucursal', 'App\Http\Controllers\SucursalController');
Route::get('/obtener-sucursales', 'App\Http\Controllers\SucursalController@getSucursales');
Route::get('/opciones/sucursal/baja/{id}', 'App\Http\Controllers\SucursalController@bajaSucursal');
Route::get('/opciones/sucursal/alta/{id}', 'App\Http\Controllers\SucursalController@altaSucursal');

//Pantalla empleados
Route::resource('/opciones/empleados', 'App\Http\Controllers\EmpleadoController');
Route::get('/obtener-empleados', 'App\Http\Controllers\EmpleadoController@getEmpleados');
Route::get('/opciones/empleados/baja/{id}', 'App\Http\Controllers\EmpleadoController@baja');
Route::get('/opciones/empleados/alta/{id}', 'App\Http\Controllers\EmpleadoController@alta');
Route::get('/obtener-departamentos', 'App\Http\Controllers\EmpleadoController@getDepartamentos');

//Pantalla de usuarios
Route::resource('/opciones/usuarios', 'App\Http\Controllers\UsuarioController');
Route::get('/obtener-usuarios', 'App\Http\Controllers\UsuarioController@getUsuarios');
Route::get('/opciones/usuarios/baja/{id}', 'App\Http\Controllers\UsuarioController@baja');
Route::get('/opciones/usuarios/alta/{id}', 'App\Http\Controllers\UsuarioController@alta');

Route::get('billing', function () {
    return view('billing');
})->name('billing');

Route::get('profile', function () {
    return view('profile');
})->name('profile');

Route::get('rtl', function () {
    return view('rtl');
})->name('rtl');

Route::get('user-management', function () {
    return view('laravel-examples/user-management');
})->name('user-management');

Route::get('tables', function () {
    return view('tables');
})->name('tables');

Route::get('virtual-reality', function () {
    return view('virtual-reality');
})->name('virtual-reality');

Route::get('static-sign-in', function () {
    return view('static-sign-in');
})->name('sign-in');

Route::get('static-sign-up', function () {
    return view('static-sign-up');
})->name('sign-up');

Route::get('/logout', [SessionsController::class, 'destroy']);
Route::get('/user-profile', [InfoUserController::class, 'create']);
Route::post('/user-profile', [InfoUserController::class, 'store']);
Route::get('/login', function () {
    return view('dashboard');
})->name('sign-up');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');

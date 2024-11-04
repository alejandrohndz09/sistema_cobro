<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
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

//Pantalla activos
Route::get('activos', 'App\Http\Controllers\ActivoController@index');
Route::get('/obtener-activos', 'App\Http\Controllers\ActivoController@getActivos');
Route::get('/obtener-bienes/{activo}', 'App\Http\Controllers\ActivoController@getBienes');
Route::get('/activos/baja/{id}', 'App\Http\Controllers\ActivoController@baja');
Route::get('/activos/alta/{id}', 'App\Http\Controllers\ActivoController@alta');

//Generar PDF
Route::get('/activos/pdf', 'App\Http\Controllers\ActivoController@pdf');


//Pantalla categorias
Route::resource('/activos/categorias', 'App\Http\Controllers\CategoriaController');
Route::get('/activos/categorias', 'App\Http\Controllers\CategoriaController@index');
Route::get('/obtener-categorias', 'App\Http\Controllers\CategoriaController@getCategorias');
Route::get('/activos/categorias/baja/{id}', 'App\Http\Controllers\CategoriaController@baja');
Route::get('/activos/categorias/alta/{id}', 'App\Http\Controllers\CategoriaController@alta');

Route::get('opciones', function () {
	return view('opciones.index');
})->name('opciones');

//Pantalla empresa
Route::resource('/opciones/empresa', 'App\Http\Controllers\EmpresaController');
Route::get('/obtener-empresa', 'App\Http\Controllers\EmpresaController@getEmpresa');
Route::get('/obtener-sucursales/{empresa}', 'App\Http\Controllers\EmpresaController@getSucursales');
Route::get('/opciones/empresa/baja/{id}', 'App\Http\Controllers\EmpresaController@baja');
Route::get('/opciones/empresa/alta/{id}', 'App\Http\Controllers\EmpresaController@alta');

//Pantalla empleados
Route::resource('/opciones/empleados', 'App\Http\Controllers\EmpleadoController');
Route::get('/obtener-empleados', 'App\Http\Controllers\EmpleadoController@getEmpleados');
Route::get('/opciones/empleados/baja/{id}', 'App\Http\Controllers\EmpleadoController@baja');
Route::get('/opciones/empleados/alta/{id}', 'App\Http\Controllers\EmpleadoController@alta');

//Pantalla de usuarios
Route::resource('/opciones/usuarios', 'App\Http\Controllers\UsuarioController');
Route::get('/obtener-usuarios', 'App\Http\Controllers\UsuarioController@getUsuarios');
Route::get('/opciones/usuarios/baja/{id}', 'App\Http\Controllers\UsuarioController@baja');
Route::get('/opciones/usuarios/alta/{id}', 'App\Http\Controllers\UsuarioController@alta');

//Pantalla de departamentos
Route::resource('/empresa/departamentos', 'App\Http\Controllers\DepartamentoController');
Route::get('/obtener-departamentos', 'App\Http\Controllers\DepartamentoController@getDepartamentos');
Route::get('/empresa/departamentos/baja/{id}', 'App\Http\Controllers\DepartamentoController@baja');
Route::get('/empresa/departamentos/alta/{id}', 'App\Http\Controllers\DepartamentoController@alta');




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

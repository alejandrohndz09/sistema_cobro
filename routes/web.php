<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\CompraController;
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

Route::group(['middleware' => 'auth'], function () {

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
	Route::get('/activos/pdfActivo/{id}', 'App\Http\Controllers\ActivoController@pdfActivo');
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
	Route::resource('/opciones/departamentos', 'App\Http\Controllers\DepartamentoController');
	Route::get('/obtener-departamentos/{id}', 'App\Http\Controllers\DepartamentoController@getDepartamentos');
	Route::get('/opciones/departamentos/baja/{id}', 'App\Http\Controllers\DepartamentoController@baja');
	Route::get('/opciones/departamentos/alta/{id}', 'App\Http\Controllers\DepartamentoController@alta');

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

	//Pantalla de productos
	Route::resource('/productos', 'App\Http\Controllers\ProductoController');
	Route::get('/obtener-productos', 'App\Http\Controllers\ProductoController@getProductos');
	Route::get('/producto-detalle/{tipo}/{id}', 'App\Http\Controllers\ProductoController@getDetallesProducto');
	Route::get('/productos/baja/{id}', 'App\Http\Controllers\ProductoController@baja');
	Route::get('/productos/alta/{id}', 'App\Http\Controllers\ProductoController@alta');

	//Pantalla de Proveedor
// 	Route::resource('/opciones/proveedores', 'App\Http\Controllers\ProveedorController');
// 	Route::get('/obtener-proveedores', 'App\Http\Controllers\ProveedorController@getProveedores');
//   Route::get('/opciones/proveedores/baja/{id}', 'App\Http\Controllers\ProveedorController@baja');
//   Route::get('/opciones/proveedores/alta/{id}', 'App\Http\Controllers\ProveedorController@alta');
Route::resource('/gestión-comercial/productos/proveedores', 'App\Http\Controllers\ProveedorController');
	Route::get('/obtener-proveedores', 'App\Http\Controllers\ProveedorController@getProveedores');
  Route::get('/gestión-comercial/productos/proveedores/baja/{id}', 'App\Http\Controllers\ProveedorController@baja');
  Route::get('/gestión-comercial/productos/proveedores/alta/{id}', 'App\Http\Controllers\ProveedorController@alta');



	Route::get('gestión-comercial', function () {
		return view('gestion-comercial.index');
	});


	//Pantalla de productos
	Route::get('/gestión-comercial/productos/obtener-productos', 'App\Http\Controllers\ProductoController@getProductos');
	Route::get('/gestión-comercial/productos/producto-detalle/{tipo}/{id}', 'App\Http\Controllers\ProductoController@getDetallesProducto');
	Route::get('/gestión-comercial/productos/baja/{id}', 'App\Http\Controllers\ProductoController@baja');
	Route::get('/gestión-comercial/productos/alta/{id}', 'App\Http\Controllers\ProductoController@alta');
	Route::resource('/gestión-comercial/productos', 'App\Http\Controllers\ProductoController');

	//Pantalla ventas
	Route::get('/gestión-comercial/ventas/pdf', 'App\Http\Controllers\VentaController@pdf');
	Route::get('/gestión-comercial/ventas/obtener-codigo', 'App\Http\Controllers\VentaController@getIdVenta');
	Route::get('/obtener-ventas/{tipoVenta}/{tipoCliente}', 'App\Http\Controllers\VentaController@getVentas');
	Route::get('/gestión-comercial/ventas/obtener-productos/{query?}', 'App\Http\Controllers\VentaController@getProductos');
	Route::get('/gestión-comercial/ventas/obtener-clientes/{query?}', 'App\Http\Controllers\VentaController@getClientes');
	Route::get('/gestión-comercial/ventas/baja/{id}', 'App\Http\Controllers\VentaController@baja');
	Route::get('/gestión-comercial/ventas/alta/{id}', 'App\Http\Controllers\VentaController@alta');
	Route::resource('/gestión-comercial/ventas', 'App\Http\Controllers\VentaController');
  Route::get('/gestión-comercial/ventas/{id}/pdfFactura', 'App\Http\Controllers\VentaController@pdfFactura')->name('ventas.pdfFactura');
  
  
  //Cuotas
  Route::resource('/gestión-comercial/cuotas', 'App\Http\Controllers\CuotaController');
  Route::get('/gestión-comercial/cuota/{id}', 'App\Http\Controllers\CuotaController@show');
  Route::get('/obtener-cuotas/{id}', 'App\Http\Controllers\CuotaController@getCuotas');
  Route::post('/gestión-comercial/cuotas/generar-automaticas/{idVenta}', 'App\Http\Controllers\CuotaController@generarCuotasAutomaticas');
  Route::get('/gestión-comercial/cuotas/{idVenta}', 'App\Http\Controllers\CuotaController@obtenerCuotasPorVenta');
  Route::post('/gestión-comercial/cuotas/{idCuota}/actualizar-fecha', 'App\Http\Controllers\CuotaController@actualizarFecha');
  Route::get('/gestión-comercial/cuotas/actualizar-estados', 'App\Http\Controllers\CuotaController@actualizarEstadoCuotas');

	Route::resource('/gestión-comercial/clientes', 'App\Http\Controllers\ClienteController');
	Route::get('/obtener-listaclientes/{tipoCliente}/{tipoClasificacion}', 'App\Http\Controllers\ClienteController@obtenerClientes');
	Route::get('/gestión-comercial/clientes/baja/{id}', 'App\Http\Controllers\ClienteController@baja');
	Route::get('/gestión-comercial/clientes/alta/{id}', 'App\Http\Controllers\ClienteController@alta');


	//Pantalla compras
	Route::resource('/gestión-comercial/compras', 'App\Http\Controllers\CompraController');
	Route::get('/g-comercial/compras/obtenerCodigo-compras', 'App\Http\Controllers\CompraController@getIdCompra');
	Route::get('/g-comercial/compras/obtenerProductos/{query?}', 'App\Http\Controllers\CompraController@getProductos');
	Route::get('/g-comercial/compras/obtenerProveedores/{query?}', 'App\Http\Controllers\CompraController@getProveedores');
	Route::get('/g-comercial/compras/bajaCompra/{id}', 'App\Http\Controllers\CompraController@bajaCompra');
	Route::get('/compras/obtener', 'App\Http\Controllers\CompraController@obtenerCompras');
	Route::get('/g-comercial/compras/altaCompra/{id}', 'App\Http\Controllers\CompraController@altaCompra');
	Route::get('/compras/{id}', 'App\Http\Controllers\CompraController@show');
	Route::get('/compras/obtenerMonto', 'App\Http\Controllers\CompraController@obtenerComprasMonto');
	Route::get('/g-comercial/compras/reporte', 'App\Http\Controllers\CompraController@generarPDF');
	Route::get('/sucursales-obtener', 'App\Http\Controllers\CompraController@getSucursalC');


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

	Route::post('/logout', 'App\Http\Controllers\LoginController@logout');
});
// Route::group(['middleware' => 'guest'], function () {
// 	Route::get('/register', [RegisterController::class, 'create']);
// 	Route::post('/register', [RegisterController::class, 'store']);
// 	Route::get('/login', [SessionsController::class, 'create']);
// 	Route::post('/session', [SessionsController::class, 'store']);
// 	Route::get('/login/forgot-password', [ResetController::class, 'create']);
// 	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
// 	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
// 	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
// });

Route::get('login', function () {
    return view('usuarios.login');
})->name('login')->middleware('guest');
Route::post('/login', 'App\Http\Controllers\LoginController@login');

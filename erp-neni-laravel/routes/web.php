<?php

use App\Http\Controllers\ComisionesRepartidorController;
use App\Http\Controllers\ComisionesVendedorController;
use App\Http\Controllers\PuntosEntregaController;
use App\Http\Controllers\ProveedoreController;
use App\Http\Controllers\RecepcioneController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlmaceneController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\RutasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*Route::get('/', function () {
    return view('auth.login');
});*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();
/*
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name('clientes');
Route::get('/proveedores', [App\Http\Controllers\ProveedoreController::class, 'index'])->name('proveedores');
Route::get('/productos', [App\Http\Controllers\ProductoController::class, 'index'])->name('productos');
Route::get('/almacenes', [App\Http\Controllers\AlmaceneController::class, 'index'])->name('almacenes');
*/
Route::resource('/', DashboardController::class)->names('dashboard');
Route::resource('/clientes', ClienteController::class)->names('clientes');
Route::resource('/proveedores', ProveedoreController::class)->names('proveedores');
Route::resource('/ventas', VentaController::class)->names('ventas');
Route::resource('/usuarios', UsuarioController::class)->names('usuarios');
Route::resource('/productos', ProductoController::class)->names('productos');
Route::resource('/almacenes', AlmaceneController::class)->names('almacenes');
Route::resource('/recepciones', RecepcioneController::class)->names('recepciones');
Route::resource('/movimientos', MovimientoController::class)->names('movimientos');
Route::resource('/inventarios', InventarioController::class)->names('inventarios');
Route::resource('/puntos_entregas', PuntosEntregaController::class)->names('puntos_entregas');
Route::resource('/entregas', EntregaController::class)->names('entregas');
Route::resource('/comisionesVendedor', ComisionesVendedorController::class)->names('comisionesVendedor');
Route::resource('/comisionesRepartidor', ComisionesRepartidorController::class)->names('comisionesRepartidor');
Route::resource('/generador_rutas', RutasController::class)->names('generador_rutas');
Route::resource('/perfil', PerfilController::class)->names('perfil');

Route::get('buscar-clientes', [ClienteController::class, 'buscar'])->name('clientes.buscar');
Route::get('buscar-proveedores', [ProveedoreController::class, 'buscar'])->name('proveedores.buscar');
Route::get('buscar-usuario', [UsuarioController::class, 'buscar'])->name('usuarios.buscar');
Route::get('buscar-puntos_entregas', [PuntosEntregaController::class, 'buscar'])->name('puntos_entregas.buscar');
Route::get('getAllProducts', [ProductoController::class, 'getAllProducts'])->name('productos.getAllProducts');
Route::get('getProductsForAlmacen', [ProductoController::class, 'getProductsForAlmacen'])->name('productos.getProductsForAlmacen');

Route::get('indexhoy', [EntregaController::class, 'indexhoy'])->name('entregas.indexhoy');
Route::get('indexmanana', [EntregaController::class, 'indexmanana'])->name('entregas.indexmanana');



Route::post('/productos/getInfo', [ProductoController::class, 'getInfo'])->name('productos.getInfo');



//Route::put('/ventas/{id}', 'VentasController@update');



/*Route::get('/hash', function () {
    return Hash::make('20042022');
});*/

Auth::routes();
Route::get('/home', [DashboardController::class, 'index'])->name('home')->middleware('auth');
/*Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');*/

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$base = "App\Http\Controllers\\";

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', $base.'LoginController@verificarAcceso');

Route::post('/guardarmarca', $base.'MarcaController@guardar');
Route::post('/editarmarca', $base.'MarcaController@editar');
Route::post('/eliminarmarca', $base.'MarcaController@eliminar');

Route::post('/guardarmodelo', $base.'ModeloController@guardar');
Route::post('/editarmodelo', $base.'ModeloController@editar');
Route::post('/eliminarmodelo', $base.'ModeloController@eliminar');

Route::post('/guardarprovincia', $base.'ProvinciaController@guardar');
Route::post('/editarprovincia', $base.'ProvinciaController@editar');
Route::post('/eliminarprovincia', $base.'ProvinciaController@eliminar');

Route::post('/guardarcanton', $base.'CantonController@guardar');
Route::post('/editarcanton', $base.'CantonController@editar');
Route::post('/eliminarcanton', $base.'CantonController@eliminar');

Route::post('/guardarrol', $base.'RolController@guardar');
Route::post('/editarrol', $base.'RolController@editar');
Route::post('/eliminarrol', $base.'RolController@eliminar');
Route::post('/guardarpermisos', $base.'RolController@guardarpermisos');

Route::post('/guardarmodulo', $base.'ModuloController@guardar');
Route::post('/editarmodulo', $base.'ModuloController@editar');
Route::post('/eliminarmodulo', $base.'ModuloController@eliminar');

Route::post('/guardarlicencia', $base.'LicenciaController@guardar');
Route::post('/editarlicencia', $base.'LicenciaController@editar');
Route::post('/eliminarlicencia', $base.'LicenciaController@eliminar');

Route::post('/guardarfpago', $base.'FormaPagoController@guardar');
Route::post('/editarfpago', $base.'FormaPagoController@editar');
Route::post('/eliminarfpago', $base.'FormaPagoController@eliminar');

Route::post('/guardarvehiculo', $base.'VehiculoController@guardar');
Route::post('/editarvehiculo', $base.'VehiculoController@editar');
Route::post('/eliminarvehiculo', $base.'VehiculoController@eliminar');
Route::post('/buscarvehiculo', $base.'VehiculoController@buscar');

Route::post('/guardarusuario', $base.'UsuarioController@guardar');
Route::post('/editarusuario', $base.'UsuarioController@editar');
Route::post('/eliminarusuario', $base.'UsuarioController@eliminar');
Route::post('/guardarvehiculousuario', $base.'UsuarioController@guardarvehiculo');
Route::post('/eliminarvehiculousuario', $base.'UsuarioController@eliminarvehiculo');

Route::post('/guardaraporte', $base.'AporteController@guardar');
Route::post('/editaraporte', $base.'AporteController@editar');
Route::post('/eliminaraporte', $base.'AporteController@eliminar');
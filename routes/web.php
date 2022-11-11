<?php

use Illuminate\Support\Facades\Route;

$base = "App\Http\Controllers\\";

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

Route::get('/', $base.'LoginController@index');
Route::get('/sesion/{usuario}/{rol}', $base.'LoginController@sesion');
Route::get('/cerrar', $base.'LoginController@cerrar');

Route::get('/dashboardadmin', $base.'DashboardAdminController@index');

Route::get('/marcas/{pagina?}', $base.'MarcaController@consultar');
Route::get('/agregarmarca', $base.'MarcaController@vistaAgregar');
Route::get('/editarmarca/{id}', $base.'MarcaController@vistaEditar');

Route::get('/modelos/{pagina?}', $base.'ModeloController@consultar');
Route::get('/agregarmodelo', $base.'ModeloController@vistaAgregar');
Route::get('/editarmodelo/{id}', $base.'ModeloController@vistaEditar');

Route::get('/provincias/{pagina?}', $base.'ProvinciaController@consultar');
Route::get('/agregarprovincia', $base.'ProvinciaController@vistaAgregar');
Route::get('/editarprovincia/{id}', $base.'ProvinciaController@vistaEditar');

Route::get('/cantones/{pagina?}', $base.'CantonController@consultar');
Route::get('/agregarcanton', $base.'CantonController@vistaAgregar');
Route::get('/editarcanton/{id}', $base.'CantonController@vistaEditar');

Route::get('/roles/{pagina?}', $base.'RolController@consultar');
Route::get('/agregarrol', $base.'RolController@vistaAgregar');
Route::get('/editarrol/{id}', $base.'RolController@vistaEditar');
Route::get('/permisos/{id}', $base.'RolController@vistaPermiso');

Route::get('/modulos/{pagina?}', $base.'ModuloController@consultar');
Route::get('/agregarmodulo', $base.'ModuloController@vistaAgregar');
Route::get('/editarmodulo/{id}', $base.'ModuloController@vistaEditar');

Route::get('/licencias/{pagina?}', $base.'LicenciaController@consultar');
Route::get('/agregarlicencia', $base.'LicenciaController@vistaAgregar');
Route::get('/editarlicencia/{id}', $base.'LicenciaController@vistaEditar');

Route::get('/fpagos/{pagina?}', $base.'FormaPagoController@consultar');
Route::get('/agregarfpago', $base.'FormaPagoController@vistaAgregar');
Route::get('/editarfpago/{id}', $base.'FormaPagoController@vistaEditar');

Route::get('/vehiculos/{pagina?}', $base.'VehiculoController@consultar');
Route::get('/agregarvehiculo', $base.'VehiculoController@vistaAgregar');
Route::get('/editarvehiculo/{id}', $base.'VehiculoController@vistaEditar');

Route::get('/socios/{pagina?}', $base.'UsuarioController@consultar');
Route::get('/agregarsocio', $base.'UsuarioController@vistaAgregar');
Route::get('/editarsocio/{id}', $base.'UsuarioController@vistaEditar');
Route::get('/vehiculosocio/{socio}/{pagina?}', $base.'UsuarioController@vistaVehiculo');
Route::get('/agregarvehiculosocio/{socio}', $base.'UsuarioController@vistaVehiculoAgregar');

Route::get('/dashboard', $base.'DashboardController@index');

Route::get('/aportes/{pagina?}', $base.'AporteController@consultar');
Route::get('/agregaraporte', $base.'AporteController@vistaAgregar');
//Route::get('/editaraporte/{id}', $base.'AporteController@vistaEditar');
Route::get('/aportesadmin/{pagina?}', $base.'AporteController@consultaradmin');

Route::get('storage/app/comprobantes/{filename}', function ($filename)
{
    $path = storage_path('app/comprobantes/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

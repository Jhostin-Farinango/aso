<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $usuariosAll = DB::table('usuario')
                    ->join("canton", "usuario.id_canton", "canton.id_canton")
                    ->join("provincia", "canton.id_provincia", "provincia.id_provincia")
                    ->join("rol", "usuario.id_rol", "rol.id_rol")
                    ->leftJoin("licencia", "usuario.id_licencia", "licencia.id_licencia")
                    ->where('status_usuario', 1)
                    ->where('status_canton', 1)
                    ->where('status_provincia', 1)
                    ->where('status_rol', 1)
                    ->get();
            $cantResultados = count($usuariosAll);

            $porPagina = 10;
            $inicio = ($pagina - 1) * $porPagina;
            $totalPaginas = ceil($cantResultados / $porPagina);
            $cantItemsPaginacion = 7;

            if($totalPaginas > $cantItemsPaginacion){
                if($pagina < ceil($cantItemsPaginacion / 2)){
                    $inicioPaginacion = 1;
                    $finalPaginacion = $cantItemsPaginacion;
                }
                else if($pagina > ($totalPaginas - $cantItemsPaginacion)){
                    $inicioPaginacion = $totalPaginas - ($cantItemsPaginacion-1);
                    $finalPaginacion = $totalPaginas;
                }
                else{
                    $inicioPaginacion = $pagina - floor($cantItemsPaginacion / 2);
                    $finalPaginacion = ($inicioPaginacion + ($cantItemsPaginacion - 1));
                }
            }
            else{
                $inicioPaginacion = 1;
                $finalPaginacion = $totalPaginas;
            }

            $usuarios = DB::table('usuario')
                        ->join("canton", "usuario.id_canton", "canton.id_canton")
                        ->join("provincia", "canton.id_provincia", "provincia.id_provincia")
                        ->join("rol", "usuario.id_rol", "rol.id_rol")
                        ->leftJoin("licencia", "usuario.id_licencia", "licencia.id_licencia")
                        ->where('status_usuario', 1)
                        ->where('status_canton', 1)
                        ->where('status_provincia', 1)
                        ->where('status_rol', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('nombres_usuario', 'asc')
                        ->get();

            return view('admin/usuario')
                        ->with('usuarios', $usuarios)
                        ->with('totalPaginas', $totalPaginas)
                        ->with('pagina', $pagina)
                        ->with('inicioPaginacion', $inicioPaginacion)
                        ->with('finalPaginacion', $finalPaginacion)
                        ->with('cantItemsPaginacion', $cantItemsPaginacion);
        }

    }

    function vistaAgregar(){
        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $provincias = DB::table('provincia')
                    ->where('status_provincia', 1)
                    ->get();
            $cantones = DB::table('canton')
                        ->join("provincia", "canton.id_provincia", "provincia.id_provincia")
                        ->where('status_canton', 1)
                        ->where('status_provincia', 1)
                        ->get();
            $roles = DB::table('rol')
                        ->where('status_rol', 1)
                        ->get();
            $licencias = DB::table('licencia')
                        ->where('status_licencia', 1)
                        ->get();
            return view('admin/usuarioAgregar')
                        ->with('provincias', $provincias)
                        ->with('cantones', $cantones)
                        ->with('roles', $roles)
                        ->with('licencias', $licencias);
        }
    }

    function vistaEditar($id){
        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $usuario = DB::table('usuario')
                        ->join("canton", "usuario.id_canton", "canton.id_canton")
                        ->join("provincia", "canton.id_provincia", "provincia.id_provincia")
                        ->join("rol", "usuario.id_rol", "rol.id_rol")
                        ->leftJoin("licencia", "usuario.id_licencia", "licencia.id_licencia")
                        ->where('id_usuario', $id)
                        ->where('status_usuario', 1)
                        ->where('status_canton', 1)
                        ->where('status_provincia', 1)
                        ->where('status_rol', 1)
                        ->where('status_licencia', 1)
                        ->get();
            $provincias = DB::table('provincia')
                        ->where('status_provincia', 1)
                        ->get();
            $cantones = DB::table('canton')
                        ->join("provincia", "canton.id_provincia", "provincia.id_provincia")
                        ->where('status_canton', 1)
                        ->where('status_provincia', 1)
                        ->get();
            $roles = DB::table('rol')
                        ->where('status_rol', 1)
                        ->get();
            $licencias = DB::table('licencia')
                        ->where('status_licencia', 1)
                        ->get();
            if(count($usuario) == 1){
                return view('admin/usuarioEditar')
                    ->with('usuario', $usuario)
                    ->with('provincias', $provincias)
                    ->with('cantones', $cantones)
                    ->with('roles', $roles)
                    ->with('licencias', $licencias);
            }
            else{
                header("Location: " . env("APP_URL") . "/socios");
                exit();
            }
        }
    }

    function vistaVehiculo($socio, $pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $vehiculosAll = DB::table('usuario_vehiculo')
                    ->join("vehiculo", "usuario_vehiculo.id_vehiculo", "vehiculo.id_vehiculo")
                    ->join("modelo", "vehiculo.id_modelo", "modelo.id_modelo")
                    ->join("marca", "modelo.id_marca", "marca.id_marca")
                    ->where('status_vehiculo', 1)
                    ->where('status_modelo', 1)
                    ->where('status_marca', 1)
                    ->where('id_usuario', $socio)
                    ->get();
            $cantResultados = count($vehiculosAll);

            $porPagina = 10;
            $inicio = ($pagina - 1) * $porPagina;
            $totalPaginas = ceil($cantResultados / $porPagina);
            $cantItemsPaginacion = 7;

            if($totalPaginas > $cantItemsPaginacion){
                if($pagina < ceil($cantItemsPaginacion / 2)){
                    $inicioPaginacion = 1;
                    $finalPaginacion = $cantItemsPaginacion;
                }
                else if($pagina > ($totalPaginas - $cantItemsPaginacion)){
                    $inicioPaginacion = $totalPaginas - ($cantItemsPaginacion-1);
                    $finalPaginacion = $totalPaginas;
                }
                else{
                    $inicioPaginacion = $pagina - floor($cantItemsPaginacion / 2);
                    $finalPaginacion = ($inicioPaginacion + ($cantItemsPaginacion - 1));
                }
            }
            else{
                $inicioPaginacion = 1;
                $finalPaginacion = $totalPaginas;
            }

            $vehiculos = DB::table('usuario_vehiculo')
                        ->join("vehiculo", "usuario_vehiculo.id_vehiculo", "vehiculo.id_vehiculo")
                        ->join("modelo", "vehiculo.id_modelo", "modelo.id_modelo")
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('status_vehiculo', 1)
                        ->where('status_modelo', 1)
                        ->where('status_marca', 1)
                        ->where('id_usuario', $socio)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('placa_vehiculo', 'asc')
                        ->get();

            $usuario = DB::table('usuario')
                        ->where('status_usuario', 1)
                        ->where('id_usuario', $socio)
                        ->get();

            if(count($usuario) == 1){
                return view('admin/usuarioVehiculo')
                        ->with('vehiculos', $vehiculos)
                        ->with('usuario', $usuario)
                        ->with('totalPaginas', $totalPaginas)
                        ->with('pagina', $pagina)
                        ->with('inicioPaginacion', $inicioPaginacion)
                        ->with('finalPaginacion', $finalPaginacion)
                        ->with('cantItemsPaginacion', $cantItemsPaginacion);
            }
            else{
                header("Location: " . env("APP_URL") . "/socios");
                exit();
            }
        }

    }

    function vistaVehiculoAgregar($socio){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $marcas = DB::table('marca')
                    ->where('status_marca', 1)
                    ->get();
            $modelos = DB::table('modelo')
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('status_modelo', 1)
                        ->get();
            $usuario = DB::table('usuario')
                        ->where('status_usuario', 1)
                        ->where('id_usuario', $socio)
                        ->get();
            
            if(count($usuario) == 1){
                return view('admin/usuarioVehiculoAgregar')
                        ->with('marcas', $marcas)
                        ->with('modelos', $modelos)
                        ->with('usuario', $usuario);
            }
            else{
                header("Location: " . env("APP_URL") . "/vehiculosocio/" . $socio);
                exit();
            }
        }
        
    }

    function guardar(Request $request){

        $cedula=$request->cedula;
        $nombre=$request->nombre;
        $apellido=$request->apellido;
        $fnacimiento=$request->fnacimiento;
        $correo=$request->correo;
        $genero=$request->genero;
        $estcivil=$request->estcivil;
        $telefono=$request->telefono;
        $alias=$request->alias;
        $clave=$request->clave;
        $licencia=$request->licencia;
        $rol=$request->rol;
        $canton=$request->canton;
        $encriptado = Hash::make($clave);
        $respuesta = new \stdClass();

        $validacion = DB::table('usuario')
                    ->where('status_usuario', 1)
                    ->where('cedula_usuario', 1)
                    ->get();

        if(count($validacion) == 0){

            $respuesta->respuesta = DB::table('usuario')->insert([
                [
                    'cedula_usuario' => $cedula,
                    'nombres_usuario' => $nombre,
                    'apellidos_usuario' => $apellido,
                    'fecha_nacimiento_usuario' => $fnacimiento,
                    'correo_usuario' => $correo,
                    'genero_usuario' => $genero,
                    'estado_civil_usuario' => $estcivil,
                    'telefono_usuario' => $telefono,
                    'alias_usuario' => $alias,
                    'clave_usuario' => $encriptado,
                    'id_licencia' => $licencia,
                    'id_rol' => $rol,
                    'id_canton' => $canton
                ]
            ]);

        }

        else{
            $respuesta = false;
        }

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $nombre=$request->nombre;
        $apellido=$request->apellido;
        $fnacimiento=$request->fnacimiento;
        $correo=$request->correo;
        $genero=$request->genero;
        $estcivil=$request->estcivil;
        $telefono=$request->telefono;
        $alias=$request->alias;
        $licencia=$request->licencia;
        $rol=$request->rol;
        $canton=$request->canton;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('usuario')
                                    ->where('id_usuario', $id)
                                    ->update(
                                        [
                                            'nombres_usuario' => $nombre,
                                            'apellidos_usuario' => $apellido,
                                            'fecha_nacimiento_usuario' => $fnacimiento,
                                            'correo_usuario' => $correo,
                                            'genero_usuario' => $genero,
                                            'estado_civil_usuario' => $estcivil,
                                            'telefono_usuario' => $telefono,
                                            'alias_usuario' => $alias,
                                            'id_licencia' => $licencia,
                                            'id_rol' => $rol,
                                            'id_canton' => $canton
                                        ]
                                    );

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('usuario')
                                    ->where('id_usuario', $id)
                                    ->update(['status_usuario' => 0]);

        return $respuesta;

    }

    function guardarvehiculo(Request $request){
        $modo=$request->modo;
        $usuario=$request->usuario;
        $placa=$request->placa;

        $respuesta = new \stdClass();

        if($modo=="agregar"){
            $vehiculo = DB::table('vehiculo')
                        ->where('status_vehiculo', 1)
                        ->where('placa_vehiculo', $placa)
                        ->get();

            $respuesta->respuesta = DB::table('usuario_vehiculo')->insert([
                [
                    'id_usuario' => $usuario,
                    'id_vehiculo' => $vehiculo[0]->id_vehiculo
                ]
            ]);
        }

        else{
            $ano=$request->ano;
            $color=$request->color;
            $modelo=$request->modelo;

            $vehiculo = DB::table('vehiculo')->insertGetId(
                [
                    'placa_vehiculo' => $placa,
                    'ano_vehiculo' => $ano,
                    'color_vehiculo' => $color,
                    'id_modelo' => $modelo
                ]
            );

            $respuesta->respuesta = DB::table('usuario_vehiculo')->insert([
                [
                    'id_usuario' => $usuario,
                    'id_vehiculo' => $vehiculo
                ]
            ]);
        }

        return $respuesta;
    }

    function eliminarvehiculo(Request $request){

        $id=$request->id;
        $usuario=$request->usuario;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('usuario_vehiculo')
                                    ->where('id_vehiculo', $id)
                                    ->where('id_usuario', $usuario)
                                    ->delete();

        return $respuesta;

    }

}

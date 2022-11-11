<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RolController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $rolesAll = DB::table('rol')
                        ->where('status_rol', 1)
                        ->get();
            $cantResultados = count($rolesAll);
            
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

            $roles = DB::table('rol')
                        ->where('status_rol', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('nombre_rol', 'asc')
                        ->get();

            return view('admin/rol')
                        ->with('roles', $roles)
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
            return view('admin/rolAgregar');
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
            $rol = DB::table('rol')
                        ->where('id_rol', $id)
                        ->where('status_rol', 1)
                        ->get();
            if(count($rol) == 1){
                return view('admin/rolEditar')->with('rol', $rol);
            }
            else{
                header("Location: " . env("APP_URL") . "/roles");
                exit();
            }
        }
    }

    function vistaPermiso($id){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $modulos = DB::table('modulo')
                    ->select(
                        DB::raw('*, 0 as consultar_permiso, 0 as crear_permiso, 0 as editar_permiso, 0 as eliminar_permiso, "'.$id.'" as id_rol, null as id_permiso')
                    )
                    ->where('status_modulo', 1)
                    ->orderBy('nombre_modulo', 'asc')
                    ->get();

            $permisos = DB::table("permiso")
                        ->where('status_permiso', 1)
                        ->where('id_rol', $id)
                        ->get();

            for($i=0; $i<count($permisos); $i++){
                for($j=0; $j<count($modulos); $j++){
                    if($modulos[$j]->id_modulo==$permisos[$i]->id_modulo){
                        $modulos[$j]->id_permiso=$permisos[$i]->id_permiso;
                        $modulos[$j]->consultar_permiso=$permisos[$i]->consultar_permiso;
                        $modulos[$j]->crear_permiso=$permisos[$i]->crear_permiso;
                        $modulos[$j]->editar_permiso=$permisos[$i]->editar_permiso;
                        $modulos[$j]->eliminar_permiso=$permisos[$i]->eliminar_permiso;
                    }
                }
            }

            $rol = DB::table('rol')
                            ->where('id_rol', $id)
                            ->where('status_rol', 1)
                            ->get();
            if(count($rol) == 1){
                return view('admin/permiso')->with('rol', $rol)->with('modulos', $modulos);
            }
            else{
                header("Location: " . env("APP_URL") . "/roles");
                exit();
            }
        }

    }

    function guardar(Request $request){

        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('rol')->insert([
            ['nombre_rol' => $nombre]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('rol')
                                    ->where('id_rol', $id)
                                    ->update(['nombre_rol' => $nombre]);

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('rol')
                                    ->where('id_rol', $id)
                                    ->update(['status_rol' => 0]);

        return $respuesta;

    }

    function guardarpermisos(Request $request){

        $permisos=json_decode($request->permisos);
        $respuesta = new \stdClass();

        for($i=0; $i<count($permisos); $i++){
            if($permisos[$i]->id_permiso==null){
                DB::table('permiso')->insert([
                    [
                        'consultar_permiso' => $permisos[$i]->consultar_permiso,
                        'crear_permiso' => $permisos[$i]->crear_permiso,
                        'editar_permiso' => $permisos[$i]->editar_permiso,
                        'eliminar_permiso' => $permisos[$i]->eliminar_permiso,
                        'id_modulo' => $permisos[$i]->id_modulo,
                        'id_rol' => $permisos[$i]->id_rol
                    ]
                ]);
            }
            else{
                DB::table('permiso')
                    ->where('id_permiso', $permisos[$i]->id_permiso)
                    ->update([
                        'consultar_permiso' => $permisos[$i]->consultar_permiso,
                        'crear_permiso' => $permisos[$i]->crear_permiso,
                        'editar_permiso' => $permisos[$i]->editar_permiso,
                        'eliminar_permiso' => $permisos[$i]->eliminar_permiso
                    ]);
            }
        }

        $respuesta->respuesta = true;

        return $respuesta;

    }

}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $modulosAll = DB::table('modulo')
                        ->where('status_modulo', 1)
                        ->get();
            $cantResultados = count($modulosAll);
            
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

            $modulos = DB::table('modulo')
                        ->where('status_modulo', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('nombre_modulo', 'asc')
                        ->get();

            return view('admin/modulo')
                        ->with('modulos', $modulos)
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
            return view('admin/moduloAgregar');
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
            $modulo = DB::table('modulo')
                        ->where('id_modulo', $id)
                        ->where('status_modulo', 1)
                        ->get();
            if(count($modulo) == 1){
                return view('admin/moduloEditar')->with('modulo', $modulo);
            }
            else{
                header("Location: " . env("APP_URL") . "/modulos");
                exit();
            }
        }
    }

    function guardar(Request $request){

        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('modulo')->insert([
            ['nombre_modulo' => $nombre]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('modulo')
                                    ->where('id_modulo', $id)
                                    ->update(['nombre_modulo' => $nombre]);

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('modulo')
                                    ->where('id_modulo', $id)
                                    ->update(['status_modulo' => 0]);

        return $respuesta;

    }

}

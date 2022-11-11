<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LicenciaController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $licenciasAll = DB::table('licencia')
                        ->where('status_licencia', 1)
                        ->get();
            $cantResultados = count($licenciasAll);
            
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

            $licencias = DB::table('licencia')
                        ->where('status_licencia', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('nombre_licencia', 'asc')
                        ->get();

            return view('admin/licencia')
                        ->with('licencias', $licencias)
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
            return view('admin/licenciaAgregar');
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
            $licencia = DB::table('licencia')
                        ->where('id_licencia', $id)
                        ->where('status_licencia', 1)
                        ->get();
            if(count($licencia) == 1){
                return view('admin/licenciaEditar')->with('licencia', $licencia);
            }
            else{
                header("Location: " . env("APP_URL") . "/licencias");
                exit();
            }
        }
    }

    function guardar(Request $request){

        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('licencia')->insert([
            ['nombre_licencia' => $nombre]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('licencia')
                                    ->where('id_licencia', $id)
                                    ->update(['nombre_licencia' => $nombre]);

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('licencia')
                                    ->where('id_licencia', $id)
                                    ->update(['status_licencia' => 0]);

        return $respuesta;

    }

}

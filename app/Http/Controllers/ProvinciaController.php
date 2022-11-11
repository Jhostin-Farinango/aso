<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProvinciaController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $provinciasAll = DB::table('provincia')
                        ->where('status_provincia', 1)
                        ->get();
            $cantResultados = count($provinciasAll);
            
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

            $provincias = DB::table('provincia')
                        ->where('status_provincia', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('nombre_provincia', 'asc')
                        ->get();

            return view('admin/provincia')
                        ->with('provincias', $provincias)
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
            return view('admin/provinciaAgregar');
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
            $provincia = DB::table('provincia')
                        ->where('id_provincia', $id)
                        ->where('status_provincia', 1)
                        ->get();
            if(count($provincia) == 1){
                return view('admin/provinciaEditar')->with('provincia', $provincia);
            }
            else{
                header("Location: " . env("APP_URL") . "/provincias");
                exit();
            }
        }
    }

    function guardar(Request $request){

        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('provincia')->insert([
            ['nombre_provincia' => $nombre]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('provincia')
                                    ->where('id_provincia', $id)
                                    ->update(['nombre_provincia' => $nombre]);

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('provincia')
                                    ->where('id_provincia', $id)
                                    ->update(['status_provincia' => 0]);

        return $respuesta;

    }

}

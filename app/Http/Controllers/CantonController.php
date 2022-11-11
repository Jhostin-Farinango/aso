<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CantonController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $cantonesAll = DB::table('canton')
                    ->join("provincia", "canton.id_provincia", "provincia.id_provincia")
                    ->where('status_canton', 1)
                    ->get();
            $cantResultados = count($cantonesAll);

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

            $cantones = DB::table('canton')
                        ->join("provincia", "canton.id_provincia", "provincia.id_provincia")
                        ->where('status_canton', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('nombre_canton', 'asc')
                        ->get();

            return view('admin/canton')
                        ->with('cantones', $cantones)
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
            return view('admin/cantonAgregar')->with('provincias', $provincias);
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
            $canton = DB::table('canton')
                        ->join("provincia", "canton.id_provincia", "provincia.id_provincia")
                        ->where('id_canton', $id)
                        ->where('status_canton', 1)
                        ->where('status_provincia', 1)
                        ->get();
            $provincias = DB::table('provincia')
                        ->where('status_provincia', 1)
                        ->get();
            if(count($canton) == 1){
                return view('admin/cantonEditar')->with('canton', $canton)->with('provincias', $provincias);
            }
            else{
                header("Location: " . env("APP_URL") . "/cantones");
                exit();
            }
        }
    }

    function guardar(Request $request){

        $nombre=$request->nombre;
        $provincia=$request->provincia;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('canton')->insert([
            [
                'nombre_canton' => $nombre,
                'id_provincia' => $provincia
            ]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $nombre=$request->nombre;
        $provincia=$request->provincia;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('canton')
                                    ->where('id_canton', $id)
                                    ->update(['nombre_canton' => $nombre, 'id_provincia' => $provincia]);

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('canton')
                                    ->where('id_canton', $id)
                                    ->update(['status_canton' => 0]);

        return $respuesta;

    }

}

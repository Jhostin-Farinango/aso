<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $marcasAll = DB::table('marca')
                        ->where('status_marca', 1)
                        ->get();
            $cantResultados = count($marcasAll);
            
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

            $marcas = DB::table('marca')
                        ->where('status_marca', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('nombre_marca', 'asc')
                        ->get();

            return view('admin/marca')
                        ->with('marcas', $marcas)
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
            return view('admin/marcaAgregar');
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
            $marca = DB::table('marca')
                        ->where('id_marca', $id)
                        ->where('status_marca', 1)
                        ->get();
            if(count($marca) == 1){
                return view('admin/marcaEditar')->with('marca', $marca);
            }
            else{
                header("Location: " . env("APP_URL") . "/marcas");
                exit();
            }
        }
    }

    function guardar(Request $request){

        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('marca')->insert([
            ['nombre_marca' => $nombre]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $nombre=$request->nombre;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('marca')
                                    ->where('id_marca', $id)
                                    ->update(['nombre_marca' => $nombre]);

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('marca')
                                    ->where('id_marca', $id)
                                    ->update(['status_marca' => 0]);

        return $respuesta;

    }

}

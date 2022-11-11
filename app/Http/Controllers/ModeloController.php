<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $modelosAll = DB::table('modelo')
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('status_modelo', 1)
                        ->where('status_marca', 1)
                        ->get();
            $cantResultados = count($modelosAll);

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

            $modelos = DB::table('modelo')
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('status_modelo', 1)
                        ->where('status_marca', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('nombre_modelo', 'asc')
                        ->get();

            return view('admin/modelo')
                        ->with('modelos', $modelos)
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
            $marcas = DB::table('marca')
                    ->where('status_marca', 1)
                    ->get();
            return view('admin/modeloAgregar')->with('marcas', $marcas);
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
            $modelo = DB::table('modelo')
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('id_modelo', $id)
                        ->where('status_modelo', 1)
                        ->where('status_marca', 1)
                        ->get();
            $marcas = DB::table('marca')
                        ->where('status_marca', 1)
                        ->get();
            if(count($modelo) == 1){
                return view('admin/modeloEditar')->with('modelo', $modelo)->with('marcas', $marcas);
            }
            else{
                header("Location: " . env("APP_URL") . "/modelos");
                exit();
            }
        }
    }

    function guardar(Request $request){

        $nombre=$request->nombre;
        $marca=$request->marca;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('modelo')->insert([
            [
                'nombre_modelo' => $nombre,
                'id_marca' => $marca
            ]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $nombre=$request->nombre;
        $marca=$request->marca;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('modelo')
                                    ->where('id_modelo', $id)
                                    ->update(['nombre_modelo' => $nombre, 'id_marca' => $marca]);

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('modelo')
                                    ->where('id_modelo', $id)
                                    ->update(['status_modelo' => 0]);

        return $respuesta;

    }

}

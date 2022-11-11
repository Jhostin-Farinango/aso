<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $vehiculosAll = DB::table('vehiculo')
                    ->join("modelo", "vehiculo.id_modelo", "modelo.id_modelo")
                    ->join("marca", "modelo.id_marca", "marca.id_marca")
                    ->where('status_vehiculo', 1)
                    ->where('status_modelo', 1)
                    ->where('status_marca', 1)
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

            $vehiculos = DB::table('vehiculo')
                        ->join("modelo", "vehiculo.id_modelo", "modelo.id_modelo")
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('status_vehiculo', 1)
                        ->where('status_modelo', 1)
                        ->where('status_marca', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('placa_vehiculo', 'asc')
                        ->get();

            return view('admin/vehiculo')
                        ->with('vehiculos', $vehiculos)
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
            $modelos = DB::table('modelo')
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('status_modelo', 1)
                        ->get();
            return view('admin/vehiculoAgregar')->with('marcas', $marcas)->with('modelos', $modelos);
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
            $vehiculo = DB::table('vehiculo')
                        ->join("modelo", "vehiculo.id_modelo", "modelo.id_modelo")
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('id_vehiculo', $id)
                        ->where('status_vehiculo', 1)
                        ->where('status_modelo', 1)
                        ->where('status_marca', 1)
                        ->get();
            $marcas = DB::table('marca')
                        ->where('status_marca', 1)
                        ->get();
            $modelos = DB::table('modelo')
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('status_modelo', 1)
                        ->get();
            if(count($vehiculo) == 1){
                return view('admin/vehiculoEditar')
                    ->with('vehiculo', $vehiculo)
                    ->with('marcas', $marcas)
                    ->with('modelos', $modelos);
            }
            else{
                header("Location: " . env("APP_URL") . "/vehiculos");
                exit();
            }
        }
    }

    function buscar(Request $request){

        $placa=$request->placa;
        
        $vehiculo = DB::table('vehiculo')
                        ->join("modelo", "vehiculo.id_modelo", "modelo.id_modelo")
                        ->join("marca", "modelo.id_marca", "marca.id_marca")
                        ->where('placa_vehiculo', $placa)
                        ->where('status_vehiculo', 1)
                        ->where('status_modelo', 1)
                        ->where('status_marca', 1)
                        ->get();

        return $vehiculo;

    }

    function guardar(Request $request){

        $placa=$request->placa;
        $ano=$request->ano;
        $color=$request->color;
        $modelo=$request->modelo;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('vehiculo')->insert([
            [
                'placa_vehiculo' => $placa,
                'ano_vehiculo' => $ano,
                'color_vehiculo' => $color,
                'id_modelo' => $modelo
            ]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $placa=$request->placa;
        $ano=$request->ano;
        $color=$request->color;
        $modelo=$request->modelo;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('vehiculo')
                                    ->where('id_vehiculo', $id)
                                    ->update(
                                        [
                                            'placa_vehiculo' => $placa,
                                            'ano_vehiculo' => $ano,
                                            'color_vehiculo' => $color,
                                            'id_modelo' => $modelo
                                        ]
                                    );

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('vehiculo')
                                    ->where('id_vehiculo', $id)
                                    ->update(['status_vehiculo' => 0]);

        return $respuesta;

    }

}

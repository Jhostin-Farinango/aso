<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AporteController extends Controller
{
    
    function consultar($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboardadmin'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            $aportesAll = DB::table('aporte')
                    ->join("forma_pago", "aporte.id_forma_pago", "forma_pago.id_forma_pago")
                    ->where('status_aporte', 1)
                    ->get();
            $cantResultados = count($aportesAll);

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

            $aportes = DB::table('aporte')
                        ->join("forma_pago", "aporte.id_forma_pago", "forma_pago.id_forma_pago")
                        ->where('status_aporte', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('fecha_aporte', 'desc')
                        ->orderBy('id_aporte', 'desc')
                        ->get();

            return view('socio/aporte')
                        ->with('aportes', $aportes)
                        ->with('totalPaginas', $totalPaginas)
                        ->with('pagina', $pagina)
                        ->with('inicioPaginacion', $inicioPaginacion)
                        ->with('finalPaginacion', $finalPaginacion)
                        ->with('cantItemsPaginacion', $cantItemsPaginacion);
        }

        

    }

    function consultaradmin($pagina=1){

        if(session('rol')==""){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
        else if(session('rol')=="Admin"){
            $aportesAll = DB::table('aporte')
                    ->join("forma_pago", "aporte.id_forma_pago", "forma_pago.id_forma_pago")
                    ->join("usuario", "aporte.id_usuario", "usuario.id_usuario")
                    ->where('status_aporte', 1)
                    ->where('status_usuario', 1)
                    ->get();
            $cantResultados = count($aportesAll);

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

            $aportes = DB::table('aporte')
                        ->join("forma_pago", "aporte.id_forma_pago", "forma_pago.id_forma_pago")
                        ->join("usuario", "aporte.id_usuario", "usuario.id_usuario")
                        ->where('status_aporte', 1)
                        ->where('status_usuario', 1)
                        ->skip($inicio)
                        ->take($porPagina)
                        ->orderBy('fecha_aporte', 'desc')
                        ->orderBy('id_aporte', 'desc')
                        ->get();

            return view('admin/aporte')
                        ->with('aportes', $aportes)
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
        else if(session('rol')=="Admin"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboardadmin'; }, 1000 );</script>";
        }
        else if(session('rol')=="Socio"){
            $formaspago = DB::table('forma_pago')
                    ->where('status_forma_pago', 1)
                    ->get();
            return view('socio/aporteAgregar')->with('formaspago', $formaspago);
        }
    }

    function vistaEditar($id){
        $aporte = DB::table('aporte')
                        ->join("forma_pago", "aporte.id_forma_pago", "forma_pago.id_forma_pago")
                        ->where('id_aporte', $id)
                        ->where('status_aporte', 1)
                        ->where('status_forma_pago', 1)
                        ->get();
        $formaspago = DB::table('forma_pago')
                    ->where('status_forma_pago', 1)
                    ->get();
        if(count($aporte) == 1){
            return view('socio/aporteEditar')->with('aporte', $aporte)->with('formaspago', $formaspago);
        }
        else{
            header("Location: " . env("APP_URL") . "/aportes");
            exit();
        }
    }

    function guardar(Request $request){

        $cantidad=$request->cantidad;
        $formapago=$request->formapago;
        $usuario=$request->usuario;
        $comprobante=null;
        $respuesta = new \stdClass();

        if ($request->hasFile('comprobante')){
            $comprobante=$request->comprobante->store('comprobantes');
        }

        $respuesta->respuesta = DB::table('aporte')->insert([
            [
                'cantidad_aporte' => $cantidad,
                'fecha_aporte' => date('Y-m-d', time()),
                'comprobante_aporte' => $comprobante,
                'id_forma_pago' => $formapago,
                'id_usuario' => $usuario
            ]
        ]);

        return $respuesta;

    }

    function editar(Request $request){

        $id=$request->id;
        $cantidad=$request->cantidad;
        $formapago=$request->formapago;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('aporte')
                                    ->where('id_aporte', $id)
                                    ->update(
                                        [
                                            'cantidad_aporte' => $cantidad,
                                            'id_forma_pago' => $formapago
                                        ]
                                    );

        return $respuesta;

    }

    function eliminar(Request $request){

        $id=$request->id;
        $respuesta = new \stdClass();

        $respuesta->respuesta = DB::table('aporte')
                                    ->where('id_aporte', $id)
                                    ->update(['status_aporte' => 0]);

        return $respuesta;

    }

}

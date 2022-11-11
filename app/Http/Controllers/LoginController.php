<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Session;

class LoginController extends Controller
{
    
    public function index(){
        return view('login');
    }

    public function verificarAcceso(Request $request){

        $respuesta = new \stdClass();
        $respuesta->respuesta = false;
        $usuario = DB::table('usuario')
                        ->join('rol', 'usuario.id_rol', 'rol.id_rol')
                        ->where("usuario.alias_usuario", $request->alias)
                        ->get();
                        
        if(count($usuario)>0){
            $esCorrecto = Hash::check($request->clave, $usuario[0]->clave_usuario);
            $respuesta->respuesta = $esCorrecto;
            $respuesta->rol = $usuario[0]->nombre_rol;
            $respuesta->id_usuario = $usuario[0]->id_usuario;
            return $respuesta;
        }

        else{
            return $respuesta;
        }
        
    }

    function sesion($usuario, $rol){
        session(['usuario' => $usuario]);
        session(['rol' => $rol]);
        echo "Entrando...";
        if($rol=="Admin"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboardadmin'; }, 1000 );</script>";
        }
        else if($rol=="Socio"){
            echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."/dashboard'; }, 1000 );</script>";
        }
    }

    function cerrar(){
        session()->forget('usuario');
        session()->forget('rol');
        echo "Saliendo...";
        echo "<script type='text/javascript'>setTimeout( function() { window.location.href = '".env("APP_URL")."'; }, 1000 );</script>";
    }

}

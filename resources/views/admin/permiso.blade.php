@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex align-items-center">
            <div class="col-md-11">
                <h1>Permisos de {{ $rol[0]->nombre_rol }}</h1>
            </div>
        </div>
        <div class="col-md-8 mt-3 d-flex flex-column align-items-center">
            <table class="table table-secondary">
                <thead>
                    <tr>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Módulo</th>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Consultar</th>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Crear</th>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Editar</th>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modulos as $modulo)
                        <tr>
                            <th style="background: #0d6efd;" class="text-white">
                                {{ $modulo->nombre_modulo }}
                            </th>
                            <td class="text-center">
                                @if($modulo->consultar_permiso)
                                    <input class="form-check-input" type="checkbox" checked onclick="marcar(this.checked, {{$modulo->id_modulo}}, 'consultar')">
                                @else
                                    <input class="form-check-input" type="checkbox" onclick="marcar(this.checked, {{$modulo->id_modulo}}, 'consultar')">
                                @endif
                            </td>
                            <td class="text-center">
                                @if($modulo->crear_permiso)
                                    <input class="form-check-input" type="checkbox" checked onclick="marcar(this.checked, {{$modulo->id_modulo}}, 'crear')">
                                @else
                                    <input class="form-check-input" type="checkbox" onclick="marcar(this.checked, {{$modulo->id_modulo}}, 'crear')">
                                @endif
                            </td>
                            <td class="text-center">
                                @if($modulo->editar_permiso)
                                    <input class="form-check-input" type="checkbox" checked onclick="marcar(this.checked, {{$modulo->id_modulo}}, 'editar')">
                                @else
                                    <input class="form-check-input" type="checkbox" onclick="marcar(this.checked, {{$modulo->id_modulo}}, 'editar')">
                                @endif
                            </td>
                            <td class="text-center">
                                @if($modulo->eliminar_permiso)
                                    <input class="form-check-input" type="checkbox" checked onclick="marcar(this.checked, {{$modulo->id_modulo}}, 'eliminar')">
                                @else
                                    <input class="form-check-input" type="checkbox" onclick="marcar(this.checked, {{$modulo->id_modulo}}, 'eliminar')">
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-primary me-2" id="btnguardar" onclick="guardar()">Guardar</button>
                <a class="btn btn-md btn-danger ms-2" href="{{ env('APP_URL') }}/roles" role="button">Cancelar</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let server='<?= env("APP_URL") ?>';
    let permisos = JSON.parse('<?= $modulos; ?>');
    function alerta(tipo){
        if(tipo=="success"){
            document.body.insertAdjacentHTML('beforeend', '<div class="alert alert-success" id="alerta" style="width: 400px; position: fixed; right: 20px; top: 400px; transition: all 2s ease; z-index: 99999;" role="alert">¡Operaci&oacute;n Exitosa!</div>');
            setTimeout(function(){
                document.getElementById("alerta").style.top="70px";
                setTimeout(function(){
                    limpiar_alerta();
                }, 2500);
            }, 100);
        }
        else if(tipo=="danger"){
            document.body.insertAdjacentHTML('beforeend', '<div class="alert alert-danger" id="alerta" style="width: 400px; position: fixed; right: 20px; top: 400px; transition: all 2s ease; z-index: 99999;" role="alert">¡Operaci&oacute;n Fallida, Error Desconocido!</div>');
            setTimeout(function(){
                document.getElementById("alerta").style.top="70px";
                setTimeout(function(){
                    limpiar_alerta();
                }, 2500);
            }, 100);
        }
        else if(tipo=="warning"){
            document.body.insertAdjacentHTML('beforeend', '<div class="alert alert-warning" id="alerta" style="width: 400px; position: fixed; right: 20px; top: 400px; transition: all 2s ease; z-index: 99999;" role="alert">¡Formulario incompleto, llene todos los campos!</div>');
            setTimeout(function(){
                document.getElementById("alerta").style.top="70px";
                setTimeout(function(){
                    limpiar_alerta();
                }, 2500);
            }, 100);
        }
    }
    function limpiar_alerta(){
        let alerta=document.getElementById("alerta");
        alerta.parentNode.removeChild(alerta);
    }
    function marcar(valor, modulo, permiso) {
        let booleano = valor?1:0;
        for(let i=0; i<permisos.length; i++){
            if(permisos[i].id_modulo==modulo){
                if(permiso=="consultar"){
                    permisos[i].consultar_permiso=booleano;
                }
                if(permiso=="crear"){
                    permisos[i].crear_permiso=booleano;
                }
                if(permiso=="editar"){
                    permisos[i].editar_permiso=booleano;
                }
                if(permiso=="eliminar"){
                    permisos[i].eliminar_permiso=booleano;
                }
            }
        }
    }
    function guardar() {
        document.getElementById("btnguardar").disabled="disabled";        
        axios.post(server+'/api/guardarpermisos', {
            permisos: JSON.stringify(permisos)
        })
        .then(function (response) {
            let respuesta=response.data;
            if(respuesta.respuesta){
                alerta("success");
                setTimeout(function(){
                    window.location.href=server+"/roles";
                }, 3000);
            }
            else{
                alerta("danger");
                document.getElementById("btnguardar").disabled="";
            }
        })
        .catch(function (error) {
            console.log(error);
            alerta("danger");
            document.getElementById("btnguardar").disabled="";
        });
    }
</script>
@endsection
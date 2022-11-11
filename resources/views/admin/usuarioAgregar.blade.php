@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex align-items-center">
            <div class="col-md-12">
                <h1>Agregar Socio</h1>
            </div>
        </div>
        <div class="col-md-8 mt-3">
            <form>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="cedula" class="form-label">Cédula</label>
                        <input type="text" class="form-control" minlength="10" id="cedula" maxlength="13">
                    </div>
                    <div class="col-4">
                        <label for="nombre" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombre">
                    </div>
                    <div class="col-4">
                        <label for="apellido" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellido">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="fnacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fnacimiento">
                    </div>
                    <div class="col-4">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo">
                    </div>
                    <div class="col-4">
                        <label for="genero" class="form-label">Genero</label>
                        <select class="form-select" id="genero">
                            <option value="" selected>Seleccione</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="estcivil" class="form-label">Estado Civil</label>
                        <select class="form-select" id="estcivil">
                            <option value="" selected>Seleccione</option>
                            <option value="Soltero/a">Soltero/a</option>
                            <option value="Casado/a">Casado/a</option>
                            <option value="Unido/a">Unido/a</option>
                            <option value="Divorciado/a">Divorciado/a</option>
                            <option value="Viudo/a">Viudo/a</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" minlength="10" maxlength="10">
                    </div>
                    <div class="col-4">
                        <label for="alias" class="form-label">Alias</label>
                        <input type="text" class="form-control" id="alias">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="clave" class="form-label">Clave</label>
                        <input type="password" class="form-control" id="clave">
                    </div>
                    <div class="col-4">
                        <label for="claverepetir" class="form-label">Repetir Clave</label>
                        <input type="password" class="form-control" id="claverepetir">
                    </div>
                    <div class="col-4">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" onchange="habilitar_licencia()">
                            <option value="" selected>Seleccione</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="licencia" class="form-label">Licencia</label>
                        <select class="form-select" id="licencia" disabled>
                            <option value="" selected>Seleccione</option>
                            @foreach ($licencias as $licencia)
                                <option value="{{ $licencia->id_licencia }}">{{ $licencia->nombre_licencia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="provincia" class="form-label">Provincia</label>
                        <select class="form-select" id="provincia" onchange="cambiar_cantones()">
                            <option value="" selected>Seleccione</option>
                            @foreach ($provincias as $provincia)
                                <option value="{{ $provincia->id_provincia }}">{{ $provincia->nombre_provincia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="canton" class="form-label">Cantón</label>
                        <select class="form-select" id="canton" disabled>
                            <option value="" selected>Seleccione</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary me-2" id="btnguardar" onclick="guardar()">Guardar</button>
                    <a class="btn btn-md btn-danger ms-2" href="{{ env('APP_URL') }}/socios" role="button">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    let server='<?= env("APP_URL") ?>';
    let roles=JSON.parse('<?= $roles; ?>');
    let cantonesDisponibles=JSON.parse('<?= $cantones; ?>');
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
    function habilitar_licencia(){
        let licencia=document.getElementById("licencia");
        let rol=document.getElementById("rol").value;

        if(rol==""){
            licencia.disabled="disabled";
            licencia.value="";
        }
        else{
            roles.forEach(function(element){
                if(element.id_rol==rol){
                    if(element.nombre_rol=="Socio"){
                        licencia.disabled="";
                    }
                    else{
                        licencia.disabled="disabled";
                        licencia.value="";
                    }
                }
            });
        }
    }
    function cambiar_cantones(){
        let canton=document.getElementById("canton");
        
        while (canton.childNodes.length > 0)
        {
            canton.removeChild(canton.firstChild);
        }

        let provincia=document.getElementById("provincia").value;
        
        if(provincia==""){
            canton.disabled="disabled";
            let opcion=document.createElement("option");
            opcion.value="";
            opcion.text="Seleccione";
            canton.appendChild(opcion);
        }
        else{
            canton.disabled="";
            let opcion=document.createElement("option");
            opcion.value="";
            opcion.text="Seleccione";
            canton.appendChild(opcion);

            let cantonesCorrespondientes=cantonesDisponibles.filter(element => element.id_provincia == provincia);

            cantonesCorrespondientes.forEach(function(element){
                let opcion=document.createElement("option");
                opcion.value=element.id_canton;
                opcion.text=element.nombre_canton;
                canton.appendChild(opcion);
            });
        }
    }
    function validar(){
        let cedula=document.getElementById("cedula").value;
        let nombre=document.getElementById("nombre").value;
        let apellido=document.getElementById("apellido").value;
        let fnacimiento=document.getElementById("fnacimiento").value;
        let correo=document.getElementById("correo").value;
        let genero=document.getElementById("genero").value;
        let estcivil=document.getElementById("estcivil").value;
        let telefono=document.getElementById("telefono").value;
        let alias=document.getElementById("alias").value;
        let clave=document.getElementById("clave").value;
        let claverepetir=document.getElementById("claverepetir").value;
        let rol=document.getElementById("rol").value;
        let licencia=document.getElementById("licencia").value;
        let provincia=document.getElementById("provincia").value;
        let canton=document.getElementById("canton").value;
        if(cedula!="" && nombre!="" && apellido!="" && fnacimiento!="" && correo!="" && genero!="" && estcivil!="" && telefono!="" && alias!="" && clave!="" && claverepetir!="" && rol!="" && provincia!="" && canton!=""){
            if(clave==claverepetir){
                roles.forEach(function(element){
                    if(element.id_rol==rol){
                        if(element.nombre_rol=="Socio"){
                            if(licencia!=""){
                                return true;
                            }
                            else{
                                return false;
                            }
                        }
                        else{
                            return true;
                        }
                    }
                });
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    function guardar(){
        document.getElementById("btnguardar").disabled="disabled";
        if(validar()==false){
            alerta("warning");
            document.getElementById("btnguardar").disabled="";
        }
        else {
            let cedula=document.getElementById("cedula").value;
            let nombre=document.getElementById("nombre").value;
            let apellido=document.getElementById("apellido").value;
            let fnacimiento=document.getElementById("fnacimiento").value;
            let correo=document.getElementById("correo").value;
            let genero=document.getElementById("genero").value;
            let estcivil=document.getElementById("estcivil").value;
            let telefono=document.getElementById("telefono").value;
            let alias=document.getElementById("alias").value;
            let clave=document.getElementById("clave").value;
            let claverepetir=document.getElementById("claverepetir").value;
            let rol=document.getElementById("rol").value;
            let licencia=document.getElementById("licencia").value;
            let provincia=document.getElementById("provincia").value;
            let canton=document.getElementById("canton").value;
            axios.post(server+'/api/guardarusuario', {
                cedula: cedula,
                nombre: nombre,
                apellido: apellido,
                fnacimiento: fnacimiento,
                correo: correo,
                genero: genero,
                estcivil: estcivil,
                telefono: telefono,
                alias: alias,
                clave: clave,
                licencia: licencia,
                rol: rol,
                provincia: provincia,
                canton: canton
            })
            .then(function (response) {
                let respuesta=response.data;
                if(respuesta.respuesta){
                    alerta("success");
                    setTimeout(function(){
                        window.location.href=server+"/socios";
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
    }
</script>
@endsection
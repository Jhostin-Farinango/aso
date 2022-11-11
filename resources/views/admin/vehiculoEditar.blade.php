@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex align-items-center">
            <div class="col-md-12">
                <h1>Editar Vehículo: {{ $vehiculo[0]->placa_vehiculo }}</h1>
            </div>
        </div>
        <div class="col-md-8 mt-3">
            <form>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="placa" class="form-label">Placa</label>
                        <input type="text" class="form-control" id="placa" value="{{ $vehiculo[0]->placa_vehiculo }}">
                    </div>
                    <div class="col-4">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" class="form-control" id="color" value="{{ $vehiculo[0]->color_vehiculo }}">
                    </div>
                    <div class="col-4">
                        <label for="ano" class="form-label">Año</label>
                        <input type="text" class="form-control" id="ano" value="{{ $vehiculo[0]->ano_vehiculo }}">
                    </div>
                    <div class="col-6">
                        <label for="marca" class="form-label">Marca</label>
                        <select class="form-select" id="marca" onchange="cambiar_modelos()">
                            <option value="" selected>Seleccione</option>
                            @foreach ($marcas as $marca)
                                @if($vehiculo[0]->id_marca==$marca->id_marca)
                                    <option selected value="{{ $marca->id_marca }}">{{ $marca->nombre_marca }}</option>
                                @else
                                    <option value="{{ $marca->id_marca }}">{{ $marca->nombre_marca }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="modelo" class="form-label">Modelo</label>
                        <select class="form-select" id="modelo" disabled>
                            <option value="" selected>Seleccione</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary me-2" id="btnguardar" onclick="guardar()">Guardar</button>
                    <a class="btn btn-md btn-danger ms-2" href="{{ env('APP_URL') }}/vehiculos" role="button">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    let server='<?= env("APP_URL") ?>';
    let modelosDisponibles=JSON.parse('<?= $modelos; ?>');
    cambiar_modelos('<?= $vehiculo[0]->id_modelo ?>');
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
    function cambiar_modelos(seleccionado=null){
        let modelo=document.getElementById("modelo");
        
        while (modelo.childNodes.length > 0)
        {
            modelo.removeChild(modelo.firstChild);
        }

        let marca=document.getElementById("marca").value;
        
        if(marca==""){
            modelo.disabled="disabled";
            let opcion=document.createElement("option");
            opcion.value="";
            opcion.text="Seleccione";
            modelo.appendChild(opcion);
        }
        else{
            modelo.disabled="";
            let opcion=document.createElement("option");
            opcion.value="";
            opcion.text="Seleccione";
            modelo.appendChild(opcion);

            let modelosCorrespondientes=modelosDisponibles.filter(element => element.id_marca == marca);

            modelosCorrespondientes.forEach(function(element){
                let opcion=document.createElement("option");
                opcion.value=element.id_modelo;
                opcion.text=element.nombre_modelo;
                if(seleccionado!=null && seleccionado==element.id_modelo){
                    opcion.selected="selected";
                }
                modelo.appendChild(opcion);
            });
        }
    }
    function validar(){
        let placa=document.getElementById("placa").value;
        let color=document.getElementById("color").value;
        let ano=document.getElementById("ano").value;
        let marca=document.getElementById("marca").value;
        let modelo=document.getElementById("modelo").value;
        if(placa!="" && color!="" && ano!="" && marca!="" && modelo!=""){
            return true;
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
        else{
            let id="{{ $vehiculo[0]->id_vehiculo }}";
            let placa=document.getElementById("placa").value;
            let color=document.getElementById("color").value;
            let ano=document.getElementById("ano").value;
            let modelo=document.getElementById("modelo").value;
            axios.post(server+'/api/editarvehiculo', {
                id: id,
                placa: placa,
                ano: ano,
                color: color,
                modelo: modelo
            })
            .then(function (response) {
                let respuesta=response.data;
                if(respuesta.respuesta){
                    alerta("success");
                    setTimeout(function(){
                        window.location.href=server+"/vehiculos";
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
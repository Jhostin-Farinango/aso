@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex align-items-center">
            <div class="col-md-12">
                <h1>Agregar Vehículo</h1>
            </div>
        </div>
        <div class="col-md-8 mt-3">
            <form>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="placa" class="form-label">Placa</label>
                        <div class="d-flex justify-content-between">
                            <div class="position-relative">
                                <input type="text" class="form-control" id="placa">
                                <button type="button" id="btnClean" class="btn btn-md btn-danger position-absolute d-none" style="top: 0; right: 0;" onclick="limpiar()">X</button>
                            </div>
                            <button type="button" id="btnSearch" class="btn btn-md btn-primary" onclick="buscar()">Buscar</button>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" class="form-control" id="color" disabled>
                    </div>
                    <div class="col-4">
                        <label for="ano" class="form-label">Año</label>
                        <input type="text" class="form-control" id="ano" disabled>
                    </div>
                    <div class="col-6">
                        <label for="marca" class="form-label">Marca</label>
                        <select class="form-select" id="marca" onchange="cambiar_modelos()" disabled>
                            <option value="" selected>Seleccione</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id_marca }}">{{ $marca->nombre_marca }}</option>
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
                    <button type="button" class="btn btn-primary me-2" id="btnguardar" onclick="guardar()" disabled>Guardar</button>
                    <a class="btn btn-md btn-danger ms-2" href="{{ env('APP_URL') }}/vehiculosocio/{{ $usuario[0]->id_usuario }}" role="button">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    let server='<?= env("APP_URL") ?>';
    let modelosDisponibles=JSON.parse('<?= $modelos; ?>');
    let usuario="<?= $usuario[0]->id_usuario; ?>";
    let modo = '';
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
    function buscar(){
        let placa=document.getElementById("placa").value;
        document.getElementById("placa").disabled="disabled";
        document.getElementById("btnSearch").disabled="disabled";
        document.getElementById("btnClean").classList.remove("d-none");
        document.getElementById("btnClean").classList.add("d-block");

        if(placa!=""){
            axios.post(server+'/api/buscarvehiculo', {
                placa: placa
            })
            .then(function (response) {
                let vehiculo=response.data;
                if(vehiculo.length==1){
                    document.getElementById("color").value=vehiculo[0].color_vehiculo;
                    document.getElementById("ano").value=vehiculo[0].ano_vehiculo;
                    document.getElementById("marca").value=vehiculo[0].id_marca;
                    cambiar_modelos(vehiculo[0].id_modelo);

                    document.getElementById("color").disabled="disabled";
                    document.getElementById("ano").disabled="disabled";
                    document.getElementById("marca").disabled="disabled";
                    document.getElementById("modelo").disabled="disabled";

                    modo="agregar";
                }
                else{
                    document.getElementById("color").value="";
                    document.getElementById("ano").value="";
                    document.getElementById("marca").value="";
                    document.getElementById("modelo").value="";

                    document.getElementById("color").disabled="";
                    document.getElementById("ano").disabled="";
                    document.getElementById("marca").disabled="";

                    modo="crear";
                }

                document.getElementById("btnguardar").disabled="";
            })
            .catch(function (error) {
                console.log(error);
                alerta("danger");
            });
        
        }
        else{
            limpiar();
        }
    }
    function limpiar(){
        document.getElementById("placa").disabled="";
        document.getElementById("btnSearch").disabled="";
        document.getElementById("btnClean").classList.remove("d-block");
        document.getElementById("btnClean").classList.add("d-none");
        document.getElementById("btnguardar").disabled="disabled";
    }
    function guardar(){
        document.getElementById("btnguardar").disabled="disabled";
        if(validar()==false){
            alerta("warning");
            document.getElementById("btnguardar").disabled="";
        }
        else {
            let placa=document.getElementById("placa").value;
            let color=document.getElementById("color").value;
            let ano=document.getElementById("ano").value;
            let modelo=document.getElementById("modelo").value;
            axios.post(server+'/api/guardarvehiculousuario', {
                placa: placa,
                ano: ano,
                color: color,
                modelo: modelo,
                usuario: usuario,
                modo: modo
            })
            .then(function (response) {
                let respuesta=response.data;
                if(respuesta.respuesta){
                    alerta("success");
                    setTimeout(function(){
                        window.location.href=server+"/vehiculosocio/"+usuario;
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
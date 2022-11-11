@extends('layouts.socio')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex align-items-center">
            <div class="col-md-12">
                <h1>Agregar Aporte</h1>
            </div>
        </div>
        <div class="col-md-8 mt-3">
            <form>
                <div class="row mb-3">
                    <div class="col-4">                       
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" pattern="^[1-9]\d*$">
                    </div>
                    <div class="col-4">
                        <label for="formapago" class="form-label">Forma de Pago</label>
                        <select class="form-select" id="formapago">
                            <option value="" selected>Seleccione</option>
                            @foreach ($formaspago as $formapago)
                            <option value="{{ $formapago->id_forma_pago }}">{{ $formapago->nombre_forma_pago }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="comprobante" class="form-label">Comprobante</label>
                        <input type="file" class="form-control" id="comprobante">
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary me-2" id="btnguardar" onclick="guardar()">Guardar</button>
                    <a class="btn btn-md btn-danger ms-2" href="{{ env('APP_URL') }}/aportes" role="button">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    let server = '<?= env("APP_URL") ?>';
    let usuario = '<?= session('usuario'); ?>';
    console.log('<?= session('usuario'); ?>');

    function alerta(tipo) {
        if (tipo == "success") {
            document.body.insertAdjacentHTML('beforeend', '<div class="alert alert-success" id="alerta" style="width: 400px; position: fixed; right: 20px; top: 400px; transition: all 2s ease; z-index: 99999;" role="alert">¡Operaci&oacute;n Exitosa!</div>');
            setTimeout(function() {
                document.getElementById("alerta").style.top = "70px";
                setTimeout(function() {
                    limpiar_alerta();
                }, 2500);
            }, 100);
        } else if (tipo == "danger") {
            document.body.insertAdjacentHTML('beforeend', '<div class="alert alert-danger" id="alerta" style="width: 400px; position: fixed; right: 20px; top: 400px; transition: all 2s ease; z-index: 99999;" role="alert">¡Operaci&oacute;n Fallida, Error Desconocido!</div>');
            setTimeout(function() {
                document.getElementById("alerta").style.top = "70px";
                setTimeout(function() {
                    limpiar_alerta();
                }, 2500);
            }, 100);
        } else if (tipo == "warning") {
            document.body.insertAdjacentHTML('beforeend', '<div class="alert alert-warning" id="alerta" style="width: 400px; position: fixed; right: 20px; top: 400px; transition: all 2s ease; z-index: 99999;" role="alert">¡Formulario incompleto, llene todos los campos recuerde no introducir valores negativos!</div>');
            setTimeout(function() {
                document.getElementById("alerta").style.top = "70px";
                setTimeout(function() {
                    limpiar_alerta();
                }, 2500);
            }, 100);
        }
    }

    function limpiar_alerta() {
        let alerta = document.getElementById("alerta");
        alerta.parentNode.removeChild(alerta);
    }

    function validar() {
        let cantidad = document.getElementById("cantidad").value;
        let formapago = document.getElementById("formapago").value;
        let comprobante = document.getElementById("comprobante").value;
        if (cantidad >= "0" && formapago != "" && comprobante != "") {
            return true;
        } else {
            return false;
        }
    }

    function guardar() {
        document.getElementById("btnguardar").disabled = "disabled";
        if (validar() == false) {
            alerta("warning");
            document.getElementById("btnguardar").disabled = "";
        } else {
            let cantidad = document.getElementById("cantidad").value;
            let formapago = document.getElementById("formapago").value;
            let comprobante = document.getElementById("comprobante").value;
            let datos = new FormData();
            datos.append('cantidad', document.getElementById("cantidad").value);
            datos.append('formapago', document.getElementById("formapago").value);
            datos.append('usuario', usuario);
            datos.append('comprobante', document.getElementById('comprobante').files[0]);
            axios.post(server + '/api/guardaraporte', datos
                    /*{
                                   cantidad: cantidad,
                                   formapago: formapago,
                                   usuario: usuario
                               }*/
                )
                .then(function(response) {
                    let respuesta = response.data;
                    if (respuesta.respuesta) {
                        alerta("success");
                        setTimeout(function() {
                            window.location.href = server + "/aportes";
                        }, 3000);
                    } else {
                        alerta("danger");
                        document.getElementById("btnguardar").disabled = "";
                    }
                })
                .catch(function(error) {
                    console.log(error);
                    alerta("danger");
                    document.getElementById("btnguardar").disabled = "";
                });
        }
    }
</script>
@endsection
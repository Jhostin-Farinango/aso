@extends('layouts.socio')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex align-items-center">
            <div class="col-md-11">
                <h1>Aportes</h1>
            </div>
            <div class="col-md-1 d-flex justify-content-center">
                <a class="btn btn-md btn-primary" href="{{ env('APP_URL') }}/agregaraporte" role="button">Nuevo</a>
            </div>
        </div>
        <div class="col-md-8 mt-3 d-flex flex-column align-items-center">
            @if(count($aportes)>0)
            <table class="table table-secondary table-striped">
                <thead>
                    <tr>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">#</th>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Fecha</th>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Cantidad</th>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Forma de Pago</th>
                        <th style="background: #0d6efd;" class="text-white text-center" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aportes as $aporte)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <th>{{ $aporte->fecha_aporte }}</th>
                        <th>{{ $aporte->cantidad_aporte }}</th>
                        <th>{{ $aporte->nombre_forma_pago }}</th>
                        <td class="d-flex justify-content-center">
                            <div class="pointer ms-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar" onclick="preparar_eliminacion('{{ $aporte->id_aporte }}', '{{ $aporte->cantidad_aporte }}')"><i class="fas fa-trash-alt"></i></div>
                            @if($aporte->comprobante_aporte!=null)
                            <div class="text-primary pointer" data-bs-toggle="modal" data-bs-target="#modalComprobante" onclick="preparar_comprobante('{{ $aporte->comprobante_aporte }}')"><i class="fas fa-eye"></i></div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <nav>
                <ul class="pagination">
                    @if($pagina==1)
                    <li class="page-item disabled">
                        <a class="page-link">&lt;&lt;</a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link">&lt;</a>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ env('APP_URL') }}/aportes/1">&lt;&lt;</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="{{ env('APP_URL') }}/aportes/{{ $pagina-1 }}">&lt;</a>
                    </li>
                    @endif
                    @for ($i = $inicioPaginacion; $i <= $finalPaginacion; $i++) @if($pagina==$i) <li class="page-item active"><a class="page-link">{{ $i }}</a></li>
                        @else
                        <li class="page-item"><a class="page-link" href="{{ env('APP_URL') }}/aportes/{{ $i }}">{{ $i }}</a></li>
                        @endif
                        @endfor
                        @if($pagina==$totalPaginas)
                        <li class="page-item disabled">
                            <a class="page-link">&gt;</a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link">&gt;&gt;</a>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ env('APP_URL') }}/aportes/{{ $pagina+1 }}">&gt;</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ env('APP_URL') }}/aportes/{{ $totalPaginas }}">&gt;&gt;</a>
                        </li>
                        @endif
                </ul>
            </nav>
            @else
            <h3>No existen aportes registrados.</h3>
            @endif
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modalEliminar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="tituloeliminar">Eliminar Aporte: </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="textoeliminar">¿Seguro que desea eliminar el aporte ?</p>
                <input type="text" class="d-none" id="ideliminar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btneliminar" onclick="eliminar()">Confirmar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modalComprobante">
    <div class="modal-dialog w-50 mw-100">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comprobante: </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="imgComprobante" src="" style="width: 100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let server = '<?= env("APP_URL") ?>';

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
        }
    }

    function limpiar_alerta() {
        let alerta = document.getElementById("alerta");
        alerta.parentNode.removeChild(alerta);
    }

    function preparar_eliminacion(id, nombre) {
        document.getElementById("tituloeliminar").innerHTML = "Eliminar Aporte: " + nombre;
        document.getElementById("textoeliminar").innerHTML = "¿Seguro que desea eliminar el aporte " + nombre + " ?";
        document.getElementById("ideliminar").value = id;
    }

    function preparar_comprobante(ruta) {
        //document.getElementById("imgComprobante").src=server+"/storage/app/"+url;
        document.getElementById("imgComprobante").src = "<?= asset('storage/app/"+ruta+"'); ?>";
    }

    function eliminar() {
        document.getElementById("btneliminar").disabled = "disabled";
        let id = document.getElementById("ideliminar").value;
        axios.post(server + '/api/eliminaraporte', {
                id: id
            })
            .then(function(response) {
                let respuesta = response.data;
                if (respuesta.respuesta) {
                    alerta("success");
                    setTimeout(function() {
                        window.location.href = server + "/aportes";
                    }, 3000);
                } else {
                    alerta("danger");
                    document.getElementById("btneliminar").disabled = "";
                }
            })
            .catch(function(error) {
                console.log(error);
                alerta("danger");
                document.getElementById("btneliminar").disabled = "";
            });
    }
</script>
@endsection
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex align-items-center">
            <div class="col-md-11">
                <h1>Vehículos</h1>
            </div>
            <div class="col-md-1 d-flex justify-content-center">
                <a class="btn btn-md btn-primary" href="{{ env('APP_URL') }}/agregarvehiculo" role="button">Nuevo</a>
            </div>
        </div>
        <div class="col-md-8 mt-3 d-flex flex-column align-items-center">
            @if(count($vehiculos)>0)
                <table class="table table-secondary table-striped">
                    <thead>
                        <tr>
                            <th style="background: #0d6efd;" class="text-white text-center" scope="col">#</th>
                            <th style="background: #0d6efd;" class="text-white text-center" scope="col">Placa</th>
                            <th style="background: #0d6efd;" class="text-white text-center" scope="col">Año</th>
                            <th style="background: #0d6efd;" class="text-white text-center" scope="col">Color</th>
                            <th style="background: #0d6efd;" class="text-white text-center" scope="col">Modelo</th>
                            <th style="background: #0d6efd;" class="text-white text-center" scope="col">Marca</th>
                            <th style="background: #0d6efd;" class="text-white text-center" scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehiculos as $vehiculo)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <th>{{ $vehiculo->placa_vehiculo }}</th>
                                <th>{{ $vehiculo->ano_vehiculo }}</th>
                                <th>{{ $vehiculo->color_vehiculo }}</th>
                                <th>{{ $vehiculo->nombre_modelo }}</th>
                                <th>{{ $vehiculo->nombre_marca }}</th>
                                <td class="d-flex justify-content-center">
                                    <a class="me-2 text-success" href="{{ env('APP_URL') }}/editarvehiculo/{{ $vehiculo->id_vehiculo }}"><i class="fas fa-edit"></i></a>
                                    <div class="pointer ms-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar" onclick="preparar_eliminacion('{{ $vehiculo->id_vehiculo }}', '{{ $vehiculo->placa_vehiculo }}')"><i class="fas fa-trash-alt"></i></div>
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
                                <a class="page-link" href="{{ env('APP_URL') }}/vehiculos/1">&lt;&lt;</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ env('APP_URL') }}/vehiculos/{{ $pagina-1 }}">&lt;</a>
                            </li>
                        @endif
                        @for ($i = $inicioPaginacion; $i <= $finalPaginacion; $i++)
                            @if($pagina==$i)
                                <li class="page-item active"><a class="page-link">{{ $i }}</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ env('APP_URL') }}/vehiculos/{{ $i }}">{{ $i }}</a></li>
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
                                <a class="page-link" href="{{ env('APP_URL') }}/vehiculos/{{ $pagina+1 }}">&gt;</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ env('APP_URL') }}/vehiculos/{{ $totalPaginas }}">&gt;&gt;</a>
                            </li>
                        @endif
                    </ul>
                </nav>
            @else
                <h3>No existen vehículos registrados.</h3>
            @endif
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modalEliminar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="tituloeliminar">Eliminar Vehículo: </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="textoeliminar">¿Seguro que desea eliminar el vehículo ?</p>
                <input type="text" class="d-none" id="ideliminar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btneliminar" onclick="eliminar()">Confirmar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let server='<?= env("APP_URL") ?>';
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
    }
    function limpiar_alerta(){
        let alerta=document.getElementById("alerta");
        alerta.parentNode.removeChild(alerta);
    }
    function preparar_eliminacion(id, placa){
        document.getElementById("tituloeliminar").innerHTML="Eliminar Vehículo: " + placa;
        document.getElementById("textoeliminar").innerHTML="¿Seguro que desea eliminar el vehículo " + placa + " ?";
        document.getElementById("ideliminar").value=id;
    }
    function eliminar(){
        document.getElementById("btneliminar").disabled="disabled";
        let id=document.getElementById("ideliminar").value;
        axios.post(server+'/api/eliminarvehiculo', {
            id: id
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
                document.getElementById("btneliminar").disabled="";
            }
        })
        .catch(function (error) {
            console.log(error);
            alerta("danger");
            document.getElementById("btneliminar").disabled="";
        });
    }
</script>
@endsection
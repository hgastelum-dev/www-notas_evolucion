@extends('layouts.sbadmin')

@section('container')

<div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Paciente</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $cita->getPaciente->nombre_s }} {{ $cita->getPaciente->apellido_paterno }} {{ $cita->getPaciente->apellido_materno }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Fecha de la cita</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $cita->fecha }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <a class="btn btn-info btn-sm" href="/agenda">
                <i class="fas fa-arrow-left"></i> Regresar a la agenda
            </a>
            <b class="float-right align-middle">
                <span class="badge badge-primary">Atiende:</span> {{ $cita->getUser->name }}
            </b> 
        </h6>
    </div>
    <div class="card-body">
        <div class="text-left">
            <p>
                <h5>
                    Estado actual de la cita: <span class="badge text-white" style="background-color: {{ $cita->getEstado->class_color }};">
                        <i class="fas fa-sync-alt"></i> {{ $cita->getEstado->cita_estado }}
                    </span>
                </h5>
            </p>
        </div>
        <div class="text-right">
            <p>
                <button class="btn btn-success" type="button" id="btn-cierre-cita">
                    <i class="fas fa-handshake"></i> Concluir cita del paciente
                </button>
            </p>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('cita/soap01/subjetivo/*') ? 'active' : '' }}" href="/cita/soap01/subjetivo/{{ $cita->id }}">
                    <h4>
                        @if($cita->getSubjetivo)
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-pencil-alt text-danger"></i>
                        @endif
                        <b>S</b>ubjetivo
                    </h4>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('cita/soap02/objetivo/*') ? 'active' : '' }}" href="/cita/soap02/objetivo/{{ $cita->id }}">
                    <h4>
                        @if($cita->getObjetivo)
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-pencil-alt text-danger"></i>
                        @endif
                        <b>O</b>bjetivo
                    </h4>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('cita/soap03/analisis/*') ? 'active' : '' }}" href="/cita/soap03/analisis/{{ $cita->id }}">
                <h4>
                        @if($cita->getAnalisis)
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-pencil-alt text-danger"></i>
                        @endif
                        <b>A</b>nalisis
                </h4>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('cita/soap04/planeacion/*') ? 'active' : '' }}" href="/cita/soap04/planeacion/{{ $cita->id }}">
                <h4>
                        @if(count($cita->getPlaneacion) > 0)
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-pencil-alt text-danger"></i>
                        @endif
                        <b>P</b>lan
                    </h4>
                </a>
            </li>
        </ul>
        <br>
        <div>
            @yield('seccion-cita')
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cierreCitaModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="cierreCitaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <p class="text-center">
            <i class="fas fa-check-circle text-success" style="font-size: 75px;"></i>
            <p>
                <h5 class="text-center">
                    La cita en pantalla ha sido <b>Concluida</b> exitosamente
                    <br><br>
                    <a class="btn btn-primary" href="/agenda">
                        <i class="fas fa-arrow-left"></i> Regresar a la agenda semanal
                    </a>
                </h5>
            </p>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="errorCierraModal" tabindex="-1" aria-labelledby="cierreCitaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Atento aviso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-center">
            <i class="fas fa-pencil-alt text-danger" style="font-size: 75px;"></i>
            <p>
                <h5 class="text-center">
                    La secciones <b>SOAP</b> de la cita en pantalla aun no han sido capturadas completamente. Favor de ingresar la informaci&oacute;n correspondiente a cada apartado
                </h5>
            </p>
        </p>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
    document.getElementById('btn-cierre-cita').addEventListener('click', function(event){
        
        this.disabled = true;

        const token = '{{ csrf_token() }}';

        var data = {
          _token: token,
          citaId: {{ $cita->id }}
        }

        fetch('/cita/cierre', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(function(response){
            
            if(response.hasOwnProperty('id')){
                $('#cierreCitaModal').modal('show')
            } else {
                $('#errorCierraModal').modal('show')    
            }
        })

        this.disabled = false;
    })
</script>

@endsection

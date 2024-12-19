@extends('layouts.sbadmin')

@section('container')

<div class="card shadow mb-4">
    
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user"></i> Actualizacion de datos del paciente
        </h6>
    </div>

    <div class="card-body">
        
        <p class="text-start">
          <a href="/pacientes" class="btn btn-warning" role="button">
            <i class="fas fa-arrow-left"></i> Regresar al listado de Pacientes
          </a>
        </p>

        <table class="table table-bordered table-hover text-nowrap align-middle text-center">
            
            <tr>
                <th>Numero de expediente:</th>
                <td class="text-center">
                    <span class="badge badge-primary">
                        {{ $paciente->numero_expediente }}
                    </span>
                </td>
                <th>Nombre del paciente:</th>
                <td>
                    <i class="fas fa-user"></i> {{ $paciente->nombre_s }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}
                </td>
                <td rowspan="3" class="text-center align-middle" style="width: 220px;">
                    <img src="{{ asset('img/user-icon.png') }}" class="img-responsive" alt="..." height="150">
                </td>
            </tr>
            
            <tr>
                <th>Genero:</th>
                <td>
                    @if($paciente->genero_id == 2)
                        Femenino
                    @else
                        Masculino
                    @endif
                </td>
                <th>Fecha de nacimiento:</th>
                <td>
                    <i class="fas fa-calendar-alt"></i> {{ substr($paciente->fecha_nacimiento, 0, 10) }}
                </td>
            </tr>
            
            <tr>
                <th>Tipo sangre:</th>
                <td>{{ $paciente->tipo_sangre }}</td>
                <th>Fecha de primera cita</th>
                <td>
                    <i class="fas fa-calendar-alt"></i> {{ substr($paciente->fecha_ingreso, 0, 10) }}
                </td>
            </tr>
        </table>

        @php
            $citasConcluidas = \App\Models\CitaPaciente::where('paciente_id', $paciente->id)
                ->where('cita_estado_id', 4);

            $planeacion = \App\Models\CitaPlaneacion::where('paciente_id', $paciente->id)
                ->where('indicador_seguimiento', false)
                ->where('padre_id', 0)
                ->get();
        @endphp

        @if(isset($citaEnProgreso))
          <a class="btn btn-info" href="/cita/soap01/subjetivo/{{ $citaEnProgreso->id }}">
            Ir a la cita en progreso: <b>{{ $citaEnProgreso->fecha }}</b>
          </a>
        @endif

        @if(isset($primeraCita))
            @if(count($citasConcluidas->get()) < 1)

              @if(count($planeacion) == 0)
                <div class="alert alert-primary" role="alert">
                  Primera cita programada: <b>{{ $primeraCita->fecha }}</b>
                  <button class="btn btn-success" type="button" id="btn-cierre-cita">
                    <i class="fas fa-handshake"></i> Concluir cita del paciente
                  </button>
                  <script type="text/javascript">
                        document.getElementById('btn-cierre-cita').addEventListener('click', function(event){
                            
                            this.disabled = true;

                            const token = '{{ csrf_token() }}';

                            var data = {
                              _token: token,
                              citaId: {{ $primeraCita->id }},
                              primerCita: 1
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
                  @if($primeraCita->en_progreso == 1)
                    {{--<a class="btn btn-info" href="/cita/soap01/subjetivo/{{ $primeraCita->id }}">
                      Ir a la cita inicial 
                    </a>--}}
                  @endif
                </div>
                
              @else
                <div class="alert alert-success" role="alert">
                  Plan inicial guardado 
                  @if($primeraCita->en_progreso == 1)
                    {{--<a class="btn btn-info" href="/cita/soap01/subjetivo/{{ $primeraCita->id }}">
                      Ir a la cita inicial: <b>{{ $primeraCita->fecha }}</b> 
                    </a>--}}
                    <button class="btn btn-success" type="button" id="btn-cierre-cita">
                        <i class="fas fa-handshake"></i> Concluir cita del paciente
                    </button>
                    <script type="text/javascript">
                        document.getElementById('btn-cierre-cita').addEventListener('click', function(event){
                            
                            this.disabled = true;

                            const token = '{{ csrf_token() }}';

                            var data = {
                              _token: token,
                              citaId: {{ $primeraCita->id }},
                              primerCita: 1
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
                  @endif
                  
                  <form method="post" action="/cita/iniciar">
                  @csrf
                  <input type="hidden" value="{{ $primeraCita->id }}" id="sesion-id-iniciar" name="cita_id">
                  {{--<button class="btn btn-success" type="submit" id="btn-iniciar-cita" onclick="this.classList.add('d-none')">
                    Atender cita
                  </button>--}}
                  </form>
                </div>
              @endif
              
            @endif
        @endif
        
        <p>
            <h4 class="text-center">
                <b>
                    Seleccione una opcion<br>
                    <i class="fas fa-arrow-down"></i>
                </b>
            </h4>
        </p>

        <ul class="nav nav-pills nav-fill">
          
          <li class="nav-item">
            <a class="nav-link border border-primary {{ request()->is('paciente/editar/*') || request()->is('paciente/editar') ? 'active' : '' }}" href="/paciente/editar/{{ $paciente->id }}">
                <i class="fas fa-user"></i> Ficha de identificaci&oacute;n
            </a>
          </li>
          &nbsp;

          <li class="nav-item">
            <a class="nav-link border border-primary {{ request()->is('paciente/antecedentes/*') || request()->is('paciente/antecedentes') ? 'active' : '' }}" href="/paciente/antecedentes/{{ $paciente->id }}">
                <i class="fas fa-history"></i> Antecedentes
            </a>
          </li>
          &nbsp;

          <li class="nav-item">
            <a class="nav-link border border-primary {{ request()->is('paciente/padecimientos/*') || request()->is('paciente/padecimientos') ? 'active' : '' }}" href="/paciente/padecimientos/{{ $paciente->id }}">
                <i class="fas fa-allergies"></i> Padecimiento actual
            </a>
          </li>
          &nbsp;

          <li class="nav-item">
            <a class="nav-link border border-primary {{ request()->is('paciente/exp-fisica/*') || request()->is('paciente/exp-fisica') ? 'active' : '' }}" href="/paciente/exp-fisica/{{ $paciente->id }}">
                <i class="far fa-eye"></i> Exploraci&oacute;n fisica
            </a>
          </li>
          &nbsp;
          
          <li class="nav-item">
            <a class="nav-link border border-primary {{ request()->is('paciente/plan/*') || request()->is('paciente/plan') ? 'active' : '' }}" href="/paciente/plan/{{ $paciente->id }}">
                <i class="far fa-map"></i> Plan
            </a>
          </li>
        </ul>
        
        <br><br>
        @yield('paciente')
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
                    Favor de capturar el <b>plan inicial</b> del paciente para poder concluir la primer cita del paciente.
                </h5>
            </p>
        </p>
      </div>
    </div>
  </div>
</div>

@endsection
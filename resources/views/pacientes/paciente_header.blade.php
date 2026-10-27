@extends('layouts.sbadmin')

@section('container')

  <style>
    .expediente-card {
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        border-radius: 0.6rem;
        overflow: hidden;
    }

    .paciente-banner {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-bottom: 1px solid #edf0f3;
    }

    .paciente-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px #e5e7eb;
        flex-shrink: 0;
    }

    .paciente-nombre {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.15rem;
    }

    .paciente-expediente-badge {
        font-size: 0.75rem;
        letter-spacing: 0.03em;
    }

    .paciente-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1.5rem;
        margin-top: 0.4rem;
    }

    .paciente-stat {
        font-size: 0.85rem;
        color: #4b5563;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }

    .paciente-stat b {
        color: #1f2937;
    }

    .fecha-ingreso-inline {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0 !important;
        white-space: nowrap;  
    }

    .fecha-ingreso-inline input[type="date"] {
        padding: 0.2rem 0.5rem;
        height: auto;
        font-size: 0.8rem;
    }

    .tabs-expediente {
        padding: 0.75rem 1.5rem 0;
        border-bottom: 1px solid #edf0f3;
        background: #fafbfc;
        flex-wrap: nowrap;
        overflow-x: auto;
        white-space: nowrap;
    }

    .tabs-expediente .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.65rem 1rem;
    }

    .tabs-expediente .nav-link:hover {
        color: #2563eb;
        background: transparent;
    }

    .tabs-expediente .nav-link.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
        background: transparent;
    }

    .tab-contenido {
        padding: 1.5rem;
    }
  </style>

  <div class="card expediente-card mb-4">

    <div class="paciente-banner">
        <img src="{{ asset('img/user-icon.png') }}" class="paciente-avatar" alt="Foto del paciente">

        <div class="flex-grow-1">
            <div class="paciente-nombre">
                {{ $paciente->nombre_s }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}
            </div>
            <span class="badge badge-primary paciente-expediente-badge">
                Expediente: {{ $paciente->numero_expediente }}
            </span>

            <div class="paciente-stats">
                <span class="paciente-stat">
                    <i class="fas fa-venus-mars text-muted"></i>
                    @if($paciente->genero_id == 2) Femenino @else Masculino @endif
                </span>

                <span class="paciente-stat">
                    <i class="fas fa-calendar-alt text-muted"></i>
                    {{ substr($paciente->fecha_nacimiento, 0, 10) }}
                    @if($paciente->fecha_nacimiento)
                        @php
                            $fecha_nacimiento_obj = new DateTime(substr($paciente->fecha_nacimiento, 0, 10));
                            $hoy = new DateTime();
                            $edad = $hoy->diff($fecha_nacimiento_obj)->y;
                        @endphp
                        <span class="badge badge-success ml-1">{{ $edad }} a&ntilde;os</span>
                    @else
                        <span class="badge badge-danger ml-1">Falta edad</span>
                    @endif
                </span>

                <span class="paciente-stat">
                    <i class="fas fa-tint text-muted"></i>
                    Tipo sangre: <b>{{ $paciente->tipo_sangre ?: 'N/D' }}</b>
                </span>

                <span class="paciente-stat">
                    <i class="fas fa-user-clock text-muted"></i>
                    <form method="post" action="/paciente/fecha_ingreso/update" class="fecha-ingreso-inline">
                        @csrf
                        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                        Primera cita:
                        <input type="date" class="form-control form-control-sm" id="fecha_ingreso" name="fecha_ingreso" value="{{ $paciente->fecha_ingreso }}" aria-describedby="fecha_ingreso" required>
                        <button class="btn btn-sm btn-outline-success" type="submit" title="Guardar fecha">
                            <i class="fas fa-save"></i>
                        </button>
                    </form>
                </span>
            </div>
        </div>

        <div>
            <a href="/pacientes" class="btn btn-outline-secondary btn-sm" role="button">
                <i class="fas fa-arrow-left"></i> Listado de pacientes
            </a>
        </div>
    </div>

    <div class="px-4 pt-3">
        {{-- cita en progreso que no es la inicial, por lo que solo se activa el hipervinculo --}}
        @if(isset($citaEnProgreso))
          <a class="btn btn-info btn-sm mb-2" href="/cita/soap01/subjetivo/{{ $citaEnProgreso->id }}">
            <i class="fas fa-notes-medical"></i> Volver a la cita en progreso: <b>{{ $citaEnProgreso->fecha }}</b>
          </a>
        @endif

        @if(isset($primeraCita))
            @if($primeraCita->en_progreso == 1)
                @php
                    $planeacion = \App\Models\CitaPlaneacion::where('paciente_id', $paciente->id)
                        ->where('indicador_seguimiento', false)
                        ->where('padre_id', 0)
                        ->where('cita_paciente_id', $primeraCita->id)
                        ->get();
                @endphp

                <div class="alert alert-primary d-flex align-items-center justify-content-between flex-wrap" role="alert">
                    <span>
                        <i class="fas fa-stethoscope"></i>
                        Primera cita programada en progreso: <b>{{ $primeraCita->fecha }}</b>
                    </span>

                    <span>
                        @if(count($planeacion) > 0)
                            <button class="btn btn-info btn-sm" type="button" id="btn-cierre-cita">
                                <i class="fas fa-handshake"></i> Concluir cita del paciente
                            </button>
                        @endif
                        <a class="btn btn-info btn-sm" href="/cita/soap01/subjetivo/{{ $primeraCita->id }}">
                          <i class="fas fa-notes-medical"></i> Ir a nota SOAP en su lugar
                        </a>
                    </span>
                </div>
            @endif
        @endif
    </div>

    <ul class="nav nav-tabs tabs-expediente">

      <li class="nav-item">
        <a class="nav-link {{ request()->is('paciente/editar/*') || request()->is('paciente/editar') ? 'active' : '' }}" href="/paciente/editar/{{ $paciente->id }}">
            <i class="fas fa-user"></i> Ficha de identificaci&oacute;n
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('paciente/antecedentes/*') || request()->is('paciente/antecedentes') ? 'active' : '' }}" href="/paciente/antecedentes/{{ $paciente->id }}">
            <i class="fas fa-history"></i> Antecedentes
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('paciente/padecimientos/*') || request()->is('paciente/padecimientos') ? 'active' : '' }}" href="/paciente/padecimientos/{{ $paciente->id }}">
            <i class="fas fa-allergies"></i> Padecimiento actual
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('paciente/exp-fisica/*') || request()->is('paciente/exp-fisica') ? 'active' : '' }}" href="/paciente/exp-fisica/{{ $paciente->id }}">
            <i class="far fa-eye"></i> Exploraci&oacute;n f&iacute;sica
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('paciente/plan/*') || request()->is('paciente/plan') ? 'active' : '' }}" href="/paciente/plan/{{ $paciente->id }}">
            <i class="far fa-map"></i> Plan inicial
        </a>
      </li>

      <li class="nav-item">
          <a class="nav-link {{ request()->is('paciente/notas-hist/*') || request()->is('paciente/notas-hist') ? 'active' : '' }}" href="/paciente/notas-hist/{{ $paciente->id }}">
              <i class="far fa-file"></i> Hist&oacute;rico de notas
          </a>
      </li>
    </ul>

    <div class="tab-contenido">
        @yield('paciente')
    </div>
  </div>

  {{-- modal aviso de finalizacion de cita inicial con historia clinica --}}
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

  {{-- modal aviso de validacion para poder finalizar cita inicial con historia clinica --}}
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

  @if(isset($primeraCita))
      @if($primeraCita->en_progreso == 1)
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
  @endif

@endsection
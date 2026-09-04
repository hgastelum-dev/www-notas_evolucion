@extends('layouts.sbadmin')

@section('container')
  <style>
    .sticky-tabs {
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
    }
    .table-medica {
        width: auto;
        max-width: 100%;
        font-size: 0.9rem;
        border-collapse: separate;
        border-spacing: 0;
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .table-medica th,
    .table-medica td {
        padding: 0.5rem 0.75rem;
        white-space: nowrap;
        vertical-align: middle;
        border-bottom: 1px solid #e0e0e0;
    }

    .table-medica th {
        background-color: #f5f5f5;
        color: #424242;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .table-medica td {
        color: #333;
    }

    .table-medica tr:last-child td {
        border-bottom: none;
    }

    .table-medica tbody tr:hover {
        background-color: #f9f9f9;
        transition: background-color 0.3s ease;
    }

    .exploracion-box {
        background: #fefefe;
        border-left: 4px solid #2196f3;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        border-radius: 0.375rem;
        box-shadow: inset 0 0 0 1px #e0e0e0;
    }

    .nav-tabs-vertical {
        flex-direction: column;
        border-right: 1px solid #dee2e6;
    }

    .nav-tabs-vertical .nav-link {
        border: 1px solid transparent;
        border-right: none;
        color: #495057;
    }

    .nav-tabs-vertical .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: bold;
    }

    .tab-content-area {
        padding-left: 1.5rem;
    }
  </style>

  {{-- modal notas de evolucion previas --}}
  <div class="modal fade" id="modal-notas-previas" tabindex="-1" aria-labelledby="notasPreviasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="notasPreviasModalLabel">Notas de evolución previas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @php
              $citasPrevias = $cita->getPaciente->citasConcluidasAnteriores($cita->fecha, $cita->hora_inicio)->get();
          @endphp

          <div class="row">
              {{-- tabs laterales con fechas --}}
              <div class="col-md-3">
                <div class="nav nav-tabs nav-tabs-vertical flex-column sticky-tabs" id="citaTabs" role="tablist" aria-orientation="vertical">
                    @foreach($citasPrevias as $index => $citaPrevia)
                        <a class="nav-link @if($loop->first) active @endif"
                           id="cita-tab-{{ $index }}" data-toggle="tab"
                           href="#cita-{{ $index }}" role="tab"
                           aria-controls="cita-{{ $index }}"
                           aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            <i class="fa fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($citaPrevia->fecha)->format('d/m/Y') }}
                            @if($loop->first)
                                <span class="badge badge-success ml-1">Última</span>
                            @endif
                        </a>
                    @endforeach
                </div>
              </div>

              {{-- contenido de cada cita --}}
              <div class="col-md-9 tab-content-area">
                  <div class="tab-content" id="citaTabsContent">
                      @foreach($citasPrevias as $index => $citaPrevia)
                          <div class="tab-pane fade @if($loop->first) show active @endif"
                               id="cita-{{ $index }}" role="tabpanel"
                               aria-labelledby="cita-tab-{{ $index }}">
                              
                              {{-- subjetivo --}}
                              <div class="mb-4">
                                  <h5><b><span class="badge badge-success">Subjetivo</span></b></h5>
                                  <p>{!! optional($citaPrevia->getSubjetivo)->subjetivo ?? 'Sin información.' !!}</p>
                              </div>

                              {{-- objetivo --}}
                              @php $objetivo = $citaPrevia->getObjetivo; @endphp
                              <div class="mb-4">
                                  <h5><b><span class="badge badge-success">Objetivo</span></b></h5>
                                  @if ($objetivo)
                                      <table class="table-medica">
                                          <tbody>
                                              <tr><th>TA</th><td>{{ $objetivo->ta }}/{{ $objetivo->ta2 }}</td><th>FC</th><td>{{ $objetivo->fc }}</td></tr>
                                              <tr><th>FR</th><td>{{ $objetivo->fr }}</td><th>Temp</th><td>{{ $objetivo->temp }} °C</td></tr>
                                              <tr><th>Peso</th><td>{{ $objetivo->peso }} kg</td><th>IMC</th><td>{{ $objetivo->imc != 'Infinity' ? $objetivo->imc : 'N/A' }}</td></tr>
                                              <tr><th>Hb</th><td>{{ $objetivo->hb }}</td><th>Hto</th><td>{{ $objetivo->hto }}</td></tr>
                                              <tr><th>VCM</th><td>{{ $objetivo->vcm }}</td><th>HCM</th><td>{{ $objetivo->hcm }}</td></tr>
                                              <tr><th>Cr</th><td>{{ $objetivo->cr }}</td><th>CKD-EPI</th><td>{{ $objetivo->ckdepi }}</td></tr>
                                              <tr><th>BUN</th><td>{{ $objetivo->bun }}</td><th>HbA1c %</th><td>{{ $objetivo->hba1c_porcentaje }}</td></tr>
                                              <tr><th>Na</th><td>{{ $objetivo->na }}</td><th>K</th><td>{{ $objetivo->k }}</td></tr>
                                              <tr><th>Cl</th><td>{{ $objetivo->cl }}</td><th>Ca</th><td>{{ $objetivo->ca }}</td></tr>
                                              <tr><th>P</th><td>{{ $objetivo->p }}</td><th>Mg</th><td>{{ $objetivo->mg }}</td></tr>
                                              <tr><th>HDL</th><td>{{ $objetivo->hdl_col }}</td><th>LDL</th><td>{{ $objetivo->ldl_col }}</td></tr>
                                              <tr><th>TG</th><td>{{ $objetivo->tgs }}</td><th>Col Total</th><td>{{ $objetivo->col ?? 'N/A' }}</td></tr>
                                              <tr><th>ALB</th><td>{{ $objetivo->alb }}</td><th>Albúmina/Cr</th><td>{{ $objetivo->albu_cru }}</td></tr>
                                              <tr><th>EGO</th><td colspan="3">{{ $objetivo->ego }}</td></tr>
                                          </tbody>
                                      </table>
                                      <br>
                                      <div class="exploracion-box">
                                          {!! $objetivo->exploracion_fisica !!}
                                      </div>
                                  @else
                                      <p>Sin información.</p>
                                  @endif
                              </div>

                              {{-- analisis --}}
                              <div class="mb-4">
                                  <h5><b><span class="badge badge-success">Análisis</span></b></h5>
                                  <p>{!! optional($citaPrevia->getAnalisis)->analisis ?? 'Sin información.' !!}</p>
                              </div>

                              {{-- planeacion --}}
                              <div class="mb-4">
                                  <h5><b><span class="badge badge-success">Planeaci&oacute;n</span></b></h5>
                                  @forelse($citaPrevia->getPlaneacion->sortBy(fn($p) => $p->getTipoPlan->tipo_plan) as $plan)
                                      <p>
                                          - <b>{{ $plan->getTipoPlan->tipo_plan }}</b>: {{ $plan->plan }}
                                          @if(count($plan->getTipoPlanAnidado) > 0)
                                              <div class="text-dark ml-3">
                                                  @foreach($plan->getTipoPlanAnidado as $planAnidadoPrevio)
                                                      <p><u>{{ $planAnidadoPrevio->plan }}</u></p>
                                                  @endforeach
                                              </div>
                                          @endif
                                      </p>
                                  @empty
                                      <p>Sin información.</p>
                                  @endforelse
                              </div>
                          </div>
                      @endforeach
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar ventana</button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Paciente
                      </div>
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
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Fecha de la cita
                          </div>
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
                  <button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal-notas-previas">
                      Ver notas previas: {{ count($cita->getPaciente->citasConcluidasAnteriores($cita->fecha, $cita->hora_inicio)->get()) }}
                  </button>
                  
                  <a class="btn btn-info" href="/paciente/plan/{{ $cita->paciente_id }}">
                      <i class="fas fa-user"></i> Ir a la historia clínica
                  </a>
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

  {{-- modal aviso de cita concluida --}}
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

  {{-- aviso secciones pendientes de captura en SOAP --}}
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
                      Las secciones <b>SOAP</b> de la cita en pantalla aún no han sido capturadas completamente. Favor de ingresar la informaci&oacute;n correspondiente a cada apartado
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
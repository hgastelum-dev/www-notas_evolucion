@extends('layouts.sbadmin')

@section('container')

    {{-- page heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Tablero de seguimiento - D&iacute;a actual</h1>
      <a href="/agenda" class="btn btn-primary btn-sm">
        <i class="fas fa-calendar-alt"></i> Ir a la agenda
      </a>
    </div>

    {{-- content row --}}
    <div class="row">

      @php
        $iconosEstado = [
            1 => 'fa-calendar-plus',
            2 => 'fa-calendar-check',
            3 => 'fa-calendar-times',
            4 => 'fa-calendar-check',
        ];
      @endphp

      @foreach($citaEstados as $citaEstado)

        @php
          switch($citaEstado->id){
            case 1: $claseColor = 'primary'; break;
            case 2: $claseColor = 'warning'; break;
            case 3: $claseColor = 'danger'; break;
            case 4: $claseColor = 'success'; break;
          }

          $icono = $iconosEstado[$citaEstado->id] ?? 'fa-calendar';
          $conteoHoy = $citasHoy->where('cita_estado_id', $citaEstado->id)->count();
        @endphp

        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-{{ $claseColor }} shadow h-100 py-2">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-{{ $claseColor }} text-uppercase mb-1">
                              {{ $citaEstado->cita_estado }} (hoy)</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">
                              {{ $conteoHoy }}
                          </div>
                      </div>
                      <div class="col-auto">
                          <i class="fas {{ $icono }} fa-2x text-gray-300"></i>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      @endforeach

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            En progreso</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $enProgreso }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-md fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                            Total del d&iacute;a</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalCitasHoy }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>

    <div class="row">

      {{-- citas en progreso ahora --}}
      <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-stethoscope"></i> Citas en progreso ahora
            </h6>
          </div>
          <div class="card-body">
            @forelse($citasEnProgresoLista as $citaProgreso)
              <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div>
                  <b>{{ $citaProgreso->getPaciente->nombre_s }} {{ $citaProgreso->getPaciente->apellido_paterno }}</b>
                  <br>
                  <span class="text-muted small">
                    <i class="fas fa-user-md"></i> {{ $citaProgreso->getUser->name }} &middot;
                    <i class="far fa-clock"></i> {{ substr($citaProgreso->hora_inicio, 0, 5) }}
                  </span>
                </div>
                <a href="/cita/atender/{{ $citaProgreso->id }}" class="btn btn-sm btn-outline-primary">
                  Ver <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            @empty
              <p class="text-muted mb-0">No hay citas en progreso en este momento.</p>
            @endforelse
          </div>
        </div>
      </div>

      {{-- proximas citas de hoy --}}
      <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-hourglass-half"></i> Pr&oacute;ximas citas de hoy
            </h6>
          </div>
          <div class="card-body">
            @forelse($proximasCitas as $proxima)
              <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div>
                  <b>{{ $proxima->getPaciente->nombre_s }} {{ $proxima->getPaciente->apellido_paterno }}</b>
                  <br>
                  <span class="text-muted small">
                    <i class="fas fa-user-md"></i> {{ $proxima->getUser->name }} &middot;
                    <i class="far fa-clock"></i> {{ substr($proxima->hora_inicio, 0, 5) }}
                  </span>
                </div>
              </div>
            @empty
              <p class="text-muted mb-0">No hay más citas programadas por iniciar hoy.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- resumen por doctor --}}
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-user-md"></i> Agenda de hoy por doctor
        </h6>
      </div>
      <div class="card-body">
        @if($resumenPorDoctor->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr class="text-muted small text-uppercase">
                  <th>Doctor</th>
                  <th class="text-center">Programadas</th>
                  <th class="text-center">Reprogramadas</th>
                  <th class="text-center">Canceladas</th>
                  <th class="text-center">Concluidas</th>
                  <th class="text-center">En progreso</th>
                  <th class="text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($resumenPorDoctor as $resumen)
                  <tr>
                    <td><b>{{ $resumen['doctor']->name ?? 'N/D' }}</b></td>
                    <td class="text-center">{{ $resumen['programadas'] }}</td>
                    <td class="text-center">{{ $resumen['reprogramadas'] }}</td>
                    <td class="text-center">{{ $resumen['canceladas'] }}</td>
                    <td class="text-center">{{ $resumen['concluidas'] }}</td>
                    <td class="text-center">
                      @if($resumen['en_progreso'] > 0)
                        <span class="badge badge-info">{{ $resumen['en_progreso'] }}</span>
                      @else
                        0
                      @endif
                    </td>
                    <td class="text-center"><b>{{ $resumen['total'] }}</b></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-muted mb-0">No hay citas agendadas para el d&iacute;a de hoy.</p>
        @endif
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-chart-line"></i> Gr&aacute;ficas de par&aacute;metros de medici&oacute;n
        </h6>
      </div>
      <div class="card-body">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label"><b>Paciente:</b></label>
            <select id="paciente" class="form-control">
                <option value="">Seleccionar...</option>
                @foreach($pacientes as $p)
                    <option value="{{ $p->id }}">{{ $p->nombre_s }} {{ $p->apellido_paterno }}</option>
                @endforeach
            </select>
          </div>
        </div>

        <div id="citas-container" style="display:none;" class="mt-3">
            <label class="form-label"><b>Fechas de citas concluidas:</b></label>
            <div id="lista-citas" class="mb-3"></div>
        </div>

        <div id="parametros-container" style="display:none;" class="mt-2">
            <label class="form-label"><b>Par&aacute;metros disponibles:</b></label>
            <select id="parametro" class="form-control mb-3"></select>

            <button id="btnGraficar" class="btn btn-primary">
              <i class="fas fa-chart-line"></i> Generar gr&aacute;fica
            </button>
        </div>

        <canvas id="grafica" height="100" class="mt-3"></canvas>
      </div>
    </div>

@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    let chart = null;

    $("#paciente").on('change', function() {
        let id = $(this).val();
        if (!id) return;

        $.get(`/paciente/${id}/citas`, function(citas) {
            $("#lista-citas").empty();
            $("#parametros-container").hide();

            citas.forEach(c => {
                $("#lista-citas").append(`
                    <div>
                        <label>
                            <input type="checkbox" class="chk-cita" value="${c.id}">
                            ${c.fecha}
                        </label>
                    </div>
                `);
            });

            $("#citas-container").show();
        });
    });

    $(document).on('change', '.chk-cita', function() {
        let citas = $(".chk-cita:checked").map(function(){ return this.value; }).get();

        if (citas.length === 0) {
            $("#parametros-container").hide();
            return;
        }

        $.post(`/paciente/parametros`, {
            _token: "{{ csrf_token() }}",
            citas: citas
        }, function(parametros) {
            $("#parametro").empty();
            parametros.forEach(p => {
                $("#parametro").append(`<option value="${p}">${p}</option>`);
            });
            $("#parametros-container").show();
        });
    });

    $("#btnGraficar").on('click', function() {
        let parametro = $("#parametro").val();
        let citas = $(".chk-cita:checked").map(function(){ return this.value; }).get();

        $.post(`/paciente/graficar`, {
            _token: "{{ csrf_token() }}",
            parametro: parametro,
            citas: citas
        }, function(data) {

            let labels = data.map(d => d.fecha);
            let valores = data.map(d => parseFloat(d[parametro]));

            if (chart) chart.destroy();

            chart = new Chart(document.getElementById('grafica'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: parametro,
                        data: valores,
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        pointStyle: 'circle',
                        pointBorderWidth: 2,
                        pointHitRadius: 12,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { labels: { font: { size: 14 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${parametro}: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });
        });
    });
  </script>

@endsection
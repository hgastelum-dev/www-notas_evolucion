@extends('layouts.sbadmin')

@section('container')

    {{-- page heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Tablero de seguimiento - D&iacute;a actual</h1>
      {{--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="alert('En construccion...')">
        <i class="fas fa-download fa-sm text-white-50"></i> Generar reporte
      </a>--}}
    </div>

    {{-- content row --}}
    <div class="row">
    
      @php 
        $totalCitas = 0;
      @endphp
      
      @foreach($citaEstados as $citaEstado)

        @php    

          $totalCitas = $totalCitas + count($citaEstado->getCitas);

          switch($citaEstado->id){

            case 1:
              $claseColor = 'primary';
              break;
            case 2:
              $claseColor = 'warning';
              break;
            case 3:
              $claseColor = 'danger';
              break;
            case 4:
              $claseColor = 'success';
              break;
          }
        @endphp

        <div class="col-xl-4 col-md-6 mb-4">
          <div class="card border-left-{{ $claseColor }} shadow h-100 py-2">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-{{ $claseColor }} text-uppercase mb-1">
                              {{ $citaEstado->cita_estado }}</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">
                              {{ count($citaEstado->getCitas) }}
                          </div>
                      </div>
                      <div class="col-auto">
                          <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      @endforeach

      <div class="col-xl-4 col-md-6 mb-4">
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
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
      </div>
      
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                            Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalCitas + $enProgreso }}
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

    <div class="container-fluid">

      <h4>Gráficas de parámetros de medición</h4>

      <label><b>Paciente:</b></label>
      <select id="paciente" class="form-control">
          <option value="">Seleccionar...</option>
          @foreach($pacientes as $p)
              <option value="{{ $p->id }}">{{ $p->nombre_s }} {{ $p->apellido_paterno }}</option>
          @endforeach
      </select>

      <div id="citas-container" style="display:none;">
          <label><b>Fechas de citas concluidas:</b></label>
          <div id="lista-citas" class="mb-3"></div>
      </div>

      <div id="parametros-container" style="display:none;">
          <label><b>Parámetros disponibles:</b></label>
          <select id="parametro" class="form-control mb-3"></select>

          <button id="btnGraficar" class="btn btn-primary">Generar gráfica</button>
      </div>

      <canvas id="grafica" height="100"></canvas>

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
                        legend: {
                            labels: {
                                font: { size: 14 }
                            }
                        },
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
@extends('pacientes.paciente_header')

@section('paciente')

  <style>
    .plan-analisis-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.6rem;
        padding: 1.25rem;
        background: #f8fafc;
        margin-bottom: 1.5rem;
    }

    .plan-analisis-card label {
        font-weight: 700;
        color: #1f2937;
        font-size: 0.95rem;
    }

    .plan-add-trigger {
        border: 1.5px dashed #93c5fd;
        background: #f0f7ff;
        color: #2563eb;
        font-weight: 600;
        width: 100%;
        text-align: left;
        padding: 0.65rem 1rem;
        border-radius: 0.5rem;
    }

    .plan-add-trigger:hover {
        background: #e0edff;
        color: #1d4ed8;
    }

    .plan-nuevo-card {
        border: 1px solid #93c5fd;
        border-radius: 0.6rem;
        margin-top: 0.75rem;
    }

    .plan-table {
        font-size: 0.9rem;
    }

    .plan-group-header th {
        background: #eef2ff;
        border-bottom: 2px solid #c7d2fe;
        padding: 0.6rem 0.75rem;
    }

    .plan-group-titulo {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }

    .plan-serie-badge {
        font-size: 0.75rem;
    }

    .plan-fecha-badge {
        font-size: 0.7rem;
        background: #6b7280;
    }

    .plan-row-hijo {
        background: #fcfcfd;
    }

    .plan-row-hijo td {
        border-top: none;
    }

    .btn-icon-sm {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.4rem;
    }
  </style>

  <div class="plan-analisis-card">
    @if(session('analisisUpdated'))
      <div class="alert alert-success mb-3">
        {{ session('analisisUpdated')['titulo'] }}
      </div>
    @endif
    <form method="post" action="/paciente/plan/inicial/analisis">
      @csrf
      <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
      <label for="plan_inicial_analisis"><i class="fas fa-file-medical-alt"></i> An&aacute;lisis</label>
      <textarea class="form-control" rows="5" name="plan_inicial_analisis" id="plan_inicial_analisis">{{ $paciente->plan_inicial_analisis }}</textarea>
      <div class="text-right mt-2">
        <button class="btn btn-info" type="submit">
          <i class="fas fa-save"></i> Guardar an&aacute;lisis
        </button>
      </div>
    </form>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <button class="plan-add-trigger" type="button" data-toggle="collapse" data-target="#div-nuevo-plan" aria-expanded="false" aria-controls="div-nuevo-plan">
    <i class="fas fa-plus-circle"></i> A&ntilde;adir nuevo diagn&oacute;stico/tratamiento
  </button>

  <div class="collapse" id="div-nuevo-plan">
    <form method="post" action="/paciente/plan">
      @csrf
      <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
      @if(isset($primeraCita))
        @if($primeraCita->en_progreso == 1)
          <input type="hidden" name="cita_inicial_id" value="{{ $primeraCita->id }}">
        @endif
      @endif
      <div class="card card-body plan-nuevo-card">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="tipo_plan_id" class="font-weight-bold">Tipo de plan</label>
              <select class="form-control border border-info rounded" id="tipo_plan_id" name="tipo_plan_id" onchange="obtenerCie10(this.value)" required>
                <option value="">Seleccione una opci&oacute;n</option>
                @foreach($tiposPlaneacion as $tipoPlaneacion)
                  <option value="{{ $tipoPlaneacion->id }}">{{ $tipoPlaneacion->tipo_plan }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="form-group">
              <label for="plan" class="font-weight-bold">Descripci&oacute;n</label>
              <select class="form-control" id="search-result" onchange="copiarTexto(this.value)">
              </select>
              <textarea class="form-control border border-info rounded" rows="8" id="plan" name="plan" required></textarea>
              <div class="text-right mt-2">
                <button class="btn btn-success" type="submit">
                  <i class="fas fa-save"></i> Guardar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <br>

  {{-- agrupado --}}
  <div class="table-responsive">
    <table class="table table-hover table-bordered plan-table">
      <tbody>
        @foreach($planesAgrupado as $key => $planAnidado)

          <tr class="plan-group-header">
            <th colspan="4">
              <span class="badge badge-primary plan-group-titulo">{{ $key }}s</span>
            </th>
          </tr>

          @php
            $consecutivo = 0;
          @endphp

          @foreach($planAnidado as $plan)

            @php
              $consecutivo = $consecutivo + 1;
            @endphp

            <tr class="bg-light" id="tr-tp-{{ $plan->id }}">
              <td class="text-center text-nowrap" style="width: 1px;">

                <button type="button" class="btn btn-sm btn-primary btn-icon-sm" value="{{ $plan->id }}" onclick="getModalEdit(event,this.value)" data-toggle="modal" data-target="#modal-editar" title="Editar">
                  <i class="fas fa-pencil-alt"></i>
                </button>

                <button type="button" class="btn btn-sm btn-danger btn-icon-sm" data-toggle="popover" data-html="true" title="Atento aviso" data-content="Est&aacute; seguro de querer borrar el registro seleccionado y sus subpuntos correspondientes?<br><button class='btn btn-sm btn-danger' onclick='eliminarPlan(this.value)' value='{{ $plan->id }}'>Si</button>&nbsp;<button class='btn btn-sm btn-info'>No</button>">
                  <i class="fas fa-trash-alt"></i>
                </button>

              </td>
              <td class="text-center text-nowrap" style="width: 1px;">
                  @if(session('userAlerts') && session('userAlerts')['icono'] == $plan->id)
                    <span class="badge badge-{{ session('userAlerts')['titulo'] }}">
                      <i class="fas fa-check"></i> {{ session('userAlerts')['mensaje'] }}
                    </span>
                  @endif 
                  <span class="badge badge-primary plan-serie-badge">
                    {{ substr($key, 0, 1) . '-' . $consecutivo }}
                  </span>
                  <span class="badge plan-fecha-badge text-white">
                    {{ $plan->created_at->format('Y-m-d') }}
                  </span>
              </td>
              <td class="font-weight-bold">{{ $plan->plan }}</td>
              <td class="text-center" style="width: 1px;">
                <button type="button" class="btn btn-sm btn-success btn-icon-sm" data-toggle="modal" data-target="#modal-subpunto-{{ $plan->id }}" title="Agregar subpunto">
                  <i class="fas fa-plus"></i>
                </button>
              </td>
            </tr>

            <div class="modal fade" id="modal-subpunto-{{ $plan->id }}" tabindex="-1" aria-labelledby="{{ $plan->id }}-ModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="{{ $plan->id }}-ModalLabel">
                      <i class="fas fa-plus-circle"></i> Agregar subpunto
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post" action="/paciente/plan">

                  @csrf
                  <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                  <input type="hidden" name="padre_id" value="{{ $plan->id }}">
                  <input type="hidden" name="tipo_plan_id" value="{{ $plan->getTipoPlan->id }}">
                  @if(isset($primeraCita))
                    <input type="hidden" name="cita_inicial_id" value="{{ $primeraCita->id }}">
                  @endif
                  <div class="modal-body">
                    <h5>
                      <span class="badge badge-info">{{ $plan->getTipoPlan->tipo_plan }}:</span>
                      {{ $plan->plan }}
                    </h5>
                    <hr>
                    <label class="font-weight-bold">Observaciones:</label>
                    <textarea class="form-control border border-info rounded" name="plan" rows="12" required></textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-save"></i> Guardar
                    </button>
                  </div>
                  </form>
                </div>
              </div>
            </div>{{-- aqui cierra la ventana modal --}}

            @php
              $subconsecutivo = 0;
            @endphp

            @foreach($plan->getTipoPlanAnidado as $planHijo)

            @php
              $subconsecutivo = $subconsecutivo + 1;
            @endphp

            <tr id="tr-tp-{{ $planHijo->id }}" class="plan-row-hijo">
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-info btn-icon-sm" value="{{ $planHijo->id }}" onclick="getModalEdit(event,this.value)" data-toggle="modal" data-target="#modal-editar" title="Editar">
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning btn-icon-sm" data-toggle="popover" data-html="true" title="Atento aviso" data-content="Est&aacute; seguro de querer borrar el registro seleccionado?<br><button class='btn btn-sm btn-danger' onclick='eliminarPlan(this.value)' value='{{ $planHijo->id }}'>Si</button>&nbsp;<button class='btn btn-sm btn-info'>No</button>">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
              <td class="text-center text-nowrap">
                @if(session('userAlerts') && session('userAlerts')['icono'] == $planHijo->id)
                  <span class="badge badge-{{ session('userAlerts')['titulo'] }}">
                    <i class="fas fa-check"></i> {{ session('userAlerts')['mensaje'] }}
                  </span>
                @endif 
                <span class="badge badge-info plan-serie-badge">
                  {{ substr($key, 0, 1) . '-' . $consecutivo }}.{{ $subconsecutivo }}
                </span>
              </td>
              <td colspan="2">{{ $planHijo->plan }}</td>
            </tr>
            @endforeach
          @endforeach
        @endforeach
      </tbody>
    </table>
  </div>
  {{-- termina agrupado --}}

  {{-- modal para modificar un registro plan --}}
  <div class="modal fade" id="modal-editar" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editarModalLabel">
            <i class="fas fa-pencil-alt"></i> Modificar registro
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="/paciente/plan/update">

        @csrf
        <div class="modal-body">
          <label class="font-weight-bold">Observaciones:</label>
          <input type="hidden" id="plan_id_edit" name="plan_id_edit" value="">
          <textarea class="form-control border border-info rounded" name="plan_edit" id="plan_edit" rows="12" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar cambios
          </button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <form method="post" action="/paciente/plan/delete" id="form-delete-plan">
    @csrf
    <input type="hidden" name="cita_planeacion_id" id="cita_planeacion_id">
  </form>

@endsection

@section('scripts')
  
  <script type="text/javascript" src="{{ asset('lib-tmp/js/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

  <script type="text/javascript">

    @if(session('avisoInicioCita'))

      Swal.fire({
          position: 'top-end',
          icon: '{!! session('avisoInicioCita')['icono'] !!}',
          title: '{!! session('avisoInicioCita')['titulo'] !!}',
          html: '{!! session('avisoInicioCita')['mensaje'] !!}',
          showConfirmButton: true,
          confirmButtonText: 'Cerrar aviso'
        });
    @endif

    $(function () {
      $('[data-toggle="popover"]').popover({sanitize: false})
    })

    function copiarTexto(texto){
      document.getElementById('plan').value = '';
      document.getElementById('plan').value = texto;
    }

    function obtenerCie10(tipo_plan_id){
      if(tipo_plan_id == 2){
        
        $("#plan").on("keyup", function(){
        var search = $(this).val();
        
        if (search !=="") {
          $.ajax({
            url:"/ajax/search",
            type:"POST",
            cache:false,
            data:{
              _token: '{{ csrf_token() }}',
              term:search
            },
            success:function(data){
              var x = document.getElementById("search-result");
              x.innerHTML = ''

              var option = document.createElement("option");
                option.value = '';
                option.text = 'Seleccione una opcion';
                x.add(option);
              for(var i = 0; i < data.length; i++){
                var option = document.createElement("option");
                option.value = data[i].letra + ' - ' + data[i].codigo + ' - ' + data[i].diagnostico;
                option.text = data[i].letra + ' - ' + data[i].codigo + ' - ' + data[i].diagnostico;
                x.add(option);
              }
              $("#search-result").fadeIn();
            }  
          });
        } else {
          $("#search-result").html("");  
          $("#search-result").fadeOut();
        }
      });
      
      $(document).on("click","li", function(){
        $('#plan').val($(this).text());
        $('#search-result').fadeOut("fast");
      });
      }
    }

    function getModalEdit(e,planId){
      
      var table = document.getElementsByTagName("table")[0];
      var tbody = table.getElementsByTagName("tbody")[0];
      
          e = e || window.event;
          var data = [];
          var target = e.srcElement || e.target;
          while (target && target.nodeName !== "TR") {
              target = target.parentNode;
          }
          if (target) {
              var cells = target.getElementsByTagName("td");
              
              document.getElementById('plan_id_edit').value = planId; 
              document.getElementById('plan_edit').value = cells[2].innerHTML; 
          }
    }

    function eliminarPlan(planId){
      
      document.getElementById('cita_planeacion_id').value = planId;

      document.getElementById("form-delete-plan").submit();
    }
  </script>
@endsection
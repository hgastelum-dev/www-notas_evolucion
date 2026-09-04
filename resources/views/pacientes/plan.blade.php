@extends('pacientes.paciente_header')

@section('paciente')

  <div class="mb-3">

    <div class="text-center">
      @if(session('analisisUpdated'))
        <div class="alert alert-success">
          {{ session('analisisUpdated')['titulo'] }}
        </div>
      @endif
      <form method="post" action="/paciente/plan/inicial/analisis">
        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
        <b>Analisis</b>:<br>
        <textarea class="form-control" rows="5" name="plan_inicial_analisis">{{ $paciente->plan_inicial_analisis }}</textarea><br>
        <button class="btn btn-info" type="submit">Guardar analisis</button>
      </form>
    </div>
  
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <p>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#div-nuevo-plan" aria-expanded="false" aria-controls="div-nuevo-plan">
          A&ntilde;adir nuevo diagnostico/tratamiento
        </button>
    </p>

    <div class="collapse" id="div-nuevo-plan">
      <form method="post" action="/paciente/plan">
        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
        @if(isset($primeraCita))
          @if($primeraCita->en_progreso == 1)
            <input type="hidden" name="cita_inicial_id" value="{{ $primeraCita->id }}">
          @endif
        @endif
        <div class="card card-body">
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
                <br>
                <p class="text-center">
                  <button class="btn btn-success" type="submit">
                    Guardar
                  </button>    
                </p>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    {{-- agrupado --}}
    <table class="table table-hover table-bordered">
      <tbody>
        @foreach($planesAgrupado as $key => $planAnidado)

          <tr class="text-center bg-primary text-white">
            <th colspan="4">
              <h3>
                <span class="badge badge-info">{{ $key }}s</span>
              </h3>
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

                <button type="button" class="btn btn-sm btn-primary" value="{{ $plan->id }}" onclick="getModalEdit(event,this.value)" data-toggle="modal" data-target="#modal-editar">
                  <i class="fas fa-pencil-alt"></i>
                </button>

                <button type="button" class="btn btn-sm btn-danger" data-toggle="popover" data-html="true" title="Atento aviso" data-content="Est&aacute; seguro de querer borrar el registro seleccionado y sus subpuntos correspondientes?<br><button class='btn btn-sm btn-danger' onclick='eliminarPlan(this.value)' value='{{ $plan->id }}'>Si</button>&nbsp;<button class='btn btn-sm btn-info'>No</button>">
                  <i class="fas fa-trash-alt"></i>
                </button>

              </td>
              <td class="text-center text-nowrap" style="width: 1px;">
                  @if(session('userAlerts') && session('userAlerts')['icono'] == $plan->id)
                    <span class="badge badge-{{ session('userAlerts')['titulo'] }}">
                      <i class="fas fa-check"></i> {{ session('userAlerts')['mensaje'] }}
                    </span>
                  @endif 
                  <span class="badge badge-primary">
                    {{ substr($key, 0, 1) . '-' . $consecutivo }}
                  </span>
                  <span class="badge badge-dark">
                    {{ $plan->created_at->format('Y-m-d') }}
                  </span>
              </td>
              <td class="font-weight-bold">{{ $plan->plan }}</td>
              <td class="text-center" style="width: 1px;">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-subpunto-{{ $plan->id }}">
                  <i class="fas fa-plus"></i>
                </button>
              </td>
            </tr>

            <div class="modal fade" id="modal-subpunto-{{ $plan->id }}" tabindex="-1" aria-labelledby="{{ $plan->id }}-ModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="{{ $plan->id }}-ModalLabel">
                      Agregar subpunto
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
                    <h3>
                      <span class="badge badge-info">{{ $plan->getTipoPlan->tipo_plan }}:</span>
                      {{ $plan->plan }}
                    </h3>
                    <br>
                    <b>Observaciones:</b>
                    <br>
                    <textarea class="form-control border border-info rounded" name="plan" rows="12" required></textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
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
            
            <tr id="tr-tp-{{ $planHijo->id }}">
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-info" value="{{ $planHijo->id }}" onclick="getModalEdit(event,this.value)" data-toggle="modal" data-target="#modal-editar">
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning" data-toggle="popover" data-html="true" title="Atento aviso" data-content="Est&aacute; seguro de querer borrar el registro seleccionado?<br><button class='btn btn-sm btn-danger' onclick='eliminarPlan(this.value)' value='{{ $planHijo->id }}'>Si</button>&nbsp;<button class='btn btn-sm btn-info'>No</button>">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
              <td class="text-center text-nowrap">
                @if(session('userAlerts') && session('userAlerts')['icono'] == $planHijo->id)
                  <span class="badge badge-{{ session('userAlerts')['titulo'] }}">
                    <i class="fas fa-check"></i> {{ session('userAlerts')['mensaje'] }}
                  </span>
                @endif 
                <span class="badge badge-info">
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
    {{-- termina agrupado --}}

    {{-- modal para modificar un registro plan --}}
    <div class="modal fade" id="modal-editar" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarModalLabel">
              Modificar registro
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="/paciente/plan/update">

          @csrf
          <div class="modal-body">
            <b>Observaciones:</b>
            <br>
            <input type="hidden" id="plan_id_edit" name="plan_id_edit" value="">
            <textarea class="form-control border border-info rounded" name="plan_edit" id="plan_edit" rows="12" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
          </form>
        </div>
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
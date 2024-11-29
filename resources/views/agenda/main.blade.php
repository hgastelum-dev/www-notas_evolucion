@extends('layouts.sbadmin')

@section("styles")

<link href='{{ asset("lib-tmp/fullcalendar-4.4.3/packages/core/main.min.css") }}' rel='stylesheet' />
<link href='{{ asset("lib-tmp/fullcalendar-4.4.3/packages/daygrid/main.min.css") }}' rel='stylesheet' />
<link href='{{ asset("lib-tmp/fullcalendar-4.4.3/packages/timegrid/main.min.css") }}' rel='stylesheet' />
<link rel="stylesheet" type="text/css" href="{{ asset('lib-tmp/js/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('lib-tmp/js/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('lib-tmp/js/select2-themes/select2-bootstrap-5-theme.min.css') }}" />

<style type="text/css">
  .fc-event-time, .fc-event-title {
      padding: 0 1px;
      white-space: nowrap;
  }

  .fc-title {
      white-space: normal;
  }
</style>

@endsection

@section('container')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-calendar-alt"></i> Agenda de citas
        </h6>
    </div>
    <div class="card-body">

    {{-- semaforizacion de citas... datos informativos --}}
    <table class="table">
      <tr class="text-center">
        <th colspan="{{ count($citaEstados) }}">
          <i class="fas fa-sync-alt"></i> Semaforizaci&oacute;n de citas por color
        </th>
      </tr>
      <tr>
      @foreach($citaEstados as $citaEstado)
        <td>
          <i class="fas fa-lightbulb" style="color: {{ $citaEstado->class_color }}"></i> = {{ $citaEstado->cita_estado }}
        </td>
      @endforeach
      </tr>
    </table>

    {{-- botones de accion --}}
    <p>
        <button id="button-nueva-sesion" class="btn btn-success">
            <i class="far fa-edit"></i> Programar citas
        </button> 

        {{-- boton oculto: sin funcionalidad --}}
        <button id="button-sesion-recurrente" class="btn btn-info d-none">
            <i class="far fa-edit"></i> Sesiones recurrentes
        </button>

        {{-- boton oculto: sin funcionalidad --}}
        <button id="copy-paste-button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#trasladarCalendarioModal">
            <i class="far fa-copy"></i> Copiar agenda a la semana siguiente
        </button> 

        {{-- boton oculto: sin funcionalidad --}}
        <button id="delete-weekend-button" class="btn btn-danger d-none" data-bs-toggle="modal" data-bs-target="#eliminarSemanaModal">
            <i class="fas fa-trash-alt"></i>
        </button>

        <button id="recargar-fc" class="btn btn-primary">
            <i class="fas fa-sync-alt"></i>
        </button>  
    </p>

    {{-- modal para programar sesiones al paciente --}}
    <div class="modal fade" id="modalSesionNueva" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalSesionNuevaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        
        <div class="modal-header">
            <h5 class="modal-title" id="modalSesionNuevaLabel">
            <i class="fas fa-pencil-alt"></i> Programaci&oacute;n de sesiones
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <p>
                <i class="fas fa-search"></i> Seleccione un paciente<br>
            </p>
            <select class="form-control" id="paciente_id" onchange="getPaciente(this.value)">
            
            <option value="">Busqueda de pacientes</option>
            
            @foreach($pacientes as $paciente)
                
                <option value="{{ $paciente->id }}">
                {{ $paciente->nombre_s }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}
                </option>
            
            @endforeach
            </select>

            <div class="d-none" id="inputs-nva-sesion">
            <br>
            <div class="row">
                <div class="col text-nowrap">
                    <i class="fas fa-calendar-alt"></i> Fecha<br><br> 
                    <input type="text" id="fecha-nueva" onkeydown="return false;" class="form-control rounded-pill datetimepicker-input" data-toggle="datetimepicker" data-target="#fecha-nueva" placeholder="Fecha" aria-label="Fecha">
                </div>
                
                <div class="col text-nowrap">
                    <i class="fas fa-calendar-alt"></i> Hora de inicio<br><br> 
                    <input type="text" id="hora-inicia-nueva" onkeydown="return false;" class="form-control rounded-pill datetimepicker-input" data-toggle="datetimepicker" data-target="#hora-inicia-nueva" placeholder="Hora de inicio" aria-label="Hora de inicio">
                </div>

                
            </div>
            
            <p class="text-center">
                <br>
                <button class="btn btn-success" id="campos-nva-sesion">
                <i class="fas fa-arrow-down"></i> Agregar sesi&oacute;n
                </button>  
            </p>

            <div id="listado-nuevas">
            </div>

            <div id="userAlertNuevas"></div>
            </div>
            
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="validar-guardar">
            <i class="fas fa-check"></i> Validar disponibilidad y agendar sesiones
            </button>
        
        </div>
        </div>
    </div>
    </div>

{{-- modal para programar sesiones recurrentes al paciente --}}
<div class="modal fade" id="modalSesionesRecurrentes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sesionRecurrenteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sesionRecurrenteModalLabel">Programaci&oacute;n de sesiones recurrentes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <select class="form-control" id="paciente_recurrente_id">
              <option value="">Busqueda de pacientes</option>
              
              @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id }}">
                  {{ $paciente->nombre_s }}
                </option>
              @endforeach

            </select>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col">
            <input type="text" onkeypress="return false;" class="form-control rounded-pill datetimepicker-input" data-toggle="datetimepicker" data-target="#inicia-recurrencia" placeholder="Inicia" aria-label="Inicia" id="inicia-recurrencia">
          </div>
          <div class="col">
            <input type="text" onkeypress="return false;" class="form-control rounded-pill datetimepicker-input" data-toggle="datetimepicker" data-target="#termina-recurrencia" placeholder="Termina" aria-label="Termina" id="termina-recurrencia">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control rounded-pill datetimepicker-input" data-toggle="datetimepicker" data-target="#hora-inicia-recurrencia" placeholder="00:00" aria-label="00:00" id="hora-inicia-recurrencia">
          </div>
          <div class="col">
            <input type="text" class="form-control rounded-pill datetimepicker-input" data-toggle="datetimepicker" data-target="#hora-termina-recurrencia" placeholder="00:00" aria-label="00:00" id="hora-termina-recurrencia">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="vista-previa-recurrencia">Agendar</button>
      </div>
    </div>
  </div>
</div>

{{-- modal para modificar una sesion del paciente --}}
<div class="modal fade" id="modalSesionCambio" tabindex="-1" aria-labelledby="modalCambioLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCambioLabel">
          <span class="badge text-white bg-warning">Reprogramar</span> / <span class="badge text-white bg-danger">Cancelar</span> / <span class="badge text-white bg-success">Concluir</span> sesi&oacute;n
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <input type="hidden" id="idSesionCambio" value="">

      <div class="modal-body">

        <div class="text-center">
          
          <form method="post" action="/cita/iniciar">
            @csrf
            <input type="hidden" value="" id="sesion-id-iniciar" name="cita_id">
            <button class="btn btn-success" type="submit" id="btn-iniciar-cita" onclick="this.classList.add('d-none')">
              Atender cita
            </button>
            <a class="btn btn-primary d-none" id="link-editar">
              <i class="fas fa-pencil-alt"></i> Modificar
            </a>
          </form>
        </div>

        <div class="row text-right">
          <div class="col-sm-12">
            <p>
              <button class="btn btn-danger" id="eliminar-sesion-db">
                <i class="fas fa-trash-alt"></i>
              </button>
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <h4>
              <b><i class="far fa-user"></i> Paciente:</b>
            </h4>
            <br>
            <h5>
              <p id="pacienteCambio"></p>
            </h5>
          </div>
          <div class="col-sm-6">
            <h4>
              <b><i class="fas fa-info-circle"></i> Estado de la sesi&oacute;n:</b>
            </h4>
            <br>
            <h5>
              <p id="statusCambio"></p>
            </h5>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <h4>
              <b><i class="far fa-calendar-alt"></i> Fecha:</b>
            </h4>
            <br>
            <h5>
              <p>
                <input type="text" onkeydown="return false;" class="form-control rounded-pill datetimepicker-input" id="fechaCambio" data-toggle="datetimepicker" data-target="#fechaCambio" value="">
              </p>
            </h5>
          </div>
          <div class="col-sm-6">
            <h4>
              <b><i class="far fa-clock"></i> Hora de inicio:</b>
            </h4>
            <br>
            <h5>
              <p>
                <input type="text" onkeydown="return false;" class="form-control rounded-pill datetimepicker-input" id="horaInicioCambio" data-toggle="datetimepicker" data-target="#horaInicioCambio" value="">
              </p>
            </h5>
          </div>
          <div class="col-sm-12" id="userAlertCambio"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="ajuste-sesion-boton">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal para transferir programacion a semanas posteriores --}}
<div class="modal fade" id="trasladarCalendarioModal" tabindex="-1" aria-labelledby="trasladarModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="width: 100%;">
        <h5 class="modal-title" id="trasladarModalLabel" style="width: 100%;">
          <div class="alert alert-info text-center text-uppercase" style="width: 100%;">
            <b>
              <div class="spinner-grow text-warning" role="status">
                <span class="visually-hidden">Loading...</span>
              </div> Atento aviso:
            </b> Es&aacute; seguro de querer realizar una copia de las sesiones programadas
            <br><br>
            De la semana 
            <h3>
              <span id="semana-origen" class="badge bg-success"></span>
            </h3> <br>
            A la semana<br>
            <h3>
              <span id="semana-destino" class="badge bg-success"></span>
            </h3>
            <h1>
              <i class="fas fa-question-circle"></i>
            </h1>
          </div>
        </h5>
      </div>
      <div class="modal-body">
        
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Paciente</th>
              <th>Inicio</th>
              <th>Termino</th>
              <th>Estado</th>
            </tr>  
          </thead>
          <tbody id="sesiones-traslado"></tbody>
        </table>
      </div>
      <div class="modal-footer text-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> No, no copiar
        </button>
        
        <button type="button" class="btn btn-primary" id="btn-trans-sesiones" onclick="copyPasteSesiones()">
          <i class="far fa-copy"></i> Si, proceder a realizar la copia de la programaci&oacute;n semanal 
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Modal para eliminar toda la programacion semanal --}}
<div class="modal fade" id="eliminarSemanaModal" tabindex="-1" aria-labelledby="deleteWeekModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteWeekModalLabel">
          <i class="fas fa-exclamation-circle"></i> Atento aviso
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Est&aacute; seguro de querer eliminar todas las sesiones contenidas en la semana del <b id="semana-origen-delete" class="text-info"></b> al <b id="semana-destino-delete" class="text-info"></b>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
          <i class="fas fa-arrow-left"></i> No, cancelar petici&oacute;n de borrado
        </button>
        <button type="button" class="btn btn-danger" id="btn-eliminar-semana">
          Si, proceder <i class="far fa-trash-alt"></i>
        </button>
      </div>
    </div>
  </div>
</div>




        {{-- fullCalendar --}}
        <div id='calendar'></div>
    </div>
</div>

@endsection

@section("scripts")

<script src="{{ asset('lib-tmp/js/moment/min/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('lib-tmp/js/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('lib-tmp/js/fullcalendar-4.4.3/packages/core/main.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib-tmp/js/fullcalendar-4.4.3/packages/interaction/main.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib-tmp/js/fullcalendar-4.4.3/packages/daygrid/main.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib-tmp/js/fullcalendar-4.4.3/packages/timegrid/main.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib-tmp/js/fullcalendar-4.4.3/resources/resources-common/main.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib-tmp/js/fullcalendar-4.4.3/resources/resources-daygrid/main.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib-tmp/js/fullcalendar-4.4.3/resources/resources-timegrid/main.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib-tmp/js/fullcalendar-4.4.3/packages/core/locales/es.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('lib-tmp/js/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('lib-tmp/js/select2/dist/js/select2.full.min.js') }}"></script>

@if(session('userAlerts'))
<script type="text/javascript">
  Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: '¡Aviso!',
          html: '{!! session('userAlerts')['mensaje'] !!} <br><br> {!! session('userAlerts')['icono'] !!}',
          showConfirmButton: true,
          confirmButtonText: 'Cerrar aviso'
        });
</script>
@endif
<script type="text/javascript" src="{{ asset('agenda.js') }}"></script>

@endsection
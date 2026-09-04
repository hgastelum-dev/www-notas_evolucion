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
        <tr class="text-center bg-info text-white">
          <th colspan="{{ count($citaEstados) + 1 }}">
            <i class="fas fa-sync-alt"></i> Semaforizaci&oacute;n de citas por color
          </th>
        </tr>
        <tr>
          @foreach($citaEstados as $citaEstado)
            <td>
              <i class="fas fa-lightbulb" style="color: {{ $citaEstado->class_color }}"></i> = {{ $citaEstado->cita_estado }}
            </td>
          @endforeach
          <td>
            <i class="fas fa-lightbulb" style="color: purple;"></i> = En progreso
          </td>
        </tr>
      </table>

      {{-- botones de accion --}}
      <p>
        <button id="button-nueva-sesion" class="btn btn-success">
          <i class="far fa-edit"></i> Programar citas
        </button>

        <button id="recargar-fc" class="btn btn-primary">
          <i class="fas fa-sync-alt"></i> Actualizar agenda
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
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseNvoPaciente" aria-expanded="false" aria-controls="collapseNvoPaciente">
                  Registrar paciente
                </button>
              </p>
              <div class="collapse" id="collapseNvoPaciente">
                <div class="card card-body">
                  <form method="post" action="/agenda/paciente/registrar">
                      @csrf
                      <div class="form-group row">
                        <label for="nombre_s" class="col-sm-2 col-form-label">Nombre(s)</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="nombre_s" name="nombre_s" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="apellido_paterno" class="col-sm-2 col-form-label">Apellido paterno</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="apellido_materno" class="col-sm-2 col-form-label">Apellido materno</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
                        </div>
                      </div>
                      
                      <div class="form-group row text-center">
                        <div class="col-sm-12">
                          <button type="submit" class="btn btn-primary">
                            Guardar
                          </button>
                        </div>
                      </div>
                  </form>
                </div>
              </div>
                
              <br>
              <p>
                  <i class="fas fa-search"></i> Seleccione un paciente<br>
              </p>
              @if(session('PacienteRegistrado'))
                <p>
                  <div class="alert alert-success">
                    {{ session('PacienteRegistrado')['titulo'] }}. Ahora puede seleccionar al paciente recien registrado para programarlo en el calendario de citas.
                  </div>
                </p>
              @endif

              <select class="form-control" id="paciente_id" onchange="getPaciente(this.value)">

                <option value="">Búsqueda de pacientes</option>

                @foreach($pacientes as $paciente)
                  <option value="{{ $paciente->id }}"
                    @if(isset(session('PacienteRegistrado')['mensaje']) && session('PacienteRegistrado')['mensaje'] == $paciente->id) selected @endif>
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
            </div> {{-- aqui cierra modal-body --}}
          
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="validar-guardar">
                  <i class="fas fa-check"></i> Validar disponibilidad y agendar sesiones
                </button>
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
                    
                    <button
                        class="btn btn-success"
                        type="submit"
                        id="btn-iniciar-cita"
                        onclick="this.classList.add('d-none')"
                        @cannot('CitaAtender') disabled @endcannot
                    >
                        Atender cita
                    </button>
                    <a class="btn btn-primary d-none" id="link-editar">
                      Modificar
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

      <div class="form-group" style="max-width: 300px;">
        <label for="doctor_id_agenda">
          <i class="fas fa-user-md"></i> Ver agenda de
        </label>
        <select class="form-control" id="doctor_id_agenda">
          @foreach($doctores as $doctor)
            <option value="{{ $doctor->id }}" {{ $doctor->id == $doctorActualId ? 'selected' : '' }}>
              {{ $doctor->name }}
            </option>
          @endforeach
        </select>
      </div>
      {{-- fullcalendar --}}
      <div id='calendar'></div>
    </div>
  </div> {{-- aqui se cierra la card principal --}}

  @if(session('CitaInicialOpciones'))

    {{-- modal para seleccion de opciones cuando es una primera cita --}}
    <div class="modal fade" id="modalInicialOpciones" tabindex="-1" role="dialog" aria-labelledby="modalInicialLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalInicialLabel">
              Aviso de cita inicial
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            {!! session('CitaInicialOpciones')['mensaje'] !!}

            <p class="text-center">
              ¿Qu&eacute; desea hacer a continuaci&oacute;n?
            </p>
            <p class="text-center">
              <form class="text-center" method="post" action="/iniciar/plan-inicial">

                @csrf
                <input type="hidden" name="cita_id" value="{{ session('CitaInicialOpciones')['titulo'] }}">
                <button class="btn btn-primary" type="submit" onclick="this.classList.add('d-none')">
                  Historia cl&iacute;nica
                </button>
              </form>
            </p>
            <p class="text-center">
              <form class="text-center" method="post" action="/iniciar/soap">

                @csrf
                <input type="hidden" name="cita_id" value="{{ session('CitaInicialOpciones')['titulo'] }}">
                <button class="btn btn-info" type="submit" onclick="this.classList.add('d-none')">
                  Nota de evoluci&oacute;n
                </button>
              </form>
            </p>
          </div>
        </div>
      </div>
    </div>
  @endif

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
              html: `{!! session('userAlerts')['mensaje'] !!} <br><br> {!! session('userAlerts')['icono'] !!}`,
              showConfirmButton: true,
              confirmButtonText: 'Cerrar aviso'
      });
    </script>
  @endif

  @if(isset(session('PacienteRegistrado')['mensaje']))
    <script type="text/javascript">
      var modalSesionNueva = new bootstrap.Modal(document.getElementById('modalSesionNueva'));

      modalSesionNueva.show();

      getPaciente(document.getElementById('paciente_id').value);
    </script>
  @endif

  @if(session('CitaInicialOpciones'))
    <script type="text/javascript">
      var modalInicialOpciones = new bootstrap.Modal(document.getElementById('modalInicialOpciones'));

      modalInicialOpciones.show();
    </script>
  @endif
  
  <script type="text/javascript" src="{{ asset('agenda.js') }}"></script>

@endsection
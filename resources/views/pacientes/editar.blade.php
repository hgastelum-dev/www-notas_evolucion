@extends('pacientes.paciente_header')

@section('styles')

  <style type="text/css">
    .form-label{
      font-weight: bold;
    }

    .seguro-badge {
      font-size: .9rem;
      margin-right: 6px;
      margin-bottom: 6px;
      display: inline-block;
      padding: .5em .9em;
    }

    .seguro-badge .quitar-seguro {
      text-decoration: none;
      font-weight: bold;
    }

    .seguro-badge .quitar-seguro:hover {
      opacity: .7;
    }

    .ficha-section-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.6rem;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .ficha-section-header {
        background: #eef2ff;
        color: #1f2937;
        padding: 0.65rem 1.25rem;
        font-weight: 700;
        font-size: 0.95rem;
        border-bottom: 1px solid #e0e7ff;
    }

    .ficha-section-body {
        padding: 1.25rem;
    }

    .foto-preview-box {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    #foto-vista-previa {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
    }
  </style>

@endsection

@section('paciente')

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(session('userAlerts'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>{{ session('userAlerts')['titulo'] }}</strong> {{ session('userAlerts')['mensaje'] }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  <form method="post" action="/paciente/update" enctype="multipart/form-data" id="pacienteForm">

    @csrf
    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

    {{-- datos personales --}}
    <div class="ficha-section-card">
      <div class="ficha-section-header"><i class="fas fa-id-card"></i> Datos personales</div>
      <div class="ficha-section-body">

        <div class="row g-3">
          <div class="col-md-8">
            <label for="nombre_s" class="form-label">Nombre(s)</label>
            <input type="text" class="form-control" id="nombre_s" name="nombre_s" value="{{ $paciente->nombre_s }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Foto</label>
            <div class="foto-preview-box">
              <img src="{{ asset('img/blank.png') }}" class="rounded" alt="Foto del paciente" id="foto-vista-previa">
              <input type="file" id="foto_path" name="foto_path" accept="image/*" onchange="getFotoPreview(event)" class="form-control-file">
            </div>
          </div>
        </div>

        <div class="row g-3 mt-2">
          <div class="col-md-4">
            <label for="apellido_paterno" class="form-label">Apellido paterno</label>
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ $paciente->apellido_paterno }}">
          </div>

          <div class="col-md-4">
            <label for="apellido_materno" class="form-label">Apellido materno</label>
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{ $paciente->apellido_materno }}">
          </div>
        </div>

        <div class="row g-3 mt-2">
          <div class="col-md-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control datetimepicker-input" id="fecha_nacimiento" name="fecha_nacimiento" data-toggle="datetimepicker" data-target="#fecha_nacimiento" value="{{ substr($paciente->fecha_nacimiento, 0, 10) }}">
            @if($paciente->fecha_nacimiento)
              @php
                $fecha_nacimiento_obj = new DateTime(substr($paciente->fecha_nacimiento, 0, 10));
                $hoy = new DateTime();
                $edad = $hoy->diff($fecha_nacimiento_obj)->y;
              @endphp
              <span class="badge badge-success mt-1"><b>Edad:</b> {{ $edad }} a&ntilde;os</span>
            @endif
          </div>

          <div class="col-md-3">
            <label for="cat_genero_id" class="form-label">G&eacute;nero</label>
            <select class="form-control" id="cat_genero_id" name="cat_genero_id">
              <option value="">Seleccione una opci&oacute;n</option>
              <option value="1" @if($paciente->genero_id == 1) {!! 'selected' !!} @endif>Masculino</option>
              <option value="2" @if($paciente->genero_id == 2) {!! 'selected' !!} @endif>Femenino</option>
            </select>
          </div>

          <div class="col-md-2">
            <label for="tipo_sangre" class="form-label">Tipo de sangre</label>
            <input type="text" class="form-control" id="tipo_sangre" name="tipo_sangre" value="{{ $paciente->tipo_sangre }}">
          </div>

          <div class="col-md-4">
            <label for="lugar_nacimiento" class="form-label">Lugar de nacimiento</label>
            <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento" value="{{ $paciente->lugar_nacimiento }}">
          </div>
        </div>
      </div>
    </div>

    {{-- contacto --}}
    <div class="ficha-section-card">
      <div class="ficha-section-header"><i class="fas fa-address-book"></i> Contacto</div>
      <div class="ficha-section-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="email" class="form-label">Correo electr&oacute;nico</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ $paciente->email }}">
          </div>

          <div class="col-md-3">
            <label for="telefono" class="form-label">Tel&eacute;fono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $paciente->telefono }}">
          </div>

          <div class="col-md-5">
            <label for="direccion" class="form-label">Direcci&oacute;n</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $paciente->direccion }}">
          </div>
        </div>

        <div class="row g-3 mt-2">
          <div class="col-md-5">
            <label for="lugar_residencia" class="form-label">Lugar donde reside</label>
            <input type="text" class="form-control" id="lugar_residencia" name="lugar_residencia" value="{{ $paciente->lugar_residencia }}">
          </div>
        </div>
      </div>
    </div>

    {{-- seguros medicos --}}
    <div class="ficha-section-card">
      <div class="ficha-section-header"><i class="fas fa-file-medical"></i> Seguros m&eacute;dicos</div>
      <div class="ficha-section-body">

        <div id="seguros-badges" class="mb-2">
          @forelse($segurosPaciente as $seguro)
            <span class="badge badge-pill badge-info seguro-badge" data-id="{{ $seguro->id }}">
              {{ $seguro->nombre }}
              <a href="#" class="text-white ml-2 quitar-seguro" data-id="{{ $seguro->id }}" title="Quitar seguro">&times;</a>
            </span>
          @empty
            <span class="text-muted" id="sin-seguros-msg">Sin seguros m&eacute;dicos registrados</span>
          @endforelse
        </div>

        <div class="form-row align-items-center">
          <div class="col-auto" style="min-width: 260px;">
            <select class="form-control" id="seguro_medico_select">
              <option value="">Seleccione un seguro para agregar</option>
              @foreach($segurosCatalogo as $seguro)
                @if(!$segurosPaciente->contains('id', $seguro->id))
                  <option value="{{ $seguro->id }}">{{ $seguro->nombre }}</option>
                @endif
              @endforeach
            </select>
          </div>
          <div class="col-auto">
            <button class="btn btn-outline-primary" type="button" id="btn-agregar-seguro">
              <i class="fas fa-plus"></i> Agregar
            </button>
          </div>
        </div>

        <button type="button" class="btn btn-link pl-0 mt-1" data-toggle="modal" data-target="#modalNuevoSeguro">
          <i class="fas fa-plus-circle"></i> &iquest;No aparece en la lista? Registrar nuevo seguro
        </button>
      </div>
    </div>

    {{-- informacion adicional --}}
    <div class="ficha-section-card">
      <div class="ficha-section-header"><i class="fas fa-circle-info"></i> Informaci&oacute;n adicional</div>
      <div class="ficha-section-body">

        <div class="row g-3">
          <div class="col-md-3">
            <label for="fecha_ingreso" class="form-label">Fecha de primera cita</label>
            <input type="date" class="form-control datetimepicker-input" id="fecha_ingreso" name="fecha_ingreso" data-toggle="datetimepicker" data-target="#fecha_ingreso" value="{{ substr($paciente->fecha_ingreso, 0, 10) }}">
          </div>

          <div class="col-md-3">
            @php
              if($paciente->estado_civil_id == 1){
                $edoCivil = 'Soltero';
              } elseif ($paciente->estado_civil_id == 2){
                $edoCivil = 'Casado';
              } else {
                $edoCivil = $paciente->estado_civil_id;
              }
            @endphp
            <label for="cat_estado_civil_id" class="form-label">Estado civil</label>
            <input type="text" class="form-control" id="cat_estado_civil_id" name="cat_estado_civil_id" value="{{ $edoCivil }}">
          </div>

          <div class="col-md-3">
            <label for="escolaridad" class="form-label">Escolaridad</label>
            <input type="text" class="form-control" id="escolaridad" name="escolaridad" value="{{ $paciente->escolaridad }}">
          </div>

          <div class="col-md-3">
            <label for="ocupacion" class="form-label">Ocupaci&oacute;n</label>
            <input type="text" class="form-control" id="ocupacion" name="ocupacion" value="{{ $paciente->ocupacion }}">
          </div>
        </div>

        <div class="row g-3 mt-2">
          <div class="col-md-3">
            <label for="cat_procedencia_id" class="form-label">Procedencia</label>
            <select class="form-control" id="cat_procedencia_id" name="cat_procedencia_id">
              <option value="">Seleccione</option>
              @php
                $procedencias = ['Doctor', 'Enfermero', 'Redes Sociales', 'Paciente', 'Otro'];
              @endphp

              @foreach($procedencias as $procedencia)
                @if($procedencia == $paciente->cat_procedencia_id)
                  <option value="{{ $procedencia }}" selected>{{ $procedencia }}</option>
                @else
                  <option value="{{ $procedencia }}">{{ $procedencia }}</option>
                @endif
              @endforeach
            </select>
          </div>

          <div class="col-md-5">
            <label for="contacto_procedencia" class="form-label">Contacto de procedencia</label>
            <input type="text" class="form-control" id="contacto_procedencia" name="contacto_procedencia" value="{{ $paciente->contacto_procedencia }}" disabled>
          </div>

          <div class="col-md-3">
            <label for="religion" class="form-label">Religi&oacute;n</label>
            <input type="text" class="form-control" id="religion" name="religion" value="{{ $paciente->religion }}">
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success" id="pacienteSubmit">
        <i class="fas fa-save"></i> Actualizar registro de Paciente
      </button>
    </div>
  </form>

  {{-- modal para registrar un seguro nuevo en el catalogo --}}
  <div class="modal fade" id="modalNuevoSeguro" tabindex="-1" role="dialog" aria-labelledby="modalNuevoSeguroLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuevoSeguroLabel">
            <i class="fas fa-file-medical"></i> Nuevo seguro m&eacute;dico
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_nuevo_seguro">Nombre del seguro</label>
            <input type="text" class="form-control" id="nombre_nuevo_seguro" placeholder="Ej. GNP, AXA, Seguros Monterrey...">
          </div>
          <div id="alerta-nuevo-seguro"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btn-guardar-nuevo-seguro">
            <i class="fas fa-check"></i> Guardar y asociar al paciente
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')

  <script type="text/javascript">
    
    document.getElementById('cat_procedencia_id').addEventListener('change', function(){
      if (this.value == 'Doctor' || this.value == 'Enfermero' || this.value == 'Paciente'){
        document.getElementById('contacto_procedencia').removeAttribute('disabled')
      } else {
        document.getElementById('contacto_procedencia').setAttribute('disabled', 'disabled')
      }
    })

    if ("createEvent" in document) {
      
      var evt = document.createEvent("HTMLEvents");
      evt.initEvent("change", false, true);
      document.getElementById('cat_procedencia_id').dispatchEvent(evt);
    } else {
      document.getElementById('cat_procedencia_id').fireEvent("onchange");
    }

    function getFotoPreview(inputFile){
      var output = document.getElementById('foto-vista-previa');

      var tipo = output.src.substr(0, 4);

      if ( tipo == "blob" ){
        document.getElementById("foto-vista-previa").src = "{{ asset('img/blank.png') }}";
      } else {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function (){
          URL.revokeObjectURL(output.src) // free memory
        }
      }
    }

    // seguros medicos (badges + catalogo + modal)
    const pacienteIdSeguros = {{ $paciente->id }};

    function csrfToken(){
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function pintarBadgeSeguro(seguro){
      var contenedor = document.getElementById('seguros-badges');
      var msg = document.getElementById('sin-seguros-msg');
      if (msg) msg.remove();

      var span = document.createElement('span');
      span.className = 'badge badge-pill badge-info seguro-badge';
      span.dataset.id = seguro.id;
      span.innerHTML = seguro.nombre +
        ' <a href="#" class="text-white ml-2 quitar-seguro" data-id="' + seguro.id + '" title="Quitar seguro">&times;</a>';

      contenedor.appendChild(span);

      var opcion = document.querySelector('#seguro_medico_select option[value="' + seguro.id + '"]');
      if (opcion) opcion.remove();
    }

    document.getElementById('btn-agregar-seguro').addEventListener('click', function(){
      var select = document.getElementById('seguro_medico_select');
      var seguroId = select.value;

      if (!seguroId) return;

      fetch('/paciente/seguro-medico/asociar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ paciente_id: pacienteIdSeguros, seguro_medico_id: seguroId, _token: csrfToken() })
      })
      .then(res => res.json())
      .then(function(seguro){
        pintarBadgeSeguro(seguro);
        select.value = '';
      })
      .catch(err => console.error('Error al asociar seguro:', err));
    });

    document.getElementById('seguros-badges').addEventListener('click', function(event){
      if (!event.target.classList.contains('quitar-seguro')) return;

      event.preventDefault();

      var seguroId = event.target.dataset.id;
      var seguroNombre = event.target.closest('.seguro-badge').textContent.replace('×', '').trim();
      var badge = event.target.closest('.seguro-badge');

      fetch('/paciente/seguro-medico/quitar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ paciente_id: pacienteIdSeguros, seguro_medico_id: seguroId, _token: csrfToken() })
      })
      .then(res => res.json())
      .then(function(){
        var select = document.getElementById('seguro_medico_select');
        var opt = document.createElement('option');
        opt.value = seguroId;
        opt.textContent = seguroNombre;
        select.appendChild(opt);

        badge.remove();

        if (!document.querySelector('.seguro-badge')) {
          document.getElementById('seguros-badges').innerHTML = '<span class="text-muted" id="sin-seguros-msg">Sin seguros médicos registrados</span>';
        }
      })
      .catch(err => console.error('Error al quitar seguro:', err));
    });

    document.getElementById('btn-guardar-nuevo-seguro').addEventListener('click', function(){
      var nombre = document.getElementById('nombre_nuevo_seguro').value.trim();
      var alertaDiv = document.getElementById('alerta-nuevo-seguro');

      alertaDiv.innerHTML = '';

      if (!nombre) {
        alertaDiv.innerHTML = '<div class="alert alert-danger py-2 mt-2 mb-0">Escribe el nombre del seguro.</div>';
        return;
      }

      fetch('/seguros-medicos/insert', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre: nombre, paciente_id: pacienteIdSeguros, _token: csrfToken() })
      })
      .then(function(res){
        if (!res.ok) {
          return res.json().then(function(data){ throw data; });
        }
        return res.json();
      })
      .then(function(seguro){
        pintarBadgeSeguro(seguro);
        document.getElementById('nombre_nuevo_seguro').value = '';

        $('#modalNuevoSeguro').modal('hide');
      })
      .catch(function(err){
        var mensaje = (err && err.errors && err.errors.nombre) ? err.errors.nombre[0] : 'No fue posible guardar el seguro médico.';
        alertaDiv.innerHTML = '<div class="alert alert-danger py-2 mt-2 mb-0">' + mensaje + '</div>';
      });
    });

    $('#modalNuevoSeguro').on('hidden.bs.modal', function () {
      document.getElementById('nombre_nuevo_seguro').value = '';
      document.getElementById('alerta-nuevo-seguro').innerHTML = '';
    });

  </script>

@endsection
@extends('pacientes.paciente_header')

@section('styles')

<style type="text/css">
  .form-label{
    font-weight: bold;
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

<div class="row g-3">
  <div class="col-md-8">      
    <label for="nombre_s" class="form-label">Nombre(s) y apellido(s)</label>
    <input type="text" class="form-control" id="nombre_s" name="nombre_s" value="{{ $paciente->nombre_s }}" onkeydown="">
  </div>

  <div class="col-md-4">
    <img src="{{ asset('img/blank.png') }}" class="rounded" alt="Foto del paciente" height="1" id="foto-vista-previa">
    <input type="file" id="foto_path" name="foto_path" accept="image/*" onchange="{{--getFotoPreview(event)--}}">
  </div>
</div>

<br>

<div class="row g-3">
  <div class="col-md-4">
    <label for="email" class="form-label">Correo electronico</label>
    <input type="text" class="form-control" id="email" name="email" value="{{ $paciente->email }}" onkeydown="">
  </div>

  <div class="col-md-4">
    <label for="direccion" class="form-label">Direcci&oacute;n</label>
    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $paciente->direccion }}" onkeydown="">
  </div>
</div>

<br>

<div class="row g-3">
  
  <div class="col-md-3">
    <label for="telefono" class="form-label">Telefono</label>
    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $paciente->telefono }}" onkeydown="">
  </div>

  <div class="col-md-2">
    <label for="tipo_sangre" class="form-label">Tipo de sangre</label>
      <input type="text" class="form-control" id="tipo_sangre" name="tipo_sangre" value="{{ $paciente->tipo_sangre }}" onkeydown="">
  </div>

  <div class="col-md-3">
    <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
    <input type="text" class="form-control datetimepicker-input" id="fecha_nacimiento" name="fecha_nacimiento" data-toggle="datetimepicker" data-target="#fecha_nacimiento" placeholder="" value="{{ $paciente->fecha_nacimiento }}" onkeydown="">
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <br>
    <label for="lugar_nacimiento" class="form-label">Lugar de nacimiento</label>
    <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento" value="{{ $paciente->lugar_nacimiento }}" onkeydown="">
  </div>

  <div class="col-md-2">
    <br>
    <label for="cat_genero_id" class="form-label">Genero</label>
    <input type="text" class="form-control" id="cat_genero_id" name="cat_genero_id" value="{{ $paciente->genero_id }}" onkeydown="">
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <br>
    <label for="fecha_ingreso" class="form-label">Fecha de primera cita</label>
    <input type="text" class="form-control datetimepicker-input" id="fecha_ingreso" name="fecha_ingreso" data-toggle="datetimepicker" data-target="#fecha_ingreso" placeholder="" value="{{ $paciente->fecha_ingreso }}" onkeydown="">
  </div>

  <div class="col-md-2">
    <br>
    <label for="cat_estado_civil_id" class="form-label">Estado civil</label>
    <input type="text" class="form-control" id="cat_estado_civil_id" name="cat_estado_civil_id" value="{{ $paciente->estado_civil_id }}" onkeydown="">
  </div>
</div>

<div class="row g-3">
  <div class="col-md-3">
    <br>
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
    <br>
    <label for="contacto_procedencia" class="form-label">Contacto de procedencia</label>
    <input type="text" class="form-control" id="contacto_procedencia" name="contacto_procedencia" value="{{ $paciente->contacto_procedencia }}" onkeydown="" disabled>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-5">
    <br>
    <label for="lugar_residencia" class="form-label">Lugar donde reside</label>
    <input type="text" class="form-control" id="lugar_residencia" name="lugar_residencia" value="{{ $paciente->lugar_residencia }}" onkeydown="">
  </div>

  <div class="col-md-3">
    <br>
    <label for="escolaridad" class="form-label">Escolaridad</label>
    <input type="text" class="form-control" id="escolaridad" name="escolaridad" value="{{ $paciente->escolaridad }}" onkeydown="">
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <br>
    <label for="ocupacion" class="form-label">Ocupaci&oacute;n</label>
    <input type="text" class="form-control" id="ocupacion" name="ocupacion" value="{{ $paciente->ocupacion }}" onkeydown="">
  </div>

  <div class="col-md-2">
    <br>
    <label for="religion" class="form-label">Religi&oacute;n</label>
    <input type="text" class="form-control" id="religion" name="religion" value="{{ $paciente->religion }}" onkeydown="">
  </div>
</div>

<div class="row g-3">
  <div class="col-md-8 text-center">
    <br>  
    <button type="submit" class="btn btn-success" type="button" id="pacienteSubmit">
      Actualizar registro de Paciente
    </button>
  </div>
</div>

</form>

@endsection

@section('scripts')

<script type="text/javascript">
  
  document.getElementById('cat_procedencia_id').addEventListener('change', function(){
    if (this.value == 'Doctor' || this.value == 'Enfermero'){
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

</script>

@endsection
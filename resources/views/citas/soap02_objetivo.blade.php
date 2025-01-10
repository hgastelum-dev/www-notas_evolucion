@extends('citas.atender')

@section('styles')

<link href="{{ asset('summernote-0.8.18-dist/summernote.min.css') }}" rel="stylesheet">

@endsection

@section('seccion-cita')

<form method="post" action="/cita/soap02/objetivo/update">

@csrf
<input type="hidden" name="cita_paciente_id" value="{{ $cita->id }}">

@if(session('userAlerts'))
  <div class="alert alert-{{ session('userAlerts')['icono'] }} alert-dismissible fade show" role="alert">
    <strong>{{ session('userAlerts')['titulo'] }}</strong> {{ session('userAlerts')['mensaje'] }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

<div class="row">
            {{--<div class="col-sm-6">
              <b>Laboratorio:</b>
              <textarea class="form-control @if($cita->laboratorio) {!! 'border-success' !!} @endif" name="laboratorio" rows="3">@if($cita->laboratorio){{$cita->laboratorio}}@endif</textarea>
            </div>--}}
            <div class="col-sm-12">
              <b>Gabinete:</b>
              <textarea class="form-control @if($cita->gabinete) {!! 'border-success' !!} @endif" name="gabinete" rows="2">@if($cita->gabinete){{$cita->gabinete}}@endif</textarea>
            </div>
            
          </div>
<br>
<div class="mb-3">
  <label for="objetivo" class="form-label">
    <h3>
        <b>O</b>bjetivo:
    </h3>
  </label>
  
  <div class="row g-3">
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> TA:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ta" name="ta" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->ta }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> FC:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fc" name="fc" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->fc }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> FR:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fr" name="fr" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->fr }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Temp:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="temp" name="temp" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->temp }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Talla:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="calcularImc(this)" id="talla" name="talla" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->talla }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Peso:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="calcularImc(this)" id="peso" name="peso" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->peso }} @endif">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> IMC:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" id="imc" name="imc" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->imc }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> SatO2:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="sat_o2" name="sat_o2" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->sat_o2 }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hb:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hb" name="hb" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->hb }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hto:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hto" name="hto" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->hto }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Vcm:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="vcm" name="vcm" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->vcm }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hcm:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hcm" name="hcm" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->hcm }} @endif">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Erit. hipoc. %:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="porcentaje_eritrocitos_hipocromicos" name="porcentaje_eritrocitos_hipocromicos" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->porcentaje_eritrocitos_hipocromicos }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Plaq:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="plaq" name="plaq" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->plaq }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Leuc:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="leuc" name="leuc" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->leuc }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Cr:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="cr" name="cr" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->cr }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> CKD-EPI:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ckdepi" name="ckdepi" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->ckdepi }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Bun:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="bun" name="bun" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->bun }} @endif">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> G:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="g" name="g" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->g }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hba1c %:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hba1c_porcentaje" name="hba1c_porcentaje" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->hba1c_porcentaje }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Insulina serica:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="insulina_serica" name="insulina_serica" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->insulina_serica }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> HOMA:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="homa" name="homa" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->homa }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Au:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="au" name="au" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->au }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Na:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="na" name="na" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->na }} @endif">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> K:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="k" name="k" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->k }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Cl:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="cl" name="cl" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->cl }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Ca:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ca" name="ca" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->ca }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> P:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="p" name="p" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->p }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Mg:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="mg" name="mg" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->mg }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Alb:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="alb" name="alb" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->alb }} @endif">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="col" name="col" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->col }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Tgs:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="tgs" name="tgs" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->tgs }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> HDL Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hdl_col" name="hdl_col" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->hdl_col }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> LDL Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ldl_col" name="ldl_col" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->ldl_col }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Ego:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ego" name="ego" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->ego }} @endif">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> AlbU/CrU:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="albu_cru" name="albu_cru" value="@if($cita->getObjetivo) {{ $cita->getObjetivo->albu_cru }} @endif">
    </div>
  </div>
  
  <br>
  
  <div class="row g-3">
    <div class="col-sm-12">
      <h6 class="font-weight-bold">
        Exploraci&oacute;n fisica:
      </h6>
      <textarea class="form-control" id="exploracion_fisica" name="exploracion_fisica" rows="7">@if($cita->getObjetivo) {{ $cita->getObjetivo->exploracion_fisica }} @endif</textarea>
    </div>
  </div>
  
  <p>
    <br>
    <button type="submit" class="btn btn-success btn-lg btn-block" id="btn-s">
      Guardar datos de apartado <b>Objetivo</b> 
      @if(session('userAlerts'))
        <span class="badge badge-secondary">
          <i class="fas fa-check-circle"></i> Actualizado exitosamente
        </span>
      @endif
    </button>
  </p>
</div>
</form>
@endsection

@section('scripts')

<script type="text/javascript" src="{{ asset('summernote-0.8.18-dist/summernote.min.js') }}"></script>

<script type="text/javascript">
  function resaltarInput(inputText){
    
    if(inputText.value){
      
      inputText.classList.add('bg-info', 'text-white', 'font-weight-bold', 'text-center')
      var sibling = inputText.previousElementSibling;

      elems = sibling.querySelectorAll('.fa-check-circle');
      elems[0].classList.remove('d-none')
      sibling.classList.add('text-primary');
    
    } else {
      
      inputText.classList.remove('bg-info', 'text-white', 'font-weight-bold', 'text-center')
      var sibling = inputText.previousElementSibling;
      
      elems = sibling.querySelectorAll('.fa-check-circle');
      elems[0].classList.add('d-none')

      sibling.classList.remove('text-primary');
    }
  }

  $(document).ready(function() {
    $('#exploracion_fisica').summernote({
      tabsize: 2,
      height: 200
    });
  });

  function calcularImc(input){

    var talla = document.getElementById('talla');
    var peso = document.getElementById('peso');

    if (talla.value && peso.value){
      document.getElementById('imc').value = peso.value / (talla.value * talla.value) ;
    } else {
      document.getElementById('imc').value = '';
    }
    
  }
</script>
@endsection
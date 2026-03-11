@extends('pacientes.paciente_header')

@section('styles')

<link href="{{ asset('summernote-0.8.18-dist/summernote.min.css') }}" rel="stylesheet">

@endsection


@section('paciente')

@if($paciente->getExploracionFisica)
	
	@php
		$expFisica = $paciente->getExploracionFisica->padecimiento;

          $ta = $paciente->getExploracionFisica->ta;
          $ta2 = $paciente->getExploracionFisica->ta2;
          $fc = $paciente->getExploracionFisica->fc;
          $fr = $paciente->getExploracionFisica->fr;
          $temp = $paciente->getExploracionFisica->temp;
          $talla = $paciente->getExploracionFisica->talla;
          $peso = $paciente->getExploracionFisica->peso;
          $imc = $paciente->getExploracionFisica->imc;
          $sat_o2 = $paciente->getExploracionFisica->sat_o2;
          $hb = $paciente->getExploracionFisica->hb;
          $hto = $paciente->getExploracionFisica->hto;
          $vcm = $paciente->getExploracionFisica->vcm;
          $hcm = $paciente->getExploracionFisica->hcm;
          $porcentaje_eritrocitos_hipocromicos = $paciente->getExploracionFisica->porcentaje_eritrocitos_hipocromicos;
          $plaq = $paciente->getExploracionFisica->plaq;
          $leuc = $paciente->getExploracionFisica->leuc;
          $cr = $paciente->getExploracionFisica->cr;
          $ckdepi = $paciente->getExploracionFisica->ckdepi;
          $bun = $paciente->getExploracionFisica->bun;
          $g = $paciente->getExploracionFisica->g;
          $hba1c_porcentaje = $paciente->getExploracionFisica->hba1c_porcentaje;
          $insulina_serica = $paciente->getExploracionFisica->insulina_serica;
          $homa = $paciente->getExploracionFisica->homa;
          $au = $paciente->getExploracionFisica->au;
          $na = $paciente->getExploracionFisica->na;
          $k = $paciente->getExploracionFisica->k;
          $cl = $paciente->getExploracionFisica->cl;
          $ca = $paciente->getExploracionFisica->ca;
          $p = $paciente->getExploracionFisica->p;
          $mg = $paciente->getExploracionFisica->mg;
          $alb = $paciente->getExploracionFisica->alb;
          $col = $paciente->getExploracionFisica->col;
          $tgs = $paciente->getExploracionFisica->tgs;
          $hdl_col = $paciente->getExploracionFisica->hdl_col;
          $ldl_col = $paciente->getExploracionFisica->ldl_col;
          $ego = $paciente->getExploracionFisica->ego;
          $albu_cru = $paciente->getExploracionFisica->albu_cru;
          $tsh = $paciente->getExploracionFisica->tsh;
          $vit_d_serica = $paciente->getExploracionFisica->vit_d_serica;
          $bnp = $paciente->getExploracionFisica->bnp;
          $ca_125 = $paciente->getExploracionFisica->ca_125;
          $fk = $paciente->getExploracionFisica->fk;
	@endphp

@else 
	
	@php
		$expFisica = '';

          $ta = '';
          $ta2 = '';
          $fc = '';
          $fr = '';
          $temp = '';
          $talla = '';
          $peso = '';
          $imc = '';
          $sat_o2 = '';
          $hb = '';
          $hto = '';
          $vcm = '';
          $hcm = '';
          $porcentaje_eritrocitos_hipocromicos = '';
          $plaq = '';
          $leuc = '';
          $cr = '';
          $ckdepi = '';
          $bun = '';
          $g = '';
          $hba1c_porcentaje = '';
          $insulina_serica = '';
          $homa = '';
          $au = '';
          $na = '';
          $k = '';
          $cl = '';
          $ca = '';
          $p = '';
          $mg = '';
          $alb = '';
          $col = '';
          $tgs = '';
          $hdl_col = '';
          $ldl_col = '';
          $ego = '';
          $albu_cru = '';
          $tsh = '';
          $vit_d_serica = '';
          $bnp = '';
          $ca_125 = '';
          $fk = '';
	@endphp
	
	<div class="alert alert-warning" role="alert">
	  <i class="fas fa-exclamation-circle"></i> Sin registro de <b>exploraci&oacute;n física</b>...
	</div>
@endif

@if( session('userAlerts') )
	
	<div class="alert alert-{{ session('userAlerts')['icono'] }} alert-dismissible fade show" role="alert">
      <strong>
      	{{ session('userAlerts')['titulo'] }}
      </strong> {{ session('userAlerts')['mensaje'] }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
@endif

<form method="post" action="/paciente/exp-fisica/update">
	@csrf

  <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

	<div class="mb-3">
  <label for="objetivo" class="form-label">
    <h3>
        <b>O</b>bjetivo:
    </h3>
  </label>
  
  <div class="row g-3">
    <div class="col-sm-1">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> TA:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ta" name="ta" value="{{ $ta }}">
    </div>
    <div class="col-sm-1">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i><br>
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ta2" name="ta2" value="{{ $ta2 }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> FC:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fc" name="fc" value="{{ $fc }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> FR:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fr" name="fr" value="{{ $fr }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Temp:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="temp" name="temp" value="{{ $temp }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Talla:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="calcularImc(this)" id="talla" name="talla" value="{{ $talla }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Peso:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="calcularImc(this)" id="peso" name="peso" value="{{ $peso }}">
    </div>
  </div>
  <br>
  <div class="row g-3">
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> IMC:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" id="imc" name="imc" value="{{ $imc }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> SatO2:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="sat_o2" name="sat_o2" value="{{ $sat_o2 }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hb:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hb" name="hb" value="{{ $hb }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hto:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hto" name="hto" value="{{ $hto }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Vcm:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="vcm" name="vcm" value="{{ $vcm }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hcm:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hcm" name="hcm" value="{{ $hcm }}">
    </div>
  </div>
  <br>
  <div class="row g-3">
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Erit. hipoc. %:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="porcentaje_eritrocitos_hipocromicos" name="porcentaje_eritrocitos_hipocromicos" value="{{ $porcentaje_eritrocitos_hipocromicos }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Plaq:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="plaq" name="plaq" value="{{ $plaq }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Leuc:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="leuc" name="leuc" value="{{ $leuc }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Cr:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="cr" name="cr" value="{{ $cr }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> CKD-EPI:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ckdepi" name="ckdepi" value="{{ $ckdepi }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Bun:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="bun" name="bun" value="{{ $bun }}">
    </div>
  </div>
  <br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> G:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="g" name="g" value="{{ $g }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hba1c %:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hba1c_porcentaje" name="hba1c_porcentaje" value="{{ $hba1c_porcentaje }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Insulina sérica:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="insulina_serica" name="insulina_serica" value="{{ $insulina_serica }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> HOMA:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="homa" name="homa" value="{{ $homa }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Au:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="au" name="au" value="{{ $au }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Na:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="na" name="na" value="{{ $na }}">
    </div>
  </div>
  <br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> K:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="k" name="k" value="{{ $k }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Cl:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="cl" name="cl" value="{{ $cl }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Ca:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ca" name="ca" value="{{ $ca }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> P:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="p" name="p" value="{{ $p }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Mg:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="mg" name="mg" value="{{ $mg }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Alb:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="alb" name="alb" value="{{ $alb }}">
    </div>
  </div>
  <br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="col" name="col" value="{{ $col }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Tgs:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="tgs" name="tgs" value="{{ $tgs }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> HDL Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hdl_col" name="hdl_col" value="{{ $hdl_col }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> LDL Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ldl_col" name="ldl_col" value="{{ $ldl_col }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Ego:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ego" name="ego" value="{{ $ego }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> AlbU/CrU:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="albu_cru" name="albu_cru" value="{{ $albu_cru }}">
    </div>
  </div>

  <br>

  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> TSH:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="tsh" name="tsh" value="{{ $tsh }}">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Vit. D sér.:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="vit_d_serica" name="vit_d_serica" value="{{ $vit_d_serica }}">
    </div>

    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> BNP:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="bnp" name="bnp" value="{{ $bnp }}">
    </div>

    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> CA-125:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ca_125" name="ca_125" value="{{ $ca_125 }}">
    </div>

    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> FK:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fk" name="fk" value="{{ $fk }}">
    </div>
  </div>
  
  <br>
  
  <div class="row g-3">
    <div class="col-sm-12">
      <h6 class="font-weight-bold">
        Exploraci&oacute;n fisica:
      </h6>
      <textarea class="form-control" id="exploracion_fisica" name="exploracion_fisica" rows="7">{{ $expFisica }}</textarea>
    </div>
  </div>
  
  <p>
    <br>
    <button type="submit" class="btn btn-success btn-lg btn-block" id="btn-s">
      Guardar{{-- datos de apartado <b>Objetivo</b>--}} 
      @if(session('userAlerts'))
        <span class="badge badge-secondary">
          <i class="fas fa-check-circle"></i> Actualizado exitosamente
        </span>
      @endif
    </button>
  </p>

  {{--<form> 
<h3>Filtrado Glomerular (CKD-EPI)</h3> 
<table border="1" style="border: 1px solid #e0dede;"> 
<tr> 
<td>Introduce Edad en años: </td> 
<td><input id ="E" name="E"></td> 
</tr> 
<tr> 
<td>Introduce Creatinina Sérica en mg/dL: </td> 
<td><input id="C" name="C"></td> 
</tr> 
<tr> 
<td>Señala si el enfermo es mujer: </td> 
<td><center><input id="M" TYPE = "radio" name ="M" value= "1.018" ></td> 
</tr> 
<tr> 
<td><center>Señala si el enfermo es de raza negra: </td> 
<td><center><input id="N" TYPE ="radio" name ="N" value="1.159" ></td> 
</tr> 
</table> 
  
 <br> 
 <input onclick="CKD1(this.form)" type="button" value="Calcula Filtrado Glomerular"><br><br> 
 
 Filtrado Glomerular CKD-EPI <input id="ACr" name="ACr" > mL/min/1.73 m<sup>2</sup><br> 
<br> <br> 
<input type="reset" value="Borrar información"> 
</form>
--}}
</div>























	{{--<input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
	<h5>
		<b>Exploraci&oacute;n fisica:</b>
	</h5>
	<textarea class="form-control" rows="10" name="padecimiento" id="padecimiento">{{ $expFisica }}</textarea>
	<br>
	<button type="submit" class="btn btn-success btn-lg btn-block">
		Guardar
	</button>--}}
</form>

@endsection

@section('scripts')

<script type="text/javascript" src="{{ asset('summernote-0.8.18-dist/summernote.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#exploracion_fisica').summernote({
      tabsize: 2,
      height: 500
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

  {{--function CKD1(form) { 
    var e = document.getElementById("E").value; 
        e = parseFloat(e); 
    
    var c = document.getElementById("C").value; 
        c = parseFloat(c); 
    
    if (document.getElementById("M").checked ){ MU = document.getElementById("M").value } else { MU = 1 }; 
    if (document.getElementById("N").checked) { NE = document.getElementById("N").value } else { NE = 1 }; 
    
    MU = parseFloat(MU); 
    NE = parseFloat(NE); 
    
    E = Math.pow(0.993, e);

    if ((c <= 0.7) && (MU == 1.018)) { 

      J = Math.pow(c/0.7, -0.329) 
    } else if ((c > 0.7) && (MU == 1.018)) 
      {
        J = Math.pow(c/0.7, -1.209)
      } else if ((c <= 0.7) && (MU == 1)) { 

        J = Math.pow(c/0.9, -0.411)
      } else {

        J = Math.pow(c/0.9, -1.209)
      }; 
      
      ACr = 141 * J * E * MU * NE; 
      ACr= Math.round(ACr * 100) / 100; 
      form.ACr.value = ACr; 
  }--}}
</script>
@endsection
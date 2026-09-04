@extends('pacientes.paciente_header')

@section('paciente')
	
	<a href="/paciente/notas-hist/{{ $notaHistorica->paciente_id }}" class="btn btn-primary">
		<i class="fas fa-arrow-left"></i>
	</a>

	<p class="text-center" style="font-size: 24px;">
		<b>Historial de notas de evoluci&oacute;n</b>: {{ $notaHistorica->fecha }} 
	</p>

	<form method="post" action="/nota-hist/update">
		@csrf
		<input type="hidden" name="nota_hist_id" value="{{ $notaHistorica->id }}">
	  
    <div class="form-group row">
      <label for="fecha" class="col-sm-2 col-form-label">
        Fecha
      </label>
      <div class="col-sm-10">
        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $notaHistorica->fecha }}" required>
      </div>
    </div>

	  <div class="form-group row">
	    <label for="subjetivo" class="col-sm-2 col-form-label">
	    	Subjetivo
	    </label>
	    <div class="col-sm-10">
	      <textarea class="form-control" id="subjetivo" name="subjetivo" rows="3" required>{{ $notaHistorica->subjetivo }}</textarea>
	    </div>
	  </div>

	  <div class="form-group row">
	    <label for="" class="col-sm-2 col-form-label">
	    	Objetivo
	    </label>
	    <div class="col-sm-10">
	    	<div class="row g-3">
          <div class="col-sm-1">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> TA:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ta" name="ta" value="{{ $notaHistorica->obj_ta }}">
          </div>
          <div class="col-sm-1">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i><br>
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ta2" name="ta2" value="{{ $notaHistorica->obj_ta2 }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> FC:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fc" name="fc" value="{{ $notaHistorica->obj_fc }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> FR:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fr" name="fr" value="{{ $notaHistorica->obj_fr }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Temp:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="temp" name="temp" value="{{ $notaHistorica->obj_temp }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Talla:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="calcularImc(this)" id="talla" name="talla" value="{{ $notaHistorica->obj_talla }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Peso:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="calcularImc(this)" id="peso" name="peso" value="{{ $notaHistorica->obj_peso }}">
          </div>
        </div>
        <br><br>
        <div class="row g-3">
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> IMC:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" id="imc" name="imc" value="{{ $notaHistorica->obj_imc }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> SatO2:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="sat_o2" name="sat_o2" value="{{ $notaHistorica->obj_sat_o2 }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Hb:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hb" name="hb" value="{{ $notaHistorica->obj_hb }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Hto:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hto" name="hto" value="{{ $notaHistorica->obj_hto }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Vcm:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="vcm" name="vcm" value="{{ $notaHistorica->obj_vcm }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Hcm:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hcm" name="hcm" value="{{ $notaHistorica->obj_hcm }}">
          </div>
        </div>
        <br><br>
        <div class="row g-3">
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Erit. hipoc. %:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="porcentaje_eritrocitos_hipocromicos" name="porcentaje_eritrocitos_hipocromicos" value="{{ $notaHistorica->obj_porcentaje_eritrocitos_hipocromicos }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Plaq:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="plaq" name="plaq" value="{{ $notaHistorica->obj_plaq }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Leuc:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="leuc" name="leuc" value="{{ $notaHistorica->obj_leuc }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Cr:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="cr" name="cr" value="{{ $notaHistorica->obj_cr }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> CKD-EPI:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ckdepi" name="ckdepi" value="{{ $notaHistorica->obj_ckdepi }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Bun:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="bun" name="bun" value="{{ $notaHistorica->obj_bun }}">
          </div>
        </div>
        <br><br>
        <div class="row g-3">
          
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> G:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="g" name="g" value="{{ $notaHistorica->obj_g }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Hba1c %:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hba1c_porcentaje" name="hba1c_porcentaje" value="{{ $notaHistorica->obj_hba1c_porcentaje }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Insulina serica:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="insulina_serica" name="insulina_serica" value="{{ $notaHistorica->obj_insulina_serica }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> HOMA:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="homa" name="homa" value="{{ $notaHistorica->obj_homa }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Au:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="au" name="au" value="{{ $notaHistorica->obj_au }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Na:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="na" name="na" value="{{ $notaHistorica->obj_na }}">
          </div>
        </div>
        <br><br>
        <div class="row g-3">
          
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> K:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="k" name="k" value="{{ $notaHistorica->obj_k }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Cl:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="cl" name="cl" value="{{ $notaHistorica->obj_cl }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Ca:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ca" name="ca" value="{{ $notaHistorica->obj_ca }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> P:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="p" name="p" value="{{ $notaHistorica->obj_p }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Mg:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="mg" name="mg" value="{{ $notaHistorica->obj_mg }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Alb:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="alb" name="alb" value="{{ $notaHistorica->obj_alb }}">
          </div>
        </div>
        <br><br>
        <div class="row g-3">
          
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Col:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="col" name="col" value="{{ $notaHistorica->obj_col }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Tgs:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="tgs" name="tgs" value="{{ $notaHistorica->obj_tgs }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> HDL Col:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hdl_col" name="hdl_col" value="{{ $notaHistorica->obj_hdl_col }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> LDL Col:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ldl_col" name="ldl_col" value="{{ $notaHistorica->obj_ldl_col }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> Ego:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ego" name="ego" value="{{ $notaHistorica->obj_ego }}">
          </div>
          <div class="col-sm-2">
            <h6 class="font-weight-bold">
              <i class="fas fa-check-circle text-primary d-none"></i> AlbU/CrU:
            </h6>
            <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="albu_cru" name="albu_cru" value="{{ $notaHistorica->obj_albu_cru }}">
          </div>
        </div>
        <br>
        <div class="row g-3">
          <div class="col-sm-12">
            <h6 class="font-weight-bold text-center">
              Exploraci&oacute;n fisica:
            </h6>
            <textarea class="form-control" id="exploracion_fisica" name="exploracion_fisica" rows="7">{{ $notaHistorica->obj_exploracion_fisica }}</textarea>
          </div>
        </div>
	    </div>
	  </div>
	  
	  <div class="form-group row">
	    <label for="analisis" class="col-sm-2 col-form-label">
	    	An&aacute;lisis
	    </label>
	    <div class="col-sm-10">
	      <textarea class="form-control" id="analisis" name="analisis" rows="3" required>{{ $notaHistorica->analisis }}</textarea>
	    </div>
	  </div>

	  <div class="form-group row">
	    <label for="" class="col-sm-2 col-form-label">
	    	Plan
	    </label>
	    <div class="col-sm-10">
	      @foreach($notaHistorica->getPlanHist as $key => $planHist)

	      	<input type="hidden" name="plan[{{ $key }}][plan_hist_id]" value="{{ $planHist->id }}">

	      	<div class="row">
	      		<div class="col-sm-6">
	      			<select class="form-control" name="plan[{{ $key }}][tipo_plan_id]" required>
	      				<option value="">Seleccione una opcion</option>
	      				@foreach($tiposPlan as $tipoPlan)
	      					@if($tipoPlan->id == $planHist->tipo_plan_id)
	      						<option value="{{ $tipoPlan->id }}" selected>{{ $tipoPlan->tipo_plan }}</option>
	      					@else
	      						<option value="{{ $tipoPlan->id }}">{{ $tipoPlan->tipo_plan }}</option>
	      					@endif

	      				@endforeach
	      			</select>
	      		</div>
	      		<br><br><br>
	      		<div class="col-sm-6">
	      			<textarea class="form-control" name="plan[{{ $key }}][plan]" required>{{ $planHist->plan }}</textarea>
	      		</div>
	      	</div>
	      @endforeach
	    </div>
	  </div>
	  
	  <div class="form-group row">
	    <div class="col-sm-12">
	      <button type="submit" class="btn btn-primary btn-lg btn-block">Actualizar</button>
	    </div>
	  </div>
	</form>

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
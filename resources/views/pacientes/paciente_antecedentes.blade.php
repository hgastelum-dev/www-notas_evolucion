@extends('pacientes.paciente_header')

@section('paciente')

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

  {{-- creacion de variables para precargar el formulario --}}
  @if ($paciente->getAntecedentes)
  	
  	@php

  		$he_diabetes = $paciente->getAntecedentes->he_diabetes;
  		$he_has = $paciente->getAntecedentes->he_has;
  		$he_car_izq = $paciente->getAntecedentes->he_car_izq;
  		$he_cancer = $paciente->getAntecedentes->he_cancer;
  		$he_neumopatia = $paciente->getAntecedentes->he_neumopatia;
  		$he_enf_renal = $paciente->getAntecedentes->he_enf_renal;
  		$he_enf_hepatica = $paciente->getAntecedentes->he_enf_hepatica;
  		$he_otros = $paciente->getAntecedentes->he_otros;

  		$pnp_tabaco = $paciente->getAntecedentes->pnp_tabaco;
  		$pnp_tabaco_cantidad = $paciente->getAntecedentes->pnp_tabaco_cantidad;
  		$pnp_tabaco_inicio = $paciente->getAntecedentes->pnp_tabaco_inicio;
  		$pnp_tabaco_fin = $paciente->getAntecedentes->pnp_tabaco_fin;
  		$pnp_oh = $paciente->getAntecedentes->pnp_oh;
  		$pnp_oh_cantidad = $paciente->getAntecedentes->pnp_oh_cantidad;
  		$pnp_oh_inicio = $paciente->getAntecedentes->pnp_oh_inicio;
  		$pnp_oh_fin = $paciente->getAntecedentes->pnp_oh_fin;
  		$pnp_toxicos = $paciente->getAntecedentes->pnp_toxicos;
  		$pnp_toxicos_inicio = $paciente->getAntecedentes->pnp_toxicos_inicio;
  		$pnp_toxicos_fin = $paciente->getAntecedentes->pnp_toxicos_fin;
  		$pnp_toxicos_tipo = $paciente->getAntecedentes->pnp_toxicos_tipo;
  		$pnp_gineco_obstetricos = $paciente->getAntecedentes->pnp_gineco_obstetricos;
  		$pnp_otro = $paciente->getAntecedentes->pnp_otro;
  		$pp_qx = $paciente->getAntecedentes->pp_qx;
  		$pp_qx_tipo = $paciente->getAntecedentes->pp_qx_tipo;
  		$pp_fx = $paciente->getAntecedentes->pp_fx;
  		$pp_fx_tipo = $paciente->getAntecedentes->pp_fx_tipo;
  		$pp_alergia = $paciente->getAntecedentes->pp_alergia;
  		$pp_transfusiones = $paciente->getAntecedentes->pp_transfusiones;
  		$pp_transfusiones_numero = $paciente->getAntecedentes->pp_transfusiones_numero;
  		$pp_transfusiones_inicial = $paciente->getAntecedentes->pp_transfusiones_inicial;
  		$pp_transfusiones_ultima = $paciente->getAntecedentes->pp_transfusiones_ultima;
  		$pp_patias = $paciente->getAntecedentes->pp_patias;
  		$pp_anos = $paciente->getAntecedentes->pp_anos;

  	@endphp

  @else 

  	@php
  		$he_diabetes = '';
  		$he_has = '';
  		$he_car_izq = '';
  		$he_cancer = '';
  		$he_neumopatia = '';
  		$he_enf_renal = '';
  		$he_enf_hepatica = '';
  		$he_otros = '';

  		$pnp_tabaco = "";
  		$pnp_tabaco_cantidad = "";
  		$pnp_tabaco_inicio = "";
  		$pnp_tabaco_fin = "";
  		$pnp_oh = "";
  		$pnp_oh_cantidad = "";
  		$pnp_oh_inicio = "";
  		$pnp_oh_fin = "";
  		$pnp_toxicos = "";
  		$pnp_toxicos_inicio = "";
  		$pnp_toxicos_fin = "";
  		$pnp_toxicos_tipo = "";
  		$pnp_gineco_obstetricos = "";
  		$pnp_otro = "";
  		$pp_qx = "";
  		$pp_qx_tipo = "";
  		$pp_fx = "";
  		$pp_fx_tipo = "";
  		$pp_alergia = "";
  		$pp_transfusiones = "";
  		$pp_transfusiones_numero = "";
  		$pp_transfusiones_inicial = "";
  		$pp_transfusiones_ultima = "";
  		$pp_patias = "";
  		$pp_anos = "";
    @endphp

  	<div class="alert alert-warning" role="alert">
  	  <i class="fas fa-exclamation-circle"></i> Sin registro de <b>antecedentes</b>...
  	</div>

  @endif

  <form method="post" action="/paciente/antecedentes/update">
	
    @csrf
    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

    <div class="row">
    	<div class="bg-info bg-gradient p-2 text-white col-sm-12">
    		<h4 class="text-center">
    			<b>Heredo - Familiar:</b>
    		</h4>
    	</div>
    </div>

    <br>

    <div class="form-group row">
      <label for="he_diabetes" class="col-sm-2 col-form-label">
      	<b>Diabetes:</b>
      </label>
      <div class="col-sm-10">
      	<textarea class="form-control" id="he_diabetes" name="he_diabetes" rows="2">{{ $he_diabetes }}</textarea>
      </div>
    </div>

    <div class="form-group row">
        <label for="he_has" class="col-sm-2 col-form-label">
        	<b>HAS:</b>
        </label>
        <div class="col-sm-10">
          <textarea class="form-control" id="he_has" name="he_has" rows="2">{{ $he_has }}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="he_car_izq" class="col-sm-2 col-form-label">
        	<b>Card. isq.:</b>
        </label>
        <div class="col-sm-10">
          <textarea class="form-control" id="he_car_izq" name="he_car_izq" rows="2">{{ $he_car_izq }}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="he_cancer" class="col-sm-2 col-form-label">
        	<b>Cáncer:</b>
        </label>
        <div class="col-sm-10">
          <textarea class="form-control" id="he_cancer" name="he_cancer" rows="2">{{ $he_cancer }}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="he_neumopatia" class="col-sm-2 col-form-label">
        	<b>Neumopatía:</b>
        </label>
        <div class="col-sm-10">
          <textarea class="form-control" id="he_neumopatia" name="he_neumopatia" rows="2">{{ $he_neumopatia }}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="he_enf_renal" class="col-sm-2 col-form-label">
        	<b>Enf. renal:</b>
        </label>
        <div class="col-sm-10">
          <textarea class="form-control" id="he_enf_renal" name="he_enf_renal" rows="2">{{ $he_enf_renal }}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="he_enf_hepatica" class="col-sm-2 col-form-label">
        	<b>Enf. hepática:</b>
        </label>
        <div class="col-sm-10">
          <textarea class="form-control" id="he_enf_hepatica" name="he_enf_hepatica" rows="2">{{ $he_enf_hepatica }}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="he_otros" class="col-sm-2 col-form-label">
        	<b>Otros:</b>
        </label>
        <div class="col-sm-10">
          <textarea class="form-control" id="he_otros" name="he_otros" rows="2">{{ $he_otros }}</textarea>
        </div>
    </div>

    <br>

    <div class="row">
    	<div class="bg-info bg-gradient p-2 text-white col-sm-12">
    			<h4 class="text-center">
    				<b>Personales No Patol&oacute;gicos:</b>
    			</h4>
    	</div>
    </div>

    <div class="row">

    	<div class="col-sm-2">
    		<br>
    		<b>Tabaco:</b><br>

    		<div class="form-check form-check-inline">
    			<input class="form-check-input" type="radio" name="pnp_tabaco" id="pnp_tabaco1" value="Si" @if($pnp_tabaco == "Si") {{ 'checked' }} @endif>
    			<label class="form-check-label" for="pnp_tabaco1">Si</label>
    		</div>
    		
    		<div class="form-check form-check-inline">
    			<input class="form-check-input" type="radio" name="pnp_tabaco" id="pnp_tabaco2" value="No" @if($pnp_tabaco == "No") {{ 'checked' }} @endif>
    			<label class="form-check-label" for="pnp_tabaco2">No</label>
    		</div>
    	
    	</div>

    	<div class="col-sm-2">
    		<br>
    		<b>Cant./d&iacute;a:</b><br>
    		<input type="text" name="pnp_tabaco_cantidad" class="form-control" value="{{ $pnp_tabaco_cantidad }}">
    	</div>

    	<div class="col-sm-4">
    		<br>
    		<b>Inicio:</b><br>
    		<input type="text" name="pnp_tabaco_inicio" class="form-control" value="{{ $pnp_tabaco_inicio }}">
    	</div>

    	<div class="col-sm-4">
    		<br>
    		<b>Fin:</b><br>
    		<input type="text" name="pnp_tabaco_fin" class="form-control" value="{{ $pnp_tabaco_fin }}">
    	</div>
    </div>

    <br>

    <div class="row">

    	<div class="col-sm-2">
    		<b>OH-:</b><br>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pnp_oh" id="pnp_oh1" value="Si" @if($pnp_oh == "Si") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pnp_oh1">Si</label>
    		</div>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pnp_oh" id="pnp_oh2" value="No" @if($pnp_oh == "No") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pnp_oh2">No</label>
    		</div>
    	</div>

    	<div class="col-sm-2">
    		<b>Cant. L/d:</b><br>
    		<input type="text" name="pnp_oh_cantidad" class="form-control" value="{{ $pnp_oh_cantidad }}">
    	</div>

    	<div class="col-sm-4">
    		<b>Inicio:</b><br>
    		<input type="text" name="pnp_oh_inicio" class="form-control" value="{{ $pnp_oh_inicio }}">
    	</div>

    	<div class="col-sm-4">
    		<b>Fin:</b><br>
    		<input type="text" name="pnp_oh_fin" class="form-control" value="{{ $pnp_oh_fin }}">
    	</div>
    </div>

    <br>

    <div class="row">

    	<div class="col-sm-2">
    		<b>Tóxicos:</b><br>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pnp_toxicos" id="pnp_toxicos1" value="Si" @if($pnp_toxicos == "Si") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pnp_toxicos1">Si</label>
    		</div>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pnp_toxicos" id="pnp_toxicos2" value="No" @if($pnp_toxicos == "No") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pnp_toxicos2">No</label>
    		</div>
    	</div>

    	<div class="col-sm-2">
    		<b>Inicio:</b><br>
    		<input type="text" name="pnp_toxicos_inicio" class="form-control" value="{{ $pnp_toxicos_inicio }}">
    	</div>

    	<div class="col-sm-4">
    		<b>Fin:</b><br>
    		<input type="text" name="pnp_toxicos_fin" class="form-control" value="{{ $pnp_toxicos_fin }}">
    	</div>

    	<div class="col-sm-4">
    		<b>Tipo:</b><br>
    		<input type="text" name="pnp_toxicos_tipo" class="form-control" value="{{ $pnp_toxicos_tipo }}">
    	</div>
    </div>

    <br>

    <div class="row">
    	<div class="col-sm-12">
    		<b>Otro:</b><br>
    		<textarea class="form-control" rows="2" name="pnp_otro">{{ $pnp_otro }}</textarea>
    	</div>
    </div>
    <br>
    <div class="row">
    	<div class="col-sm-12">
    		<b>Gineco obst&eacute;tricos:</b><br>
    		<textarea class="form-control" rows="2" name="pnp_gineco_obstetricos">{{ $pnp_gineco_obstetricos }}</textarea>
    	</div>
    </div>

    <br>
    <div class="row">
    	<div class="bg-info bg-gradient p-2 text-white col-sm-12">
    			<h4 class="text-center">
    				<b>Personales Patol&oacute;gicos:</b>
    			</h4>
    	</div>
    </div>

    <div class="row">
    	<div class="col-sm-2">
    		<br>
    		<b>Cirugías:</b><br>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pp_qx" id="pp_qx1" value="Si" @if($pp_qx == "Si") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pp_qx1">Si</label>
    		</div>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pp_qx" id="pp_qx2" value="No" @if($pp_qx == "No") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pp_qx2">No</label>
    		</div>
    	</div>
    	<div class="col-sm-4">
    		<br>
    		<b>Tipo:</b><br>
    		<input type="text" name="pp_qx_tipo" class="form-control" value="{{ $pp_qx_tipo }}">
    	</div>
    	<div class="col-sm-2">
    		<br>
    		<b>Fracturas:</b><br>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pp_fx" id="pp_fx1" value="Si" @if($pp_fx == "Si") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pp_fx1">Si</label>
    		</div>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pp_fx" id="pp_fx2" value="No" @if($pp_fx == "No") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pp_fx2">No</label>
    		</div>
    	</div>
    	<div class="col-sm-4">
    		<br>
    		<b>Tipo:</b><br>
    		<input type="text" name="pp_fx_tipo" class="form-control" value="{{ $pp_fx_tipo }}">
    	</div>
    </div>

    <br>

    <div class="row">

    	<div class="col-sm-3">
    		<b>Alergias:</b><br>
    		<input type="text" name="pp_alergia" class="form-control" value="{{ $pp_alergia }}">
    	</div>

    	<div class="col-sm-2 text-center">
    		<b>Transfusiones:</b><br>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pp_transfusiones" id="pp_transfusiones1" value="Si" @if($pp_transfusiones == "Si") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pp_transfusiones1">Si</label>
    		</div>
    		<div class="form-check form-check-inline">
    		  <input class="form-check-input" type="radio" name="pp_transfusiones" id="pp_transfusiones2" value="No" @if($pp_transfusiones == "No") {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="pp_transfusiones2">No</label>
    		</div>
    	</div>

    	<div class="col-sm-1">
    		<b>#:</b><br>
    		<input type="text" name="pp_transfusiones_numero" class="form-control" value="{{ $pp_transfusiones_numero }}">
    	</div>

    	<div class="col-sm-3">
    		<b>Inicial:</b><br>
    		<input type="text" name="pp_transfusiones_inicial" class="form-control" value="{{ $pp_transfusiones_inicial }}">
    	</div>

    	<div class="col-sm-3">
    		<b>Última:</b><br>
    		<input type="text" name="pp_transfusiones_ultima" class="form-control" value="{{ $pp_transfusiones_ultima }}">
    	</div>

    </div>

    <br>

    <div class="row">
    	<div class="col-sm-12">
    		<b>Enfermedades:</b><br>
    		<textarea name="pp_patias" class="form-control" rows="2">{{ $pp_patias }}</textarea>
    	</div>
    </div>

    <br>

    <div class="d-grid gap-2 text-center">
    	<button class="btn btn-success" type="submit">
    		<i class="fas fa-save"></i> Guardar antecedentes
    	</button>		
    </div>

  </form>

@endsection
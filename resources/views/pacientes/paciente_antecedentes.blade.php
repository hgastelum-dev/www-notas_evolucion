@extends('pacientes.paciente_header')

@section('paciente')

  <style>
    .antecedentes-section-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.6rem;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .antecedentes-section-header {
        background: #eef2ff;
        color: #1f2937;
        padding: 0.75rem 1.25rem;
        font-weight: 700;
        font-size: 1rem;
        border-bottom: 1px solid #e0e7ff;
    }

    .antecedentes-section-body {
        padding: 1.25rem;
    }

    .antecedentes-field label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #374151;
        margin-bottom: 0.25rem;
        display: block;
    }

    .antecedentes-subcard {
        background: #f8fafc;
        border: 1px solid #edf0f3;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .antecedentes-subcard-title {
        font-weight: 700;
        color: #1f2937;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .si-no-toggle {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0;
    }
  </style>

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

    {{-- heredo-familiar --}}
    <div class="antecedentes-section-card">
      <div class="antecedentes-section-header">
        <i class="fas fa-dna"></i> Heredo - Familiar
      </div>
      <div class="antecedentes-section-body">
        <div class="row g-3">
          <div class="col-md-6 antecedentes-field">
            <label for="he_diabetes">Diabetes</label>
            <textarea class="form-control" id="he_diabetes" name="he_diabetes" rows="2">{{ $he_diabetes }}</textarea>
          </div>

          <div class="col-md-6 antecedentes-field">
            <label for="he_has">HAS</label>
            <textarea class="form-control" id="he_has" name="he_has" rows="2">{{ $he_has }}</textarea>
          </div>

          <div class="col-md-6 antecedentes-field">
            <label for="he_car_izq">Card. isq.</label>
            <textarea class="form-control" id="he_car_izq" name="he_car_izq" rows="2">{{ $he_car_izq }}</textarea>
          </div>

          <div class="col-md-6 antecedentes-field">
            <label for="he_cancer">Cáncer</label>
            <textarea class="form-control" id="he_cancer" name="he_cancer" rows="2">{{ $he_cancer }}</textarea>
          </div>

          <div class="col-md-6 antecedentes-field">
            <label for="he_neumopatia">Neumopatía</label>
            <textarea class="form-control" id="he_neumopatia" name="he_neumopatia" rows="2">{{ $he_neumopatia }}</textarea>
          </div>

          <div class="col-md-6 antecedentes-field">
            <label for="he_enf_renal">Enf. renal</label>
            <textarea class="form-control" id="he_enf_renal" name="he_enf_renal" rows="2">{{ $he_enf_renal }}</textarea>
          </div>

          <div class="col-md-6 antecedentes-field">
            <label for="he_enf_hepatica">Enf. hepática</label>
            <textarea class="form-control" id="he_enf_hepatica" name="he_enf_hepatica" rows="2">{{ $he_enf_hepatica }}</textarea>
          </div>

          <div class="col-md-6 antecedentes-field">
            <label for="he_otros">Otros</label>
            <textarea class="form-control" id="he_otros" name="he_otros" rows="2">{{ $he_otros }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- personales no patologicos --}}
    <div class="antecedentes-section-card">
      <div class="antecedentes-section-header">
        <i class="fas fa-walking"></i> Personales No Patol&oacute;gicos
      </div>
      <div class="antecedentes-section-body">

        <div class="antecedentes-subcard">
          <div class="antecedentes-subcard-title">Tabaco</div>
          <div class="row g-3 align-items-end">
            <div class="col-sm-3">
              <div class="si-no-toggle">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pnp_tabaco" id="pnp_tabaco1" value="Si" @if($pnp_tabaco == "Si") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pnp_tabaco1">S&iacute;</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pnp_tabaco" id="pnp_tabaco2" value="No" @if($pnp_tabaco == "No") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pnp_tabaco2">No</label>
                </div>
              </div>
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Cant./d&iacute;a</label>
              <input type="text" name="pnp_tabaco_cantidad" class="form-control" value="{{ $pnp_tabaco_cantidad }}">
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Inicio</label>
              <input type="text" name="pnp_tabaco_inicio" class="form-control" value="{{ $pnp_tabaco_inicio }}">
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Fin</label>
              <input type="text" name="pnp_tabaco_fin" class="form-control" value="{{ $pnp_tabaco_fin }}">
            </div>
          </div>
        </div>

        <div class="antecedentes-subcard">
          <div class="antecedentes-subcard-title">OH-</div>
          <div class="row g-3 align-items-end">
            <div class="col-sm-3">
              <div class="si-no-toggle">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pnp_oh" id="pnp_oh1" value="Si" @if($pnp_oh == "Si") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pnp_oh1">S&iacute;</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pnp_oh" id="pnp_oh2" value="No" @if($pnp_oh == "No") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pnp_oh2">No</label>
                </div>
              </div>
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Cant. L/d</label>
              <input type="text" name="pnp_oh_cantidad" class="form-control" value="{{ $pnp_oh_cantidad }}">
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Inicio</label>
              <input type="text" name="pnp_oh_inicio" class="form-control" value="{{ $pnp_oh_inicio }}">
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Fin</label>
              <input type="text" name="pnp_oh_fin" class="form-control" value="{{ $pnp_oh_fin }}">
            </div>
          </div>
        </div>

        <div class="antecedentes-subcard">
          <div class="antecedentes-subcard-title">T&oacute;xicos</div>
          <div class="row g-3 align-items-end">
            <div class="col-sm-3">
              <div class="si-no-toggle">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pnp_toxicos" id="pnp_toxicos1" value="Si" @if($pnp_toxicos == "Si") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pnp_toxicos1">S&iacute;</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pnp_toxicos" id="pnp_toxicos2" value="No" @if($pnp_toxicos == "No") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pnp_toxicos2">No</label>
                </div>
              </div>
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Inicio</label>
              <input type="text" name="pnp_toxicos_inicio" class="form-control" value="{{ $pnp_toxicos_inicio }}">
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Fin</label>
              <input type="text" name="pnp_toxicos_fin" class="form-control" value="{{ $pnp_toxicos_fin }}">
            </div>
            <div class="col-sm-3 antecedentes-field">
              <label>Tipo</label>
              <input type="text" name="pnp_toxicos_tipo" class="form-control" value="{{ $pnp_toxicos_tipo }}">
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6 antecedentes-field">
            <label>Otro</label>
            <textarea class="form-control" rows="2" name="pnp_otro">{{ $pnp_otro }}</textarea>
          </div>
          <div class="col-md-6 antecedentes-field">
            <label>Gineco obst&eacute;tricos</label>
            <textarea class="form-control" rows="2" name="pnp_gineco_obstetricos">{{ $pnp_gineco_obstetricos }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- personales patologicos --}}
    <div class="antecedentes-section-card">
      <div class="antecedentes-section-header">
        <i class="fas fa-file-medical"></i> Personales Patol&oacute;gicos
      </div>
      <div class="antecedentes-section-body">

        <div class="antecedentes-subcard">
          <div class="row g-3 align-items-end">
            <div class="col-sm-2">
              <div class="antecedentes-subcard-title">Cirug&iacute;as</div>
              <div class="si-no-toggle">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pp_qx" id="pp_qx1" value="Si" @if($pp_qx == "Si") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pp_qx1">S&iacute;</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pp_qx" id="pp_qx2" value="No" @if($pp_qx == "No") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pp_qx2">No</label>
                </div>
              </div>
            </div>
            <div class="col-sm-4 antecedentes-field">
              <label>Tipo</label>
              <input type="text" name="pp_qx_tipo" class="form-control" value="{{ $pp_qx_tipo }}">
            </div>
            <div class="col-sm-2">
              <div class="antecedentes-subcard-title">Fracturas</div>
              <div class="si-no-toggle">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pp_fx" id="pp_fx1" value="Si" @if($pp_fx == "Si") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pp_fx1">S&iacute;</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pp_fx" id="pp_fx2" value="No" @if($pp_fx == "No") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pp_fx2">No</label>
                </div>
              </div>
            </div>
            <div class="col-sm-4 antecedentes-field">
              <label>Tipo</label>
              <input type="text" name="pp_fx_tipo" class="form-control" value="{{ $pp_fx_tipo }}">
            </div>
          </div>
        </div>

        <div class="antecedentes-subcard">
          <div class="row g-3 align-items-end">
            <div class="col-sm-3 antecedentes-field">
              <label>Alergias</label>
              <input type="text" name="pp_alergia" class="form-control" value="{{ $pp_alergia }}">
            </div>

            <div class="col-sm-2">
              <div class="antecedentes-subcard-title">Transfusiones</div>
              <div class="si-no-toggle">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pp_transfusiones" id="pp_transfusiones1" value="Si" @if($pp_transfusiones == "Si") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pp_transfusiones1">S&iacute;</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pp_transfusiones" id="pp_transfusiones2" value="No" @if($pp_transfusiones == "No") {{ 'checked' }} @endif>
                  <label class="form-check-label" for="pp_transfusiones2">No</label>
                </div>
              </div>
            </div>

            <div class="col-sm-1 antecedentes-field">
              <label>#</label>
              <input type="text" name="pp_transfusiones_numero" class="form-control" value="{{ $pp_transfusiones_numero }}">
            </div>

            <div class="col-sm-3 antecedentes-field">
              <label>Inicial</label>
              <input type="text" name="pp_transfusiones_inicial" class="form-control" value="{{ $pp_transfusiones_inicial }}">
            </div>

            <div class="col-sm-3 antecedentes-field">
              <label>&Uacute;ltima</label>
              <input type="text" name="pp_transfusiones_ultima" class="form-control" value="{{ $pp_transfusiones_ultima }}">
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-12 antecedentes-field">
            <label>Enfermedades</label>
            <textarea name="pp_patias" class="form-control" rows="2">{{ $pp_patias }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="d-grid gap-2 text-center">
      <button class="btn btn-success" type="submit">
        <i class="fas fa-save"></i> Guardar antecedentes
      </button>   
    </div>

  </form>

@endsection
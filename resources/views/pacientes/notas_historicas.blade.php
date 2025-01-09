@extends('pacientes.paciente_header')

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('lib/js/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">

@endsection

@section('paciente')

	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNotaHistorica">
	  Registrar nota de evoluci&oacute;n historica
	</button>

	<form method="post" action="/paciente/notas-hist/insert" id="form-nota-historica">

	@csrf
	<input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

	<div class="modal fade" id="modalNotaHistorica" tabindex="-1" role="dialog" aria-labelledby="ModalLongTitle" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="ModalLongTitle">Registrar nota de evoluci&oacute;n</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">

	      	<div class="row">
	      		<div class="col-6">
	      			<input class="form-control" type="date" name="fecha" id="fecha" placeholder="Fecha">	
	      		</div>
	      		<div class="col-6" style="font-size: 20px;">
	      			<b>Paciente:</b> {{ $paciente->nombre_s }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}
						</div>
	      	</div>
	      	<br>
	      	<div class="row">
					  <div class="col-3">
					    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					      <a class="nav-link active" id="v-pills-subjetivo-tab" data-toggle="pill" href="#v-pills-subjetivo" role="tab" aria-controls="v-pills-subjetivo" aria-selected="true">Subjetivo</a>
					      <a class="nav-link" id="v-pills-objetivo-tab" data-toggle="pill" href="#v-pills-objetivo" role="tab" aria-controls="v-pills-objetivo" aria-selected="false">Objetivo</a>
					      <a class="nav-link" id="v-pills-analisis-tab" data-toggle="pill" href="#v-pills-analisis" role="tab" aria-controls="v-pills-analisis" aria-selected="false">Analisis</a>
					      <a class="nav-link" id="v-pills-plan-tab" data-toggle="pill" href="#v-pills-plan" role="tab" aria-controls="v-pills-plan" aria-selected="false">Planeacion</a>
					    </div>
					  </div>
					  <div class="col-9">
					    <div class="tab-content" id="v-pills-tabContent">
					      <div class="tab-pane fade show active" id="v-pills-subjetivo" role="tabpanel" aria-labelledby="v-pills-subjetivo-tab">
					      	<textarea class="form-control" rows="5" id="subjetivo" name="subjetivo"></textarea>
					      </div>
					      <div class="tab-pane fade" id="v-pills-objetivo" role="tabpanel" aria-labelledby="v-pills-objetivo-tab">
					      	







					      						        <div class="row g-3">
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> TA:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ta" name="ta" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> FC:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fc" name="fc" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> FR:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="fr" name="fr" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Temp:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="temp" name="temp" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Talla:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="calcularImc(this)" id="talla" name="talla" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Peso:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="calcularImc(this)" id="peso" name="peso" value="">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> IMC:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" id="imc" name="imc" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> SatO2:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="sat_o2" name="sat_o2" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hb:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hb" name="hb" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hto:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hto" name="hto" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Vcm:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="vcm" name="vcm" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hcm:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hcm" name="hcm" value="">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Erit. hipoc. %:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="porcentaje_eritrocitos_hipocromicos" name="porcentaje_eritrocitos_hipocromicos" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Plaq:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="plaq" name="plaq" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Leuc:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="leuc" name="leuc" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Cr:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="cr" name="cr" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> CKD-EPI:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ckdepi" name="ckdepi" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Bun:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="bun" name="bun" value="">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> G:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="g" name="g" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Hba1c %:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hba1c_porcentaje" name="hba1c_porcentaje" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Insulina serica:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="insulina_serica" name="insulina_serica" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> HOMA:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="homa" name="homa" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Au:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="au" name="au" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Na:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="na" name="na" value="">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> K:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="k" name="k" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Cl:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="cl" name="cl" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Ca:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ca" name="ca" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> P:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="p" name="p" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Mg:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="mg" name="mg" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Alb:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="alb" name="alb" value="">
    </div>
  </div>
  <br><br>
  <div class="row g-3">
    
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="col" name="col" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Tgs:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="tgs" name="tgs" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> HDL Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="hdl_col" name="hdl_col" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> LDL Col:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ldl_col" name="ldl_col" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> Ego:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="ego" name="ego" value="">
    </div>
    <div class="col-sm-2">
      <h6 class="font-weight-bold">
        <i class="fas fa-check-circle text-primary d-none"></i> AlbU/CrU:
      </h6>
      <input type="text" class="form-control border border-info rounded-pill" onkeyup="resaltarInput(this)" id="albu_cru" name="albu_cru" value="">
    </div>
  </div>
  
  <br>
  
  <div class="row g-3">
    <div class="col-sm-12">
      <h6 class="font-weight-bold">
        Exploraci&oacute;n fisica:
      </h6>
      <textarea class="form-control" id="exploracion_fisica" name="exploracion_fisica" rows="7"></textarea>
    </div>
  </div>













					      </div>
					      <div class="tab-pane fade" id="v-pills-analisis" role="tabpanel" aria-labelledby="v-pills-analisis-tab">
					      	<textarea class="form-control" rows="5" id="analisis" name="analisis"></textarea>
					      </div>
					      <div class="tab-pane fade" id="v-pills-plan" role="tabpanel" aria-labelledby="v-pills-plan-tab">
					      	<button type="button" class="btn btn-success" class="btn btn-primary" id="btn-agregar-inputs">
					        			Agregar registro
					        		</button>
									<br><br>
									<div id="inputsContainer"></div>
					      </div>
					    </div>
					  </div>
					</div>
					<br>
	        <p class="text-center">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
	        	<button type="submit" class="btn btn-primary" id="btn-submit">Guardar</button>
	        </p>
	      </div>
	    </div>
	  </div>
	</div>

	<br>
	<p>
		<b><i>Historial de notas previas al uso de la agenda digital:</i></b> 
		{{ count($notasHistoricas) }}
	</p>

	<table class="table table-bordered table-hover" id="table-notas">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Subjetivo</th>
				<th>Objetivo</th>
				<th>Analisis</th>
				<th>Plan</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@foreach($notasHistoricas as $notaHistorica)
			<tr>
				<td class="text-center text-primary"><b>{{ $notaHistorica->fecha }}</b></td>
				<td>{{ $notaHistorica->subjetivo }}</td>
				<td class="text-center">
					<button class="btn btn-info" data-toggle="modal" data-target="#objetivoModal-{{ $notaHistorica->id }}" type="button">
						Ver datos
					</button>
				</td>
				<td>{{ $notaHistorica->analisis }}</td>
				<td class="text-center">
					<button class="btn btn-info" data-toggle="modal" data-target="#planModal-{{ $notaHistorica->id }}" type="button" type="button">
						Ver planeaci&oacute;n
					</button>
				</td>
				<td class="text-center">
					<a class="btn btn-primary" href="/nota-hist/{{ $notaHistorica->id }}">
						<i class="fas fa-pencil-alt"></i>
					</a>
          <a class="btn btn-danger" href="/nota-hist/confirmar-borrar/{{ $notaHistorica->id }}">
            <i class="fas fa-trash-alt"></i>
          </a>
				</td>
			</tr>

      <!-- Modal -->
      <div class="modal fade" id="objetivoModal-{{ $notaHistorica->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Objetivo de nota de evoluci&oacute;n del d&iacute;a <b>{{ $notaHistorica->fecha }}</b>, paciente <b>{{ $paciente->nombre_s }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-2"><b>TA:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>FC:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>FR:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Temp:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Talla:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Peso:</b> {{ $notaHistorica->obj_ta }}</div>
              </div>
              <br>
              <div class="row">
                <div class="col-2"><b>IMC:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>SatO2:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Hb:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Hto:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Vcm:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Hcm:</b> {{ $notaHistorica->obj_ta }}</div>
              </div>
              <br>
              <div class="row">
                <div class="col-2"><b>Erit. hipoc. %:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Plaq:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Leuc:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Cr:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>CKD-EPI:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Bun:</b> {{ $notaHistorica->obj_ta }}</div>
              </div>
              <br>
              <div class="row">
                <div class="col-2"><b>G:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Hba1c %:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Insulina serica:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>HOMA:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Au:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Na:</b> {{ $notaHistorica->obj_ta }}</div>
              </div>
              <br>
              <div class="row">
                <div class="col-2"><b>K:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Cl:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Ca:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>P:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Mg:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Alb:</b> {{ $notaHistorica->obj_ta }}</div>
              </div>
              <br>
              <div class="row">
                <div class="col-2"><b>Col:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Tgs:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>HDL Col:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>LDL Col:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>Ego:</b> {{ $notaHistorica->obj_ta }}</div>
                <div class="col-2"><b>AlbU/CrU:</b> {{ $notaHistorica->obj_ta }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- Modal -->
      <div class="modal fade" id="planModal-{{ $notaHistorica->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Plan de nota de evoluci&oacute;n del d&iacute;a <b>{{ $notaHistorica->fecha }}</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <ul class="list-group">
              @foreach($notaHistorica->getPlanHist as $planHist)

                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $planHist->plan }}
                    <span class="badge badge-primary badge-pill">
                      {{ $planHist->getTipoPlan->tipo_plan }}
                    </span>
                  </li>
                
              @endforeach
              </ul>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
		@endforeach
		</tbody>
	</table>

@endsection

@section('scripts')

	<script type="text/javascript" src="{{ asset('lib/js/jquery/dist/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/js/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/js/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

	<script type="text/javascript">
		
    document.addEventListener('DOMContentLoaded', () => {
        // Escuchar clics en los botones de eliminar
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('btn-eliminar')) {
                const row = event.target.closest('.row'); // Encuentra el div con la clase "row"
                if (row) {
                    row.remove(); // Elimina el div
                    console.log('Elemento eliminado');
                }
            }
        });
    });

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

		var indiceInputs = 0;

    document.getElementById("btn-agregar-inputs").addEventListener("click", function () {
	    
	    const container = document.getElementById("inputsContainer");
	    
	    const newInputDesc = document.createElement("textarea");
	    const newInputEvid = document.createElement("select");
      const newButtonDel = document.createElement("button");
	    const newDivRow = document.createElement("div");
	    const newCol1 = document.createElement("div");
	    const newCol2 = document.createElement("div");
      const newCol3 = document.createElement("div");
	    const br = document.createElement("br");

	    newInputDesc.classList.add('form-control');
			newInputDesc.rows = 3;
			newInputDesc.placeholder = "";
			newInputDesc.name = "plan[" + indiceInputs + "][plan]";

			newInputEvid.classList.add('form-control');
			newInputEvid.name = "plan[" + indiceInputs + "][tipo_plan_id]";
		
			var option = document.createElement("option");
					option.value = ''
				  option.text = "Seleccione una opcion";
				  newInputEvid.add(option);

			var option = document.createElement("option");
					option.value = '1'
				  option.text = "Diagnostico nefrolofico";
				  newInputEvid.add(option);

			var option = document.createElement("option");
					option.value = '2'
				  option.text = "Diagnostico CIE-10";
				  newInputEvid.add(option);

			var option = document.createElement("option");
					option.value = '3'
				  option.text = "Tratamiento";
				  newInputEvid.add(option);

      newButtonDel.type = 'button'
      newButtonDel.classList.add('btn', 'btn-danger', 'btn-eliminar');
      newButtonDel.innerText = 'Borrar'
			
			newDivRow.classList.add('row');
	    newCol1.classList.add('col-5');
	    newCol2.classList.add('col-5');
      newCol3.classList.add('col-2');

	    newCol1.appendChild(newInputDesc);
	    newCol2.appendChild(newInputEvid);
      newCol3.appendChild(newButtonDel);

	    newDivRow.appendChild(newCol2);
	    newDivRow.appendChild(newCol1);
      newDivRow.appendChild(newCol3);
	    
			container.appendChild(newDivRow);
	    container.appendChild(br);

	    indiceInputs = indiceInputs + 1;

    });

		document.getElementById('form-nota-historica').addEventListener('submit', function(event) {
	    
	    var fecha = document.getElementById('fecha').value;
	    var subjetivo = document.getElementById('subjetivo').value;
	    var analisis = document.getElementById('analisis').value;
	    var totalFaltantes = 0;
	    var mensajeValidacion = '';

	    if(!fecha){
	    	mensajeValidacion += ' Fecha de la nota ';
	    	totalFaltantes = totalFaltantes + 1;
	    }

	    if(!subjetivo){
	    	mensajeValidacion += ' Subjetivo ';
	    	totalFaltantes = totalFaltantes + 1;
	    }

	    if(!analisis){
	    	mensajeValidacion += ' Analisis ';
	    	totalFaltantes = totalFaltantes + 1;
	    }

	    // Busca inputs dinámicos que sigan el patrón `plan[indice][...]`
	    const inputs = document.querySelectorAll('[name^="plan["]');
	    
	    // Si no hay elementos, evita el envío del formulario
	    if (inputs.length === 0) {
	      mensajeValidacion += ' Plan ';
	      totalFaltantes = totalFaltantes + 1;
	    } else {

	    	const planInputs = document.querySelectorAll('[name^="plan["][name$="[plan]"]');
	    	const tipoPlanInputs = document.querySelectorAll('[name^="plan["][name$="[tipo_plan_id]"]');

	    	 // Verifica que cada par de inputs tenga datos
		    for (let i = 0; i < planInputs.length; i++) {
		      if (!planInputs[i].value.trim() || !tipoPlanInputs[i]?.value.trim()) {
		        //event.preventDefault(); // Detener envío
		        //alert('Todos los pares de inputs deben tener datos.');
		      	//return; // sale de la funcion
		      	mensajeValidacion += ' Plan ';
		      	totalFaltantes = totalFaltantes + 1;
		        break; // sale del bucle
		      }
		    }
	    }

	    if(totalFaltantes > 0){
	    	event.preventDefault(); // detiene el envío
	      alert('Complete la siguiente informacion para poder guardar la nota: ' + mensajeValidacion);
	      return; // sale de la funcion
	    }

			document.getElementById('btn-submit').disabled = true;
	  });

		$(document).ready(function () {
	      $('#table-notas').DataTable({
	          order: [[0, 'desc']],
	          language: {
	            url: '{{ asset('js/dataTables.es-MX.json') }}'
	          }
	      });
	  }); 

	</script>
@endsection


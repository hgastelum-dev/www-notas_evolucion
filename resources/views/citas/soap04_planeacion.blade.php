@extends('citas.atender')

@section('seccion-cita')
  
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
  <style type="text/css">
    /* vista previa tamaño carta a escala, dentro del modal del editor */
    .preview-carta-wrapper {
        display: flex;
        justify-content: center;
        background: #e9ecef;
        padding: 15px;
        border-radius: 4px;
    }
    .preview-carta-pagina {
        position: relative;
        width: 400px;   /* 8.5in a escala */
        height: 517px;  /* 11in a escala, misma proporcion (400/8.5 = 517/11) */
        background: #fff;
        box-shadow: 0 0 6px rgba(0,0,0,.35);
        overflow: hidden;
    }
    .preview-carta-contenido {
        position: absolute;
        left: 24px;
        right: 24px;
        font-size: 11px;
        line-height: 1.35;
        color: #212529;
    }
    .preview-carta-contenido p { margin: 3px 0; }
    .preview-carta-contenido ul,
    .preview-carta-contenido ol { margin: 2px 0 6px 18px; }
    .preview-carta-label {
        text-align: center;
        font-size: 12px;
        color: #6c757d;
        margin-top: 6px;
    }
  </style>

  <div class="mb-3">
    <br>
    
    @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
    @endif

    <p>
      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#div-nuevo-plan" aria-expanded="false" aria-controls="div-nuevo-plan">
        Añadir nuevo diagnóstico / tratamiento
      </button>
    </p>

    <div class="collapse" id="div-nuevo-plan">
      <form method="post" action="/cita/soap04/planeacion">
      @csrf
      <input type="hidden" name="cita_paciente_id" value="{{ $cita->id }}">
      <input type="hidden" name="desdeSoap" value="1">
      <div class="card card-body">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="tipo_plan_id" class="font-weight-bold">Tipo de plan</label>
              <select class="form-control border border-info rounded" id="tipo_plan_id" name="tipo_plan_id" onchange="obtenerCie10(this.value)" required>
                <option value="">Seleccione una opci&oacute;n</option>
                @foreach($tiposPlaneacion as $tipoPlaneacion)
                  <option value="{{ $tipoPlaneacion->id }}">{{ $tipoPlaneacion->tipo_plan }}</option>
                  
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="form-group">
              <label for="plan" class="font-weight-bold">Descripci&oacute;n</label>
              <select class="form-control" id="search-result" onchange="copiarTexto(this.value)">
              </select>
              <textarea class="form-control border border-info rounded" rows="8" id="plan" name="plan" required></textarea>
              <br>
              <p class="text-center">
                <button class="btn btn-success" type="submit">
                  Guardar
                </button>    
              </p>
            </div>
          </div>
        </div>
      </div>
      </form>
    </div>

    <br>
    <h4 class="text-center">
      Diagnósticos y tratamientos<br>
      de <b>cita anterior:</b>  <br>
      @if($cita->getCitaAnterior)
        <i class="fas fa-calendar-alt"></i> {{ $cita->getCitaAnterior->fecha }}
      @else
        <b class="text-danger">
          <i class="fas fa-exclamation-circle"></i> Primera cita del paciente, favor de actualizar sus Diagnósticos y Tratamientos...
        </b>
      @endif
    </h4>

    @if(!empty($planesAgrupado['Tratamiento']))
      <div class="text-right mb-3">
        <button class="btn btn-danger" onclick="abrirReceta({{ $cita->id }})">
          <i class="fas fa-file-pdf"></i> Ver receta
        </button>
      </div>

      {{--
        snapshot de los tratamientos actuales, en el formato que se
        precargara en el editor quill... es de solo lectura (d-none), nunca
        se envia a ningun lado; solo sirve para "sembrar" el editor y para
        el boton "Restaurar original"...
      --}}
      <div id="receta-contenido-original" class="d-none">
        @foreach($planesAgrupado['Tratamiento'] as $tratamiento)
          <p><strong>{{ $tratamiento->plan }}</strong></p>
          @if($tratamiento->getTipoPlanAnidado->count() > 0)
            <ul>
              @foreach($tratamiento->getTipoPlanAnidado as $hijo)
                <li>{{ $hijo->plan }}</li>
              @endforeach
            </ul>
          @endif
        @endforeach
      </div>

      {{-- modal con el editor quill --}}
      <div class="modal fade" id="modal-receta-editor" tabindex="-1" aria-labelledby="recetaEditorLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 90%;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="recetaEditorLabel">
                <i class="fas fa-file-medical-alt"></i> Editar receta antes de imprimir
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="text-muted mb-2">
                <i class="fas fa-info-circle"></i> Estos cambios son solo para esta impresión; no se guardan en el expediente del paciente.
              </p>

              {{-- control de posicion vertical, para cuadrar con la hoja membretada --}}
              <div class="card card-body bg-light mb-3">
                <label class="mb-1"><b><i class="fas fa-arrows-alt-v"></i> Posición vertical del texto</b></label>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                  <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-margen-subir-5" title="Subir 5px">▲▲</button>
                  <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-margen-subir-1" title="Subir 1px">▲</button>
                  <input type="number" class="form-control form-control-sm text-center" id="margen-top-receta" style="width: 90px;" value="200" min="0" max="600">
                  <span class="text-muted">px desde arriba</span>
                  <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-margen-bajar-1" title="Bajar 1px">▼</button>
                  <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-margen-bajar-5" title="Bajar 5px">▼▼</button>
                  <button type="button" class="btn btn-outline-primary btn-sm ml-auto" id="btn-margen-guardar-default">
                    <i class="fas fa-save"></i> Recordar en este equipo
                  </button>
                </div>
                <small class="text-muted mt-1" id="margen-guardado-msg" style="display:none;">
                  <i class="fas fa-check text-success"></i> Guardado. La próxima receta ya abrirá con esta posición.
                </small>
              </div>

              <div class="row">
                  <div class="col-md-7">
                      <div id="quill-toolbar-receta">
                        <span class="ql-formats">
                          <button class="ql-bold"></button>
                          <button class="ql-italic"></button>
                          <button class="ql-underline"></button>
                        </span>
                        <span class="ql-formats">
                          <button class="ql-list" value="ordered"></button>
                          <button class="ql-list" value="bullet"></button>
                        </span>
                        <span class="ql-formats">
                          <button class="ql-clean"></button>
                        </span>
                      </div>
                      <div id="quill-editor-receta" style="height: 350px; background: #fff;"></div>
                  </div>
                  <div class="col-md-5">
                      <div class="preview-carta-wrapper">
                          <div class="preview-carta-pagina">
                              <div id="preview-carta-contenido" class="preview-carta-contenido"></div>
                          </div>
                      </div>
                      <div class="preview-carta-label">Vista previa (hoja carta a escala)</div>
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="btn-restaurar-receta">
                <i class="fas fa-undo"></i> Restaurar original
              </button>
              <button type="button" class="btn btn-success" id="btn-generar-pdf-receta">
                <i class="fas fa-file-pdf"></i> Generar PDF
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- form oculto que manda el HTML editado al backend, apuntando al iframe de abajo --}}
      <form id="form-receta-editor" method="POST" action="{{ route('receta.generar.pdf') }}" target="iframe-receta" style="display:none;">
        @csrf
        <input type="hidden" name="cita_id" value="{{ $cita->id }}">
        <input type="hidden" name="margin_top" id="input-margin-top">
        <textarea name="contenido_html" id="input-contenido-receta"></textarea>
      </form>
    @endif

    {{-- agrupado --}}
    <table class="table table-hover table-bordered">
      <tbody>
        @foreach($planesAgrupado as $key => $planAnidado)

          <tr class="text-center bg-primary text-white">
            <th colspan="4">
              <h3>
                <span class="badge badge-info">{{ $key }}s</span>
              </h3>
            </th>        
          </tr>

          @php
            $consecutivo = 0;
          @endphp

          @foreach($planAnidado as $plan)

            @php
              $consecutivo = $consecutivo + 1;
            @endphp

            <tr class="bg-light" id="tr-tp-{{ $plan->id }}">
              <td class="text-center text-nowrap" style="width: 1px;">

                <button type="button" class="btn btn-sm btn-primary" value="{{ $plan->id }}" onclick="getModalEdit(event,this.value)" data-toggle="modal" data-target="#modal-editar">
                  <i class="fas fa-pencil-alt"></i>
                </button>

                <button type="button" class="btn btn-sm btn-danger" data-toggle="popover" data-html="true" title="Atento aviso" data-content="Est&aacute; seguro de querer borrar el registro seleccionado y sus subpuntos correspondientes?<br><button class='btn btn-sm btn-danger' onclick='eliminarPlan(this.value)' value='{{ $plan->id }}'>Si</button>&nbsp;<button class='btn btn-sm btn-info'>No</button>">
                  <i class="fas fa-trash-alt"></i>
                </button>

              </td>
              <td class="text-center text-nowrap" style="width: 1px;">
                  @if(session('userAlerts') && session('userAlerts')['icono'] == $plan->id)
                    <span class="badge badge-{{ session('userAlerts')['titulo'] }}">
                      <i class="fas fa-check"></i> {{ session('userAlerts')['mensaje'] }}
                    </span>
                  @endif 
                  <span class="badge badge-primary">
                    {{ substr($key, 0, 1) . '-' . $consecutivo }}
                  </span>
              </td>
              <td class="font-weight-bold">{{ $plan->plan }}</td>
              <td class="text-center" style="width: 1px;">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-subpunto-{{ $plan->id }}">
                  <i class="fas fa-plus"></i>
                </button>
              </td>
            </tr>

            <div class="modal fade" id="modal-subpunto-{{ $plan->id }}" tabindex="-1" aria-labelledby="{{ $plan->id }}-ModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="{{ $plan->id }}-ModalLabel">
                      Agregar subpunto
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post" action="/cita/soap04/planeacion">
                  
                  @csrf
                  <input type="hidden" name="cita_paciente_id" value="{{ $cita->id }}">
                  <input type="hidden" name="padre_id" value="{{ $plan->id }}">
                  <input type="hidden" name="tipo_plan_id" value="{{ $plan->getTipoPlan->id }}">
                  
                  <div class="modal-body">
                    <h3>
                      <span class="badge badge-info">{{ $plan->getTipoPlan->tipo_plan }}:</span>
                      {{ $plan->plan }}
                    </h3>
                    <br>
                    <b>Observaciones:</b>
                    <br>
                    <textarea class="form-control border border-info rounded" name="plan" rows="12" required></textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>{{-- aqui cierra la ventana modal --}}

            @php
              $subconsecutivo = 0;
            @endphp

            @foreach($plan->getTipoPlanAnidado as $planHijo)

            @php
              $subconsecutivo = $subconsecutivo + 1;
            @endphp
            
            <tr id="tr-tp-{{ $planHijo->id }}">
              <td class="text-center" style="width: 1px;">
                <button type="button" class="btn btn-sm btn-info" value="{{ $planHijo->id }}" onclick="getModalEdit(event,this.value)" data-toggle="modal" data-target="#modal-editar">
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning" data-toggle="popover" data-html="true" title="Atento aviso" data-content="Est&aacute; seguro de querer borrar el registro seleccionado?<br><button class='btn btn-sm btn-danger' onclick='eliminarPlan(this.value)' value='{{ $planHijo->id }}'>Si</button>&nbsp;<button class='btn btn-sm btn-info'>No</button>">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
              <td class="text-center text-nowrap" style="width: 1px;">
                @if(session('userAlerts') && session('userAlerts')['icono'] == $planHijo->id)
                  <span class="badge badge-{{ session('userAlerts')['titulo'] }}">
                    <i class="fas fa-check"></i> {{ session('userAlerts')['mensaje'] }}
                  </span>
                @endif 
                <span class="badge badge-info">
                  {{ substr($key, 0, 1) . '-' . $consecutivo }}.{{ $subconsecutivo }}
                </span>
              </td>
              <td colspan="2">{{ $planHijo->plan }}</td>
            </tr>
            @endforeach
          @endforeach
        @endforeach
      </tbody>
    </table>
    {{-- termina agrupado --}}
    
    {{-- modal para modificar un registro plan --}}
    <div class="modal fade" id="modal-editar" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarModalLabel">
              Modificar registro
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="/cita/soap04/planeacion/update">

          @csrf
          <div class="modal-body">
            <b>Observaciones:</b>
            <br>
            <input type="hidden" id="plan_id_edit" name="plan_id_edit" value="">
            <textarea class="form-control border border-info rounded" name="plan_edit" id="plan_edit" rows="12" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <form method="post" action="/cita/soap04/planeacion/delete" id="form-delete-plan">
    @csrf
    <input type="hidden" name="cita_planeacion_id" id="cita_planeacion_id">
  </form>

  <div class="modal fade" id="modal-receta" tabindex="-1">
    <div class="modal-dialog modal-xl" style="max-width: 95%;">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title">Receta médica</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body p-0" style="height: 80vh;">
          <iframe id="iframe-receta" name="iframe-receta" src="" width="100%" height="100%" style="border:none;"></iframe>
        </div>

      </div>
    </div>
  </div>

@endsection

@section('scripts')
  
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <script type="text/javascript">

    $(function () {
      $('[data-toggle="popover"]').popover({sanitize: false})
    })

    function copiarTexto(texto){
      document.getElementById('plan').value = '';
      document.getElementById('plan').value = texto;
    }

    function obtenerCie10(tipo_plan_id){
      if(tipo_plan_id == 2){
        
        $("#plan").on("keyup", function(){
        var search = $(this).val();
        
        if (search !=="") {
          $.ajax({
            url:"/ajax/search",
            type:"POST",
            cache:false,
            data:{
              _token: '{{ csrf_token() }}',
              term:search
            },
            success:function(data){
              
              var x = document.getElementById("search-result");
              x.innerHTML = ''

              var option = document.createElement("option");
                option.value = '';
                option.text = 'Seleccione una opción';
                x.add(option);
              for(var i = 0; i < data.length; i++){
                var option = document.createElement("option");
                option.value = data[i].letra + ' - ' + data[i].codigo + ' - ' + data[i].diagnostico;
                option.text = data[i].letra + ' - ' + data[i].codigo + ' - ' + data[i].diagnostico;
                x.add(option);
              }
              $("#search-result").fadeIn();
            }  
          });
        } else {
          $("#search-result").html("");  
          $("#search-result").fadeOut();
        }
      });
      
      $(document).on("click","li", function(){
        $('#plan').val($(this).text());
        $('#search-result').fadeOut("fast");
      });
      }
    }

    function getModalEdit(e,planId){
      
      var table = document.getElementsByTagName("table")[0];
      var tbody = table.getElementsByTagName("tbody")[0];
      e = e || window.event;
      var data = [];
      var target = e.srcElement || e.target;
      while (target && target.nodeName !== "TR") {
          target = target.parentNode;
      }
      if (target) {
          var cells = target.getElementsByTagName("td");
          document.getElementById('plan_id_edit').value = planId; 
          document.getElementById('plan_edit').value = cells[2].innerHTML; 
      }
    }

    function eliminarPlan(planId){
      
      document.getElementById('cita_planeacion_id').value = planId;

      document.getElementById("form-delete-plan").submit();
    }

    // editor de receta con quill... la edicion es solo para esta
    // impresion, nunca toca los registros..
    var quillReceta = null;

    function inicializarQuillReceta(){
      if (quillReceta) return quillReceta;

      quillReceta = new Quill('#quill-editor-receta', {
        theme: 'snow',
        modules: { toolbar: '#quill-toolbar-receta' }
      });

      quillReceta.on('text-change', actualizarPreviewReceta); 

      return quillReceta;
    }

    function contenidoOriginalReceta(){
      return document.getElementById('receta-contenido-original').innerHTML.trim();
    }

    // posicion vertical del texto, recordada por equipo/impresora
    // (localStorage), no por paciente ni por cita...
    var LS_KEY_MARGEN_RECETA = 'receta_margen_top_px';
    // escala usada en el preview: 400px de ancho representan 8.5in (=816px a 96dpi)
    var ESCALA_PREVIEW_CARTA = 400 / 816;

    function actualizarPreviewReceta(){
        var contenedor = document.getElementById('preview-carta-contenido');
        if (!contenedor || !quillReceta) return;

        var margenPx = parseInt(document.getElementById('margen-top-receta').value || 0, 10);
        contenedor.style.top = Math.round(margenPx * ESCALA_PREVIEW_CARTA) + 'px';
        contenedor.innerHTML = quillReceta.root.innerHTML;
    }

    function margenTopGuardado(){
      var valor = localStorage.getItem(LS_KEY_MARGEN_RECETA);
      return valor ? parseInt(valor, 10) : 200;
    }

    function ajustarMargen(delta){
      var input = document.getElementById('margen-top-receta');
      var nuevo = parseInt(input.value || 0, 10) + delta;
      if (nuevo < 0) nuevo = 0;
      input.value = nuevo;
      actualizarPreviewReceta();
    }

    document.getElementById('btn-margen-subir-5').addEventListener('click', function(){ ajustarMargen(-5); });
    document.getElementById('btn-margen-subir-1').addEventListener('click', function(){ ajustarMargen(-1); });
    document.getElementById('btn-margen-bajar-1').addEventListener('click', function(){ ajustarMargen(1); });
    document.getElementById('btn-margen-bajar-5').addEventListener('click', function(){ ajustarMargen(5); });
    document.getElementById('margen-top-receta').addEventListener('input', actualizarPreviewReceta);

    document.getElementById('btn-margen-guardar-default').addEventListener('click', function(){
      localStorage.setItem(LS_KEY_MARGEN_RECETA, document.getElementById('margen-top-receta').value);

      var msg = document.getElementById('margen-guardado-msg');
      msg.style.display = 'inline';
      setTimeout(function(){ msg.style.display = 'none'; }, 2500);
    });

    function abrirReceta(citaId){
      var editor = inicializarQuillReceta();

      editor.root.innerHTML = contenidoOriginalReceta();
      document.getElementById('margen-top-receta').value = margenTopGuardado();
      actualizarPreviewReceta();
      $('#modal-receta-editor').modal('show');
    }

    document.getElementById('btn-restaurar-receta').addEventListener('click', function(){
      quillReceta.root.innerHTML = contenidoOriginalReceta();
      document.getElementById('margen-top-receta').value = margenTopGuardado();
      actualizarPreviewReceta();
    });

    document.getElementById('btn-generar-pdf-receta').addEventListener('click', function(){
      document.getElementById('input-contenido-receta').value = quillReceta.root.innerHTML;
      document.getElementById('input-margin-top').value = document.getElementById('margen-top-receta').value;

      $('#modal-receta-editor').modal('hide');
      $('#modal-receta').modal('show');

      document.getElementById('form-receta-editor').submit();
    });
  </script>
  
@endsection
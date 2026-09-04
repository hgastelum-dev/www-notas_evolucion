document.getElementById("button-nueva-sesion").addEventListener("click", function(){

  var modalSesionNueva = new bootstrap.Modal(document.getElementById('modalSesionNueva'));

  modalSesionNueva.show();
});

$(function () {
  
  $('#fechaCambio').datetimepicker({
    format: 'YYYY-MM-DD',
    sideBySide: true
  });

  $('#horaInicioCambio').datetimepicker({
    format: 'HH:mm',
    sideBySide: true
  });

  $('#fechaInicioCambio').on('change.datetimepicker',function(e){
    
    $('#fechaTerminoCambio').val( $(this).val() );
  });

  $('#fecha-nueva').datetimepicker({
    format: 'YYYY-MM-DD',
    sideBySide: true
  });

  $('#hora-inicia-nueva').datetimepicker({
    format: 'HH:mm', 
    sideBySide: true
  });

  $('#inicia-recurrencia').datetimepicker({
    format: 'YYYY-MM-DD',
    sideBySide: true
  });
  
  $('#termina-recurrencia').datetimepicker({
    format: 'YYYY-MM-DD',
    sideBySide: true
  });

   $('#hora-inicia-recurrencia').datetimepicker({
    format: 'HH:mm',
    sideBySide: true
  });
  
  $('#hora-termina-recurrencia').datetimepicker({
    format: 'HH:mm',
    sideBySide: true
  });
});

$('#paciente_id').select2( { 
  theme: "bootstrap-5",
  dropdownParent: $('#modalSesionNueva') 
} );

$('#paciente_recurrente_id').select2( { 
  theme: "bootstrap-5",
  dropdownParent: $('#modalSesionesRecurrentes') 
} );

var calendar;

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'es',
      timezone: 'America/Tijuana',
      plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
      defaultView: 'dayGridWeek',
      businessHours: true,
      displayEventEnd: true,
      header: {
        left: 'prev,next',
        center: 'title',
        right: 'dayGridMonth,listMonth,dayGridDay,dayGridWeek'
      },
      editable: false,
      events: function(info, successCallback, failureCallback) {
        var doctorId = document.getElementById('doctor_id_agenda').value;
        fetch('/citas?start=' + info.startStr + '&end=' + info.endStr + '&doctor_id=' + doctorId)
          .then(res => res.json())
          .then(data => successCallback(data))
          .catch(err => failureCallback(err));
      },
      eventRender: function(info) {
        
        var timeElement = info.el.querySelector('.fc-time');

        if (timeElement && timeElement.innerText.includes('-')) {
          let horaInicio = timeElement.innerText.split('-')[0].trim();
          timeElement.innerText = horaInicio;
        }

        var listTimeEl = info.el.querySelector('.fc-list-item-time');
        if (listTimeEl && listTimeEl.innerText.includes('-')) {
          let horaInicio = listTimeEl.innerText.split('-')[0].trim();
          listTimeEl.innerText = horaInicio;
        }
      },
      eventClick: function(info){

        var modalSesionCambio = new bootstrap.Modal(document.getElementById('modalSesionCambio'));
        
        var idSesionCambio = document.getElementById("idSesionCambio");

        var pacienteCambio = document.getElementById("pacienteCambio");
        var statusCambio = document.getElementById("statusCambio");
        var fechaCambio = document.getElementById("fechaCambio");
        var horaInicioCambio = document.getElementById("horaInicioCambio");
        
        var divAlert = document.getElementById("userAlertCambio");

        pacienteCambio.innerHTML = info.event.title;
        divAlert.innerHTML = "";

        idSesionCambio.value = info.event.extendedProps.sesionId;
        
        document.getElementById('sesion-id-iniciar').value = idSesionCambio.value;

        fechaCambio.value = moment(info.event.start).format("YYYY-MM-DD");
        horaInicioCambio.value = moment(info.event.start).format("HH:mm");
        
        statusActualId = info.event.extendedProps.statusSesion;
        var statusArray = [ 2, 3, 4 ];
        
        switch(statusActualId){

          case 2: 
            var colorClase = "bg-warning text-white";
            document.getElementById('link-editar').classList.add('d-none')
            document.getElementById('link-editar').removeAttribute('href') 
            document.getElementById('btn-iniciar-cita').disabled = false     
            break;
          case 3: 
            var colorClase = "bg-danger text-white";
            document.getElementById('link-editar').classList.add('d-none')
            document.getElementById('link-editar').removeAttribute('href')  
            document.getElementById('btn-iniciar-cita').disabled = true    
            break; 

          case 4: 
            var colorClase = "bg-success text-white";
            document.getElementById('link-editar').classList.remove('d-none')
            document.getElementById('link-editar').setAttribute('href','/cita/soap01/subjetivo/' + idSesionCambio.value)
            document.getElementById('btn-iniciar-cita').disabled = true
            break; 

          default:
            document.getElementById('link-editar').classList.add('d-none')
            document.getElementById('link-editar').removeAttribute('href')
            document.getElementById('btn-iniciar-cita').disabled = false
            break;
        }

        var esMia = !!info.event.extendedProps.esMia;
        var puedeGestionar = !!info.event.extendedProps.puedeGestionar;

        var editable = esMia || puedeGestionar;

        var btnGuardarCambios = document.getElementById('ajuste-sesion-boton');
        var btnEliminarSesion = document.getElementById('eliminar-sesion-db');
        var linkEditar = document.getElementById('link-editar');

        if (editable) {
          document.getElementById('btn-iniciar-cita').classList.remove('d-none');
          btnGuardarCambios.classList.remove('d-none');
          btnEliminarSesion.classList.remove('d-none');
        } else {
          document.getElementById('btn-iniciar-cita').classList.add('d-none');
          btnGuardarCambios.classList.add('d-none');
          btnEliminarSesion.classList.add('d-none');
          
          linkEditar.classList.add('d-none');
          linkEditar.removeAttribute('href');
        }

        var selectInput = "<select class='form-control " + colorClase + "' id='statusSesionId' onChange='cambiarColor(this.value)' " + (esMia ? '' : 'disabled') + ">"

        selectInput += "<option value=''>Seleccione una opcion</option>"

        statusArray.forEach(function(elemento, indice){
            
            switch(elemento){

               case 2: 
                  var valorStatus = "Reprogramar";
                  
                  break;
               case 3: 
                  var valorStatus = "Cancelar";
                  
                  break; 

               case 4: 
                  var valorStatus = "Concluir";
                  
                  break; 

               default:
                  break;
            }

            if ( elemento == statusActualId ){

                selectInput += "<option value='" + elemento + "' selected>" + valorStatus + "</option>";
            } else {
                selectInput += "<option value='" + elemento + "'>" + valorStatus + "</option>";
            }
        });

        selectInput += "</select>";

        statusCambio.innerHTML = selectInput;

        if (!esMia) {
          divAlert.innerHTML = '<div class="alert alert-secondary mt-2"><i class="fas fa-eye"></i> Solo lectura: esta cita pertenece a la agenda de otro doctor.</div>';
        }

        modalSesionCambio.show();
      }
  });

  calendar.render();
});

document.getElementById('doctor_id_agenda').addEventListener('change', function(){
  calendar.refetchEvents();
});

document.getElementById("recargar-fc").addEventListener("click", function() {
  calendar.refetchEvents();
});

document.getElementById("ajuste-sesion-boton").addEventListener("click", function(event){

    var fechaCambio = document.getElementById("fechaCambio").value;
    var horaInicioCambio = document.getElementById("horaInicioCambio").value;
    
    if ( ! document.getElementById("statusSesionId").value ) { 
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: '¡Error!',
          html: 'Favor de seleccionar una opci&oacute;n del campo <i><b>Estado de la sesi&oacute;n</b></i> y capturar correctamente la <b>fecha/hora</b> de la sesi&oacute;n para guardar los cambios ',
          showConfirmButton: true,
          confirmButtonText: 'Cerrar aviso'
        });
        event.preventDefault();
        return false;
    }

    var url = '/cita/update';
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var data = {
      sesionId: document.getElementById("idSesionCambio").value,
      fecha: document.getElementById("fechaCambio").value,
      horaInicio: document.getElementById("horaInicioCambio").value,
      
      statusId: document.getElementById("statusSesionId").value,
      _token: token
    };

    fetch(url, {
        method: 'POST',
        body: JSON.stringify(data),
        headers:{
          'Content-Type': 'application/json'
        }
      }).then(res => res.json())
      .catch(error => console.error('Error: ', error))
      .then(response => sesionActualizada(response) );    
});

function sesionActualizada(response){
  calendar.refetchEvents();
  
  var divAlert = document.getElementById("userAlertCambio");

  divAlert.innerHTML = '<div class="alert alert-' + response.icono + '" role="alert"><p>Movimiento realizado: <span class="badge bg-'+response.background+'">' + response.operacion + '</span></p>' + response.mensaje + '</div>'
}

function cambiarColor(operacion){
  switch( operacion ){
    case "2":

      document.getElementById("statusSesionId").classList.add("text-white");
      document.getElementById("statusSesionId").classList.remove("bg-danger");
      document.getElementById("statusSesionId").classList.remove("bg-success");
      document.getElementById("statusSesionId").classList.add("bg-warning");
      break;

    case "3":

      document.getElementById("statusSesionId").classList.add("text-white");
      document.getElementById("statusSesionId").classList.remove("bg-warning");
      document.getElementById("statusSesionId").classList.remove("bg-success");
      document.getElementById("statusSesionId").classList.add("bg-danger");
      break;

    case "4":

      document.getElementById("statusSesionId").classList.add("text-white");
      document.getElementById("statusSesionId").classList.remove("bg-warning");
      document.getElementById("statusSesionId").classList.remove("bg-danger");
      document.getElementById("statusSesionId").classList.add("bg-success");
      break;

    default:

      document.getElementById("statusSesionId").classList.remove("text-white");
      document.getElementById("statusSesionId").classList.remove("bg-danger");
      document.getElementById("statusSesionId").classList.remove("bg-warning"); 
      document.getElementById("statusSesionId").classList.remove("bg-success");  
      break;
  }
}

const today = new Date();

function getPaciente(pacienteId) {
  
  const formattedDate =
  
  today.getFullYear() + '-' +
  String(today.getMonth() + 1).padStart(2, '0') + '-' +
  String(today.getDate()).padStart(2, '0');

  const time = today.toLocaleString('en-US', {
    timeZone: 'America/Los_Angeles',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  });

  document.getElementById('fecha-nueva').value = formattedDate;
  document.getElementById('hora-inicia-nueva').value = time;

  arrayNvasSesiones = [];
  document.getElementById("listado-nuevas").innerHTML = "";
  
  document.getElementById("userAlertNuevas").innerHTML = "";

  document.getElementById("campos-nva-sesion").disabled = false;
  document.getElementById("validar-guardar").disabled = false;

  var inputsNvaSesion = document.getElementById("inputs-nva-sesion");

  if ( pacienteId ){

      inputsNvaSesion.classList.remove("d-none");

      nuevaSesion = {
        pacienteId: pacienteId, 
        fechaNva: formattedDate,
        horaIniciaNva: time
      }

      arrayNvasSesiones.push(nuevaSesion);

      actualizarListado();

  } else {

    inputsNvaSesion.classList.add("d-none"); 
  }
}

var arrayNvasSesiones = [];

document.getElementById("campos-nva-sesion").addEventListener("click", function(event){

  var fechaNva = document.getElementById("fecha-nueva").value;
  var horaIniciaNva = document.getElementById("hora-inicia-nueva").value;
  var pacienteId = document.getElementById("paciente_id").value;

  if( !fechaNva || !horaIniciaNva ){

      Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: '¡Error!',
          html: 'Favor de capturar correctamente los datos correspondientes antes de pulsar el bot&oacute;n color verde: <i><b>Fecha, hora de inicio y hora de termino</b></i>',
          showConfirmButton: true,
          confirmButtonText: 'Cerrar aviso'
      });
      
      event.preventDefault();
      return false;
  }

  if (arrayNvasSesiones.filter(x => x.fechaNva === fechaNva).length){
        
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: '¡Error!',
      html: 'La fecha que intenta agregar a la vista previa ya ha sido ingresada anteriormente',
      showConfirmButton: true,
      confirmButtonText: 'Cerrar aviso'
    });
      
    event.preventDefault();
    return false;
  }

  nuevaSesion = {
    pacienteId: pacienteId, 
    fechaNva: fechaNva,
    horaIniciaNva: horaIniciaNva
  }

  arrayNvasSesiones.push(nuevaSesion);

  actualizarListado();
});

function actualizarListado(){
  var tablaSesionesNuevas = "<table class='table table-bordered table-hover align-middle text-center'>";

  tablaSesionesNuevas += "<tr><th>Fecha</th><th>Hora de inicio</th><th>Hora de termino</th><th>Acciones</th></tr>"

  if ( arrayNvasSesiones.length > 0 ){
      for ( var i = 0; i < arrayNvasSesiones.length; i ++ )
      {
        tablaSesionesNuevas += "<tr>";
        tablaSesionesNuevas += "<td><i class='fas fa-arrow-up'></i> "+arrayNvasSesiones[i].fechaNva+"</td>";
        tablaSesionesNuevas += "<td><i class='fas fa-arrow-down'></i> "+arrayNvasSesiones[i].horaIniciaNva+"</td>";
        tablaSesionesNuevas += "<td><i class='fas fa-arrow-down'></i> N/A</td>";
        
        tablaSesionesNuevas += "<td><button class='btn btn-danger' onclick='eliminarSesionObjeto("+i+")'><i class='fas fa-trash'></i></button></td>";
        tablaSesionesNuevas += "</tr>";
      }
  } else {

      tablaSesionesNuevas += "<tr><td colspan='3'>Favor de ingresar como minimo <b>1 sesi&oacute;n</b> a este listado</td></tr>";
  }
  
  tablaSesionesNuevas += "</table>";

  document.getElementById("listado-nuevas").innerHTML = tablaSesionesNuevas;
}

document.getElementById("validar-guardar").addEventListener("click", function(event){

    if ( arrayNvasSesiones.length < 1 ) {
      Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: '¡Error!',
          html: 'Favor de capturar minimo <i><b>1 cita</b></i> antes de realizar esta acci&oacute;n',
          showConfirmButton: true,
          confirmButtonText: 'Cerrar aviso'
      });
      
      event.preventDefault();
      return false;
    }

    var url = '/citas/insert';
    
    this.disabled = true;
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var data = {
      arrayNvasSesiones: arrayNvasSesiones,
      doctorId: document.getElementById('doctor_id_agenda').value,
      _token: token
    };

    fetch(url, {
        method: 'POST',
        body: JSON.stringify(data),
        headers:{
          'Content-Type': 'application/json'
        }
      })
      .then(res => res.text())
      .catch(error => console.error('Error: ', error))
      .then(response => sesionesAgendadasAviso(response) );
});

function sesionesAgendadasAviso(response){
  var convertResponse = JSON.parse(response);
  calendar.refetchEvents();
  
  var divAlert = document.getElementById("userAlertNuevas");

  divAlert.innerHTML = '<div class="alert alert-' + convertResponse.icono + '" role="alert"><h4><span class="badge bg-'+convertResponse.background+'">' + convertResponse.operacion + '</span>: ' + convertResponse.mensaje + '</h4></div>';

  document.getElementById("campos-nva-sesion").disabled = true;
  document.getElementById("validar-guardar").disabled = true;
}

function eliminarSesionObjeto(indiceArreglo){
  arrayNvasSesiones.splice(indiceArreglo, 1);

  actualizarListado();
}

document.getElementById("eliminar-sesion-db").addEventListener("click", function(){

  idSesionCambio = document.getElementById("idSesionCambio").value;

  var url = '/cita/delete';
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
  var data = { sesionId: idSesionCambio, _token: token };

  fetch(url, {
        method: 'POST',
        body: JSON.stringify(data),
        headers:{
          'Content-Type': 'application/json'
        }
      })
      .then(res => res.json())
      .catch(error => console.error('Error: ', error))
      .then(function(response){
        calendar.refetchEvents()
        alert(response.mensaje)
      } );
});

function sesionEliminada(){

  var myModalEl = document.getElementById('modalSesionCambio');
  var modal = bootstrap.Modal.getInstance(myModalEl);

  modal.hide();

  calendar.refetchEvents();
  
  Swal.fire({
    position: 'top-end',
    icon: 'success',
    title: 'Sesion eliminada correctamente',
    showConfirmButton: false,
    timer: 2000
  });
}
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
    format: 'YYYY-MM-DD', //  HH:mm
    sideBySide: true
  });

  $('#fecha-nueva').on('change.datetimepicker',function(e){
      
      if ( $(this).val().substring(11, 16) <= '10:59' )
      {
        // $('#hora-inicia-nueva').val( $(this).val().substring(0,10) + ' 11:00:00' );
      } 
      else
      {
        // $('#hora-inicia-nueva').val( $(this).val().substring(0,10) + ' 14:30:00' );
      }
  });

  $('#hora-inicia-nueva').datetimepicker({
    format: 'HH:mm', // YYYY-MM-DD 
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
      businessHours: true, // display business hours
      displayEventEnd: true,
      header: {
        left: 'prev,next',
        center: 'title',
        right: 'dayGridMonth,listMonth,dayGridDay,dayGridWeek' // timeGridWeek,timeGridDay,
      },
      editable: false,
      events: '/citas',
	eventRender: function(info) {
  // Para eventos en timeGrid y dayGrid
  var timeElement = info.el.querySelector('.fc-time');

  if (timeElement && timeElement.innerText.includes('-')) {
    // Ejemplo: "14:10 - 14:11"
    let horaInicio = timeElement.innerText.split('-')[0].trim();
    timeElement.innerText = horaInicio; // reemplaza texto
  }

  // Para vista lista
  var listTimeEl = info.el.querySelector('.fc-list-item-time');
  if (listTimeEl && listTimeEl.innerText.includes('-')) {
    let horaInicio = listTimeEl.innerText.split('-')[0].trim();
    listTimeEl.innerText = horaInicio;
  }
},

      eventClick: function(info)
      {
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

        switch(statusActualId)
        {
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

        var selectInput = "<select class='form-control " + colorClase + "' id='statusSesionId' onChange='cambiarColor(this.value)'>"

        selectInput += "<option value=''>Seleccione una opcion</option>"

        statusArray.forEach(function(elemento, indice){
            
            switch(elemento)
            {
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

            if ( elemento == statusActualId )
            {
                selectInput += "<option value='" + elemento + "' selected>" + valorStatus + "</option>";
            } else {
                selectInput += "<option value='" + elemento + "'>" + valorStatus + "</option>";
            }
            
        });

        selectInput += "</select>";

        statusCambio.innerHTML = selectInput;

        modalSesionCambio.show();
      }
  });

  calendar.render();
});

document.getElementById("recargar-fc").addEventListener("click", function() {
  calendar.refetchEvents();
});

document.getElementById("ajuste-sesion-boton").addEventListener("click", function(event){

    var fechaCambio = document.getElementById("fechaCambio").value;
    var horaInicioCambio = document.getElementById("horaInicioCambio").value;
    

    if ( ! document.getElementById("statusSesionId").value /*|| (iniciaCambio.substring(0,10) != terminaCambio.substring(0,10))*/ )
    { 
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

function getPaciente(pacienteId)
{
  //const formattedDate = today.toISOString().split('T')[0]; // Obtiene YYYY-MM-DD
  const formattedDate =
  today.getFullYear() + '-' +
  String(today.getMonth() + 1).padStart(2, '0') + '-' +
  String(today.getDate()).padStart(2, '0');

  const time = today.toLocaleString('en-US', {
    timeZone: 'America/Los_Angeles',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false, // Formato 24 horas
  });

  // se resetea el valor de los inputs cuando el usuario cambia el paciente en el input select
  document.getElementById('fecha-nueva').value = formattedDate;
  document.getElementById('hora-inicia-nueva').value = time;

  // reinicio de arreglo de objetos (sesiones nuevas del paciente)
  arrayNvasSesiones = [];
  document.getElementById("listado-nuevas").innerHTML = "";
  
  document.getElementById("userAlertNuevas").innerHTML = "";

  document.getElementById("campos-nva-sesion").disabled = false;
  document.getElementById("validar-guardar").disabled = false;

  var inputsNvaSesion = document.getElementById("inputs-nva-sesion");

  if ( pacienteId )
  {
      var url = "/citas/paciente/";
  
      fetch(url + pacienteId, {
            method: 'GET',
      }).then(res => res.json())
      .catch(error => console.error('Error: ', error))
      .then(response => inputsNvaSesion.classList.remove("d-none") ); 

      nuevaSesion = {
        pacienteId: pacienteId, 
        fechaNva: formattedDate,
        horaIniciaNva: time//,
        //horaTerminaNva: horaTerminaNva
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
  //var horaTerminaNva = document.getElementById("hora-termina-nueva").value;
  var pacienteId = document.getElementById("paciente_id").value;

  if( !fechaNva || !horaIniciaNva /*|| (horaIniciaNva >= horaTerminaNva)*//* || (iniciaNva.substring(0,10) != terminaNva.substring(0,10))*/ )
  {
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
    horaIniciaNva: horaIniciaNva//,
    //horaTerminaNva: horaTerminaNva
  }

  arrayNvasSesiones.push(nuevaSesion);

  actualizarListado();

  
});

function actualizarListado(){
  var tablaSesionesNuevas = "<table class='table table-bordered table-hover align-middle text-center'>";

  tablaSesionesNuevas += "<tr><th>Fecha</th><th>Hora de inicio</th><th>Hora de termino</th><th>Acciones</th></tr>"

  if ( arrayNvasSesiones.length > 0 )
  {
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
        // sesionEliminada()
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

var getDaysBetweenDates = function(startDate, endDate){
  var now = startDate.clone(), dates = [];
  
  while (now.isSameOrBefore(endDate))
  {
    dates.push(now.format('YYYY-MM-DD'));
    now.add(1, 'days');
  }
  return dates;
};

document.getElementById('button-sesion-recurrente').addEventListener('click', function(){

  var modalSesionesRecurrentes = new bootstrap.Modal(document.getElementById('modalSesionesRecurrentes'));

  modalSesionesRecurrentes.show();
});

document.getElementById('vista-previa-recurrencia').addEventListener('click', function(event){

  var pacienteRecurrenteId = document.getElementById('paciente_recurrente_id').value;
  var iniciaRecurrencia = document.getElementById('inicia-recurrencia').value;
  var terminaRecurrencia = document.getElementById('termina-recurrencia').value;
  var horaIniciaRecurrencia = document.getElementById('hora-inicia-recurrencia').value;
  var horaTerminaRecurrencia = document.getElementById('hora-termina-recurrencia').value;

  var inicioIsValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(horaIniciaRecurrencia);
  var terminoIsValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(horaTerminaRecurrencia);

  if (!inicioIsValid || !terminoIsValid || !pacienteRecurrenteId || !iniciaRecurrencia || !terminaRecurrencia || !horaIniciaRecurrencia || !horaTerminaRecurrencia || (horaIniciaRecurrencia >= horaTerminaRecurrencia) || (iniciaRecurrencia > terminaRecurrencia))
  {
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Error!',
      html: 'Favor de capturar correctamente los campos <i><b>Paciente, fecha de inicio/termino, y hora de inicio/termino</b></i>',
      showConfirmButton: true,
      confirmButtonText: 'Cerrar aviso'
    });
    event.preventDefault();
    return false;
  }

  var startDate = moment(iniciaRecurrencia);
  var endDate = moment(terminaRecurrencia);
  
  var dateList = getDaysBetweenDates(startDate, endDate);

  var fechasJson = [];

  for(var i = 0; i < dateList.length; i++)
  {
    var nuevaFecha = {
      fechaInicio: dateList[i] + ' ' + horaIniciaRecurrencia,
      fechaTermino: dateList[i] + ' ' + horaTerminaRecurrencia,
      pacienteId: pacienteRecurrenteId
    }

    fechasJson.push(nuevaFecha);
  }

  var url = '/sesiones/recurrentes/insert';
    
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var data = {
      sesionesJson: fechasJson,
      _token: token
    };

    fetch(url, {
      method: 'POST',
      body: JSON.stringify(data),
      headers:{
        'Content-Type': 'application/json'
      }
    })
    .then(res => res.json())
    .catch(error => console.error('Error: ', error))
    .then(response => calendar.refetchEvents() );

    var myModalEl = document.getElementById('modalSesionesRecurrentes');
    var modal = bootstrap.Modal.getInstance(myModalEl);

    modal.hide();

    document.getElementById('inicia-recurrencia').value = '';
    document.getElementById('termina-recurrencia').value = '';
    document.getElementById('hora-inicia-recurrencia').value = '';
    document.getElementById('hora-termina-recurrencia').value = '';
});

document.getElementById('copy-paste-button').addEventListener('click', function(){

  var currentDatesRange = calendar.state.dateProfile.currentRange;

  var startDate = moment(currentDatesRange.start).add(1, 'days').format("YYYY-MM-DD");
  var endDate = moment(currentDatesRange.end).format("YYYY-MM-DD");

  var destinoStartDate = moment(currentDatesRange.start).add(8, 'days').format("YYYY-MM-DD");
  var destinoEndDate = moment(currentDatesRange.end).add(7, 'days').format("YYYY-MM-DD");

  fetch('/sesiones/previa/trasladar/'+startDate+'/'+endDate, {
    method: 'GET',
  }).then(res => res.json())
    .catch(error => console.error('Error: ', error))
    .then(response => viewPreviewSesionsList(response, startDate, endDate, destinoStartDate, destinoEndDate) );
});

var copyPasteObject;

function viewPreviewSesionsList( response, startDate, endDate, destinoStartDate, destinoEndDate )
{

  var semanaOrigen = startDate + ' &nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>&nbsp;&nbsp;&nbsp; ' + endDate; 
  var semanaDestino = destinoStartDate + ' &nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>&nbsp;&nbsp;&nbsp; ' + destinoEndDate;

  document.getElementById("semana-origen").innerHTML = semanaOrigen;
  document.getElementById("semana-destino").innerHTML = semanaDestino;

  var table = document.getElementById("sesiones-traslado");

  table.innerHTML = '';

  response.forEach(function(item, index, arr) {
    
    var row = table.insertRow(0);

    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);

    var statusValor = '';
    
    switch ( item.sesion_status_id )
    {
      case (1):

        statusValor = '<h4><span class="badge bg-info">Programada</span></h4>';
        break;

      case (2):

        statusValor = '<h4><span class="badge bg-warning">Reprogramada</span></h4>';
        break;

      case (3):

        statusValor = '<h4><span class="badge bg-danger">Cancelada</span></h4>';
        break;

      case (4):

        statusValor = '<h4><span class="badge bg-success">Concluida</span></h4>';
        break;
    }

    cell1.innerHTML = item.get_paciente.nombre + ' ' + item.get_paciente.apellido_paterno + ' ' + item.get_paciente.apellido_materno;
    cell2.innerHTML = item.fecha_hora_inicio;
    cell3.innerHTML = item.fecha_hora_termino;
    cell4.innerHTML = statusValor;

  });

  copyPasteObject = response;
}

function copyPasteSesiones()
{

  if ( copyPasteObject.length < 1 )
  {
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'La semana que intenta copiar no cuenta con ninguna sesion programada',
      showConfirmButton: true
    });

    return;
  }

  var url = '/sesiones/trasladar';

  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
  var currentDatesRange = calendar.state.dateProfile.currentRange;

  var startDate = moment(currentDatesRange.start).add(1, 'days').format("YYYY-MM-DD");
  var endDate = moment(currentDatesRange.end).format("YYYY-MM-DD");

  var data = {
    arraySesiones: copyPasteObject,
    startDate: startDate,
    endDate: endDate,
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
  .then(response => avisoCopyPasteOk(response) );
}

function avisoCopyPasteOk(response)
{
  var myModalEl = document.getElementById('trasladarCalendarioModal');
  var modal = bootstrap.Modal.getInstance(myModalEl);

  modal.hide();

  if ( response == false )
  {
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Aviso',
      html: 'Est&aacute; intentando copiar un listado de sesiones a una semana que ya tiene programada m&aacute;s de 1 sesion. Esta operaci&oacute;n no est&aacute; permitida.',
      showConfirmButton: true
    });

  } 
  else
  {
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'Copia realizada correctamente',
      html: 'Sesiones programadas para la semana: <br><br>' + response + '<br><br>Ahora, al desplazarse a la semana en menci&oacute;n podr&aacute; visualizar las sesiones y realizar los cambios que estas requieran.',
      showConfirmButton: true
    });
  }  
}

document.getElementById('delete-weekend-button').addEventListener('click', function(){

  var currentDatesRange = calendar.state.dateProfile.currentRange;

  var startDate = moment(currentDatesRange.start).add(1, 'days').format("YYYY-MM-DD");
  var endDate = moment(currentDatesRange.end).format("YYYY-MM-DD");

  document.getElementById('semana-origen-delete').innerHTML = startDate;
  document.getElementById('semana-destino-delete').innerHTML = endDate;

});

document.getElementById('btn-eliminar-semana').addEventListener('click', function(){

  var url = '/sesiones/delete/semana';

  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
  var currentDatesRange = calendar.state.dateProfile.currentRange;

  var startDate = moment(currentDatesRange.start).add(1, 'days').format("YYYY-MM-DD");
  var endDate = moment(currentDatesRange.end).format("YYYY-MM-DD");

  fetch(url, {
    method: 'POST',
    body: JSON.stringify({ startDate: startDate, endDate: endDate, _token: token }),
    headers:{
      'Content-Type': 'application/json'
    }
  })
  .then(res => res.json())
  .catch(error => console.error('Error: ', error))
  .then(response => avisoDeleteSemanaOk(response) );

});

function avisoDeleteSemanaOk(response)
{
  var myModalEl = document.getElementById('eliminarSemanaModal');
  var modal = bootstrap.Modal.getInstance(myModalEl);

  modal.hide();

  Swal.fire({
    position: 'top-end',
    icon: 'success',
    title: response.length + ' sesion(es) eliminadas correctamente',
    showConfirmButton: true
  });

  calendar.refetchEvents();
}

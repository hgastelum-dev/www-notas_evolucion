<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\CitaPaciente;
use App\Models\CitaEstado;
use App\Models\CitaSubjetivo;
use App\Models\CitaObjetivo;
use App\Models\CitaAnalisis;
use App\Models\CitaPlaneacion;
use stdClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AgendaController extends Controller {

  public function __construct(){
    $this->middleware('auth');
  }

  private function assertPuedeAdministrarCita(CitaPaciente $sesion): void {

    $user = auth()->user();

    // recepcion: administra todas las agendas sin restriccion de dueño
    if ($user->can('CitaGestionarTodas')) {
      return;
    }

    // doctor: administra citas, pero unicamente las suyas
    if ($user->can('CitaGestionar') && (int)$sesion->doctor_id === (int)$user->id) {
      return;
    }

    abort(403, 'No tiene permiso para administrar esta cita.');
  }

  public function getViewMain(){

    $citaEstados = CitaEstado::all();
    $pacientes = Paciente::select('id','nombre_s','apellido_paterno','apellido_materno')->get();
    $doctores = User::doctores()->select('id','name')->get();
    $doctorActualId = auth()->id();

    return view('agenda.main', compact(['pacientes','citaEstados','doctores','doctorActualId']));
  }

  // metodo que envia los datos (citas) al fullcalendar
  public function getCitas(Request $request){

    $start = $request->query('start');
    $end   = $request->query('end');
    $doctorId = $request->query('doctor_id', auth()->id());

    if (!$start || !$end) {
      return response()->json([]);
    }

    $startDate = Carbon::parse($start)->toDateString();
    $endDate   = Carbon::parse($end)->toDateString();

    $citasPacientes = CitaPaciente::whereDate('fecha', '>=', $startDate)
      ->whereDate('fecha', '<',  $endDate)
      ->where('doctor_id', $doctorId)
      ->with([
        'getPaciente:id,nombre_s,apellido_paterno,apellido_materno',
        'getEstado:id,class_color'
      ])
      ->get();

    $citasArreglo = [];

    foreach ($citasPacientes as $citaPaciente) {

      $sesionObjeto = new stdClass();

      $sesionObjeto->title = $citaPaciente->getPaciente->nombre_s . ' ' .
        $citaPaciente->getPaciente->apellido_paterno . ' ' .
        $citaPaciente->getPaciente->apellido_materno;

      $startDT = Carbon::parse($citaPaciente->fecha . ' ' . $citaPaciente->hora_inicio);
      $endDT   = $startDT->copy()->addMinute();

      $sesionObjeto->start = $startDT->format('Y-m-d\TH:i:sP');
      $sesionObjeto->end   = $endDT->format('Y-m-d\TH:i:sP');
      $sesionObjeto->allDay = false;

      $sesionObjeto->sesionId     = $citaPaciente->id;
      $sesionObjeto->statusSesion = $citaPaciente->cita_estado_id;
      $sesionObjeto->doctorId     = $citaPaciente->doctor_id;
      $sesionObjeto->esMia        = ((int) $citaPaciente->doctor_id === (int) auth()->id());

      $sesionObjeto->puedeGestionar = auth()->user()->can('CitaGestionarTodas');

      $sesionObjeto->color = $citaPaciente->en_progreso
          ? 'purple'
          : $citaPaciente->getEstado->class_color;

      $citasArreglo[] = $sesionObjeto;
    }

    return response()->json($citasArreglo);
  }

  // metodo invocado cuando el usuario selecciona un paciente en el select del modal
  public function getPaciente($pacienteId){
    $paciente = Paciente::where("id", $pacienteId);

    return $paciente->with("getCitas")->get();
  }

  public function insertPaciente(Request $request){

    $request->validate([
      'nombre_s' => 'required|string|max:255',
      'apellido_paterno' => 'required|string|max:255',
      'apellido_materno' => 'nullable|string|max:255',
    ]);

    $nuevoPaciente = new Paciente();

    $nuevoPaciente->nombre_s = $request->nombre_s;
    $nuevoPaciente->apellido_paterno = $request->apellido_paterno;
    $nuevoPaciente->apellido_materno = $request->apellido_materno;

    $nuevoPaciente->save();

    $nuevoPaciente->numero_expediente = 'EXP_'.$nuevoPaciente->id;
    $nuevoPaciente->save();

    $request->session()->flash('PacienteRegistrado', ['titulo' => 'Paciente registrado correctamente: ' . $nuevoPaciente->nombre_s . ' ' . $nuevoPaciente->apellido_paterno . ' ' . $nuevoPaciente->apellido_materno, 'mensaje' => (int)$nuevoPaciente->id, 'icono' => 'success']);

    return redirect('/agenda');
  }

  public function insertCitas(Request $request){

    $validated = $request->validate([
      'arrayNvasSesiones' => 'required|array|min:1',
      'arrayNvasSesiones.*.pacienteId'    => 'required|integer|exists:pacientes,id',
      'arrayNvasSesiones.*.fechaNva'      => 'required|date',
      'arrayNvasSesiones.*.horaIniciaNva' => 'required',
      // de que doctor son estas citas nuevas. Si el front-end no lo
      // manda (por compatibilidad), se asume el doctor logueado
      'doctorId' => 'nullable|integer|exists:users,id',
    ]);

    // el doctor de contexto es el que se esta agendando, no
    // automaticamente el usuario logueado
    $doctorContextoId = auth()->id();

    if (auth()->user()->can('CitaGestionarTodas')) {
      $doctorContextoId = $validated['doctorId'] ?? auth()->id();
    }

    $totalInsertadas = 0;

    DB::transaction(function () use ($request, $doctorContextoId, &$totalInsertadas) {

      foreach ($request->arrayNvasSesiones as $nuevaSesion) {

        // eel choque de fechas se checa por doctor... antes
        // bloqueaba agendar el mismo dia con un doctor distinto
        $citaDuplicada = CitaPaciente::where('paciente_id', $nuevaSesion['pacienteId'])
          ->where('fecha', $nuevaSesion['fechaNva'])
          ->where('doctor_id', $doctorContextoId)
          ->exists();

        $citasConcluidasPosteriores = CitaPaciente::where('paciente_id', $nuevaSesion["pacienteId"])
          ->where('cita_estado_id', 4)
          ->where('fecha', '>=', $nuevaSesion["fechaNva"])
          ->exists();

        if (!$citaDuplicada && !$citasConcluidasPosteriores) {

          $totalInsertadas++;

          $sesion = new CitaPaciente();

          $sesion->paciente_id = $nuevaSesion["pacienteId"];
          $sesion->fecha = $nuevaSesion["fechaNva"];
          $sesion->hora_inicio = $nuevaSesion["horaIniciaNva"];
          $sesion->hora_termino = $nuevaSesion["horaIniciaNva"];
          $sesion->user_id = 0;
          $sesion->doctor_id = $doctorContextoId;
          $sesion->cita_estado_id = 1;
          $sesion->save();
        }
      }
    });

    $userAlert = ['titulo' => 'Correcto', 'mensaje' => $totalInsertadas . ' citas agendadas correctamente', 'icono' => 'success', 'operacion' => 'Programadas', 'background' => 'info'];

    return $userAlert;
  }

  public function updateCita(Request $request){

    // 4 = concluido: esto nunca se permite desde este endpoint, solo desde
    // el flujo de atencion (CitaController::cerrarCita)... el mensaje se deja
    // igual para todos los roles porque nadie puede concluir
    // por aqui, se concluye unicamente atendiendo la cita
    if ($request->statusId == 4){

      $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => '<h4><b>Aviso</b>: Concluir una cita solo es posible atendiéndola desde el flujo de nota de evolución, no desde este panel</h4>', 'icono' => 'warning', 'operacion' => '', 'background' => ''];

      return $userAlert;
    }

    $sesion = CitaPaciente::findOrFail($request->sesionId);

    // nadie reprograma/cancela una cita que no le pertenece
    $this->assertPuedeAdministrarCita($sesion);

    if ($sesion->cita_estado_id == 4){
      $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => '<h4><b>Aviso</b>: La cita que intenta modificar ya ha sido concluida por parte del personal Medico, por lo que ya no es posible realizar modificaciones</h4>', 'icono' => 'warning', 'operacion' => '', 'background' => ''];

      return $userAlert;
    }

    $citasConcluidasPosteriores = CitaPaciente::where('paciente_id', $sesion->paciente_id)
      ->where('cita_estado_id', 4)
      ->whereNotIn('id', [$sesion->id])
      ->where('fecha', '>=', $request->fecha)
      ->exists();

    if($citasConcluidasPosteriores && $request->statusId == 2){

      $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => '<h4><b>Aviso</b>: El sistema detecta que existen citas concluidas en fechas posteriores, por lo que no es posible reactivar la cita en pantalla</h4>', 'icono' => 'warning', 'operacion' => '', 'background' => ''];

      return $userAlert;
    }

    $sesion->fecha = $request->fecha;
    $sesion->hora_inicio = $request->horaInicio;
    $sesion->cita_estado_id = $request->statusId;

    // se limpia cuando la cita se cancela (statusId 3),
    // que es el unico caso donde tiene sentido resetear todo...
    if($request->statusId == '3'){

      $sesion->user_id = 0;
      $sesion->en_progreso = 0;

      // se eliminan tambien los archivos fisicos en disco,
      // no solo la referencia en BD
      if ($sesion->gabinete_path_pdf && Storage::disk('public')->exists($sesion->gabinete_path_pdf)) {
        Storage::disk('public')->delete($sesion->gabinete_path_pdf);
      }

      if ($sesion->patologia_path_pdf && Storage::disk('public')->exists($sesion->patologia_path_pdf)) {
        Storage::disk('public')->delete($sesion->patologia_path_pdf);
      }

      $sesion->laboratorio = null;
      $sesion->gabinete = null;
      $sesion->gabinete_path_pdf = null;
      $sesion->patologia = null;
      $sesion->patologia_path_pdf = null;

      // eliminar registros de la nota de evolucion
      CitaSubjetivo::where('cita_paciente_id', $sesion->id)->delete();
      CitaObjetivo::where('cita_paciente_id', $sesion->id)->delete();
      CitaAnalisis::where('cita_paciente_id', $sesion->id)->delete();
      CitaPlaneacion::where('cita_paciente_id', $sesion->id)->delete();
    }

    $sesion->save();

    $userAlert = ['titulo' => 'Correcto', 'mensaje' => '<h4><b>Sesion actualizada</b>: De ' . $sesion->hora_inicio . " a " . $sesion->hora_termino . '</h4>', 'icono' => 'primary', 'operacion' => '', 'background' => ''];

    return $userAlert;
  }

  public function deleteCita(Request $request){

    $sesion = CitaPaciente::findOrFail($request->sesionId);

    // nadie borra una cita que no le pertenece
    $this->assertPuedeAdministrarCita($sesion);

    // 4 = concluido
    if ($sesion->cita_estado_id == 4){

      $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => 'La cita que intenta borrar ya ha sido concluida por parte del personal Medico, por lo que ya no es posible realizar modificaciones', 'icono' => 'warning', 'operacion' => '', 'background' => ''];
      return $userAlert;
    }

    // eliminar tambien los PDFs fisicos, no solo los registros
    if ($sesion->gabinete_path_pdf && Storage::disk('public')->exists($sesion->gabinete_path_pdf)) {
      Storage::disk('public')->delete($sesion->gabinete_path_pdf);
    }
    
    if ($sesion->patologia_path_pdf && Storage::disk('public')->exists($sesion->patologia_path_pdf)) {
      Storage::disk('public')->delete($sesion->patologia_path_pdf);
    }

    // eliminar registros de la nota de evolucion
    CitaSubjetivo::where('cita_paciente_id', $sesion->id)->delete();
    CitaObjetivo::where('cita_paciente_id', $sesion->id)->delete();
    CitaAnalisis::where('cita_paciente_id', $sesion->id)->delete();
    CitaPlaneacion::where('cita_paciente_id', $sesion->id)->delete();

    $sesion->delete();

    $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => 'Cita eliminada correctamente', 'icono' => 'warning', 'operacion' => '', 'background' => ''];
    return $userAlert;
  }
}
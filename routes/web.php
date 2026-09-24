<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['is-active','prevent-back-history']],function(){
    
    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/dashboard', function () {
      
      return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/usuarios', [App\Http\Controllers\UsersController::class, 'getViewUsers']);

    Route::get('/usuarios/registrar', [App\Http\Controllers\UsersController::class, 'getViewNewUser']);

    Route::post('/usuarios/insert', [App\Http\Controllers\UsersController::class, 'insertUser']);

    Route::get('/usuarios/editar/{userId}', [App\Http\Controllers\UsersController::class, 'getViewEditUser']);

    Route::post('/usuarios/update', [App\Http\Controllers\UsersController::class, 'updateUser']);

    Route::get('/usuarios/confirm/delete/{userId}/{link}', [App\Http\Controllers\UsersController::class, 'getViewDeleteUser']);

    Route::post('/usuarios/delete', [App\Http\Controllers\UsersController::class, 'deleteUser']);

    Route::post('/usuarios/delete/multi', [App\Http\Controllers\UsersController::class, 'deleteMultipleUsers']);

    Route::post('/usuarios/permiso/asignar', [App\Http\Controllers\UsersController::class, 'asignarPermiso']);

    Route::post('/usuarios/permiso/denegar', [App\Http\Controllers\UsersController::class, 'denegarPermiso']);

    Route::get('/tablero', [App\Http\Controllers\TableroPrincipalController::class, 'getViewMain']);
    Route::get('/paciente/{id}/citas', [App\Http\Controllers\TableroPrincipalController::class, 'citasConcluidas']);
    Route::post('/paciente/parametros', [App\Http\Controllers\TableroPrincipalController::class, 'parametros']);
    Route::post('/paciente/graficar', [App\Http\Controllers\TableroPrincipalController::class, 'graficar']);

    Route::post('/ajax/search', [App\Http\Controllers\TableroPrincipalController::class, 'getDiagnosticosCie10']);

    Route::get('/pacientes', [App\Http\Controllers\PacientesController::class, 'getViewPacientes']);
    Route::get('/pacientes/alta', [App\Http\Controllers\PacientesController::class, 'getViewAlta']);
    Route::post('/paciente/insert', [App\Http\Controllers\PacientesController::class, 'insertPaciente']);
    Route::post('/paciente/fecha_ingreso/update', [App\Http\Controllers\PacientesController::class, 'updateFechaIngreso']);
    Route::get('/paciente/editar/{pacienteId}', [App\Http\Controllers\PacientesController::class, 'getViewEditar']);
    Route::post('/paciente/update', [App\Http\Controllers\PacientesController::class, 'updatePaciente']);     
    Route::post('/seguros-medicos/insert', [App\Http\Controllers\PacientesController::class, 'insertSeguroMedico']);
    Route::post('/paciente/seguro-medico/asociar', [App\Http\Controllers\PacientesController::class, 'asociarSeguroMedico']);
    Route::post('/paciente/seguro-medico/quitar', [App\Http\Controllers\PacientesController::class, 'quitarSeguroMedico']);
    Route::post('/paciente/plan/inicial/analisis', [App\Http\Controllers\PacientesController::class, 'updateAnalisisPaciente']);
    Route::get('/paciente/borrar/{pacienteId}', [App\Http\Controllers\PacientesController::class, 'viewBorrarPaciente']);
    Route::post('/paciente/delete', [App\Http\Controllers\PacientesController::class, 'deletePaciente']);
    Route::get('/images/paciente/{PacienteId}/img', [App\Http\Controllers\PacientesController::class, 'getFotoPaciente']);
    Route::get('/paciente/antecedentes/{pacienteId}', [App\Http\Controllers\PacientesController::class, 'getViewAntecedentes']);
    Route::post('/paciente/antecedentes/update', [App\Http\Controllers\PacientesController::class, 'updateAntecedentes']);
    Route::get('/paciente/padecimientos/{pacienteId}', [App\Http\Controllers\PacientesController::class, 'getViewPadecimientos']);
    Route::post('/paciente/padecimientos/update', [App\Http\Controllers\PacientesController::class, 'updatePadecimientos']);
    
    // gestion del Plan en expediente del paciente
    Route::get('/paciente/plan/{pacienteId}', [App\Http\Controllers\PacientesController::class, 'getViewPlan']);
    Route::post('/paciente/plan', [App\Http\Controllers\PacientesController::class, 'insertPlan']);
    Route::post('/paciente/plan/update', [App\Http\Controllers\PacientesController::class, 'updatePlan']);
    Route::post('/paciente/plan/delete', [App\Http\Controllers\PacientesController::class, 'deletePlan']);

    Route::get('/paciente/exp-fisica/{pacienteId}', [App\Http\Controllers\PacientesController::class, 'getViewExpFisica']);
    Route::post('/paciente/exp-fisica/update', [App\Http\Controllers\PacientesController::class, 'updateExpFisica']);

    Route::get('/paciente/notas-hist/{pacienteId}', [App\Http\Controllers\PacientesController::class, 'getViewNotasHist']);
    Route::post('/paciente/notas-hist/insert', [App\Http\Controllers\PacientesController::class, 'insertNotasHist']);
    Route::get('/nota-hist/{notaHistId}', [App\Http\Controllers\PacientesController::class, 'getViewNotaHist']);
    Route::post('/nota-hist/update', [App\Http\Controllers\PacientesController::class, 'updateNotaHist']);
    Route::get('/nota-hist/confirmar-borrar/{notaHistId}', [App\Http\Controllers\PacientesController::class, 'getViewBorrarNotaHist']);
    Route::post('/nota-hist/delete', [App\Http\Controllers\PacientesController::class, 'deleteNotaHist']);

    Route::get('/agenda', [App\Http\Controllers\AgendaController::class, 'getViewMain']);
    Route::get('/citas', [App\Http\Controllers\AgendaController::class, 'getCitas']);
    Route::post('/agenda/paciente/registrar', [App\Http\Controllers\AgendaController::class, 'insertPaciente']);
    Route::get('/citas/paciente/{pacienteId}', [App\Http\Controllers\AgendaController::class, 'getPaciente']);
    Route::post('/citas/insert', [App\Http\Controllers\AgendaController::class, 'insertCitas']);
    Route::post('/cita/update', [App\Http\Controllers\AgendaController::class, 'updateCita']);
    Route::post('/cita/delete', [App\Http\Controllers\AgendaController::class, 'deleteCita']);
    Route::post('/cita/iniciar', [App\Http\Controllers\CitaController::class, 'iniciarCita']);
    Route::post('/iniciar/plan-inicial', [App\Http\Controllers\CitaController::class, 'iniciarPlanInicial']);
    Route::post('/iniciar/soap', [App\Http\Controllers\CitaController::class, 'iniciarSoap']);
    Route::get('/cita/atender/{citaId}', [App\Http\Controllers\CitaController::class, 'getViewAtenderCita']);
    Route::post('/lab-gab/update', [App\Http\Controllers\CitaController::class, 'updateLaboratorio']);
    Route::post('/cita/cierre', [App\Http\Controllers\CitaController::class, 'cerrarCita']);

    // captura SOAP 01
    Route::get('/cita/soap01/subjetivo/{citaId}', [App\Http\Controllers\CitaController::class, 'getViewSoap01']);
    Route::post('/cita/soap01/subjetivo/update', [App\Http\Controllers\CitaController::class, 'updateSoap01']);

    // captura SOAP 02
    Route::get('/cita/soap02/objetivo/{citaId}', [App\Http\Controllers\CitaController::class, 'getViewSoap02']);
    Route::post('/cita/soap02/objetivo/update', [App\Http\Controllers\CitaController::class, 'updateSoap02']);
    Route::post('/fecha-nac/genero/update', [App\Http\Controllers\CitaController::class, 'updateFechaSexoCkdepi']);    
    
    // captura SOAP 03
    Route::get('/cita/soap03/analisis/{citaId}', [App\Http\Controllers\CitaController::class, 'getViewSoap03']);
    Route::post('/cita/soap03/analisis/update', [App\Http\Controllers\CitaController::class, 'updateSoap03']);

    // captura SOAP 04
    Route::get('/cita/soap04/planeacion/{citaId}', [App\Http\Controllers\CitaController::class, 'getViewSoap04']);
    Route::post('/cita/soap04/planeacion', [App\Http\Controllers\CitaController::class, 'insertSoap04']);
    Route::post('/cita/soap04/planeacion/update', [App\Http\Controllers\CitaController::class, 'updateSoap04']);
    Route::post('/cita/soap04/planeacion/delete', [App\Http\Controllers\CitaController::class, 'deleteSoap04']);
    Route::get('/receta/{cita}', [App\Http\Controllers\CitaController::class, 'generar'])->name('receta.pdf');

    require __DIR__.'/auth.php';
});

Route::get('/usuarios/exportar', [App\Http\Controllers\UsersController::class, 'exportarUsuarios'])->middleware('is-active');
Route::get('/usuarios/exportar/pdf', [App\Http\Controllers\UsersController::class, 'exportarUsuariosPdf'])->middleware('is-active');
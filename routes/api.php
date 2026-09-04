<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PacientesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(LoginController::class)->group(function(){
    Route::post('login', 'login');
});

Route::controller(PacientesController::class)->group(function(){
    Route::get('/pacientes', 'getPacientes');
    Route::post('/paciente/insert', 'insert');
    Route::post('/pacientes/reset', 'resetPacientes');
});
*/
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
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

// returns user details list
Route::get('logIn', [APIController::class, 'getUsers']);
// deleting user
Route::post('userDelete', [APIController::class, 'deleteUser']);
// change password using email
Route::post('changePassword', [APIController::class, 'changePassword']);
// returns sessions per patient
Route::get('patientsSessions/{patientId}', [APIController::class, 'getPatientsSessions']);
// returns count of sessions per patient
Route::post('patientsGraph/{patientId}', [APIController::class, 'getPatientsGraph']);

// Group for patient reated routes
Route::prefix('patient/{patient}')->group(function () {
    // Return patient data
    Route::get('data', [APIController::class, 'getPatientData']);
    // Return patient session by sessionId
    Route::get('sessions/{sessionId?}', [APIController::class, 'getPatientSessions']);

    // Route group of update related requests
    Route::prefix('update')->group(function () {
        // Upload & set patient profile picture
        Route::post('picture', [APIController::class, 'setPatientPicture']);
        // Update patient data
        Route::post('data', [APIController::class, 'updatePatientData']);
    });
});
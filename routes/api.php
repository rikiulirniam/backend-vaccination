<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\SpotController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'index']);
        
        Route::apiResource('consultations', ConsultationController::class)->only('index', 'store');
        Route::apiResource('vaccinations', VaccinationController::class)->only('index', 'store');
        Route::apiResource('spots', SpotController::class)->only('index', 'show');
    });
    
    // Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

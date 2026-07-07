<?php

use App\Http\Controllers\Api\AcceptabilitySurveyController;
use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MissionController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\PerformanceDashboardController;
use App\Http\Controllers\Api\StrandController;
use App\Http\Controllers\Api\GameSessionController;
use Illuminate\Support\Facades\Route;

// ── NO AUTH REQUIRED ─────────────────────────────────────────────────
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

Route::get('/strands',       [StrandController::class, 'index']);
Route::get('/strands/{id}',  [StrandController::class, 'show']);
Route::get('/modules',       [ModuleController::class, 'index']);
Route::get('/modules/{id}',  [ModuleController::class, 'show']);
Route::get('/missions',      [MissionController::class, 'index']);
Route::get('/missions/{id}', [MissionController::class, 'show']);

// ── REQUIRES TOKEN ───────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    Route::post('/strands',        [StrandController::class, 'store']);
    Route::put('/strands/{id}',    [StrandController::class, 'update']);
    Route::delete('/strands/{id}', [StrandController::class, 'destroy']);

    Route::post('/modules',        [ModuleController::class, 'store']);
    Route::put('/modules/{id}',    [ModuleController::class, 'update']);
    Route::delete('/modules/{id}', [ModuleController::class, 'destroy']);

    Route::post('/missions',        [MissionController::class, 'store']);
    Route::put('/missions/{id}',    [MissionController::class, 'update']);
    Route::delete('/missions/{id}', [MissionController::class, 'destroy']);

    Route::get('/assessments',                               [AssessmentController::class, 'index']);
    Route::post('/assessments',                              [AssessmentController::class, 'store']);
    Route::get('/assessments/compare/{userId}/{missionId}',  [AssessmentController::class, 'compare']);

    Route::get('/dashboard/{userId}',            [PerformanceDashboardController::class, 'show']);
    Route::get('/dashboard/{userId}/{strandId}', [PerformanceDashboardController::class, 'showByStrand']);
    Route::get('/leaderboard/{strandId}',        [PerformanceDashboardController::class, 'leaderboard']);

    Route::get('/survey',         [AcceptabilitySurveyController::class, 'index']);
    Route::post('/survey',        [AcceptabilitySurveyController::class, 'store']);
    Route::get('/survey/results', [AcceptabilitySurveyController::class, 'results']);

    // ── Game Session (Unity sync) ─────────────────────────────────────
    Route::post('/game/heartbeat',        [GameSessionController::class, 'heartbeat']);
    Route::post('/game/mission/start',    [GameSessionController::class, 'startMission']);
    Route::post('/game/mission/complete', [GameSessionController::class, 'completeMission']);
    Route::get('/game/online',            [GameSessionController::class, 'onlinePlayers']);
});

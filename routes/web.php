<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StrandPageController;
use App\Http\Controllers\ModulePageController;
use App\Http\Controllers\MissionPageController;
use App\Http\Controllers\AssessmentPageController;
use App\Http\Controllers\LoginLogController;
use App\Http\Controllers\SurveyPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

// ── Public: redirect root to login ──────────────────────────────────
Route::get('/', fn() => redirect()->route('admin.login'));

// ── Auth routes (no middleware) ──────────────────────────────────────
Route::get('/admin/login',    [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login',   [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::get('/admin/register', [AdminAuthController::class, 'showRegister'])->name('admin.register');
Route::post('/admin/register',[AdminAuthController::class, 'register'])->name('admin.register.post');
Route::post('/admin/logout',  [AdminAuthController::class, 'logout'])->name('admin.logout');

// ── Protected admin routes ───────────────────────────────────────────
// admin.auth confirms login first, THEN scope.spec resolves cms_scoped_strand
Route::middleware(['admin.auth', 'scope.spec'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Live Monitoring — read access for everyone logged in; scope.spec
    // restricts a teacher's view to students active in their own strand
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

    // Players — students only. Open to BOTH admin and teacher; PlayerController
    // itself enforces the strand scope on every method (index/show/store/
    // update/destroy), since "can this teacher touch THIS specific student"
    // depends on the record, not just the route, so middleware alone can't
    // express it — the scoping check lives inside the controller.
    Route::get('/players',         [PlayerController::class, 'index'])->name('players.index');
    Route::get('/players/{id}',    [PlayerController::class, 'show'])->name('players.show');
    Route::post('/players',        [PlayerController::class, 'store'])->name('players.store');
    Route::put('/players/{id}',    [PlayerController::class, 'update'])->name('players.update');
    Route::delete('/players/{id}', [PlayerController::class, 'destroy'])->name('players.destroy');

    // Admin-only: Manage Teachers + Strand writes.
    // Teachers must NEVER manage other teacher/admin accounts, and strands
    // are structural (ICT / Home Economics / Industrial Arts), not content,
    // so both stay locked to admin regardless of specialization.
    Route::middleware('admin.only')->group(function () {
        Route::get('/teachers',         [TeacherController::class, 'index'])->name('teachers.index');
        Route::post('/teachers',        [TeacherController::class, 'store'])->name('teachers.store');
        Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

        Route::post('/strands',         [StrandPageController::class, 'store'])->name('strands.store');
        Route::put('/strands/{id}',     [StrandPageController::class, 'update'])->name('strands.update');
        Route::delete('/strands/{id}',  [StrandPageController::class, 'destroy'])->name('strands.destroy');
    });

    // Strands — read access for everyone logged in (teachers see only their own via scope.spec)
    Route::get('/strands', [StrandPageController::class, 'index'])->name('strands.index');

    // Modules — teacher CRUD scoped to their own strand inside the controller
    Route::get('/modules',          [ModulePageController::class, 'index'])->name('modules.index');
    Route::post('/modules',         [ModulePageController::class, 'store'])->name('modules.store');
    Route::put('/modules/{id}',     [ModulePageController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{id}',  [ModulePageController::class, 'destroy'])->name('modules.destroy');

    // Missions — same scoping pattern as Modules
    Route::get('/missions',         [MissionPageController::class, 'index'])->name('missions.index');
    Route::post('/missions',        [MissionPageController::class, 'store'])->name('missions.store');
    Route::put('/missions/{id}',    [MissionPageController::class, 'update'])->name('missions.update');
    Route::delete('/missions/{id}', [MissionPageController::class, 'destroy'])->name('missions.destroy');

    // Assessments
    Route::get('/assessments', [AssessmentPageController::class, 'index'])->name('assessments.index');

    // Login Logs
    Route::get('/login-logs', [LoginLogController::class, 'index'])->name('loginlogs.index');

    // Surveys
    Route::get('/surveys', [SurveyPageController::class, 'index'])->name('surveys.index');

    // Profile
    Route::get('/profile',              [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/picture',     [ProfileController::class, 'updatePicture'])->name('profile.picture');
    Route::post('/profile/info',        [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::post('/profile/password',    [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/picture',   [ProfileController::class, 'removePicture'])->name('profile.picture.remove');
});

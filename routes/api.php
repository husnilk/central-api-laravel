<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\LecturerController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/unauthorized', [AuthController::class, 'unauthorized'])->name('api.unauthorized');
Route::get('/forbidden', [AuthController::class, 'forbidden'])->name('api.forbidden');
Route::post('/forgot-password', [PasswordController::class, 'forgot']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    /*--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------*/
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/me', [ProfileController::class, 'update']);
    Route::get('/me', [ProfileController::class, 'index']);
    Route::post('/password', [PasswordController::class, 'update']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/submit-token', [ProfileController::class, 'mobileToken']);
    Route::get('/login/history', [AuthController::class, 'history']);
    Route::post("/v2/me", [ProfileController::class, "updateV2"])->name("profile.update");

    /*--------------------------------------------------------------------------
    | Manajemen Data Master
    |--------------------------------------------------------------------------*/
    Route::apiResource('buildings', BuildingController::class);
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('faculties',FacultyController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('lecturers', LecturerController::class);
    Route::apiResource('staff', StaffController::class);
});

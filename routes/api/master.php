<?php


use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\LecturerController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;


/*--------------------------------------------------------------------------
/ Manajemen Data Master
/--------------------------------------------------------------------------*/
Route::apiResource('buildings', BuildingController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('faculties', FacultyController::class);
Route::apiResource('departments', DepartmentController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('lecturers', LecturerController::class);
Route::apiResource('staff', StaffController::class);

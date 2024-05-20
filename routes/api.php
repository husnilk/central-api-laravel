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
    / Manajemen Data Master
    /--------------------------------------------------------------------------*/
    Route::apiResource('buildings', BuildingController::class);
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('faculties',FacultyController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('lecturers', LecturerController::class);
    Route::apiResource('staff', StaffController::class);
    /*--------------------------------------------------------------------------
    / Manajemen Kurikulum
    /--------------------------------------------------------------------------*/
    Route::apiResource('curricula', App\Http\Controllers\Api\CurriculumController::class);
    Route::apiResource('curriculum-peos', App\Http\Controllers\Api\CurriculumPeoController::class);
    Route::apiResource('curriculum-plos', App\Http\Controllers\Api\CurriculumPloController::class);
    Route::apiResource('curriculum-peo-plos', App\Http\Controllers\Api\CurriculumPeoPloController::class);
    Route::apiResource('curriculum-indicators', App\Http\Controllers\Api\CurriculumIndicatorController::class);
    Route::apiResource('curriculum-boks', App\Http\Controllers\Api\CurriculumBokController::class);
    Route::apiResource('curriculum-bok-details', App\Http\Controllers\Api\CurriculumBokDetailController::class);
    Route::apiResource('courses', App\Http\Controllers\Api\CourseController::class);
    Route::apiResource('course-plans', App\Http\Controllers\Api\CoursePlanController::class);
    Route::apiResource('course-plan-detail-activities', App\Http\Controllers\Api\CoursePlanDetailActivityController::class);
    Route::apiResource('course-plan-medias', App\Http\Controllers\Api\CoursePlanMediaController::class);
    Route::apiResource('course-plan-requirements', App\Http\Controllers\Api\CoursePlanRequirementController::class);
    Route::apiResource('course-plan-los', App\Http\Controllers\Api\CoursePlanLoController::class);
    Route::apiResource('course-plan-references', App\Http\Controllers\Api\CoursePlanReferenceController::class);
    Route::apiResource('course-plan-materials', App\Http\Controllers\Api\CoursePlanMaterialController::class);
    Route::apiResource('course-plan-lecturers', App\Http\Controllers\Api\CoursePlanLecturerController::class);
    Route::apiResource('course-plan-details', App\Http\Controllers\Api\CoursePlanDetailController::class);
    Route::apiResource('course-curriculum-indicators', App\Http\Controllers\Api\CourseCurriculumIndicatorController::class);
    Route::apiResource('course-plan-assessments', App\Http\Controllers\Api\CoursePlanAssessmentController::class);
    /*--------------------------------------------------------------------------
    / Manajemen Penilaian
    /--------------------------------------------------------------------------*/
    Route::apiResource('assessment-details', App\Http\Controllers\Api\AssessmentDetailController::class);
    Route::apiResource('assessment-criterias', App\Http\Controllers\Api\AssessmentCriteriaController::class);
    Route::apiResource('assessment-rubrics', App\Http\Controllers\Api\AssessmentRubricController::class);
    Route::apiResource('assessments', App\Http\Controllers\Api\AssessmentController::class);
    /*--------------------------------------------------------------------------
    / Manajemen KRS
    /--------------------------------------------------------------------------*/
    Route::apiResource('periods', App\Http\Controllers\Api\PeriodController::class);
    Route::apiResource('class-courses', App\Http\Controllers\Api\ClassCourseController::class);
    Route::apiResource('class-schedules', App\Http\Controllers\Api\ClassScheduleController::class);
    Route::apiResource('class-lecturers', App\Http\Controllers\Api\ClassLecturerController::class);
    Route::apiResource('study-plans', App\Http\Controllers\Api\StudyPlanController::class);
    Route::apiResource('study-plan-details', App\Http\Controllers\Api\StudyPlanDetailController::class);
    /*--------------------------------------------------------------------------
    / Manajemen Kehadiran
    /--------------------------------------------------------------------------*/
    Route::apiResource('class-meetings', App\Http\Controllers\Api\ClassMeetingController::class);
    Route::apiResource('class-attendances', App\Http\Controllers\Api\ClassAttendanceController::class);
    /*--------------------------------------------------------------------------
    / Manajemen Penelitian dan Pengabdian
    /--------------------------------------------------------------------------*/
    Route::apiResource('research-schemas', App\Http\Controllers\Api\ResearchSchemaController::class);
    Route::apiResource('research', App\Http\Controllers\Api\ResearchController::class);
    Route::apiResource('research-members', App\Http\Controllers\Api\ResearchMemberController::class);
    Route::apiResource('community-service-schemas', App\Http\Controllers\Api\CommunityServiceSchemaController::class);
    Route::apiResource('community-services', App\Http\Controllers\Api\CommunityServiceController::class);
    Route::apiResource('community-service-members', App\Http\Controllers\Api\CommunityServiceMemberController::class);
    /*--------------------------------------------------------------------------
    / Manajemen Data Publikasi
    /--------------------------------------------------------------------------*/
    Route::apiResource('publishers', App\Http\Controllers\Api\PublisherController::class);
    Route::apiResource('publications', App\Http\Controllers\Api\PublicationController::class);
    Route::apiResource('publication-authors', App\Http\Controllers\Api\PublicationAuthorController::class);
    /*--------------------------------------------------------------------------
    / Manajemen Kerja Praktek
    /--------------------------------------------------------------------------*/
    Route::apiResource('internship-companies', App\Http\Controllers\Api\InternshipCompanyController::class);
    Route::apiResource('internship-proposals', App\Http\Controllers\Api\InternshipProposalController::class);
    Route::apiResource('internships', App\Http\Controllers\Api\InternshipController::class);
    Route::apiResource('internship-logbooks', App\Http\Controllers\Api\InternshipLogbookController::class);
    Route::apiResource('internship-seminar-audiences', App\Http\Controllers\Api\InternshipSeminarAudienceController::class);
    /*--------------------------------------------------------------------------
    / Manajemen Tugas Akhir
    /--------------------------------------------------------------------------*/
    Route::apiResource('thesis-rubrics', App\Http\Controllers\Api\ThesisRubricController::class);
    Route::apiResource('thesis-rubric-details', App\Http\Controllers\Api\ThesisRubricDetailController::class);
    Route::apiResource('thesis-topics', App\Http\Controllers\Api\ThesisTopicController::class);
    Route::apiResource('theses', App\Http\Controllers\Api\ThesisController::class);
    Route::apiResource('thesis-supervisors', App\Http\Controllers\Api\ThesisSupervisorController::class);
    Route::apiResource('thesis-proposals', App\Http\Controllers\Api\ThesisProposalController::class);
    Route::apiResource('thesis-proposal-audiences', App\Http\Controllers\Api\ThesisProposalAudienceController::class);
    Route::apiResource('thesis-logbooks', App\Http\Controllers\Api\ThesisLogbookController::class);
    Route::apiResource('thesis-seminars', App\Http\Controllers\Api\ThesisSeminarController::class);
    Route::apiResource('thesis-seminar-audiences', App\Http\Controllers\Api\ThesisSeminarAudienceController::class);
    Route::apiResource('thesis-seminar-reviewers', App\Http\Controllers\Api\ThesisSeminarReviewerController::class);
    Route::apiResource('thesis-defenses', App\Http\Controllers\Api\ThesisDefenseController::class);
    Route::apiResource('thesis-defense-examiners', App\Http\Controllers\Api\ThesisDefenseExaminerController::class);
    Route::apiResource('thesis-defense-scores', App\Http\Controllers\Api\ThesisDefenseScoreController::class);
});

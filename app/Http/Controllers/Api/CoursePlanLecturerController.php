<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanLecturerStoreRequest;
use App\Http\Requests\CoursePlanLecturerUpdateRequest;
use App\Http\Resources\CoursePlanLecturerCollection;
use App\Http\Resources\CoursePlanLecturerResource;
use App\Models\CoursePlanLecturer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanLecturerController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlanLecturers = CoursePlanLecturer::all();

        return new CoursePlanLecturerCollection($coursePlanLecturers);
    }

    public function store(CoursePlanLecturerStoreRequest $request): Response
    {
        $coursePlanLecturer = CoursePlanLecturer::create($request->validated());

        return new CoursePlanLecturerResource($coursePlanLecturer);
    }

    public function show(Request $request, CoursePlanLecturer $coursePlanLecturer): Response
    {
        return new CoursePlanLecturerResource($coursePlanLecturer);
    }

    public function update(CoursePlanLecturerUpdateRequest $request, CoursePlanLecturer $coursePlanLecturer): Response
    {
        $coursePlanLecturer->update($request->validated());

        return new CoursePlanLecturerResource($coursePlanLecturer);
    }

    public function destroy(Request $request, CoursePlanLecturer $coursePlanLecturer): Response
    {
        $coursePlanLecturer->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanAssessmentStoreRequest;
use App\Http\Requests\CoursePlanAssessmentUpdateRequest;
use App\Http\Resources\CoursePlanAssessmentCollection;
use App\Http\Resources\CoursePlanAssessmentResource;
use App\Models\CoursePlanAssessment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanAssessmentController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlanAssessments = CoursePlanAssessment::all();

        return new CoursePlanAssessmentCollection($coursePlanAssessments);
    }

    public function store(CoursePlanAssessmentStoreRequest $request): Response
    {
        $coursePlanAssessment = CoursePlanAssessment::create($request->validated());

        return new CoursePlanAssessmentResource($coursePlanAssessment);
    }

    public function show(Request $request, CoursePlanAssessment $coursePlanAssessment): Response
    {
        return new CoursePlanAssessmentResource($coursePlanAssessment);
    }

    public function update(CoursePlanAssessmentUpdateRequest $request, CoursePlanAssessment $coursePlanAssessment): Response
    {
        $coursePlanAssessment->update($request->validated());

        return new CoursePlanAssessmentResource($coursePlanAssessment);
    }

    public function destroy(Request $request, CoursePlanAssessment $coursePlanAssessment): Response
    {
        $coursePlanAssessment->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyPlanStoreRequest;
use App\Http\Requests\StudyPlanUpdateRequest;
use App\Http\Resources\StudyPlanCollection;
use App\Http\Resources\StudyPlanResource;
use App\Models\StudyPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudyPlanController extends Controller
{
    public function index(Request $request): Response
    {
        $studyPlans = StudyPlan::all();

        return new StudyPlanCollection($studyPlans);
    }

    public function store(StudyPlanStoreRequest $request): Response
    {
        $studyPlan = StudyPlan::create($request->validated());

        return new StudyPlanResource($studyPlan);
    }

    public function show(Request $request, StudyPlan $studyPlan): Response
    {
        return new StudyPlanResource($studyPlan);
    }

    public function update(StudyPlanUpdateRequest $request, StudyPlan $studyPlan): Response
    {
        $studyPlan->update($request->validated());

        return new StudyPlanResource($studyPlan);
    }

    public function destroy(Request $request, StudyPlan $studyPlan): Response
    {
        $studyPlan->delete();

        return response()->noContent();
    }
}

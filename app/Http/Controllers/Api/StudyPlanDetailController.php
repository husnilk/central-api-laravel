<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyPlanDetailStoreRequest;
use App\Http\Requests\StudyPlanDetailUpdateRequest;
use App\Http\Resources\StudyPlanDetailCollection;
use App\Http\Resources\StudyPlanDetailResource;
use App\Models\StudyPlanDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudyPlanDetailController extends Controller
{
    public function index(Request $request): Response
    {
        $studyPlanDetails = StudyPlanDetail::all();

        return new StudyPlanDetailCollection($studyPlanDetails);
    }

    public function store(StudyPlanDetailStoreRequest $request): Response
    {
        $studyPlanDetail = StudyPlanDetail::create($request->validated());

        return new StudyPlanDetailResource($studyPlanDetail);
    }

    public function show(Request $request, StudyPlanDetail $studyPlanDetail): Response
    {
        return new StudyPlanDetailResource($studyPlanDetail);
    }

    public function update(StudyPlanDetailUpdateRequest $request, StudyPlanDetail $studyPlanDetail): Response
    {
        $studyPlanDetail->update($request->validated());

        return new StudyPlanDetailResource($studyPlanDetail);
    }

    public function destroy(Request $request, StudyPlanDetail $studyPlanDetail): Response
    {
        $studyPlanDetail->delete();

        return response()->noContent();
    }
}

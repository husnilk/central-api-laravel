<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentRubricStoreRequest;
use App\Http\Requests\AssessmentRubricUpdateRequest;
use App\Http\Resources\AssessmentRubricCollection;
use App\Http\Resources\AssessmentRubricResource;
use App\Models\AssessmentRubric;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AssessmentRubricController extends Controller
{
    public function index(Request $request): Response
    {
        $assessmentRubrics = AssessmentRubric::all();

        return new AssessmentRubricCollection($assessmentRubrics);
    }

    public function store(AssessmentRubricStoreRequest $request): Response
    {
        $assessmentRubric = AssessmentRubric::create($request->validated());

        return new AssessmentRubricResource($assessmentRubric);
    }

    public function show(Request $request, AssessmentRubric $assessmentRubric): Response
    {
        return new AssessmentRubricResource($assessmentRubric);
    }

    public function update(AssessmentRubricUpdateRequest $request, AssessmentRubric $assessmentRubric): Response
    {
        $assessmentRubric->update($request->validated());

        return new AssessmentRubricResource($assessmentRubric);
    }

    public function destroy(Request $request, AssessmentRubric $assessmentRubric): Response
    {
        $assessmentRubric->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentStoreRequest;
use App\Http\Requests\AssessmentUpdateRequest;
use App\Http\Resources\AssessmentCollection;
use App\Http\Resources\AssessmentResource;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AssessmentController extends Controller
{
    public function index(Request $request): Response
    {
        $assessments = Assessment::all();

        return new AssessmentCollection($assessments);
    }

    public function store(AssessmentStoreRequest $request): Response
    {
        $assessment = Assessment::create($request->validated());

        return new AssessmentResource($assessment);
    }

    public function show(Request $request, Assessment $assessment): Response
    {
        return new AssessmentResource($assessment);
    }

    public function update(AssessmentUpdateRequest $request, Assessment $assessment): Response
    {
        $assessment->update($request->validated());

        return new AssessmentResource($assessment);
    }

    public function destroy(Request $request, Assessment $assessment): Response
    {
        $assessment->delete();

        return response()->noContent();
    }
}

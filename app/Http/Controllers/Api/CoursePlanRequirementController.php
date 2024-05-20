<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanRequirementStoreRequest;
use App\Http\Requests\CoursePlanRequirementUpdateRequest;
use App\Http\Resources\CoursePlanRequirementCollection;
use App\Http\Resources\CoursePlanRequirementResource;
use App\Models\CoursePlanRequirement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanRequirementController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlanRequirements = CoursePlanRequirement::all();

        return new CoursePlanRequirementCollection($coursePlanRequirements);
    }

    public function store(CoursePlanRequirementStoreRequest $request): Response
    {
        $coursePlanRequirement = CoursePlanRequirement::create($request->validated());

        return new CoursePlanRequirementResource($coursePlanRequirement);
    }

    public function show(Request $request, CoursePlanRequirement $coursePlanRequirement): Response
    {
        return new CoursePlanRequirementResource($coursePlanRequirement);
    }

    public function update(CoursePlanRequirementUpdateRequest $request, CoursePlanRequirement $coursePlanRequirement): Response
    {
        $coursePlanRequirement->update($request->validated());

        return new CoursePlanRequirementResource($coursePlanRequirement);
    }

    public function destroy(Request $request, CoursePlanRequirement $coursePlanRequirement): Response
    {
        $coursePlanRequirement->delete();

        return response()->noContent();
    }
}

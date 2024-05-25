<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanReferenceStoreRequest;
use App\Http\Requests\CoursePlanReferenceUpdateRequest;
use App\Http\Resources\CoursePlanReferenceCollection;
use App\Http\Resources\CoursePlanReferenceResource;
use App\Models\CoursePlanReference;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanReferenceController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlanReferences = CoursePlanReference::all();

        return new CoursePlanReferenceCollection($coursePlanReferences);
    }

    public function store(CoursePlanReferenceStoreRequest $request): Response
    {
        $coursePlanReference = CoursePlanReference::create($request->validated());

        return new CoursePlanReferenceResource($coursePlanReference);
    }

    public function show(Request $request, CoursePlanReference $coursePlanReference): Response
    {
        return new CoursePlanReferenceResource($coursePlanReference);
    }

    public function update(CoursePlanReferenceUpdateRequest $request, CoursePlanReference $coursePlanReference): Response
    {
        $coursePlanReference->update($request->validated());

        return new CoursePlanReferenceResource($coursePlanReference);
    }

    public function destroy(Request $request, CoursePlanReference $coursePlanReference): Response
    {
        $coursePlanReference->delete();

        return response()->noContent();
    }
}

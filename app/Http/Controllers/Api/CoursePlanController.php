<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanStoreRequest;
use App\Http\Requests\CoursePlanUpdateRequest;
use App\Http\Resources\CoursePlanCollection;
use App\Http\Resources\CoursePlanResource;
use App\Models\CoursePlan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlans = CoursePlan::all();

        return new CoursePlanCollection($coursePlans);
    }

    public function store(CoursePlanStoreRequest $request): Response
    {
        $coursePlan = CoursePlan::create($request->validated());

        return new CoursePlanResource($coursePlan);
    }

    public function show(Request $request, CoursePlan $coursePlan): Response
    {
        return new CoursePlanResource($coursePlan);
    }

    public function update(CoursePlanUpdateRequest $request, CoursePlan $coursePlan): Response
    {
        $coursePlan->update($request->validated());

        return new CoursePlanResource($coursePlan);
    }

    public function destroy(Request $request, CoursePlan $coursePlan): Response
    {
        $coursePlan->delete();

        return response()->noContent();
    }
}

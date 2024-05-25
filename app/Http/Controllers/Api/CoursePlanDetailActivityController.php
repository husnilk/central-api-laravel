<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanDetailActivityStoreRequest;
use App\Http\Requests\CoursePlanDetailActivityUpdateRequest;
use App\Http\Resources\CoursePlanDetailActivityCollection;
use App\Http\Resources\CoursePlanDetailActivityResource;
use App\Models\CoursePlanDetailActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanDetailActivityController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlanDetailActivities = CoursePlanDetailActivity::all();

        return new CoursePlanDetailActivityCollection($coursePlanDetailActivities);
    }

    public function store(CoursePlanDetailActivityStoreRequest $request): Response
    {
        $coursePlanDetailActivity = CoursePlanDetailActivity::create($request->validated());

        return new CoursePlanDetailActivityResource($coursePlanDetailActivity);
    }

    public function show(Request $request, CoursePlanDetailActivity $coursePlanDetailActivity): Response
    {
        return new CoursePlanDetailActivityResource($coursePlanDetailActivity);
    }

    public function update(CoursePlanDetailActivityUpdateRequest $request, CoursePlanDetailActivity $coursePlanDetailActivity): Response
    {
        $coursePlanDetailActivity->update($request->validated());

        return new CoursePlanDetailActivityResource($coursePlanDetailActivity);
    }

    public function destroy(Request $request, CoursePlanDetailActivity $coursePlanDetailActivity): Response
    {
        $coursePlanDetailActivity->delete();

        return response()->noContent();
    }
}

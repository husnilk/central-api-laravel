<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanDetailStoreRequest;
use App\Http\Requests\CoursePlanDetailUpdateRequest;
use App\Http\Resources\CoursePlanDetailCollection;
use App\Http\Resources\CoursePlanDetailResource;
use App\Models\CoursePlanDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanDetailController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlanDetails = CoursePlanDetail::all();

        return new CoursePlanDetailCollection($coursePlanDetails);
    }

    public function store(CoursePlanDetailStoreRequest $request): Response
    {
        $coursePlanDetail = CoursePlanDetail::create($request->validated());

        return new CoursePlanDetailResource($coursePlanDetail);
    }

    public function show(Request $request, CoursePlanDetail $coursePlanDetail): Response
    {
        return new CoursePlanDetailResource($coursePlanDetail);
    }

    public function update(CoursePlanDetailUpdateRequest $request, CoursePlanDetail $coursePlanDetail): Response
    {
        $coursePlanDetail->update($request->validated());

        return new CoursePlanDetailResource($coursePlanDetail);
    }

    public function destroy(Request $request, CoursePlanDetail $coursePlanDetail): Response
    {
        $coursePlanDetail->delete();

        return response()->noContent();
    }
}

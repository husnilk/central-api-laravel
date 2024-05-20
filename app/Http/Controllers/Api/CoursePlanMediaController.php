<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanMediaStoreRequest;
use App\Http\Requests\CoursePlanMediaUpdateRequest;
use App\Http\Resources\CoursePlanMediaCollection;
use App\Http\Resources\CoursePlanMediaResource;
use App\Models\CoursePlanMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanMediaController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlanMedia = CoursePlanMedia::all();

        return new CoursePlanMediaCollection($coursePlanMedia);
    }

    public function store(CoursePlanMediaStoreRequest $request): Response
    {
        $coursePlanMedia = CoursePlanMedia::create($request->validated());

        return new CoursePlanMediaResource($coursePlanMedia);
    }

    public function show(Request $request, CoursePlanMedia $coursePlanMedia): Response
    {
        return new CoursePlanMediaResource($coursePlanMedia);
    }

    public function update(CoursePlanMediaUpdateRequest $request, CoursePlanMedia $coursePlanMedia): Response
    {
        $coursePlanMedia->update($request->validated());

        return new CoursePlanMediaResource($coursePlanMedia);
    }

    public function destroy(Request $request, CoursePlanMedia $coursePlanMedia): Response
    {
        $coursePlanMedia->delete();

        return response()->noContent();
    }
}

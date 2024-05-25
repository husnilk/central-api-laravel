<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThesisSeminarReviewerStoreRequest;
use App\Http\Requests\ThesisSeminarReviewerUpdateRequest;
use App\Http\Resources\ThesisSeminarReviewerCollection;
use App\Http\Resources\ThesisSeminarReviewerResource;
use App\Models\ThesisSeminarReviewer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThesisSeminarReviewerController extends Controller
{
    public function index(Request $request): Response
    {
        $thesisSeminarReviewers = ThesisSeminarReviewer::all();

        return new ThesisSeminarReviewerCollection($thesisSeminarReviewers);
    }

    public function store(ThesisSeminarReviewerStoreRequest $request): Response
    {
        $thesisSeminarReviewer = ThesisSeminarReviewer::create($request->validated());

        return new ThesisSeminarReviewerResource($thesisSeminarReviewer);
    }

    public function show(Request $request, ThesisSeminarReviewer $thesisSeminarReviewer): Response
    {
        return new ThesisSeminarReviewerResource($thesisSeminarReviewer);
    }

    public function update(ThesisSeminarReviewerUpdateRequest $request, ThesisSeminarReviewer $thesisSeminarReviewer): Response
    {
        $thesisSeminarReviewer->update($request->validated());

        return new ThesisSeminarReviewerResource($thesisSeminarReviewer);
    }

    public function destroy(Request $request, ThesisSeminarReviewer $thesisSeminarReviewer): Response
    {
        $thesisSeminarReviewer->delete();

        return response()->noContent();
    }
}

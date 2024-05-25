<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThesisRubricDetailStoreRequest;
use App\Http\Requests\ThesisRubricDetailUpdateRequest;
use App\Http\Resources\ThesisRubricDetailCollection;
use App\Http\Resources\ThesisRubricDetailResource;
use App\Models\ThesisRubricDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThesisRubricDetailController extends Controller
{
    public function index(Request $request): Response
    {
        $thesisRubricDetails = ThesisRubricDetail::all();

        return new ThesisRubricDetailCollection($thesisRubricDetails);
    }

    public function store(ThesisRubricDetailStoreRequest $request): Response
    {
        $thesisRubricDetail = ThesisRubricDetail::create($request->validated());

        return new ThesisRubricDetailResource($thesisRubricDetail);
    }

    public function show(Request $request, ThesisRubricDetail $thesisRubricDetail): Response
    {
        return new ThesisRubricDetailResource($thesisRubricDetail);
    }

    public function update(ThesisRubricDetailUpdateRequest $request, ThesisRubricDetail $thesisRubricDetail): Response
    {
        $thesisRubricDetail->update($request->validated());

        return new ThesisRubricDetailResource($thesisRubricDetail);
    }

    public function destroy(Request $request, ThesisRubricDetail $thesisRubricDetail): Response
    {
        $thesisRubricDetail->delete();

        return response()->noContent();
    }
}

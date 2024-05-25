<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurriculumBokDetailStoreRequest;
use App\Http\Requests\CurriculumBokDetailUpdateRequest;
use App\Http\Resources\CurriculumBokDetailCollection;
use App\Http\Resources\CurriculumBokDetailResource;
use App\Models\CurriculumBokDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurriculumBokDetailController extends Controller
{
    public function index(Request $request): Response
    {
        $curriculumBokDetails = CurriculumBokDetail::all();

        return new CurriculumBokDetailCollection($curriculumBokDetails);
    }

    public function store(CurriculumBokDetailStoreRequest $request): Response
    {
        $curriculumBokDetail = CurriculumBokDetail::create($request->validated());

        return new CurriculumBokDetailResource($curriculumBokDetail);
    }

    public function show(Request $request, CurriculumBokDetail $curriculumBokDetail): Response
    {
        return new CurriculumBokDetailResource($curriculumBokDetail);
    }

    public function update(CurriculumBokDetailUpdateRequest $request, CurriculumBokDetail $curriculumBokDetail): Response
    {
        $curriculumBokDetail->update($request->validated());

        return new CurriculumBokDetailResource($curriculumBokDetail);
    }

    public function destroy(Request $request, CurriculumBokDetail $curriculumBokDetail): Response
    {
        $curriculumBokDetail->delete();

        return response()->noContent();
    }
}

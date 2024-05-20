<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurriculumPeoPloStoreRequest;
use App\Http\Requests\CurriculumPeoPloUpdateRequest;
use App\Http\Resources\CurriculumPeoPloCollection;
use App\Http\Resources\CurriculumPeoPloResource;
use App\Models\CurriculumPeoPlo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurriculumPeoPloController extends Controller
{
    public function index(Request $request): Response
    {
        $curriculumPeoPlos = CurriculumPeoPlo::all();

        return new CurriculumPeoPloCollection($curriculumPeoPlos);
    }

    public function store(CurriculumPeoPloStoreRequest $request): Response
    {
        $curriculumPeoPlo = CurriculumPeoPlo::create($request->validated());

        return new CurriculumPeoPloResource($curriculumPeoPlo);
    }

    public function show(Request $request, CurriculumPeoPlo $curriculumPeoPlo): Response
    {
        return new CurriculumPeoPloResource($curriculumPeoPlo);
    }

    public function update(CurriculumPeoPloUpdateRequest $request, CurriculumPeoPlo $curriculumPeoPlo): Response
    {
        $curriculumPeoPlo->update($request->validated());

        return new CurriculumPeoPloResource($curriculumPeoPlo);
    }

    public function destroy(Request $request, CurriculumPeoPlo $curriculumPeoPlo): Response
    {
        $curriculumPeoPlo->delete();

        return response()->noContent();
    }
}

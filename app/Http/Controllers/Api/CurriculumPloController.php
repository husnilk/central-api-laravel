<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurriculumPloStoreRequest;
use App\Http\Requests\CurriculumPloUpdateRequest;
use App\Http\Resources\CurriculumPloCollection;
use App\Http\Resources\CurriculumPloResource;
use App\Models\CurriculumPlo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurriculumPloController extends Controller
{
    public function index(Request $request): Response
    {
        $curriculumPlos = CurriculumPlo::all();

        return new CurriculumPloCollection($curriculumPlos);
    }

    public function store(CurriculumPloStoreRequest $request): Response
    {
        $curriculumPlo = CurriculumPlo::create($request->validated());

        return new CurriculumPloResource($curriculumPlo);
    }

    public function show(Request $request, CurriculumPlo $curriculumPlo): Response
    {
        return new CurriculumPloResource($curriculumPlo);
    }

    public function update(CurriculumPloUpdateRequest $request, CurriculumPlo $curriculumPlo): Response
    {
        $curriculumPlo->update($request->validated());

        return new CurriculumPloResource($curriculumPlo);
    }

    public function destroy(Request $request, CurriculumPlo $curriculumPlo): Response
    {
        $curriculumPlo->delete();

        return response()->noContent();
    }
}

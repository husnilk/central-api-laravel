<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurriculumBokStoreRequest;
use App\Http\Requests\CurriculumBokUpdateRequest;
use App\Http\Resources\CurriculumBokCollection;
use App\Http\Resources\CurriculumBokResource;
use App\Models\CurriculumBok;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurriculumBokController extends Controller
{
    public function index(Request $request): Response
    {
        $curriculumBoks = CurriculumBok::all();

        return new CurriculumBokCollection($curriculumBoks);
    }

    public function store(CurriculumBokStoreRequest $request): Response
    {
        $curriculumBok = CurriculumBok::create($request->validated());

        return new CurriculumBokResource($curriculumBok);
    }

    public function show(Request $request, CurriculumBok $curriculumBok): Response
    {
        return new CurriculumBokResource($curriculumBok);
    }

    public function update(CurriculumBokUpdateRequest $request, CurriculumBok $curriculumBok): Response
    {
        $curriculumBok->update($request->validated());

        return new CurriculumBokResource($curriculumBok);
    }

    public function destroy(Request $request, CurriculumBok $curriculumBok): Response
    {
        $curriculumBok->delete();

        return response()->noContent();
    }
}

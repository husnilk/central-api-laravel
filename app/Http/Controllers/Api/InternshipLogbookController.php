<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternshipLogbookStoreRequest;
use App\Http\Requests\InternshipLogbookUpdateRequest;
use App\Http\Resources\InternshipLogbookCollection;
use App\Http\Resources\InternshipLogbookResource;
use App\Models\InternshipLogbook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternshipLogbookController extends Controller
{
    public function index(Request $request): Response
    {
        $internshipLogbooks = InternshipLogbook::all();

        return new InternshipLogbookCollection($internshipLogbooks);
    }

    public function store(InternshipLogbookStoreRequest $request): Response
    {
        $internshipLogbook = InternshipLogbook::create($request->validated());

        return new InternshipLogbookResource($internshipLogbook);
    }

    public function show(Request $request, InternshipLogbook $internshipLogbook): Response
    {
        return new InternshipLogbookResource($internshipLogbook);
    }

    public function update(InternshipLogbookUpdateRequest $request, InternshipLogbook $internshipLogbook): Response
    {
        $internshipLogbook->update($request->validated());

        return new InternshipLogbookResource($internshipLogbook);
    }

    public function destroy(Request $request, InternshipLogbook $internshipLogbook): Response
    {
        $internshipLogbook->delete();

        return response()->noContent();
    }
}

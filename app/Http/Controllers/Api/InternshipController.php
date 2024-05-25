<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternshipStoreRequest;
use App\Http\Requests\InternshipUpdateRequest;
use App\Http\Resources\InternshipCollection;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternshipController extends Controller
{
    public function index(Request $request): Response
    {
        $internships = Internship::all();

        return new InternshipCollection($internships);
    }

    public function store(InternshipStoreRequest $request): Response
    {
        $internship = Internship::create($request->validated());

        return new InternshipResource($internship);
    }

    public function show(Request $request, Internship $internship): Response
    {
        return new InternshipResource($internship);
    }

    public function update(InternshipUpdateRequest $request, Internship $internship): Response
    {
        $internship->update($request->validated());

        return new InternshipResource($internship);
    }

    public function destroy(Request $request, Internship $internship): Response
    {
        $internship->delete();

        return response()->noContent();
    }
}

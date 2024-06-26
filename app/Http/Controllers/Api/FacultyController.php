<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyStoreRequest;
use App\Http\Requests\FacultyUpdateRequest;
use App\Http\Resources\FacultyCollection;
use App\Http\Resources\FacultyResource;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FacultyController extends Controller
{
    public function index(Request $request): Response
    {
        $faculties = Faculty::all();

        return new FacultyCollection($faculties);
    }

    public function store(FacultyStoreRequest $request): Response
    {
        $faculty = Faculty::create($request->validated());

        return new FacultyResource($faculty);
    }

    public function show(Request $request, Faculty $faculty): Response
    {
        return new FacultyResource($faculty);
    }

    public function update(FacultyUpdateRequest $request, Faculty $faculty): Response
    {
        $faculty->update($request->validated());

        return new FacultyResource($faculty);
    }

    public function destroy(Request $request, Faculty $faculty): Response
    {
        $faculty->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassLecturerStoreRequest;
use App\Http\Requests\ClassLecturerUpdateRequest;
use App\Http\Resources\ClassLecturerCollection;
use App\Http\Resources\ClassLecturerResource;
use App\Models\ClassLecturer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClassLecturerController extends Controller
{
    public function index(Request $request): Response
    {
        $classLecturers = ClassLecturer::all();

        return new ClassLecturerCollection($classLecturers);
    }

    public function store(ClassLecturerStoreRequest $request): Response
    {
        $classLecturer = ClassLecturer::create($request->validated());

        return new ClassLecturerResource($classLecturer);
    }

    public function show(Request $request, ClassLecturer $classLecturer): Response
    {
        return new ClassLecturerResource($classLecturer);
    }

    public function update(ClassLecturerUpdateRequest $request, ClassLecturer $classLecturer): Response
    {
        $classLecturer->update($request->validated());

        return new ClassLecturerResource($classLecturer);
    }

    public function destroy(Request $request, ClassLecturer $classLecturer): Response
    {
        $classLecturer->delete();

        return response()->noContent();
    }
}

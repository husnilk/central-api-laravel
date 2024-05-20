<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePlanMaterialStoreRequest;
use App\Http\Requests\CoursePlanMaterialUpdateRequest;
use App\Http\Resources\CoursePlanMaterialCollection;
use App\Http\Resources\CoursePlanMaterialResource;
use App\Models\CoursePlanMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursePlanMaterialController extends Controller
{
    public function index(Request $request): Response
    {
        $coursePlanMaterials = CoursePlanMaterial::all();

        return new CoursePlanMaterialCollection($coursePlanMaterials);
    }

    public function store(CoursePlanMaterialStoreRequest $request): Response
    {
        $coursePlanMaterial = CoursePlanMaterial::create($request->validated());

        return new CoursePlanMaterialResource($coursePlanMaterial);
    }

    public function show(Request $request, CoursePlanMaterial $coursePlanMaterial): Response
    {
        return new CoursePlanMaterialResource($coursePlanMaterial);
    }

    public function update(CoursePlanMaterialUpdateRequest $request, CoursePlanMaterial $coursePlanMaterial): Response
    {
        $coursePlanMaterial->update($request->validated());

        return new CoursePlanMaterialResource($coursePlanMaterial);
    }

    public function destroy(Request $request, CoursePlanMaterial $coursePlanMaterial): Response
    {
        $coursePlanMaterial->delete();

        return response()->noContent();
    }
}

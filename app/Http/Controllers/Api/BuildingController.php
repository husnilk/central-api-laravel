<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuildingStoreRequest;
use App\Http\Requests\BuildingUpdateRequest;
use App\Http\Resources\BuildingCollection;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuildingController extends Controller
{
    public function index(Request $request): Response
    {
        $buildings = Building::all();

        return new BuildingCollection($buildings);
    }

    public function store(BuildingStoreRequest $request): Response
    {
        $building = Building::create($request->validated());

        return new BuildingResource($building);
    }

    public function show(Request $request, Building $building): Response
    {
        return new BuildingResource($building);
    }

    public function update(BuildingUpdateRequest $request, Building $building): Response
    {
        $building->update($request->validated());

        return new BuildingResource($building);
    }

    public function destroy(Request $request, Building $building): Response
    {
        $building->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityServiceSchemaStoreRequest;
use App\Http\Requests\CommunityServiceSchemaUpdateRequest;
use App\Http\Resources\CommunityServiceSchemaCollection;
use App\Http\Resources\CommunityServiceSchemaResource;
use App\Models\CommunityServiceSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommunityServiceSchemaController extends Controller
{
    public function index(Request $request): Response
    {
        $communityServiceSchemas = CommunityServiceSchema::all();

        return new CommunityServiceSchemaCollection($communityServiceSchemas);
    }

    public function store(CommunityServiceSchemaStoreRequest $request): Response
    {
        $communityServiceSchema = CommunityServiceSchema::create($request->validated());

        return new CommunityServiceSchemaResource($communityServiceSchema);
    }

    public function show(Request $request, CommunityServiceSchema $communityServiceSchema): Response
    {
        return new CommunityServiceSchemaResource($communityServiceSchema);
    }

    public function update(CommunityServiceSchemaUpdateRequest $request, CommunityServiceSchema $communityServiceSchema): Response
    {
        $communityServiceSchema->update($request->validated());

        return new CommunityServiceSchemaResource($communityServiceSchema);
    }

    public function destroy(Request $request, CommunityServiceSchema $communityServiceSchema): Response
    {
        $communityServiceSchema->delete();

        return response()->noContent();
    }
}

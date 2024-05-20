<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityServiceStoreRequest;
use App\Http\Requests\CommunityServiceUpdateRequest;
use App\Http\Resources\CommunityServiceCollection;
use App\Http\Resources\CommunityServiceResource;
use App\Models\CommunityService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommunityServiceController extends Controller
{
    public function index(Request $request): Response
    {
        $communityServices = CommunityService::all();

        return new CommunityServiceCollection($communityServices);
    }

    public function store(CommunityServiceStoreRequest $request): Response
    {
        $communityService = CommunityService::create($request->validated());

        return new CommunityServiceResource($communityService);
    }

    public function show(Request $request, CommunityService $communityService): Response
    {
        return new CommunityServiceResource($communityService);
    }

    public function update(CommunityServiceUpdateRequest $request, CommunityService $communityService): Response
    {
        $communityService->update($request->validated());

        return new CommunityServiceResource($communityService);
    }

    public function destroy(Request $request, CommunityService $communityService): Response
    {
        $communityService->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityServiceMemberStoreRequest;
use App\Http\Requests\CommunityServiceMemberUpdateRequest;
use App\Http\Resources\CommunityServiceMemberCollection;
use App\Http\Resources\CommunityServiceMemberResource;
use App\Models\CommunityServiceMember;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommunityServiceMemberController extends Controller
{
    public function index(Request $request): Response
    {
        $communityServiceMembers = CommunityServiceMember::all();

        return new CommunityServiceMemberCollection($communityServiceMembers);
    }

    public function store(CommunityServiceMemberStoreRequest $request): Response
    {
        $communityServiceMember = CommunityServiceMember::create($request->validated());

        return new CommunityServiceMemberResource($communityServiceMember);
    }

    public function show(Request $request, CommunityServiceMember $communityServiceMember): Response
    {
        return new CommunityServiceMemberResource($communityServiceMember);
    }

    public function update(CommunityServiceMemberUpdateRequest $request, CommunityServiceMember $communityServiceMember): Response
    {
        $communityServiceMember->update($request->validated());

        return new CommunityServiceMemberResource($communityServiceMember);
    }

    public function destroy(Request $request, CommunityServiceMember $communityServiceMember): Response
    {
        $communityServiceMember->delete();

        return response()->noContent();
    }
}

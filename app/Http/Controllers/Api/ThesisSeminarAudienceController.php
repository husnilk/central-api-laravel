<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThesisSeminarAudienceStoreRequest;
use App\Http\Requests\ThesisSeminarAudienceUpdateRequest;
use App\Http\Resources\ThesisSeminarAudienceCollection;
use App\Http\Resources\ThesisSeminarAudienceResource;
use App\Models\ThesisSeminarAudience;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThesisSeminarAudienceController extends Controller
{
    public function index(Request $request): Response
    {
        $thesisSeminarAudiences = ThesisSeminarAudience::all();

        return new ThesisSeminarAudienceCollection($thesisSeminarAudiences);
    }

    public function store(ThesisSeminarAudienceStoreRequest $request): Response
    {
        $thesisSeminarAudience = ThesisSeminarAudience::create($request->validated());

        return new ThesisSeminarAudienceResource($thesisSeminarAudience);
    }

    public function show(Request $request, ThesisSeminarAudience $thesisSeminarAudience): Response
    {
        return new ThesisSeminarAudienceResource($thesisSeminarAudience);
    }

    public function update(ThesisSeminarAudienceUpdateRequest $request, ThesisSeminarAudience $thesisSeminarAudience): Response
    {
        $thesisSeminarAudience->update($request->validated());

        return new ThesisSeminarAudienceResource($thesisSeminarAudience);
    }

    public function destroy(Request $request, ThesisSeminarAudience $thesisSeminarAudience): Response
    {
        $thesisSeminarAudience->delete();

        return response()->noContent();
    }
}

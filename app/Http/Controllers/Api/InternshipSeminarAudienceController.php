<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternshipSeminarAudienceStoreRequest;
use App\Http\Requests\InternshipSeminarAudienceUpdateRequest;
use App\Http\Resources\InternshipSeminarAudienceCollection;
use App\Http\Resources\InternshipSeminarAudienceResource;
use App\Models\InternshipSeminarAudience;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternshipSeminarAudienceController extends Controller
{
    public function index(Request $request): Response
    {
        $internshipSeminarAudiences = InternshipSeminarAudience::all();

        return new InternshipSeminarAudienceCollection($internshipSeminarAudiences);
    }

    public function store(InternshipSeminarAudienceStoreRequest $request): Response
    {
        $internshipSeminarAudience = InternshipSeminarAudience::create($request->validated());

        return new InternshipSeminarAudienceResource($internshipSeminarAudience);
    }

    public function show(Request $request, InternshipSeminarAudience $internshipSeminarAudience): Response
    {
        return new InternshipSeminarAudienceResource($internshipSeminarAudience);
    }

    public function update(InternshipSeminarAudienceUpdateRequest $request, InternshipSeminarAudience $internshipSeminarAudience): Response
    {
        $internshipSeminarAudience->update($request->validated());

        return new InternshipSeminarAudienceResource($internshipSeminarAudience);
    }

    public function destroy(Request $request, InternshipSeminarAudience $internshipSeminarAudience): Response
    {
        $internshipSeminarAudience->delete();

        return response()->noContent();
    }
}

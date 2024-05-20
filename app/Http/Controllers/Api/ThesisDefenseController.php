<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThesisDefenseStoreRequest;
use App\Http\Requests\ThesisDefenseUpdateRequest;
use App\Http\Resources\ThesisDefenseCollection;
use App\Http\Resources\ThesisDefenseResource;
use App\Models\ThesisDefense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThesisDefenseController extends Controller
{
    public function index(Request $request): Response
    {
        $thesisDefenses = ThesisDefense::all();

        return new ThesisDefenseCollection($thesisDefenses);
    }

    public function store(ThesisDefenseStoreRequest $request): Response
    {
        $thesisDefense = ThesisDefense::create($request->validated());

        return new ThesisDefenseResource($thesisDefense);
    }

    public function show(Request $request, ThesisDefense $thesisDefense): Response
    {
        return new ThesisDefenseResource($thesisDefense);
    }

    public function update(ThesisDefenseUpdateRequest $request, ThesisDefense $thesisDefense): Response
    {
        $thesisDefense->update($request->validated());

        return new ThesisDefenseResource($thesisDefense);
    }

    public function destroy(Request $request, ThesisDefense $thesisDefense): Response
    {
        $thesisDefense->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PeriodStoreRequest;
use App\Http\Requests\PeriodUpdateRequest;
use App\Http\Resources\PeriodCollection;
use App\Http\Resources\PeriodResource;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PeriodController extends Controller
{
    public function index(Request $request): Response
    {
        $periods = Period::all();

        return new PeriodCollection($periods);
    }

    public function store(PeriodStoreRequest $request): Response
    {
        $period = Period::create($request->validated());

        return new PeriodResource($period);
    }

    public function show(Request $request, Period $period): Response
    {
        return new PeriodResource($period);
    }

    public function update(PeriodUpdateRequest $request, Period $period): Response
    {
        $period->update($request->validated());

        return new PeriodResource($period);
    }

    public function destroy(Request $request, Period $period): Response
    {
        $period->delete();

        return response()->noContent();
    }
}

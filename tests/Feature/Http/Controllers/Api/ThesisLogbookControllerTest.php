<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Supervisor;
use App\Models\Thesis;
use App\Models\ThesisLogbook;
use App\Models\ThesisSupervisor;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisLogbooks = ThesisLogbook::factory()->count(3)->create();

    $response = get(route('thesis-logbooks.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisLogbookController::class,
        'store',
        \App\Http\Requests\ThesisLogbookStoreRequest::class
    );

test('store saves', function (): void {
    $thesis = Thesis::factory()->create();
    $supervisor = Supervisor::factory()->create();
    $date = Carbon::parse(fake()->date());
    $progress = fake()->text();
    $status = fake()->numberBetween(-10000, 10000);
    $thesis_supervisor = ThesisSupervisor::factory()->create();

    $response = post(route('thesis-logbooks.store'), [
        'thesis_id' => $thesis->id,
        'supervisor_id' => $supervisor->id,
        'date' => $date->toDateString(),
        'progress' => $progress,
        'status' => $status,
        'thesis_supervisor_id' => $thesis_supervisor->id,
    ]);

    $thesisLogbooks = ThesisLogbook::query()
        ->where('thesis_id', $thesis->id)
        ->where('supervisor_id', $supervisor->id)
        ->where('date', $date)
        ->where('progress', $progress)
        ->where('status', $status)
        ->where('thesis_supervisor_id', $thesis_supervisor->id)
        ->get();
    expect($thesisLogbooks)->toHaveCount(1);
    $thesisLogbook = $thesisLogbooks->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisLogbook = ThesisLogbook::factory()->create();

    $response = get(route('thesis-logbooks.show', $thesisLogbook));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisLogbookController::class,
        'update',
        \App\Http\Requests\ThesisLogbookUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisLogbook = ThesisLogbook::factory()->create();
    $thesis = Thesis::factory()->create();
    $supervisor = Supervisor::factory()->create();
    $date = Carbon::parse(fake()->date());
    $progress = fake()->text();
    $status = fake()->numberBetween(-10000, 10000);
    $thesis_supervisor = ThesisSupervisor::factory()->create();

    $response = put(route('thesis-logbooks.update', $thesisLogbook), [
        'thesis_id' => $thesis->id,
        'supervisor_id' => $supervisor->id,
        'date' => $date->toDateString(),
        'progress' => $progress,
        'status' => $status,
        'thesis_supervisor_id' => $thesis_supervisor->id,
    ]);

    $thesisLogbook->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis->id)->toEqual($thesisLogbook->thesis_id);
    expect($supervisor->id)->toEqual($thesisLogbook->supervisor_id);
    expect($date)->toEqual($thesisLogbook->date);
    expect($progress)->toEqual($thesisLogbook->progress);
    expect($status)->toEqual($thesisLogbook->status);
    expect($thesis_supervisor->id)->toEqual($thesisLogbook->thesis_supervisor_id);
});


test('destroy deletes and responds with', function (): void {
    $thesisLogbook = ThesisLogbook::factory()->create();

    $response = delete(route('thesis-logbooks.destroy', $thesisLogbook));

    $response->assertNoContent();

    assertModelMissing($thesisLogbook);
});

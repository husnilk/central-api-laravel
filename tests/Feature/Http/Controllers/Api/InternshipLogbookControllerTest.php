<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Internship;
use App\Models\InternshipLogbook;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $internshipLogbooks = InternshipLogbook::factory()->count(3)->create();

    $response = get(route('internship-logbooks.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipLogbookController::class,
        'store',
        \App\Http\Requests\InternshipLogbookStoreRequest::class
    );

test('store saves', function (): void {
    $internship = Internship::factory()->create();
    $date = Carbon::parse(fake()->date());

    $response = post(route('internship-logbooks.store'), [
        'internship_id' => $internship->id,
        'date' => $date->toDateString(),
    ]);

    $internshipLogbooks = InternshipLogbook::query()
        ->where('internship_id', $internship->id)
        ->where('date', $date)
        ->get();
    expect($internshipLogbooks)->toHaveCount(1);
    $internshipLogbook = $internshipLogbooks->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $internshipLogbook = InternshipLogbook::factory()->create();

    $response = get(route('internship-logbooks.show', $internshipLogbook));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipLogbookController::class,
        'update',
        \App\Http\Requests\InternshipLogbookUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $internshipLogbook = InternshipLogbook::factory()->create();
    $internship = Internship::factory()->create();
    $date = Carbon::parse(fake()->date());

    $response = put(route('internship-logbooks.update', $internshipLogbook), [
        'internship_id' => $internship->id,
        'date' => $date->toDateString(),
    ]);

    $internshipLogbook->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($internship->id)->toEqual($internshipLogbook->internship_id);
    expect($date)->toEqual($internshipLogbook->date);
});


test('destroy deletes and responds with', function (): void {
    $internshipLogbook = InternshipLogbook::factory()->create();

    $response = delete(route('internship-logbooks.destroy', $internshipLogbook));

    $response->assertNoContent();

    assertModelMissing($internshipLogbook);
});

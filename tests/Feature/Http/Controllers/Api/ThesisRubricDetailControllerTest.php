<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ThesisRubric;
use App\Models\ThesisRubricDetail;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisRubricDetails = ThesisRubricDetail::factory()->count(3)->create();

    $response = get(route('thesis-rubric-details.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisRubricDetailController::class,
        'store',
        \App\Http\Requests\ThesisRubricDetailStoreRequest::class
    );

test('store saves', function (): void {
    $thesis_rubric = ThesisRubric::factory()->create();
    $description = fake()->text();
    $percentage = fake()->randomFloat(/** float_attributes **/);

    $response = post(route('thesis-rubric-details.store'), [
        'thesis_rubric_id' => $thesis_rubric->id,
        'description' => $description,
        'percentage' => $percentage,
    ]);

    $thesisRubricDetails = ThesisRubricDetail::query()
        ->where('thesis_rubric_id', $thesis_rubric->id)
        ->where('description', $description)
        ->where('percentage', $percentage)
        ->get();
    expect($thesisRubricDetails)->toHaveCount(1);
    $thesisRubricDetail = $thesisRubricDetails->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisRubricDetail = ThesisRubricDetail::factory()->create();

    $response = get(route('thesis-rubric-details.show', $thesisRubricDetail));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisRubricDetailController::class,
        'update',
        \App\Http\Requests\ThesisRubricDetailUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisRubricDetail = ThesisRubricDetail::factory()->create();
    $thesis_rubric = ThesisRubric::factory()->create();
    $description = fake()->text();
    $percentage = fake()->randomFloat(/** float_attributes **/);

    $response = put(route('thesis-rubric-details.update', $thesisRubricDetail), [
        'thesis_rubric_id' => $thesis_rubric->id,
        'description' => $description,
        'percentage' => $percentage,
    ]);

    $thesisRubricDetail->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis_rubric->id)->toEqual($thesisRubricDetail->thesis_rubric_id);
    expect($description)->toEqual($thesisRubricDetail->description);
    expect($percentage)->toEqual($thesisRubricDetail->percentage);
});


test('destroy deletes and responds with', function (): void {
    $thesisRubricDetail = ThesisRubricDetail::factory()->create();

    $response = delete(route('thesis-rubric-details.destroy', $thesisRubricDetail));

    $response->assertNoContent();

    assertModelMissing($thesisRubricDetail);
});

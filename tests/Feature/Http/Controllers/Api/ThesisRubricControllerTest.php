<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ThesisRubric;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisRubrics = ThesisRubric::factory()->count(3)->create();

    $response = get(route('thesis-rubrics.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisRubricController::class,
        'store',
        \App\Http\Requests\ThesisRubricStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();
    $active = fake()->numberBetween(-10000, 10000);

    $response = post(route('thesis-rubrics.store'), [
        'name' => $name,
        'active' => $active,
    ]);

    $thesisRubrics = ThesisRubric::query()
        ->where('name', $name)
        ->where('active', $active)
        ->get();
    expect($thesisRubrics)->toHaveCount(1);
    $thesisRubric = $thesisRubrics->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisRubric = ThesisRubric::factory()->create();

    $response = get(route('thesis-rubrics.show', $thesisRubric));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisRubricController::class,
        'update',
        \App\Http\Requests\ThesisRubricUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisRubric = ThesisRubric::factory()->create();
    $name = fake()->name();
    $active = fake()->numberBetween(-10000, 10000);

    $response = put(route('thesis-rubrics.update', $thesisRubric), [
        'name' => $name,
        'active' => $active,
    ]);

    $thesisRubric->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($thesisRubric->name);
    expect($active)->toEqual($thesisRubric->active);
});


test('destroy deletes and responds with', function (): void {
    $thesisRubric = ThesisRubric::factory()->create();

    $response = delete(route('thesis-rubrics.destroy', $thesisRubric));

    $response->assertNoContent();

    assertModelMissing($thesisRubric);
});

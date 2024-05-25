<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Curriculum;
use App\Models\CurriculumBok;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $curriculumBoks = CurriculumBok::factory()->count(3)->create();

    $response = get(route('curriculum-boks.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumBokController::class,
        'store',
        \App\Http\Requests\CurriculumBokStoreRequest::class
    );

test('store saves', function (): void {
    $curriculum = Curriculum::factory()->create();
    $code = fake()->word();
    $name = fake()->name();

    $response = post(route('curriculum-boks.store'), [
        'curriculum_id' => $curriculum->id,
        'code' => $code,
        'name' => $name,
    ]);

    $curriculumBoks = CurriculumBok::query()
        ->where('curriculum_id', $curriculum->id)
        ->where('code', $code)
        ->where('name', $name)
        ->get();
    expect($curriculumBoks)->toHaveCount(1);
    $curriculumBok = $curriculumBoks->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $curriculumBok = CurriculumBok::factory()->create();

    $response = get(route('curriculum-boks.show', $curriculumBok));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumBokController::class,
        'update',
        \App\Http\Requests\CurriculumBokUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $curriculumBok = CurriculumBok::factory()->create();
    $curriculum = Curriculum::factory()->create();
    $code = fake()->word();
    $name = fake()->name();

    $response = put(route('curriculum-boks.update', $curriculumBok), [
        'curriculum_id' => $curriculum->id,
        'code' => $code,
        'name' => $name,
    ]);

    $curriculumBok->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($curriculum->id)->toEqual($curriculumBok->curriculum_id);
    expect($code)->toEqual($curriculumBok->code);
    expect($name)->toEqual($curriculumBok->name);
});


test('destroy deletes and responds with', function (): void {
    $curriculumBok = CurriculumBok::factory()->create();

    $response = delete(route('curriculum-boks.destroy', $curriculumBok));

    $response->assertNoContent();

    assertModelMissing($curriculumBok);
});

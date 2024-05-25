<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Curriculum;
use App\Models\CurriculumPlo;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $curriculumPlos = CurriculumPlo::factory()->count(3)->create();

    $response = get(route('curriculum-plos.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumPloController::class,
        'store',
        \App\Http\Requests\CurriculumPloStoreRequest::class
    );

test('store saves', function (): void {
    $curriculum = Curriculum::factory()->create();
    $code = fake()->word();
    $outcome = fake()->text();
    $min_grade = fake()->numberBetween(-10000, 10000);

    $response = post(route('curriculum-plos.store'), [
        'curriculum_id' => $curriculum->id,
        'code' => $code,
        'outcome' => $outcome,
        'min_grade' => $min_grade,
    ]);

    $curriculumPlos = CurriculumPlo::query()
        ->where('curriculum_id', $curriculum->id)
        ->where('code', $code)
        ->where('outcome', $outcome)
        ->where('min_grade', $min_grade)
        ->get();
    expect($curriculumPlos)->toHaveCount(1);
    $curriculumPlo = $curriculumPlos->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $curriculumPlo = CurriculumPlo::factory()->create();

    $response = get(route('curriculum-plos.show', $curriculumPlo));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumPloController::class,
        'update',
        \App\Http\Requests\CurriculumPloUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $curriculumPlo = CurriculumPlo::factory()->create();
    $curriculum = Curriculum::factory()->create();
    $code = fake()->word();
    $outcome = fake()->text();
    $min_grade = fake()->numberBetween(-10000, 10000);

    $response = put(route('curriculum-plos.update', $curriculumPlo), [
        'curriculum_id' => $curriculum->id,
        'code' => $code,
        'outcome' => $outcome,
        'min_grade' => $min_grade,
    ]);

    $curriculumPlo->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($curriculum->id)->toEqual($curriculumPlo->curriculum_id);
    expect($code)->toEqual($curriculumPlo->code);
    expect($outcome)->toEqual($curriculumPlo->outcome);
    expect($min_grade)->toEqual($curriculumPlo->min_grade);
});


test('destroy deletes and responds with', function (): void {
    $curriculumPlo = CurriculumPlo::factory()->create();

    $response = delete(route('curriculum-plos.destroy', $curriculumPlo));

    $response->assertNoContent();

    assertModelMissing($curriculumPlo);
});

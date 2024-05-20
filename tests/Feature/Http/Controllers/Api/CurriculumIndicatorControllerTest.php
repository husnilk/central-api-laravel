<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CurriculumIndicator;
use App\Models\CurriculumPlo;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $curriculumIndicators = CurriculumIndicator::factory()->count(3)->create();

    $response = get(route('curriculum-indicators.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumIndicatorController::class,
        'store',
        \App\Http\Requests\CurriculumIndicatorStoreRequest::class
    );

test('store saves', function (): void {
    $curriculum_plo = CurriculumPlo::factory()->create();
    $code = fake()->word();
    $indicator = fake()->word();
    $min_grade = fake()->numberBetween(-10000, 10000);

    $response = post(route('curriculum-indicators.store'), [
        'curriculum_plo_id' => $curriculum_plo->id,
        'code' => $code,
        'indicator' => $indicator,
        'min_grade' => $min_grade,
    ]);

    $curriculumIndicators = CurriculumIndicator::query()
        ->where('curriculum_plo_id', $curriculum_plo->id)
        ->where('code', $code)
        ->where('indicator', $indicator)
        ->where('min_grade', $min_grade)
        ->get();
    expect($curriculumIndicators)->toHaveCount(1);
    $curriculumIndicator = $curriculumIndicators->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $curriculumIndicator = CurriculumIndicator::factory()->create();

    $response = get(route('curriculum-indicators.show', $curriculumIndicator));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumIndicatorController::class,
        'update',
        \App\Http\Requests\CurriculumIndicatorUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $curriculumIndicator = CurriculumIndicator::factory()->create();
    $curriculum_plo = CurriculumPlo::factory()->create();
    $code = fake()->word();
    $indicator = fake()->word();
    $min_grade = fake()->numberBetween(-10000, 10000);

    $response = put(route('curriculum-indicators.update', $curriculumIndicator), [
        'curriculum_plo_id' => $curriculum_plo->id,
        'code' => $code,
        'indicator' => $indicator,
        'min_grade' => $min_grade,
    ]);

    $curriculumIndicator->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($curriculum_plo->id)->toEqual($curriculumIndicator->curriculum_plo_id);
    expect($code)->toEqual($curriculumIndicator->code);
    expect($indicator)->toEqual($curriculumIndicator->indicator);
    expect($min_grade)->toEqual($curriculumIndicator->min_grade);
});


test('destroy deletes and responds with', function (): void {
    $curriculumIndicator = CurriculumIndicator::factory()->create();

    $response = delete(route('curriculum-indicators.destroy', $curriculumIndicator));

    $response->assertNoContent();

    assertModelMissing($curriculumIndicator);
});

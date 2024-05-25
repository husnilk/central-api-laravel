<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CurriculumPeo;
use App\Models\CurriculumPeoPlo;
use App\Models\CurriculumPlo;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $curriculumPeoPlos = CurriculumPeoPlo::factory()->count(3)->create();

    $response = get(route('curriculum-peo-plos.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumPeoPloController::class,
        'store',
        \App\Http\Requests\CurriculumPeoPloStoreRequest::class
    );

test('store saves', function (): void {
    $curriculum_peo = CurriculumPeo::factory()->create();
    $curriculum_plo = CurriculumPlo::factory()->create();

    $response = post(route('curriculum-peo-plos.store'), [
        'curriculum_peo_id' => $curriculum_peo->id,
        'curriculum_plo_id' => $curriculum_plo->id,
    ]);

    $curriculumPeoPlos = CurriculumPeoPlo::query()
        ->where('curriculum_peo_id', $curriculum_peo->id)
        ->where('curriculum_plo_id', $curriculum_plo->id)
        ->get();
    expect($curriculumPeoPlos)->toHaveCount(1);
    $curriculumPeoPlo = $curriculumPeoPlos->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $curriculumPeoPlo = CurriculumPeoPlo::factory()->create();

    $response = get(route('curriculum-peo-plos.show', $curriculumPeoPlo));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumPeoPloController::class,
        'update',
        \App\Http\Requests\CurriculumPeoPloUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $curriculumPeoPlo = CurriculumPeoPlo::factory()->create();
    $curriculum_peo = CurriculumPeo::factory()->create();
    $curriculum_plo = CurriculumPlo::factory()->create();

    $response = put(route('curriculum-peo-plos.update', $curriculumPeoPlo), [
        'curriculum_peo_id' => $curriculum_peo->id,
        'curriculum_plo_id' => $curriculum_plo->id,
    ]);

    $curriculumPeoPlo->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($curriculum_peo->id)->toEqual($curriculumPeoPlo->curriculum_peo_id);
    expect($curriculum_plo->id)->toEqual($curriculumPeoPlo->curriculum_plo_id);
});


test('destroy deletes and responds with', function (): void {
    $curriculumPeoPlo = CurriculumPeoPlo::factory()->create();

    $response = delete(route('curriculum-peo-plos.destroy', $curriculumPeoPlo));

    $response->assertNoContent();

    assertModelMissing($curriculumPeoPlo);
});

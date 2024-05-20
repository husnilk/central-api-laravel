<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Curriculum;
use App\Models\CurriculumPeo;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $curriculumPeos = CurriculumPeo::factory()->count(3)->create();

    $response = get(route('curriculum-peos.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumPeoController::class,
        'store',
        \App\Http\Requests\CurriculumPeoStoreRequest::class
    );

test('store saves', function (): void {
    $curriculum = Curriculum::factory()->create();
    $code = fake()->word();
    $profile = fake()->text();

    $response = post(route('curriculum-peos.store'), [
        'curriculum_id' => $curriculum->id,
        'code' => $code,
        'profile' => $profile,
    ]);

    $curriculumPeos = CurriculumPeo::query()
        ->where('curriculum_id', $curriculum->id)
        ->where('code', $code)
        ->where('profile', $profile)
        ->get();
    expect($curriculumPeos)->toHaveCount(1);
    $curriculumPeo = $curriculumPeos->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $curriculumPeo = CurriculumPeo::factory()->create();

    $response = get(route('curriculum-peos.show', $curriculumPeo));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumPeoController::class,
        'update',
        \App\Http\Requests\CurriculumPeoUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $curriculumPeo = CurriculumPeo::factory()->create();
    $curriculum = Curriculum::factory()->create();
    $code = fake()->word();
    $profile = fake()->text();

    $response = put(route('curriculum-peos.update', $curriculumPeo), [
        'curriculum_id' => $curriculum->id,
        'code' => $code,
        'profile' => $profile,
    ]);

    $curriculumPeo->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($curriculum->id)->toEqual($curriculumPeo->curriculum_id);
    expect($code)->toEqual($curriculumPeo->code);
    expect($profile)->toEqual($curriculumPeo->profile);
});


test('destroy deletes and responds with', function (): void {
    $curriculumPeo = CurriculumPeo::factory()->create();

    $response = delete(route('curriculum-peos.destroy', $curriculumPeo));

    $response->assertNoContent();

    assertModelMissing($curriculumPeo);
});

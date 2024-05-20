<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CurriculumBok;
use App\Models\CurriculumBokDetail;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $curriculumBokDetails = CurriculumBokDetail::factory()->count(3)->create();

    $response = get(route('curriculum-bok-details.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumBokDetailController::class,
        'store',
        \App\Http\Requests\CurriculumBokDetailStoreRequest::class
    );

test('store saves', function (): void {
    $curriculum_bok = CurriculumBok::factory()->create();
    $lo = fake()->text();

    $response = post(route('curriculum-bok-details.store'), [
        'curriculum_bok_id' => $curriculum_bok->id,
        'lo' => $lo,
    ]);

    $curriculumBokDetails = CurriculumBokDetail::query()
        ->where('curriculum_bok_id', $curriculum_bok->id)
        ->where('lo', $lo)
        ->get();
    expect($curriculumBokDetails)->toHaveCount(1);
    $curriculumBokDetail = $curriculumBokDetails->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $curriculumBokDetail = CurriculumBokDetail::factory()->create();

    $response = get(route('curriculum-bok-details.show', $curriculumBokDetail));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumBokDetailController::class,
        'update',
        \App\Http\Requests\CurriculumBokDetailUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $curriculumBokDetail = CurriculumBokDetail::factory()->create();
    $curriculum_bok = CurriculumBok::factory()->create();
    $lo = fake()->text();

    $response = put(route('curriculum-bok-details.update', $curriculumBokDetail), [
        'curriculum_bok_id' => $curriculum_bok->id,
        'lo' => $lo,
    ]);

    $curriculumBokDetail->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($curriculum_bok->id)->toEqual($curriculumBokDetail->curriculum_bok_id);
    expect($lo)->toEqual($curriculumBokDetail->lo);
});


test('destroy deletes and responds with', function (): void {
    $curriculumBokDetail = CurriculumBokDetail::factory()->create();

    $response = delete(route('curriculum-bok-details.destroy', $curriculumBokDetail));

    $response->assertNoContent();

    assertModelMissing($curriculumBokDetail);
});

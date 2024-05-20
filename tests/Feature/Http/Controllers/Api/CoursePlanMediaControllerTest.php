<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlan;
use App\Models\CoursePlanMedia;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanMedia = CoursePlanMedia::factory()->count(3)->create();

    $response = get(route('course-plan-medias.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanMediaController::class,
        'store',
        \App\Http\Requests\CoursePlanMediaStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan = CoursePlan::factory()->create();
    $type = fake()->numberBetween(-10000, 10000);
    $media = fake()->word();

    $response = post(route('course-plan-medias.store'), [
        'course_plan_id' => $course_plan->id,
        'type' => $type,
        'media' => $media,
    ]);

    $coursePlanMedia = CoursePlanMedia::query()
        ->where('course_plan_id', $course_plan->id)
        ->where('type', $type)
        ->where('media', $media)
        ->get();
    expect($coursePlanMedia)->toHaveCount(1);
    $coursePlanMedia = $coursePlanMedia->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanMedia = CoursePlanMedia::factory()->create();

    $response = get(route('course-plan-medias.show', $coursePlanMedia));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanMediaController::class,
        'update',
        \App\Http\Requests\CoursePlanMediaUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanMedia = CoursePlanMedia::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $type = fake()->numberBetween(-10000, 10000);
    $media = fake()->word();

    $response = put(route('course-plan-medias.update', $coursePlanMedia), [
        'course_plan_id' => $course_plan->id,
        'type' => $type,
        'media' => $media,
    ]);

    $coursePlanMedia->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan->id)->toEqual($coursePlanMedia->course_plan_id);
    expect($type)->toEqual($coursePlanMedia->type);
    expect($media)->toEqual($coursePlanMedia->media);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanMedia = CoursePlanMedia::factory()->create();

    $response = delete(route('course-plan-medias.destroy', $coursePlanMedia));

    $response->assertNoContent();

    assertModelMissing($coursePlanMedia);
});

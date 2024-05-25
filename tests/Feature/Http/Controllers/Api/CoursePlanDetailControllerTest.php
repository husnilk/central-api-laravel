<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlan;
use App\Models\CoursePlanDetail;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanDetails = CoursePlanDetail::factory()->count(3)->create();

    $response = get(route('course-plan-details.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanDetailController::class,
        'store',
        \App\Http\Requests\CoursePlanDetailStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan = CoursePlan::factory()->create();
    $week = fake()->numberBetween(-10000, 10000);

    $response = post(route('course-plan-details.store'), [
        'course_plan_id' => $course_plan->id,
        'week' => $week,
    ]);

    $coursePlanDetails = CoursePlanDetail::query()
        ->where('course_plan_id', $course_plan->id)
        ->where('week', $week)
        ->get();
    expect($coursePlanDetails)->toHaveCount(1);
    $coursePlanDetail = $coursePlanDetails->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanDetail = CoursePlanDetail::factory()->create();

    $response = get(route('course-plan-details.show', $coursePlanDetail));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanDetailController::class,
        'update',
        \App\Http\Requests\CoursePlanDetailUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanDetail = CoursePlanDetail::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $week = fake()->numberBetween(-10000, 10000);

    $response = put(route('course-plan-details.update', $coursePlanDetail), [
        'course_plan_id' => $course_plan->id,
        'week' => $week,
    ]);

    $coursePlanDetail->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan->id)->toEqual($coursePlanDetail->course_plan_id);
    expect($week)->toEqual($coursePlanDetail->week);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanDetail = CoursePlanDetail::factory()->create();

    $response = delete(route('course-plan-details.destroy', $coursePlanDetail));

    $response->assertNoContent();

    assertModelMissing($coursePlanDetail);
});

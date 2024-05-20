<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlanDetail;
use App\Models\CoursePlanDetailActivity;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanDetailActivities = CoursePlanDetailActivity::factory()->count(3)->create();

    $response = get(route('course-plan-detail-activities.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanDetailActivityController::class,
        'store',
        \App\Http\Requests\CoursePlanDetailActivityStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan_detail = CoursePlanDetail::factory()->create();
    $activity = fake()->numberBetween(-10000, 10000);
    $student_activity = fake()->text();

    $response = post(route('course-plan-detail-activities.store'), [
        'course_plan_detail_id' => $course_plan_detail->id,
        'activity' => $activity,
        'student_activity' => $student_activity,
    ]);

    $coursePlanDetailActivities = CoursePlanDetailActivity::query()
        ->where('course_plan_detail_id', $course_plan_detail->id)
        ->where('activity', $activity)
        ->where('student_activity', $student_activity)
        ->get();
    expect($coursePlanDetailActivities)->toHaveCount(1);
    $coursePlanDetailActivity = $coursePlanDetailActivities->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanDetailActivity = CoursePlanDetailActivity::factory()->create();

    $response = get(route('course-plan-detail-activities.show', $coursePlanDetailActivity));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanDetailActivityController::class,
        'update',
        \App\Http\Requests\CoursePlanDetailActivityUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanDetailActivity = CoursePlanDetailActivity::factory()->create();
    $course_plan_detail = CoursePlanDetail::factory()->create();
    $activity = fake()->numberBetween(-10000, 10000);
    $student_activity = fake()->text();

    $response = put(route('course-plan-detail-activities.update', $coursePlanDetailActivity), [
        'course_plan_detail_id' => $course_plan_detail->id,
        'activity' => $activity,
        'student_activity' => $student_activity,
    ]);

    $coursePlanDetailActivity->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan_detail->id)->toEqual($coursePlanDetailActivity->course_plan_detail_id);
    expect($activity)->toEqual($coursePlanDetailActivity->activity);
    expect($student_activity)->toEqual($coursePlanDetailActivity->student_activity);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanDetailActivity = CoursePlanDetailActivity::factory()->create();

    $response = delete(route('course-plan-detail-activities.destroy', $coursePlanDetailActivity));

    $response->assertNoContent();

    assertModelMissing($coursePlanDetailActivity);
});

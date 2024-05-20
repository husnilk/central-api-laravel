<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlan;
use App\Models\CoursePlanAssessment;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanAssessments = CoursePlanAssessment::factory()->count(3)->create();

    $response = get(route('course-plan-assessments.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanAssessmentController::class,
        'store',
        \App\Http\Requests\CoursePlanAssessmentStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan = CoursePlan::factory()->create();
    $name = fake()->name();
    $percentage = fake()->randomFloat(/** double_attributes **/);

    $response = post(route('course-plan-assessments.store'), [
        'course_plan_id' => $course_plan->id,
        'name' => $name,
        'percentage' => $percentage,
    ]);

    $coursePlanAssessments = CoursePlanAssessment::query()
        ->where('course_plan_id', $course_plan->id)
        ->where('name', $name)
        ->where('percentage', $percentage)
        ->get();
    expect($coursePlanAssessments)->toHaveCount(1);
    $coursePlanAssessment = $coursePlanAssessments->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanAssessment = CoursePlanAssessment::factory()->create();

    $response = get(route('course-plan-assessments.show', $coursePlanAssessment));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanAssessmentController::class,
        'update',
        \App\Http\Requests\CoursePlanAssessmentUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanAssessment = CoursePlanAssessment::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $name = fake()->name();
    $percentage = fake()->randomFloat(/** double_attributes **/);

    $response = put(route('course-plan-assessments.update', $coursePlanAssessment), [
        'course_plan_id' => $course_plan->id,
        'name' => $name,
        'percentage' => $percentage,
    ]);

    $coursePlanAssessment->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan->id)->toEqual($coursePlanAssessment->course_plan_id);
    expect($name)->toEqual($coursePlanAssessment->name);
    expect($percentage)->toEqual($coursePlanAssessment->percentage);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanAssessment = CoursePlanAssessment::factory()->create();

    $response = delete(route('course-plan-assessments.destroy', $coursePlanAssessment));

    $response->assertNoContent();

    assertModelMissing($coursePlanAssessment);
});

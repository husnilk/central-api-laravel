<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlan;
use App\Models\CoursePlanRequirement;
use App\Models\ReqCourse;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanRequirements = CoursePlanRequirement::factory()->count(3)->create();

    $response = get(route('course-plan-requirements.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanRequirementController::class,
        'store',
        \App\Http\Requests\CoursePlanRequirementStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan = CoursePlan::factory()->create();
    $req_course = ReqCourse::factory()->create();
    $req_level = fake()->numberBetween(-10000, 10000);

    $response = post(route('course-plan-requirements.store'), [
        'course_plan_id' => $course_plan->id,
        'req_course_id' => $req_course->id,
        'req_level' => $req_level,
    ]);

    $coursePlanRequirements = CoursePlanRequirement::query()
        ->where('course_plan_id', $course_plan->id)
        ->where('req_course_id', $req_course->id)
        ->where('req_level', $req_level)
        ->get();
    expect($coursePlanRequirements)->toHaveCount(1);
    $coursePlanRequirement = $coursePlanRequirements->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanRequirement = CoursePlanRequirement::factory()->create();

    $response = get(route('course-plan-requirements.show', $coursePlanRequirement));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanRequirementController::class,
        'update',
        \App\Http\Requests\CoursePlanRequirementUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanRequirement = CoursePlanRequirement::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $req_course = ReqCourse::factory()->create();
    $req_level = fake()->numberBetween(-10000, 10000);

    $response = put(route('course-plan-requirements.update', $coursePlanRequirement), [
        'course_plan_id' => $course_plan->id,
        'req_course_id' => $req_course->id,
        'req_level' => $req_level,
    ]);

    $coursePlanRequirement->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan->id)->toEqual($coursePlanRequirement->course_plan_id);
    expect($req_course->id)->toEqual($coursePlanRequirement->req_course_id);
    expect($req_level)->toEqual($coursePlanRequirement->req_level);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanRequirement = CoursePlanRequirement::factory()->create();

    $response = delete(route('course-plan-requirements.destroy', $coursePlanRequirement));

    $response->assertNoContent();

    assertModelMissing($coursePlanRequirement);
});

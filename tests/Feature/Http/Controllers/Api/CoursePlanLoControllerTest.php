<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlan;
use App\Models\CoursePlanLo;
use App\Models\CurriculumIndicator;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanLos = CoursePlanLo::factory()->count(3)->create();

    $response = get(route('course-plan-los.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanLoController::class,
        'store',
        \App\Http\Requests\CoursePlanLoStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan = CoursePlan::factory()->create();
    $curriculum_indicator = CurriculumIndicator::factory()->create();
    $code = fake()->word();
    $name = fake()->name();

    $response = post(route('course-plan-los.store'), [
        'course_plan_id' => $course_plan->id,
        'curriculum_indicator_id' => $curriculum_indicator->id,
        'code' => $code,
        'name' => $name,
    ]);

    $coursePlanLos = CoursePlanLo::query()
        ->where('course_plan_id', $course_plan->id)
        ->where('curriculum_indicator_id', $curriculum_indicator->id)
        ->where('code', $code)
        ->where('name', $name)
        ->get();
    expect($coursePlanLos)->toHaveCount(1);
    $coursePlanLo = $coursePlanLos->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanLo = CoursePlanLo::factory()->create();

    $response = get(route('course-plan-los.show', $coursePlanLo));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanLoController::class,
        'update',
        \App\Http\Requests\CoursePlanLoUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanLo = CoursePlanLo::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $curriculum_indicator = CurriculumIndicator::factory()->create();
    $code = fake()->word();
    $name = fake()->name();

    $response = put(route('course-plan-los.update', $coursePlanLo), [
        'course_plan_id' => $course_plan->id,
        'curriculum_indicator_id' => $curriculum_indicator->id,
        'code' => $code,
        'name' => $name,
    ]);

    $coursePlanLo->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan->id)->toEqual($coursePlanLo->course_plan_id);
    expect($curriculum_indicator->id)->toEqual($coursePlanLo->curriculum_indicator_id);
    expect($code)->toEqual($coursePlanLo->code);
    expect($name)->toEqual($coursePlanLo->name);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanLo = CoursePlanLo::factory()->create();

    $response = delete(route('course-plan-los.destroy', $coursePlanLo));

    $response->assertNoContent();

    assertModelMissing($coursePlanLo);
});

<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Class;
use App\Models\ClassCourse;
use App\Models\StudyPlan;
use App\Models\StudyPlanDetail;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $studyPlanDetails = StudyPlanDetail::factory()->count(3)->create();

    $response = get(route('study-plan-details.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\StudyPlanDetailController::class,
        'store',
        \App\Http\Requests\StudyPlanDetailStoreRequest::class
    );

test('store saves', function (): void {
    $study_plan = StudyPlan::factory()->create();
    $class = Class::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $in_transcript = fake()->numberBetween(-10000, 10000);
    $class_course = ClassCourse::factory()->create();

    $response = post(route('study-plan-details.store'), [
        'study_plan_id' => $study_plan->id,
        'class_id' => $class->id,
        'status' => $status,
        'in_transcript' => $in_transcript,
        'class_course_id' => $class_course->id,
    ]);

    $studyPlanDetails = StudyPlanDetail::query()
        ->where('study_plan_id', $study_plan->id)
        ->where('class_id', $class->id)
        ->where('status', $status)
        ->where('in_transcript', $in_transcript)
        ->where('class_course_id', $class_course->id)
        ->get();
    expect($studyPlanDetails)->toHaveCount(1);
    $studyPlanDetail = $studyPlanDetails->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $studyPlanDetail = StudyPlanDetail::factory()->create();

    $response = get(route('study-plan-details.show', $studyPlanDetail));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\StudyPlanDetailController::class,
        'update',
        \App\Http\Requests\StudyPlanDetailUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $studyPlanDetail = StudyPlanDetail::factory()->create();
    $study_plan = StudyPlan::factory()->create();
    $class = Class::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $in_transcript = fake()->numberBetween(-10000, 10000);
    $class_course = ClassCourse::factory()->create();

    $response = put(route('study-plan-details.update', $studyPlanDetail), [
        'study_plan_id' => $study_plan->id,
        'class_id' => $class->id,
        'status' => $status,
        'in_transcript' => $in_transcript,
        'class_course_id' => $class_course->id,
    ]);

    $studyPlanDetail->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($study_plan->id)->toEqual($studyPlanDetail->study_plan_id);
    expect($class->id)->toEqual($studyPlanDetail->class_id);
    expect($status)->toEqual($studyPlanDetail->status);
    expect($in_transcript)->toEqual($studyPlanDetail->in_transcript);
    expect($class_course->id)->toEqual($studyPlanDetail->class_course_id);
});


test('destroy deletes and responds with', function (): void {
    $studyPlanDetail = StudyPlanDetail::factory()->create();

    $response = delete(route('study-plan-details.destroy', $studyPlanDetail));

    $response->assertNoContent();

    assertModelMissing($studyPlanDetail);
});

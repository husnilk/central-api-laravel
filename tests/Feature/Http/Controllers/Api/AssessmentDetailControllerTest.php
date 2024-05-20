<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\AssessmentDetail;
use App\Models\CoursePlanAssessment;
use App\Models\CoursePlanLo;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $assessmentDetails = AssessmentDetail::factory()->count(3)->create();

    $response = get(route('assessment-details.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\AssessmentDetailController::class,
        'store',
        \App\Http\Requests\AssessmentDetailStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan_assessment = CoursePlanAssessment::factory()->create();
    $course_plan_lo = CoursePlanLo::factory()->create();
    $percentage = fake()->randomFloat(/** double_attributes **/);

    $response = post(route('assessment-details.store'), [
        'course_plan_assessment_id' => $course_plan_assessment->id,
        'course_plan_lo_id' => $course_plan_lo->id,
        'percentage' => $percentage,
    ]);

    $assessmentDetails = AssessmentDetail::query()
        ->where('course_plan_assessment_id', $course_plan_assessment->id)
        ->where('course_plan_lo_id', $course_plan_lo->id)
        ->where('percentage', $percentage)
        ->get();
    expect($assessmentDetails)->toHaveCount(1);
    $assessmentDetail = $assessmentDetails->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $assessmentDetail = AssessmentDetail::factory()->create();

    $response = get(route('assessment-details.show', $assessmentDetail));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\AssessmentDetailController::class,
        'update',
        \App\Http\Requests\AssessmentDetailUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $assessmentDetail = AssessmentDetail::factory()->create();
    $course_plan_assessment = CoursePlanAssessment::factory()->create();
    $course_plan_lo = CoursePlanLo::factory()->create();
    $percentage = fake()->randomFloat(/** double_attributes **/);

    $response = put(route('assessment-details.update', $assessmentDetail), [
        'course_plan_assessment_id' => $course_plan_assessment->id,
        'course_plan_lo_id' => $course_plan_lo->id,
        'percentage' => $percentage,
    ]);

    $assessmentDetail->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan_assessment->id)->toEqual($assessmentDetail->course_plan_assessment_id);
    expect($course_plan_lo->id)->toEqual($assessmentDetail->course_plan_lo_id);
    expect($percentage)->toEqual($assessmentDetail->percentage);
});


test('destroy deletes and responds with', function (): void {
    $assessmentDetail = AssessmentDetail::factory()->create();

    $response = delete(route('assessment-details.destroy', $assessmentDetail));

    $response->assertNoContent();

    assertModelMissing($assessmentDetail);
});

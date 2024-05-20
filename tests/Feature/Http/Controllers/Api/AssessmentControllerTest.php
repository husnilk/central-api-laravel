<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Assessment;
use App\Models\AssessmentDetail;
use App\Models\StudyPlanDetail;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $assessments = Assessment::factory()->count(3)->create();

    $response = get(route('assessments.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\AssessmentController::class,
        'store',
        \App\Http\Requests\AssessmentStoreRequest::class
    );

test('store saves', function (): void {
    $assessment_detail = AssessmentDetail::factory()->create();
    $study_plan_detail = StudyPlanDetail::factory()->create();
    $grade = fake()->randomFloat(/** double_attributes **/);

    $response = post(route('assessments.store'), [
        'assessment_detail_id' => $assessment_detail->id,
        'study_plan_detail_id' => $study_plan_detail->id,
        'grade' => $grade,
    ]);

    $assessments = Assessment::query()
        ->where('assessment_detail_id', $assessment_detail->id)
        ->where('study_plan_detail_id', $study_plan_detail->id)
        ->where('grade', $grade)
        ->get();
    expect($assessments)->toHaveCount(1);
    $assessment = $assessments->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $assessment = Assessment::factory()->create();

    $response = get(route('assessments.show', $assessment));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\AssessmentController::class,
        'update',
        \App\Http\Requests\AssessmentUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $assessment = Assessment::factory()->create();
    $assessment_detail = AssessmentDetail::factory()->create();
    $study_plan_detail = StudyPlanDetail::factory()->create();
    $grade = fake()->randomFloat(/** double_attributes **/);

    $response = put(route('assessments.update', $assessment), [
        'assessment_detail_id' => $assessment_detail->id,
        'study_plan_detail_id' => $study_plan_detail->id,
        'grade' => $grade,
    ]);

    $assessment->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($assessment_detail->id)->toEqual($assessment->assessment_detail_id);
    expect($study_plan_detail->id)->toEqual($assessment->study_plan_detail_id);
    expect($grade)->toEqual($assessment->grade);
});


test('destroy deletes and responds with', function (): void {
    $assessment = Assessment::factory()->create();

    $response = delete(route('assessments.destroy', $assessment));

    $response->assertNoContent();

    assertModelMissing($assessment);
});

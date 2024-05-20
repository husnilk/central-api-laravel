<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\AssessmentCriteria;
use App\Models\AssessmentRubric;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $assessmentRubrics = AssessmentRubric::factory()->count(3)->create();

    $response = get(route('assessment-rubrics.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\AssessmentRubricController::class,
        'store',
        \App\Http\Requests\AssessmentRubricStoreRequest::class
    );

test('store saves', function (): void {
    $assessment_criteria = AssessmentCriteria::factory()->create();
    $grade = fake()->randomFloat(/** double_attributes **/);

    $response = post(route('assessment-rubrics.store'), [
        'assessment_criteria_id' => $assessment_criteria->id,
        'grade' => $grade,
    ]);

    $assessmentRubrics = AssessmentRubric::query()
        ->where('assessment_criteria_id', $assessment_criteria->id)
        ->where('grade', $grade)
        ->get();
    expect($assessmentRubrics)->toHaveCount(1);
    $assessmentRubric = $assessmentRubrics->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $assessmentRubric = AssessmentRubric::factory()->create();

    $response = get(route('assessment-rubrics.show', $assessmentRubric));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\AssessmentRubricController::class,
        'update',
        \App\Http\Requests\AssessmentRubricUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $assessmentRubric = AssessmentRubric::factory()->create();
    $assessment_criteria = AssessmentCriteria::factory()->create();
    $grade = fake()->randomFloat(/** double_attributes **/);

    $response = put(route('assessment-rubrics.update', $assessmentRubric), [
        'assessment_criteria_id' => $assessment_criteria->id,
        'grade' => $grade,
    ]);

    $assessmentRubric->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($assessment_criteria->id)->toEqual($assessmentRubric->assessment_criteria_id);
    expect($grade)->toEqual($assessmentRubric->grade);
});


test('destroy deletes and responds with', function (): void {
    $assessmentRubric = AssessmentRubric::factory()->create();

    $response = delete(route('assessment-rubrics.destroy', $assessmentRubric));

    $response->assertNoContent();

    assertModelMissing($assessmentRubric);
});

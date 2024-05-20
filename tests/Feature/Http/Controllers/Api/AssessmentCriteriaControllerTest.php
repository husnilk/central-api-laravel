<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\AssessmentCriteria;
use App\Models\AssessmentCriterion;
use App\Models\AssessmentDetail;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $assessmentCriteria = AssessmentCriteria::factory()->count(3)->create();

    $response = get(route('assessment-criteria.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\AssessmentCriteriaController::class,
        'store',
        \App\Http\Requests\AssessmentCriteriaStoreRequest::class
    );

test('store saves', function (): void {
    $assessment_detail = AssessmentDetail::factory()->create();
    $criteria = fake()->word();

    $response = post(route('assessment-criteria.store'), [
        'assessment_detail_id' => $assessment_detail->id,
        'criteria' => $criteria,
    ]);

    $assessmentCriteria = AssessmentCriterion::query()
        ->where('assessment_detail_id', $assessment_detail->id)
        ->where('criteria', $criteria)
        ->get();
    expect($assessmentCriteria)->toHaveCount(1);
    $assessmentCriterion = $assessmentCriteria->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $assessmentCriterion = AssessmentCriteria::factory()->create();

    $response = get(route('assessment-criteria.show', $assessmentCriterion));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\AssessmentCriteriaController::class,
        'update',
        \App\Http\Requests\AssessmentCriteriaUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $assessmentCriterion = AssessmentCriteria::factory()->create();
    $assessment_detail = AssessmentDetail::factory()->create();
    $criteria = fake()->word();

    $response = put(route('assessment-criteria.update', $assessmentCriterion), [
        'assessment_detail_id' => $assessment_detail->id,
        'criteria' => $criteria,
    ]);

    $assessmentCriterion->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($assessment_detail->id)->toEqual($assessmentCriterion->assessment_detail_id);
    expect($criteria)->toEqual($assessmentCriterion->criteria);
});


test('destroy deletes and responds with', function (): void {
    $assessmentCriterion = AssessmentCriteria::factory()->create();
    $assessmentCriterion = AssessmentCriterion::factory()->create();

    $response = delete(route('assessment-criteria.destroy', $assessmentCriterion));

    $response->assertNoContent();

    assertModelMissing($assessmentCriterion);
});

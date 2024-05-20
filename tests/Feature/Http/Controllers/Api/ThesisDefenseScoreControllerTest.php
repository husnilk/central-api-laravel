<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ThesisDefenseExaminer;
use App\Models\ThesisDefenseScore;
use App\Models\ThesisRubricDetail;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisDefenseScores = ThesisDefenseScore::factory()->count(3)->create();

    $response = get(route('thesis-defense-scores.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisDefenseScoreController::class,
        'store',
        \App\Http\Requests\ThesisDefenseScoreStoreRequest::class
    );

test('store saves', function (): void {
    $thesis_defense_examiner = ThesisDefenseExaminer::factory()->create();
    $thesis_rubric_detail = ThesisRubricDetail::factory()->create();

    $response = post(route('thesis-defense-scores.store'), [
        'thesis_defense_examiner_id' => $thesis_defense_examiner->id,
        'thesis_rubric_detail_id' => $thesis_rubric_detail->id,
    ]);

    $thesisDefenseScores = ThesisDefenseScore::query()
        ->where('thesis_defense_examiner_id', $thesis_defense_examiner->id)
        ->where('thesis_rubric_detail_id', $thesis_rubric_detail->id)
        ->get();
    expect($thesisDefenseScores)->toHaveCount(1);
    $thesisDefenseScore = $thesisDefenseScores->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisDefenseScore = ThesisDefenseScore::factory()->create();

    $response = get(route('thesis-defense-scores.show', $thesisDefenseScore));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisDefenseScoreController::class,
        'update',
        \App\Http\Requests\ThesisDefenseScoreUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisDefenseScore = ThesisDefenseScore::factory()->create();
    $thesis_defense_examiner = ThesisDefenseExaminer::factory()->create();
    $thesis_rubric_detail = ThesisRubricDetail::factory()->create();

    $response = put(route('thesis-defense-scores.update', $thesisDefenseScore), [
        'thesis_defense_examiner_id' => $thesis_defense_examiner->id,
        'thesis_rubric_detail_id' => $thesis_rubric_detail->id,
    ]);

    $thesisDefenseScore->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis_defense_examiner->id)->toEqual($thesisDefenseScore->thesis_defense_examiner_id);
    expect($thesis_rubric_detail->id)->toEqual($thesisDefenseScore->thesis_rubric_detail_id);
});


test('destroy deletes and responds with', function (): void {
    $thesisDefenseScore = ThesisDefenseScore::factory()->create();

    $response = delete(route('thesis-defense-scores.destroy', $thesisDefenseScore));

    $response->assertNoContent();

    assertModelMissing($thesisDefenseScore);
});

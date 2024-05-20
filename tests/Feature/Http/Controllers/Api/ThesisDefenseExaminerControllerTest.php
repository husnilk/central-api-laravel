<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Examiner;
use App\Models\Lecturer;
use App\Models\ThesisDefense;
use App\Models\ThesisDefenseExaminer;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisDefenseExaminers = ThesisDefenseExaminer::factory()->count(3)->create();

    $response = get(route('thesis-defense-examiners.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisDefenseExaminerController::class,
        'store',
        \App\Http\Requests\ThesisDefenseExaminerStoreRequest::class
    );

test('store saves', function (): void {
    $thesis_defense = ThesisDefense::factory()->create();
    $examiner = Examiner::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $position = fake()->numberBetween(-10000, 10000);
    $lecturer = Lecturer::factory()->create();

    $response = post(route('thesis-defense-examiners.store'), [
        'thesis_defense_id' => $thesis_defense->id,
        'examiner_id' => $examiner->id,
        'status' => $status,
        'position' => $position,
        'lecturer_id' => $lecturer->id,
    ]);

    $thesisDefenseExaminers = ThesisDefenseExaminer::query()
        ->where('thesis_defense_id', $thesis_defense->id)
        ->where('examiner_id', $examiner->id)
        ->where('status', $status)
        ->where('position', $position)
        ->where('lecturer_id', $lecturer->id)
        ->get();
    expect($thesisDefenseExaminers)->toHaveCount(1);
    $thesisDefenseExaminer = $thesisDefenseExaminers->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisDefenseExaminer = ThesisDefenseExaminer::factory()->create();

    $response = get(route('thesis-defense-examiners.show', $thesisDefenseExaminer));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisDefenseExaminerController::class,
        'update',
        \App\Http\Requests\ThesisDefenseExaminerUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisDefenseExaminer = ThesisDefenseExaminer::factory()->create();
    $thesis_defense = ThesisDefense::factory()->create();
    $examiner = Examiner::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $position = fake()->numberBetween(-10000, 10000);
    $lecturer = Lecturer::factory()->create();

    $response = put(route('thesis-defense-examiners.update', $thesisDefenseExaminer), [
        'thesis_defense_id' => $thesis_defense->id,
        'examiner_id' => $examiner->id,
        'status' => $status,
        'position' => $position,
        'lecturer_id' => $lecturer->id,
    ]);

    $thesisDefenseExaminer->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis_defense->id)->toEqual($thesisDefenseExaminer->thesis_defense_id);
    expect($examiner->id)->toEqual($thesisDefenseExaminer->examiner_id);
    expect($status)->toEqual($thesisDefenseExaminer->status);
    expect($position)->toEqual($thesisDefenseExaminer->position);
    expect($lecturer->id)->toEqual($thesisDefenseExaminer->lecturer_id);
});


test('destroy deletes and responds with', function (): void {
    $thesisDefenseExaminer = ThesisDefenseExaminer::factory()->create();

    $response = delete(route('thesis-defense-examiners.destroy', $thesisDefenseExaminer));

    $response->assertNoContent();

    assertModelMissing($thesisDefenseExaminer);
});

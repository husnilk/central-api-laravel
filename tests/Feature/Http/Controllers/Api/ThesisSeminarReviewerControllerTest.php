<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Lecturer;
use App\Models\Reviewer;
use App\Models\ThesisSeminar;
use App\Models\ThesisSeminarReviewer;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisSeminarReviewers = ThesisSeminarReviewer::factory()->count(3)->create();

    $response = get(route('thesis-seminar-reviewers.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisSeminarReviewerController::class,
        'store',
        \App\Http\Requests\ThesisSeminarReviewerStoreRequest::class
    );

test('store saves', function (): void {
    $thesis_seminar = ThesisSeminar::factory()->create();
    $reviewer = Reviewer::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $lecturer = Lecturer::factory()->create();

    $response = post(route('thesis-seminar-reviewers.store'), [
        'thesis_seminar_id' => $thesis_seminar->id,
        'reviewer_id' => $reviewer->id,
        'status' => $status,
        'lecturer_id' => $lecturer->id,
    ]);

    $thesisSeminarReviewers = ThesisSeminarReviewer::query()
        ->where('thesis_seminar_id', $thesis_seminar->id)
        ->where('reviewer_id', $reviewer->id)
        ->where('status', $status)
        ->where('lecturer_id', $lecturer->id)
        ->get();
    expect($thesisSeminarReviewers)->toHaveCount(1);
    $thesisSeminarReviewer = $thesisSeminarReviewers->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisSeminarReviewer = ThesisSeminarReviewer::factory()->create();

    $response = get(route('thesis-seminar-reviewers.show', $thesisSeminarReviewer));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisSeminarReviewerController::class,
        'update',
        \App\Http\Requests\ThesisSeminarReviewerUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisSeminarReviewer = ThesisSeminarReviewer::factory()->create();
    $thesis_seminar = ThesisSeminar::factory()->create();
    $reviewer = Reviewer::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $lecturer = Lecturer::factory()->create();

    $response = put(route('thesis-seminar-reviewers.update', $thesisSeminarReviewer), [
        'thesis_seminar_id' => $thesis_seminar->id,
        'reviewer_id' => $reviewer->id,
        'status' => $status,
        'lecturer_id' => $lecturer->id,
    ]);

    $thesisSeminarReviewer->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis_seminar->id)->toEqual($thesisSeminarReviewer->thesis_seminar_id);
    expect($reviewer->id)->toEqual($thesisSeminarReviewer->reviewer_id);
    expect($status)->toEqual($thesisSeminarReviewer->status);
    expect($lecturer->id)->toEqual($thesisSeminarReviewer->lecturer_id);
});


test('destroy deletes and responds with', function (): void {
    $thesisSeminarReviewer = ThesisSeminarReviewer::factory()->create();

    $response = delete(route('thesis-seminar-reviewers.destroy', $thesisSeminarReviewer));

    $response->assertNoContent();

    assertModelMissing($thesisSeminarReviewer);
});

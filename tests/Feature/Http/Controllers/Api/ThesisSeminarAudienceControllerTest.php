<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Student;
use App\Models\ThesisSeminar;
use App\Models\ThesisSeminarAudience;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisSeminarAudiences = ThesisSeminarAudience::factory()->count(3)->create();

    $response = get(route('thesis-seminar-audiences.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisSeminarAudienceController::class,
        'store',
        \App\Http\Requests\ThesisSeminarAudienceStoreRequest::class
    );

test('store saves', function (): void {
    $thesis_seminar = ThesisSeminar::factory()->create();
    $student = Student::factory()->create();

    $response = post(route('thesis-seminar-audiences.store'), [
        'thesis_seminar_id' => $thesis_seminar->id,
        'student_id' => $student->id,
    ]);

    $thesisSeminarAudiences = ThesisSeminarAudience::query()
        ->where('thesis_seminar_id', $thesis_seminar->id)
        ->where('student_id', $student->id)
        ->get();
    expect($thesisSeminarAudiences)->toHaveCount(1);
    $thesisSeminarAudience = $thesisSeminarAudiences->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisSeminarAudience = ThesisSeminarAudience::factory()->create();

    $response = get(route('thesis-seminar-audiences.show', $thesisSeminarAudience));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisSeminarAudienceController::class,
        'update',
        \App\Http\Requests\ThesisSeminarAudienceUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisSeminarAudience = ThesisSeminarAudience::factory()->create();
    $thesis_seminar = ThesisSeminar::factory()->create();
    $student = Student::factory()->create();

    $response = put(route('thesis-seminar-audiences.update', $thesisSeminarAudience), [
        'thesis_seminar_id' => $thesis_seminar->id,
        'student_id' => $student->id,
    ]);

    $thesisSeminarAudience->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis_seminar->id)->toEqual($thesisSeminarAudience->thesis_seminar_id);
    expect($student->id)->toEqual($thesisSeminarAudience->student_id);
});


test('destroy deletes and responds with', function (): void {
    $thesisSeminarAudience = ThesisSeminarAudience::factory()->create();

    $response = delete(route('thesis-seminar-audiences.destroy', $thesisSeminarAudience));

    $response->assertNoContent();

    assertModelMissing($thesisSeminarAudience);
});

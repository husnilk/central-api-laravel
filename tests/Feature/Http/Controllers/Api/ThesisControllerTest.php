<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CreatedBy;
use App\Models\Student;
use App\Models\Thesi;
use App\Models\Thesis;
use App\Models\ThesisTopic;
use App\Models\Topic;
use App\Models\User;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesis = Thesis::factory()->count(3)->create();

    $response = get(route('thesis.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisController::class,
        'store',
        \App\Http\Requests\ThesisStoreRequest::class
    );

test('store saves', function (): void {
    $topic = Topic::factory()->create();
    $student = Student::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $created_by = CreatedBy::factory()->create();
    $thesis_topic = ThesisTopic::factory()->create();
    $user = User::factory()->create();

    $response = post(route('thesis.store'), [
        'topic_id' => $topic->id,
        'student_id' => $student->id,
        'status' => $status,
        'created_by' => $created_by->id,
        'thesis_topic_id' => $thesis_topic->id,
        'user_id' => $user->id,
    ]);

    $thesis = Thesi::query()
        ->where('topic_id', $topic->id)
        ->where('student_id', $student->id)
        ->where('status', $status)
        ->where('created_by', $created_by->id)
        ->where('thesis_topic_id', $thesis_topic->id)
        ->where('user_id', $user->id)
        ->get();
    expect($thesis)->toHaveCount(1);
    $thesi = $thesis->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesi = Thesis::factory()->create();

    $response = get(route('thesis.show', $thesi));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisController::class,
        'update',
        \App\Http\Requests\ThesisUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesi = Thesis::factory()->create();
    $topic = Topic::factory()->create();
    $student = Student::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $created_by = CreatedBy::factory()->create();
    $thesis_topic = ThesisTopic::factory()->create();
    $user = User::factory()->create();

    $response = put(route('thesis.update', $thesi), [
        'topic_id' => $topic->id,
        'student_id' => $student->id,
        'status' => $status,
        'created_by' => $created_by->id,
        'thesis_topic_id' => $thesis_topic->id,
        'user_id' => $user->id,
    ]);

    $thesi->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($topic->id)->toEqual($thesi->topic_id);
    expect($student->id)->toEqual($thesi->student_id);
    expect($status)->toEqual($thesi->status);
    expect($created_by->id)->toEqual($thesi->created_by);
    expect($thesis_topic->id)->toEqual($thesi->thesis_topic_id);
    expect($user->id)->toEqual($thesi->user_id);
});


test('destroy deletes and responds with', function (): void {
    $thesi = Thesis::factory()->create();
    $thesi = Thesi::factory()->create();

    $response = delete(route('thesis.destroy', $thesi));

    $response->assertNoContent();

    assertModelMissing($thesi);
});

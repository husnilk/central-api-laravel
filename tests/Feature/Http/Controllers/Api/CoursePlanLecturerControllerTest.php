<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlan;
use App\Models\CoursePlanLecturer;
use App\Models\Lecturer;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanLecturers = CoursePlanLecturer::factory()->count(3)->create();

    $response = get(route('course-plan-lecturers.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanLecturerController::class,
        'store',
        \App\Http\Requests\CoursePlanLecturerStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan = CoursePlan::factory()->create();
    $lecturer = Lecturer::factory()->create();
    $creator = fake()->numberBetween(-10000, 10000);

    $response = post(route('course-plan-lecturers.store'), [
        'course_plan_id' => $course_plan->id,
        'lecturer_id' => $lecturer->id,
        'creator' => $creator,
    ]);

    $coursePlanLecturers = CoursePlanLecturer::query()
        ->where('course_plan_id', $course_plan->id)
        ->where('lecturer_id', $lecturer->id)
        ->where('creator', $creator)
        ->get();
    expect($coursePlanLecturers)->toHaveCount(1);
    $coursePlanLecturer = $coursePlanLecturers->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanLecturer = CoursePlanLecturer::factory()->create();

    $response = get(route('course-plan-lecturers.show', $coursePlanLecturer));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanLecturerController::class,
        'update',
        \App\Http\Requests\CoursePlanLecturerUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanLecturer = CoursePlanLecturer::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $lecturer = Lecturer::factory()->create();
    $creator = fake()->numberBetween(-10000, 10000);

    $response = put(route('course-plan-lecturers.update', $coursePlanLecturer), [
        'course_plan_id' => $course_plan->id,
        'lecturer_id' => $lecturer->id,
        'creator' => $creator,
    ]);

    $coursePlanLecturer->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan->id)->toEqual($coursePlanLecturer->course_plan_id);
    expect($lecturer->id)->toEqual($coursePlanLecturer->lecturer_id);
    expect($creator)->toEqual($coursePlanLecturer->creator);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanLecturer = CoursePlanLecturer::factory()->create();

    $response = delete(route('course-plan-lecturers.destroy', $coursePlanLecturer));

    $response->assertNoContent();

    assertModelMissing($coursePlanLecturer);
});

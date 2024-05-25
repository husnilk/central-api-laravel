<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Class;
use App\Models\ClassCourse;
use App\Models\ClassLecturer;
use App\Models\Lecturer;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $classLecturers = ClassLecturer::factory()->count(3)->create();

    $response = get(route('class-lecturers.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassLecturerController::class,
        'store',
        \App\Http\Requests\ClassLecturerStoreRequest::class
    );

test('store saves', function (): void {
    $class = Class::factory()->create();
    $lecturer = Lecturer::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);
    $grading = fake()->numberBetween(-10000, 10000);
    $class_course = ClassCourse::factory()->create();

    $response = post(route('class-lecturers.store'), [
        'class_id' => $class->id,
        'lecturer_id' => $lecturer->id,
        'position' => $position,
        'grading' => $grading,
        'class_course_id' => $class_course->id,
    ]);

    $classLecturers = ClassLecturer::query()
        ->where('class_id', $class->id)
        ->where('lecturer_id', $lecturer->id)
        ->where('position', $position)
        ->where('grading', $grading)
        ->where('class_course_id', $class_course->id)
        ->get();
    expect($classLecturers)->toHaveCount(1);
    $classLecturer = $classLecturers->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $classLecturer = ClassLecturer::factory()->create();

    $response = get(route('class-lecturers.show', $classLecturer));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassLecturerController::class,
        'update',
        \App\Http\Requests\ClassLecturerUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $classLecturer = ClassLecturer::factory()->create();
    $class = Class::factory()->create();
    $lecturer = Lecturer::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);
    $grading = fake()->numberBetween(-10000, 10000);
    $class_course = ClassCourse::factory()->create();

    $response = put(route('class-lecturers.update', $classLecturer), [
        'class_id' => $class->id,
        'lecturer_id' => $lecturer->id,
        'position' => $position,
        'grading' => $grading,
        'class_course_id' => $class_course->id,
    ]);

    $classLecturer->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($class->id)->toEqual($classLecturer->class_id);
    expect($lecturer->id)->toEqual($classLecturer->lecturer_id);
    expect($position)->toEqual($classLecturer->position);
    expect($grading)->toEqual($classLecturer->grading);
    expect($class_course->id)->toEqual($classLecturer->class_course_id);
});


test('destroy deletes and responds with', function (): void {
    $classLecturer = ClassLecturer::factory()->create();

    $response = delete(route('class-lecturers.destroy', $classLecturer));

    $response->assertNoContent();

    assertModelMissing($classLecturer);
});

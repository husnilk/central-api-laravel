<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Curriculum;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $courses = Course::factory()->count(3)->create();

    $response = get(route('courses.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CourseController::class,
        'store',
        \App\Http\Requests\CourseStoreRequest::class
    );

test('store saves', function (): void {
    $curriculum = Curriculum::factory()->create();
    $code = fake()->word();
    $name = fake()->name();
    $credit = fake()->numberBetween(-10000, 10000);
    $semester = fake()->numberBetween(-10000, 10000);
    $mandatory = fake()->numberBetween(-10000, 10000);

    $response = post(route('courses.store'), [
        'curriculum_id' => $curriculum->id,
        'code' => $code,
        'name' => $name,
        'credit' => $credit,
        'semester' => $semester,
        'mandatory' => $mandatory,
    ]);

    $courses = Course::query()
        ->where('curriculum_id', $curriculum->id)
        ->where('code', $code)
        ->where('name', $name)
        ->where('credit', $credit)
        ->where('semester', $semester)
        ->where('mandatory', $mandatory)
        ->get();
    expect($courses)->toHaveCount(1);
    $course = $courses->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $course = Course::factory()->create();

    $response = get(route('courses.show', $course));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CourseController::class,
        'update',
        \App\Http\Requests\CourseUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $course = Course::factory()->create();
    $curriculum = Curriculum::factory()->create();
    $code = fake()->word();
    $name = fake()->name();
    $credit = fake()->numberBetween(-10000, 10000);
    $semester = fake()->numberBetween(-10000, 10000);
    $mandatory = fake()->numberBetween(-10000, 10000);

    $response = put(route('courses.update', $course), [
        'curriculum_id' => $curriculum->id,
        'code' => $code,
        'name' => $name,
        'credit' => $credit,
        'semester' => $semester,
        'mandatory' => $mandatory,
    ]);

    $course->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($curriculum->id)->toEqual($course->curriculum_id);
    expect($code)->toEqual($course->code);
    expect($name)->toEqual($course->name);
    expect($credit)->toEqual($course->credit);
    expect($semester)->toEqual($course->semester);
    expect($mandatory)->toEqual($course->mandatory);
});


test('destroy deletes and responds with', function (): void {
    $course = Course::factory()->create();

    $response = delete(route('courses.destroy', $course));

    $response->assertNoContent();

    assertModelMissing($course);
});

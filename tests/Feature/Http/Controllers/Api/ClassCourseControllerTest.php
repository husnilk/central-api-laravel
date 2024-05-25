<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ClassCourse;
use App\Models\Course;
use App\Models\CoursePlan;
use App\Models\Period;
use App\Models\Periode;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $classCourses = ClassCourse::factory()->count(3)->create();

    $response = get(route('class-courses.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassCourseController::class,
        'store',
        \App\Http\Requests\ClassCourseStoreRequest::class
    );

test('store saves', function (): void {
    $course = Course::factory()->create();
    $periode = Periode::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $name = fake()->name();
    $period = Period::factory()->create();

    $response = post(route('class-courses.store'), [
        'course_id' => $course->id,
        'periode_id' => $periode->id,
        'course_plan_id' => $course_plan->id,
        'name' => $name,
        'period_id' => $period->id,
    ]);

    $classCourses = ClassCourse::query()
        ->where('course_id', $course->id)
        ->where('periode_id', $periode->id)
        ->where('course_plan_id', $course_plan->id)
        ->where('name', $name)
        ->where('period_id', $period->id)
        ->get();
    expect($classCourses)->toHaveCount(1);
    $classCourse = $classCourses->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $classCourse = ClassCourse::factory()->create();

    $response = get(route('class-courses.show', $classCourse));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassCourseController::class,
        'update',
        \App\Http\Requests\ClassCourseUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $classCourse = ClassCourse::factory()->create();
    $course = Course::factory()->create();
    $periode = Periode::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $name = fake()->name();
    $period = Period::factory()->create();

    $response = put(route('class-courses.update', $classCourse), [
        'course_id' => $course->id,
        'periode_id' => $periode->id,
        'course_plan_id' => $course_plan->id,
        'name' => $name,
        'period_id' => $period->id,
    ]);

    $classCourse->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course->id)->toEqual($classCourse->course_id);
    expect($periode->id)->toEqual($classCourse->periode_id);
    expect($course_plan->id)->toEqual($classCourse->course_plan_id);
    expect($name)->toEqual($classCourse->name);
    expect($period->id)->toEqual($classCourse->period_id);
});


test('destroy deletes and responds with', function (): void {
    $classCourse = ClassCourse::factory()->create();

    $response = delete(route('class-courses.destroy', $classCourse));

    $response->assertNoContent();

    assertModelMissing($classCourse);
});

<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Course;
use App\Models\CoursePlan;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlans = CoursePlan::factory()->count(3)->create();

    $response = get(route('course-plans.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanController::class,
        'store',
        \App\Http\Requests\CoursePlanStoreRequest::class
    );

test('store saves', function (): void {
    $course = Course::factory()->create();
    $rev = fake()->numberBetween(-10000, 10000);
    $code = fake()->word();
    $name = fake()->name();
    $credit = fake()->numberBetween(-10000, 10000);
    $semester = fake()->numberBetween(-10000, 10000);
    $mandatory = fake()->numberBetween(-10000, 10000);
    $description = fake()->text();

    $response = post(route('course-plans.store'), [
        'course_id' => $course->id,
        'rev' => $rev,
        'code' => $code,
        'name' => $name,
        'credit' => $credit,
        'semester' => $semester,
        'mandatory' => $mandatory,
        'description' => $description,
    ]);

    $coursePlans = CoursePlan::query()
        ->where('course_id', $course->id)
        ->where('rev', $rev)
        ->where('code', $code)
        ->where('name', $name)
        ->where('credit', $credit)
        ->where('semester', $semester)
        ->where('mandatory', $mandatory)
        ->where('description', $description)
        ->get();
    expect($coursePlans)->toHaveCount(1);
    $coursePlan = $coursePlans->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlan = CoursePlan::factory()->create();

    $response = get(route('course-plans.show', $coursePlan));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanController::class,
        'update',
        \App\Http\Requests\CoursePlanUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlan = CoursePlan::factory()->create();
    $course = Course::factory()->create();
    $rev = fake()->numberBetween(-10000, 10000);
    $code = fake()->word();
    $name = fake()->name();
    $credit = fake()->numberBetween(-10000, 10000);
    $semester = fake()->numberBetween(-10000, 10000);
    $mandatory = fake()->numberBetween(-10000, 10000);
    $description = fake()->text();

    $response = put(route('course-plans.update', $coursePlan), [
        'course_id' => $course->id,
        'rev' => $rev,
        'code' => $code,
        'name' => $name,
        'credit' => $credit,
        'semester' => $semester,
        'mandatory' => $mandatory,
        'description' => $description,
    ]);

    $coursePlan->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course->id)->toEqual($coursePlan->course_id);
    expect($rev)->toEqual($coursePlan->rev);
    expect($code)->toEqual($coursePlan->code);
    expect($name)->toEqual($coursePlan->name);
    expect($credit)->toEqual($coursePlan->credit);
    expect($semester)->toEqual($coursePlan->semester);
    expect($mandatory)->toEqual($coursePlan->mandatory);
    expect($description)->toEqual($coursePlan->description);
});


test('destroy deletes and responds with', function (): void {
    $coursePlan = CoursePlan::factory()->create();

    $response = delete(route('course-plans.destroy', $coursePlan));

    $response->assertNoContent();

    assertModelMissing($coursePlan);
});

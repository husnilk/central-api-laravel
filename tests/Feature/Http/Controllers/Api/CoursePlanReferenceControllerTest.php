<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlan;
use App\Models\CoursePlanReference;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanReferences = CoursePlanReference::factory()->count(3)->create();

    $response = get(route('course-plan-references.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanReferenceController::class,
        'store',
        \App\Http\Requests\CoursePlanReferenceStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan = CoursePlan::factory()->create();
    $title = fake()->sentence(4);
    $author = fake()->word();
    $publisher = fake()->word();
    $year = fake()->numberBetween(-10000, 10000);
    $type = fake()->numberBetween(-10000, 10000);
    $primary = fake()->numberBetween(-10000, 10000);

    $response = post(route('course-plan-references.store'), [
        'course_plan_id' => $course_plan->id,
        'title' => $title,
        'author' => $author,
        'publisher' => $publisher,
        'year' => $year,
        'type' => $type,
        'primary' => $primary,
    ]);

    $coursePlanReferences = CoursePlanReference::query()
        ->where('course_plan_id', $course_plan->id)
        ->where('title', $title)
        ->where('author', $author)
        ->where('publisher', $publisher)
        ->where('year', $year)
        ->where('type', $type)
        ->where('primary', $primary)
        ->get();
    expect($coursePlanReferences)->toHaveCount(1);
    $coursePlanReference = $coursePlanReferences->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanReference = CoursePlanReference::factory()->create();

    $response = get(route('course-plan-references.show', $coursePlanReference));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanReferenceController::class,
        'update',
        \App\Http\Requests\CoursePlanReferenceUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanReference = CoursePlanReference::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $title = fake()->sentence(4);
    $author = fake()->word();
    $publisher = fake()->word();
    $year = fake()->numberBetween(-10000, 10000);
    $type = fake()->numberBetween(-10000, 10000);
    $primary = fake()->numberBetween(-10000, 10000);

    $response = put(route('course-plan-references.update', $coursePlanReference), [
        'course_plan_id' => $course_plan->id,
        'title' => $title,
        'author' => $author,
        'publisher' => $publisher,
        'year' => $year,
        'type' => $type,
        'primary' => $primary,
    ]);

    $coursePlanReference->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan->id)->toEqual($coursePlanReference->course_plan_id);
    expect($title)->toEqual($coursePlanReference->title);
    expect($author)->toEqual($coursePlanReference->author);
    expect($publisher)->toEqual($coursePlanReference->publisher);
    expect($year)->toEqual($coursePlanReference->year);
    expect($type)->toEqual($coursePlanReference->type);
    expect($primary)->toEqual($coursePlanReference->primary);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanReference = CoursePlanReference::factory()->create();

    $response = delete(route('course-plan-references.destroy', $coursePlanReference));

    $response->assertNoContent();

    assertModelMissing($coursePlanReference);
});

<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Course;
use App\Models\CourseCurriculumIndicator;
use App\Models\CurriculumIndicator;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $courseCurriculumIndicators = CourseCurriculumIndicator::factory()->count(3)->create();

    $response = get(route('course-curriculum-indicators.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CourseCurriculumIndicatorController::class,
        'store',
        \App\Http\Requests\CourseCurriculumIndicatorStoreRequest::class
    );

test('store saves', function (): void {
    $course = Course::factory()->create();
    $curriculum_indicator = CurriculumIndicator::factory()->create();

    $response = post(route('course-curriculum-indicators.store'), [
        'course_id' => $course->id,
        'curriculum_indicator_id' => $curriculum_indicator->id,
    ]);

    $courseCurriculumIndicators = CourseCurriculumIndicator::query()
        ->where('course_id', $course->id)
        ->where('curriculum_indicator_id', $curriculum_indicator->id)
        ->get();
    expect($courseCurriculumIndicators)->toHaveCount(1);
    $courseCurriculumIndicator = $courseCurriculumIndicators->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $courseCurriculumIndicator = CourseCurriculumIndicator::factory()->create();

    $response = get(route('course-curriculum-indicators.show', $courseCurriculumIndicator));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CourseCurriculumIndicatorController::class,
        'update',
        \App\Http\Requests\CourseCurriculumIndicatorUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $courseCurriculumIndicator = CourseCurriculumIndicator::factory()->create();
    $course = Course::factory()->create();
    $curriculum_indicator = CurriculumIndicator::factory()->create();

    $response = put(route('course-curriculum-indicators.update', $courseCurriculumIndicator), [
        'course_id' => $course->id,
        'curriculum_indicator_id' => $curriculum_indicator->id,
    ]);

    $courseCurriculumIndicator->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course->id)->toEqual($courseCurriculumIndicator->course_id);
    expect($curriculum_indicator->id)->toEqual($courseCurriculumIndicator->curriculum_indicator_id);
});


test('destroy deletes and responds with', function (): void {
    $courseCurriculumIndicator = CourseCurriculumIndicator::factory()->create();

    $response = delete(route('course-curriculum-indicators.destroy', $courseCurriculumIndicator));

    $response->assertNoContent();

    assertModelMissing($courseCurriculumIndicator);
});

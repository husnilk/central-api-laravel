<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Class;
use App\Models\ClassCourse;
use App\Models\ClassMeeting;
use App\Models\CoursePlanDetail;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $classMeetings = ClassMeeting::factory()->count(3)->create();

    $response = get(route('class-meetings.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassMeetingController::class,
        'store',
        \App\Http\Requests\ClassMeetingStoreRequest::class
    );

test('store saves', function (): void {
    $meet_no = fake()->numberBetween(-10000, 10000);
    $class = Class::factory()->create();
    $course_plan_detail = CoursePlanDetail::factory()->create();
    $material_real = fake()->text();
    $assessment_real = fake()->text();
    $method = fake()->numberBetween(-10000, 10000);
    $class_course = ClassCourse::factory()->create();

    $response = post(route('class-meetings.store'), [
        'meet_no' => $meet_no,
        'class_id' => $class->id,
        'course_plan_detail_id' => $course_plan_detail->id,
        'material_real' => $material_real,
        'assessment_real' => $assessment_real,
        'method' => $method,
        'class_course_id' => $class_course->id,
    ]);

    $classMeetings = ClassMeeting::query()
        ->where('meet_no', $meet_no)
        ->where('class_id', $class->id)
        ->where('course_plan_detail_id', $course_plan_detail->id)
        ->where('material_real', $material_real)
        ->where('assessment_real', $assessment_real)
        ->where('method', $method)
        ->where('class_course_id', $class_course->id)
        ->get();
    expect($classMeetings)->toHaveCount(1);
    $classMeeting = $classMeetings->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $classMeeting = ClassMeeting::factory()->create();

    $response = get(route('class-meetings.show', $classMeeting));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassMeetingController::class,
        'update',
        \App\Http\Requests\ClassMeetingUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $classMeeting = ClassMeeting::factory()->create();
    $meet_no = fake()->numberBetween(-10000, 10000);
    $class = Class::factory()->create();
    $course_plan_detail = CoursePlanDetail::factory()->create();
    $material_real = fake()->text();
    $assessment_real = fake()->text();
    $method = fake()->numberBetween(-10000, 10000);
    $class_course = ClassCourse::factory()->create();

    $response = put(route('class-meetings.update', $classMeeting), [
        'meet_no' => $meet_no,
        'class_id' => $class->id,
        'course_plan_detail_id' => $course_plan_detail->id,
        'material_real' => $material_real,
        'assessment_real' => $assessment_real,
        'method' => $method,
        'class_course_id' => $class_course->id,
    ]);

    $classMeeting->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($meet_no)->toEqual($classMeeting->meet_no);
    expect($class->id)->toEqual($classMeeting->class_id);
    expect($course_plan_detail->id)->toEqual($classMeeting->course_plan_detail_id);
    expect($material_real)->toEqual($classMeeting->material_real);
    expect($assessment_real)->toEqual($classMeeting->assessment_real);
    expect($method)->toEqual($classMeeting->method);
    expect($class_course->id)->toEqual($classMeeting->class_course_id);
});


test('destroy deletes and responds with', function (): void {
    $classMeeting = ClassMeeting::factory()->create();

    $response = delete(route('class-meetings.destroy', $classMeeting));

    $response->assertNoContent();

    assertModelMissing($classMeeting);
});

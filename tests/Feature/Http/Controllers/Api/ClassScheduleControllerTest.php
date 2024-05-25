<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Class;
use App\Models\ClassCourse;
use App\Models\ClassSchedule;
use App\Models\Room;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $classSchedules = ClassSchedule::factory()->count(3)->create();

    $response = get(route('class-schedules.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassScheduleController::class,
        'store',
        \App\Http\Requests\ClassScheduleStoreRequest::class
    );

test('store saves', function (): void {
    $class = Class::factory()->create();
    $room = Room::factory()->create();
    $weekday = fake()->numberBetween(-10000, 10000);
    $start_at = fake()->time();
    $end_at = fake()->time();
    $class_course = ClassCourse::factory()->create();

    $response = post(route('class-schedules.store'), [
        'class_id' => $class->id,
        'room_id' => $room->id,
        'weekday' => $weekday,
        'start_at' => $start_at,
        'end_at' => $end_at,
        'class_course_id' => $class_course->id,
    ]);

    $classSchedules = ClassSchedule::query()
        ->where('class_id', $class->id)
        ->where('room_id', $room->id)
        ->where('weekday', $weekday)
        ->where('start_at', $start_at)
        ->where('end_at', $end_at)
        ->where('class_course_id', $class_course->id)
        ->get();
    expect($classSchedules)->toHaveCount(1);
    $classSchedule = $classSchedules->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $classSchedule = ClassSchedule::factory()->create();

    $response = get(route('class-schedules.show', $classSchedule));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassScheduleController::class,
        'update',
        \App\Http\Requests\ClassScheduleUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $classSchedule = ClassSchedule::factory()->create();
    $class = Class::factory()->create();
    $room = Room::factory()->create();
    $weekday = fake()->numberBetween(-10000, 10000);
    $start_at = fake()->time();
    $end_at = fake()->time();
    $class_course = ClassCourse::factory()->create();

    $response = put(route('class-schedules.update', $classSchedule), [
        'class_id' => $class->id,
        'room_id' => $room->id,
        'weekday' => $weekday,
        'start_at' => $start_at,
        'end_at' => $end_at,
        'class_course_id' => $class_course->id,
    ]);

    $classSchedule->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($class->id)->toEqual($classSchedule->class_id);
    expect($room->id)->toEqual($classSchedule->room_id);
    expect($weekday)->toEqual($classSchedule->weekday);
    expect($start_at)->toEqual($classSchedule->start_at);
    expect($end_at)->toEqual($classSchedule->end_at);
    expect($class_course->id)->toEqual($classSchedule->class_course_id);
});


test('destroy deletes and responds with', function (): void {
    $classSchedule = ClassSchedule::factory()->create();

    $response = delete(route('class-schedules.destroy', $classSchedule));

    $response->assertNoContent();

    assertModelMissing($classSchedule);
});

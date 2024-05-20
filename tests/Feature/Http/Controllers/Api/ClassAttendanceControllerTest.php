<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ClassAttendance;
use App\Models\ClassMeeting;
use App\Models\StudyPlanDetail;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $classAttendances = ClassAttendance::factory()->count(3)->create();

    $response = get(route('class-attendances.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassAttendanceController::class,
        'store',
        \App\Http\Requests\ClassAttendanceStoreRequest::class
    );

test('store saves', function (): void {
    $study_plan_detail = StudyPlanDetail::factory()->create();
    $class_meeting = ClassMeeting::factory()->create();
    $meet_no = fake()->numberBetween(-10000, 10000);
    $attendance_status = fake()->numberBetween(-10000, 10000);
    $need_attention = fake()->numberBetween(-10000, 10000);
    $information = fake()->text();

    $response = post(route('class-attendances.store'), [
        'study_plan_detail_id' => $study_plan_detail->id,
        'class_meeting_id' => $class_meeting->id,
        'meet_no' => $meet_no,
        'attendance_status' => $attendance_status,
        'need_attention' => $need_attention,
        'information' => $information,
    ]);

    $classAttendances = ClassAttendance::query()
        ->where('study_plan_detail_id', $study_plan_detail->id)
        ->where('class_meeting_id', $class_meeting->id)
        ->where('meet_no', $meet_no)
        ->where('attendance_status', $attendance_status)
        ->where('need_attention', $need_attention)
        ->where('information', $information)
        ->get();
    expect($classAttendances)->toHaveCount(1);
    $classAttendance = $classAttendances->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $classAttendance = ClassAttendance::factory()->create();

    $response = get(route('class-attendances.show', $classAttendance));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ClassAttendanceController::class,
        'update',
        \App\Http\Requests\ClassAttendanceUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $classAttendance = ClassAttendance::factory()->create();
    $study_plan_detail = StudyPlanDetail::factory()->create();
    $class_meeting = ClassMeeting::factory()->create();
    $meet_no = fake()->numberBetween(-10000, 10000);
    $attendance_status = fake()->numberBetween(-10000, 10000);
    $need_attention = fake()->numberBetween(-10000, 10000);
    $information = fake()->text();

    $response = put(route('class-attendances.update', $classAttendance), [
        'study_plan_detail_id' => $study_plan_detail->id,
        'class_meeting_id' => $class_meeting->id,
        'meet_no' => $meet_no,
        'attendance_status' => $attendance_status,
        'need_attention' => $need_attention,
        'information' => $information,
    ]);

    $classAttendance->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($study_plan_detail->id)->toEqual($classAttendance->study_plan_detail_id);
    expect($class_meeting->id)->toEqual($classAttendance->class_meeting_id);
    expect($meet_no)->toEqual($classAttendance->meet_no);
    expect($attendance_status)->toEqual($classAttendance->attendance_status);
    expect($need_attention)->toEqual($classAttendance->need_attention);
    expect($information)->toEqual($classAttendance->information);
});


test('destroy deletes and responds with', function (): void {
    $classAttendance = ClassAttendance::factory()->create();

    $response = delete(route('class-attendances.destroy', $classAttendance));

    $response->assertNoContent();

    assertModelMissing($classAttendance);
});

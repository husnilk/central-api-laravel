<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Counselor;
use App\Models\Period;
use App\Models\Periode;
use App\Models\Student;
use App\Models\StudyPlan;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $studyPlans = StudyPlan::factory()->count(3)->create();

    $response = get(route('study-plans.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\StudyPlanController::class,
        'store',
        \App\Http\Requests\StudyPlanStoreRequest::class
    );

test('store saves', function (): void {
    $student = Student::factory()->create();
    $periode = Periode::factory()->create();
    $counselor = Counselor::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $registered_at = Carbon::parse(fake()->date());
    $gpa = fake()->randomFloat(/** double_attributes **/);
    $period = Period::factory()->create();

    $response = post(route('study-plans.store'), [
        'student_id' => $student->id,
        'periode_id' => $periode->id,
        'counselor_id' => $counselor->id,
        'status' => $status,
        'registered_at' => $registered_at->toDateString(),
        'gpa' => $gpa,
        'period_id' => $period->id,
    ]);

    $studyPlans = StudyPlan::query()
        ->where('student_id', $student->id)
        ->where('periode_id', $periode->id)
        ->where('counselor_id', $counselor->id)
        ->where('status', $status)
        ->where('registered_at', $registered_at)
        ->where('gpa', $gpa)
        ->where('period_id', $period->id)
        ->get();
    expect($studyPlans)->toHaveCount(1);
    $studyPlan = $studyPlans->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $studyPlan = StudyPlan::factory()->create();

    $response = get(route('study-plans.show', $studyPlan));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\StudyPlanController::class,
        'update',
        \App\Http\Requests\StudyPlanUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $studyPlan = StudyPlan::factory()->create();
    $student = Student::factory()->create();
    $periode = Periode::factory()->create();
    $counselor = Counselor::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $registered_at = Carbon::parse(fake()->date());
    $gpa = fake()->randomFloat(/** double_attributes **/);
    $period = Period::factory()->create();

    $response = put(route('study-plans.update', $studyPlan), [
        'student_id' => $student->id,
        'periode_id' => $periode->id,
        'counselor_id' => $counselor->id,
        'status' => $status,
        'registered_at' => $registered_at->toDateString(),
        'gpa' => $gpa,
        'period_id' => $period->id,
    ]);

    $studyPlan->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($student->id)->toEqual($studyPlan->student_id);
    expect($periode->id)->toEqual($studyPlan->periode_id);
    expect($counselor->id)->toEqual($studyPlan->counselor_id);
    expect($status)->toEqual($studyPlan->status);
    expect($registered_at)->toEqual($studyPlan->registered_at);
    expect($gpa)->toEqual($studyPlan->gpa);
    expect($period->id)->toEqual($studyPlan->period_id);
});


test('destroy deletes and responds with', function (): void {
    $studyPlan = StudyPlan::factory()->create();

    $response = delete(route('study-plans.destroy', $studyPlan));

    $response->assertNoContent();

    assertModelMissing($studyPlan);
});

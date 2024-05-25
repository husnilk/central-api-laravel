<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Department;
use App\Models\Student;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $students = Student::factory()->count(3)->create();

    $response = get(route('students.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\StudentController::class,
        'store',
        \App\Http\Requests\StudentStoreRequest::class
    );

test('store saves', function (): void {
    $nim = fake()->word();
    $name = fake()->name();
    $department = Department::factory()->create();
    $religion = fake()->numberBetween(-10000, 10000);
    $status = fake()->numberBetween(-10000, 10000);

    $response = post(route('students.store'), [
        'nim' => $nim,
        'name' => $name,
        'department_id' => $department->id,
        'religion' => $religion,
        'status' => $status,
    ]);

    $students = Student::query()
        ->where('nim', $nim)
        ->where('name', $name)
        ->where('department_id', $department->id)
        ->where('religion', $religion)
        ->where('status', $status)
        ->get();
    expect($students)->toHaveCount(1);
    $student = $students->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $student = Student::factory()->create();

    $response = get(route('students.show', $student));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\StudentController::class,
        'update',
        \App\Http\Requests\StudentUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $student = Student::factory()->create();
    $nim = fake()->word();
    $name = fake()->name();
    $department = Department::factory()->create();
    $religion = fake()->numberBetween(-10000, 10000);
    $status = fake()->numberBetween(-10000, 10000);

    $response = put(route('students.update', $student), [
        'nim' => $nim,
        'name' => $name,
        'department_id' => $department->id,
        'religion' => $religion,
        'status' => $status,
    ]);

    $student->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($nim)->toEqual($student->nim);
    expect($name)->toEqual($student->name);
    expect($department->id)->toEqual($student->department_id);
    expect($religion)->toEqual($student->religion);
    expect($status)->toEqual($student->status);
});


test('destroy deletes and responds with', function (): void {
    $student = Student::factory()->create();

    $response = delete(route('students.destroy', $student));

    $response->assertNoContent();

    assertModelMissing($student);
});

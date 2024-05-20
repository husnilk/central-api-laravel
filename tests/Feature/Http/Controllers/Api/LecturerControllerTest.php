<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Department;
use App\Models\Lecturer;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $lecturers = Lecturer::factory()->count(3)->create();

    $response = get(route('lecturers.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\LecturerController::class,
        'store',
        \App\Http\Requests\LecturerStoreRequest::class
    );

test('store saves', function (): void {
    $nik = fake()->word();
    $name = fake()->name();
    $department = Department::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);

    $response = post(route('lecturers.store'), [
        'nik' => $nik,
        'name' => $name,
        'department_id' => $department->id,
        'status' => $status,
    ]);

    $lecturers = Lecturer::query()
        ->where('nik', $nik)
        ->where('name', $name)
        ->where('department_id', $department->id)
        ->where('status', $status)
        ->get();
    expect($lecturers)->toHaveCount(1);
    $lecturer = $lecturers->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $lecturer = Lecturer::factory()->create();

    $response = get(route('lecturers.show', $lecturer));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\LecturerController::class,
        'update',
        \App\Http\Requests\LecturerUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $lecturer = Lecturer::factory()->create();
    $nik = fake()->word();
    $name = fake()->name();
    $department = Department::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);

    $response = put(route('lecturers.update', $lecturer), [
        'nik' => $nik,
        'name' => $name,
        'department_id' => $department->id,
        'status' => $status,
    ]);

    $lecturer->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($nik)->toEqual($lecturer->nik);
    expect($name)->toEqual($lecturer->name);
    expect($department->id)->toEqual($lecturer->department_id);
    expect($status)->toEqual($lecturer->status);
});


test('destroy deletes and responds with', function (): void {
    $lecturer = Lecturer::factory()->create();

    $response = delete(route('lecturers.destroy', $lecturer));

    $response->assertNoContent();

    assertModelMissing($lecturer);
});

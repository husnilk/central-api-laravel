<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Department;
use App\Models\Faculty;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $departments = Department::factory()->count(3)->create();

    $response = get(route('departments.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\DepartmentController::class,
        'store',
        \App\Http\Requests\DepartmentStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();
    $faculty = Faculty::factory()->create();

    $response = post(route('departments.store'), [
        'name' => $name,
        'faculty_id' => $faculty->id,
    ]);

    $departments = Department::query()
        ->where('name', $name)
        ->where('faculty_id', $faculty->id)
        ->get();
    expect($departments)->toHaveCount(1);
    $department = $departments->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $department = Department::factory()->create();

    $response = get(route('departments.show', $department));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\DepartmentController::class,
        'update',
        \App\Http\Requests\DepartmentUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $department = Department::factory()->create();
    $name = fake()->name();
    $faculty = Faculty::factory()->create();

    $response = put(route('departments.update', $department), [
        'name' => $name,
        'faculty_id' => $faculty->id,
    ]);

    $department->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($department->name);
    expect($faculty->id)->toEqual($department->faculty_id);
});


test('destroy deletes and responds with', function (): void {
    $department = Department::factory()->create();

    $response = delete(route('departments.destroy', $department));

    $response->assertNoContent();

    assertModelMissing($department);
});

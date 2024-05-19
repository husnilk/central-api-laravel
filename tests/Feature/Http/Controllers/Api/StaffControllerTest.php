<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Department;
use App\Models\Staff;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $staff = Staff::factory()->count(3)->create();

    $response = get(route('staff.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\StaffController::class,
        'store',
        \App\Http\Requests\StaffStoreRequest::class
    );

test('store saves', function (): void {
    $nik = fake()->word();
    $name = fake()->name();
    $department = Department::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);

    $response = post(route('staff.store'), [
        'nik' => $nik,
        'name' => $name,
        'department_id' => $department->id,
        'status' => $status,
    ]);

    $staff = Staff::query()
        ->where('nik', $nik)
        ->where('name', $name)
        ->where('department_id', $department->id)
        ->where('status', $status)
        ->get();
    expect($staff)->toHaveCount(1);
    $staff = $staff->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $staff = Staff::factory()->create();

    $response = get(route('staff.show', $staff));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\StaffController::class,
        'update',
        \App\Http\Requests\StaffUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $staff = Staff::factory()->create();
    $nik = fake()->word();
    $name = fake()->name();
    $department = Department::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);

    $response = put(route('staff.update', $staff), [
        'nik' => $nik,
        'name' => $name,
        'department_id' => $department->id,
        'status' => $status,
    ]);

    $staff->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($nik)->toEqual($staff->nik);
    expect($name)->toEqual($staff->name);
    expect($department->id)->toEqual($staff->department_id);
    expect($status)->toEqual($staff->status);
});


test('destroy deletes and responds with', function (): void {
    $staff = Staff::factory()->create();

    $response = delete(route('staff.destroy', $staff));

    $response->assertNoContent();

    assertModelMissing($staff);
});

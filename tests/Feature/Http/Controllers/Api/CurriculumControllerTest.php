<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Curriculum;
use App\Models\Department;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $curricula = Curriculum::factory()->count(3)->create();

    $response = get(route('curricula.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumController::class,
        'store',
        \App\Http\Requests\CurriculumStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();
    $department = Department::factory()->create();
    $year_start = fake()->numberBetween(-10000, 10000);
    $year_end = fake()->numberBetween(-10000, 10000);
    $active = fake()->numberBetween(-10000, 10000);

    $response = post(route('curricula.store'), [
        'name' => $name,
        'department_id' => $department->id,
        'year_start' => $year_start,
        'year_end' => $year_end,
        'active' => $active,
    ]);

    $curricula = Curriculum::query()
        ->where('name', $name)
        ->where('department_id', $department->id)
        ->where('year_start', $year_start)
        ->where('year_end', $year_end)
        ->where('active', $active)
        ->get();
    expect($curricula)->toHaveCount(1);
    $curriculum = $curricula->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $curriculum = Curriculum::factory()->create();

    $response = get(route('curricula.show', $curriculum));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CurriculumController::class,
        'update',
        \App\Http\Requests\CurriculumUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $curriculum = Curriculum::factory()->create();
    $name = fake()->name();
    $department = Department::factory()->create();
    $year_start = fake()->numberBetween(-10000, 10000);
    $year_end = fake()->numberBetween(-10000, 10000);
    $active = fake()->numberBetween(-10000, 10000);

    $response = put(route('curricula.update', $curriculum), [
        'name' => $name,
        'department_id' => $department->id,
        'year_start' => $year_start,
        'year_end' => $year_end,
        'active' => $active,
    ]);

    $curriculum->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($curriculum->name);
    expect($department->id)->toEqual($curriculum->department_id);
    expect($year_start)->toEqual($curriculum->year_start);
    expect($year_end)->toEqual($curriculum->year_end);
    expect($active)->toEqual($curriculum->active);
});


test('destroy deletes and responds with', function (): void {
    $curriculum = Curriculum::factory()->create();

    $response = delete(route('curricula.destroy', $curriculum));

    $response->assertNoContent();

    assertModelMissing($curriculum);
});

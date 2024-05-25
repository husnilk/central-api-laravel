<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Faculty;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $faculties = Faculty::factory()->count(3)->create();

    $response = get(route('faculties.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\FacultyController::class,
        'store',
        \App\Http\Requests\FacultyStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();
    $type = fake()->numberBetween(-10000, 10000);

    $response = post(route('faculties.store'), [
        'name' => $name,
        'type' => $type,
    ]);

    $faculties = Faculty::query()
        ->where('name', $name)
        ->where('type', $type)
        ->get();
    expect($faculties)->toHaveCount(1);
    $faculty = $faculties->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $faculty = Faculty::factory()->create();

    $response = get(route('faculties.show', $faculty));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\FacultyController::class,
        'update',
        \App\Http\Requests\FacultyUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $faculty = Faculty::factory()->create();
    $name = fake()->name();
    $type = fake()->numberBetween(-10000, 10000);

    $response = put(route('faculties.update', $faculty), [
        'name' => $name,
        'type' => $type,
    ]);

    $faculty->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($faculty->name);
    expect($type)->toEqual($faculty->type);
});


test('destroy deletes and responds with', function (): void {
    $faculty = Faculty::factory()->create();

    $response = delete(route('faculties.destroy', $faculty));

    $response->assertNoContent();

    assertModelMissing($faculty);
});

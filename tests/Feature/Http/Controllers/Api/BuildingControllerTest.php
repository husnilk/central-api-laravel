<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Building;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $buildings = Building::factory()->count(3)->create();

    $response = get(route('buildings.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\BuildingController::class,
        'store',
        \App\Http\Requests\BuildingStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();

    $response = post(route('buildings.store'), [
        'name' => $name,
    ]);

    $buildings = Building::query()
        ->where('name', $name)
        ->get();
    expect($buildings)->toHaveCount(1);
    $building = $buildings->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $building = Building::factory()->create();

    $response = get(route('buildings.show', $building));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\BuildingController::class,
        'update',
        \App\Http\Requests\BuildingUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $building = Building::factory()->create();
    $name = fake()->name();

    $response = put(route('buildings.update', $building), [
        'name' => $name,
    ]);

    $building->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($building->name);
});


test('destroy deletes and responds with', function (): void {
    $building = Building::factory()->create();

    $response = delete(route('buildings.destroy', $building));

    $response->assertNoContent();

    assertModelMissing($building);
});

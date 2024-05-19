<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Building;
use App\Models\Room;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $rooms = Room::factory()->count(3)->create();

    $response = get(route('rooms.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\RoomController::class,
        'store',
        \App\Http\Requests\RoomStoreRequest::class
    );

test('store saves', function (): void {
    $building = Building::factory()->create();
    $name = fake()->name();
    $availability = fake()->numberBetween(-10000, 10000);

    $response = post(route('rooms.store'), [
        'building_id' => $building->id,
        'name' => $name,
        'availability' => $availability,
    ]);

    $rooms = Room::query()
        ->where('building_id', $building->id)
        ->where('name', $name)
        ->where('availability', $availability)
        ->get();
    expect($rooms)->toHaveCount(1);
    $room = $rooms->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $room = Room::factory()->create();

    $response = get(route('rooms.show', $room));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\RoomController::class,
        'update',
        \App\Http\Requests\RoomUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $room = Room::factory()->create();
    $building = Building::factory()->create();
    $name = fake()->name();
    $availability = fake()->numberBetween(-10000, 10000);

    $response = put(route('rooms.update', $room), [
        'building_id' => $building->id,
        'name' => $name,
        'availability' => $availability,
    ]);

    $room->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($building->id)->toEqual($room->building_id);
    expect($name)->toEqual($room->name);
    expect($availability)->toEqual($room->availability);
});


test('destroy deletes and responds with', function (): void {
    $room = Room::factory()->create();

    $response = delete(route('rooms.destroy', $room));

    $response->assertNoContent();

    assertModelMissing($room);
});

<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Publisher;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $publishers = Publisher::factory()->count(3)->create();

    $response = get(route('publishers.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\PublisherController::class,
        'store',
        \App\Http\Requests\PublisherStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();
    $type = fake()->randomElement(/** enum_attributes **/);
    $international = fake()->numberBetween(-10000, 10000);
    $indexed = fake()->numberBetween(-10000, 10000);

    $response = post(route('publishers.store'), [
        'name' => $name,
        'type' => $type,
        'international' => $international,
        'indexed' => $indexed,
    ]);

    $publishers = Publisher::query()
        ->where('name', $name)
        ->where('type', $type)
        ->where('international', $international)
        ->where('indexed', $indexed)
        ->get();
    expect($publishers)->toHaveCount(1);
    $publisher = $publishers->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $publisher = Publisher::factory()->create();

    $response = get(route('publishers.show', $publisher));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\PublisherController::class,
        'update',
        \App\Http\Requests\PublisherUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $publisher = Publisher::factory()->create();
    $name = fake()->name();
    $type = fake()->randomElement(/** enum_attributes **/);
    $international = fake()->numberBetween(-10000, 10000);
    $indexed = fake()->numberBetween(-10000, 10000);

    $response = put(route('publishers.update', $publisher), [
        'name' => $name,
        'type' => $type,
        'international' => $international,
        'indexed' => $indexed,
    ]);

    $publisher->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($publisher->name);
    expect($type)->toEqual($publisher->type);
    expect($international)->toEqual($publisher->international);
    expect($indexed)->toEqual($publisher->indexed);
});


test('destroy deletes and responds with', function (): void {
    $publisher = Publisher::factory()->create();

    $response = delete(route('publishers.destroy', $publisher));

    $response->assertNoContent();

    assertModelMissing($publisher);
});

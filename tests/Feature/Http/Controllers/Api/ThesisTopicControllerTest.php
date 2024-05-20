<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ThesisTopic;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisTopics = ThesisTopic::factory()->count(3)->create();

    $response = get(route('thesis-topics.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisTopicController::class,
        'store',
        \App\Http\Requests\ThesisTopicStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();

    $response = post(route('thesis-topics.store'), [
        'name' => $name,
    ]);

    $thesisTopics = ThesisTopic::query()
        ->where('name', $name)
        ->get();
    expect($thesisTopics)->toHaveCount(1);
    $thesisTopic = $thesisTopics->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisTopic = ThesisTopic::factory()->create();

    $response = get(route('thesis-topics.show', $thesisTopic));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisTopicController::class,
        'update',
        \App\Http\Requests\ThesisTopicUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisTopic = ThesisTopic::factory()->create();
    $name = fake()->name();

    $response = put(route('thesis-topics.update', $thesisTopic), [
        'name' => $name,
    ]);

    $thesisTopic->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($thesisTopic->name);
});


test('destroy deletes and responds with', function (): void {
    $thesisTopic = ThesisTopic::factory()->create();

    $response = delete(route('thesis-topics.destroy', $thesisTopic));

    $response->assertNoContent();

    assertModelMissing($thesisTopic);
});

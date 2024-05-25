<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Publication;
use App\Models\Publisher;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $publications = Publication::factory()->count(3)->create();

    $response = get(route('publications.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\PublicationController::class,
        'store',
        \App\Http\Requests\PublicationStoreRequest::class
    );

test('store saves', function (): void {
    $title = fake()->sentence(4);
    $publisher = Publisher::factory()->create();
    $published_at = Carbon::parse(fake()->date());

    $response = post(route('publications.store'), [
        'title' => $title,
        'publisher_id' => $publisher->id,
        'published_at' => $published_at->toDateString(),
    ]);

    $publications = Publication::query()
        ->where('title', $title)
        ->where('publisher_id', $publisher->id)
        ->where('published_at', $published_at)
        ->get();
    expect($publications)->toHaveCount(1);
    $publication = $publications->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $publication = Publication::factory()->create();

    $response = get(route('publications.show', $publication));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\PublicationController::class,
        'update',
        \App\Http\Requests\PublicationUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $publication = Publication::factory()->create();
    $title = fake()->sentence(4);
    $publisher = Publisher::factory()->create();
    $published_at = Carbon::parse(fake()->date());

    $response = put(route('publications.update', $publication), [
        'title' => $title,
        'publisher_id' => $publisher->id,
        'published_at' => $published_at->toDateString(),
    ]);

    $publication->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($title)->toEqual($publication->title);
    expect($publisher->id)->toEqual($publication->publisher_id);
    expect($published_at)->toEqual($publication->published_at);
});


test('destroy deletes and responds with', function (): void {
    $publication = Publication::factory()->create();

    $response = delete(route('publications.destroy', $publication));

    $response->assertNoContent();

    assertModelMissing($publication);
});

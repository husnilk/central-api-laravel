<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $publicationAuthors = PublicationAuthor::factory()->count(3)->create();

    $response = get(route('publication-authors.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\PublicationAuthorController::class,
        'store',
        \App\Http\Requests\PublicationAuthorStoreRequest::class
    );

test('store saves', function (): void {
    $publication = Publication::factory()->create();
    $user = User::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);
    $corresponding = fake()->numberBetween(-10000, 10000);

    $response = post(route('publication-authors.store'), [
        'publication_id' => $publication->id,
        'user_id' => $user->id,
        'position' => $position,
        'corresponding' => $corresponding,
    ]);

    $publicationAuthors = PublicationAuthor::query()
        ->where('publication_id', $publication->id)
        ->where('user_id', $user->id)
        ->where('position', $position)
        ->where('corresponding', $corresponding)
        ->get();
    expect($publicationAuthors)->toHaveCount(1);
    $publicationAuthor = $publicationAuthors->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $publicationAuthor = PublicationAuthor::factory()->create();

    $response = get(route('publication-authors.show', $publicationAuthor));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\PublicationAuthorController::class,
        'update',
        \App\Http\Requests\PublicationAuthorUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $publicationAuthor = PublicationAuthor::factory()->create();
    $publication = Publication::factory()->create();
    $user = User::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);
    $corresponding = fake()->numberBetween(-10000, 10000);

    $response = put(route('publication-authors.update', $publicationAuthor), [
        'publication_id' => $publication->id,
        'user_id' => $user->id,
        'position' => $position,
        'corresponding' => $corresponding,
    ]);

    $publicationAuthor->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($publication->id)->toEqual($publicationAuthor->publication_id);
    expect($user->id)->toEqual($publicationAuthor->user_id);
    expect($position)->toEqual($publicationAuthor->position);
    expect($corresponding)->toEqual($publicationAuthor->corresponding);
});


test('destroy deletes and responds with', function (): void {
    $publicationAuthor = PublicationAuthor::factory()->create();

    $response = delete(route('publication-authors.destroy', $publicationAuthor));

    $response->assertNoContent();

    assertModelMissing($publicationAuthor);
});

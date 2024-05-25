<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CommunityServiceSchema;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $communityServiceSchemas = CommunityServiceSchema::factory()->count(3)->create();

    $response = get(route('community-service-schemas.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CommunityServiceSchemaController::class,
        'store',
        \App\Http\Requests\CommunityServiceSchemaStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();

    $response = post(route('community-service-schemas.store'), [
        'name' => $name,
    ]);

    $communityServiceSchemas = CommunityServiceSchema::query()
        ->where('name', $name)
        ->get();
    expect($communityServiceSchemas)->toHaveCount(1);
    $communityServiceSchema = $communityServiceSchemas->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $communityServiceSchema = CommunityServiceSchema::factory()->create();

    $response = get(route('community-service-schemas.show', $communityServiceSchema));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CommunityServiceSchemaController::class,
        'update',
        \App\Http\Requests\CommunityServiceSchemaUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $communityServiceSchema = CommunityServiceSchema::factory()->create();
    $name = fake()->name();

    $response = put(route('community-service-schemas.update', $communityServiceSchema), [
        'name' => $name,
    ]);

    $communityServiceSchema->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($communityServiceSchema->name);
});


test('destroy deletes and responds with', function (): void {
    $communityServiceSchema = CommunityServiceSchema::factory()->create();

    $response = delete(route('community-service-schemas.destroy', $communityServiceSchema));

    $response->assertNoContent();

    assertModelMissing($communityServiceSchema);
});

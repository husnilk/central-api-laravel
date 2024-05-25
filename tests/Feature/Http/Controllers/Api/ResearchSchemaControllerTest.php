<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ResearchSchema;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $researchSchemas = ResearchSchema::factory()->count(3)->create();

    $response = get(route('research-schemas.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ResearchSchemaController::class,
        'store',
        \App\Http\Requests\ResearchSchemaStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();

    $response = post(route('research-schemas.store'), [
        'name' => $name,
    ]);

    $researchSchemas = ResearchSchema::query()
        ->where('name', $name)
        ->get();
    expect($researchSchemas)->toHaveCount(1);
    $researchSchema = $researchSchemas->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $researchSchema = ResearchSchema::factory()->create();

    $response = get(route('research-schemas.show', $researchSchema));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ResearchSchemaController::class,
        'update',
        \App\Http\Requests\ResearchSchemaUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $researchSchema = ResearchSchema::factory()->create();
    $name = fake()->name();

    $response = put(route('research-schemas.update', $researchSchema), [
        'name' => $name,
    ]);

    $researchSchema->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($researchSchema->name);
});


test('destroy deletes and responds with', function (): void {
    $researchSchema = ResearchSchema::factory()->create();

    $response = delete(route('research-schemas.destroy', $researchSchema));

    $response->assertNoContent();

    assertModelMissing($researchSchema);
});

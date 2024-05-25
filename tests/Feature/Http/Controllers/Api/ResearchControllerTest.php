<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Research;
use App\Models\ResearchSchema;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $research = Research::factory()->count(3)->create();

    $response = get(route('research.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ResearchController::class,
        'store',
        \App\Http\Requests\ResearchStoreRequest::class
    );

test('store saves', function (): void {
    $title = fake()->sentence(4);
    $research_schema = ResearchSchema::factory()->create();

    $response = post(route('research.store'), [
        'title' => $title,
        'research_schema_id' => $research_schema->id,
    ]);

    $research = Research::query()
        ->where('title', $title)
        ->where('research_schema_id', $research_schema->id)
        ->get();
    expect($research)->toHaveCount(1);
    $research = $research->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $research = Research::factory()->create();

    $response = get(route('research.show', $research));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ResearchController::class,
        'update',
        \App\Http\Requests\ResearchUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $research = Research::factory()->create();
    $title = fake()->sentence(4);
    $research_schema = ResearchSchema::factory()->create();

    $response = put(route('research.update', $research), [
        'title' => $title,
        'research_schema_id' => $research_schema->id,
    ]);

    $research->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($title)->toEqual($research->title);
    expect($research_schema->id)->toEqual($research->research_schema_id);
});


test('destroy deletes and responds with', function (): void {
    $research = Research::factory()->create();

    $response = delete(route('research.destroy', $research));

    $response->assertNoContent();

    assertModelMissing($research);
});

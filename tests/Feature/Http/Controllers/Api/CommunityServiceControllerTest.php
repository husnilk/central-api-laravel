<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CommunityService;
use App\Models\CommunityServiceSchema;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $communityServices = CommunityService::factory()->count(3)->create();

    $response = get(route('community-services.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CommunityServiceController::class,
        'store',
        \App\Http\Requests\CommunityServiceStoreRequest::class
    );

test('store saves', function (): void {
    $title = fake()->sentence(4);
    $community_service_schema = CommunityServiceSchema::factory()->create();

    $response = post(route('community-services.store'), [
        'title' => $title,
        'community_service_schema_id' => $community_service_schema->id,
    ]);

    $communityServices = CommunityService::query()
        ->where('title', $title)
        ->where('community_service_schema_id', $community_service_schema->id)
        ->get();
    expect($communityServices)->toHaveCount(1);
    $communityService = $communityServices->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $communityService = CommunityService::factory()->create();

    $response = get(route('community-services.show', $communityService));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CommunityServiceController::class,
        'update',
        \App\Http\Requests\CommunityServiceUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $communityService = CommunityService::factory()->create();
    $title = fake()->sentence(4);
    $community_service_schema = CommunityServiceSchema::factory()->create();

    $response = put(route('community-services.update', $communityService), [
        'title' => $title,
        'community_service_schema_id' => $community_service_schema->id,
    ]);

    $communityService->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($title)->toEqual($communityService->title);
    expect($community_service_schema->id)->toEqual($communityService->community_service_schema_id);
});


test('destroy deletes and responds with', function (): void {
    $communityService = CommunityService::factory()->create();

    $response = delete(route('community-services.destroy', $communityService));

    $response->assertNoContent();

    assertModelMissing($communityService);
});

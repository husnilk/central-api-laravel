<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CommunityService;
use App\Models\CommunityServiceMember;
use App\Models\User;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $communityServiceMembers = CommunityServiceMember::factory()->count(3)->create();

    $response = get(route('community-service-members.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CommunityServiceMemberController::class,
        'store',
        \App\Http\Requests\CommunityServiceMemberStoreRequest::class
    );

test('store saves', function (): void {
    $user = User::factory()->create();
    $community_service = CommunityService::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);

    $response = post(route('community-service-members.store'), [
        'user_id' => $user->id,
        'community_service_id' => $community_service->id,
        'position' => $position,
    ]);

    $communityServiceMembers = CommunityServiceMember::query()
        ->where('user_id', $user->id)
        ->where('community_service_id', $community_service->id)
        ->where('position', $position)
        ->get();
    expect($communityServiceMembers)->toHaveCount(1);
    $communityServiceMember = $communityServiceMembers->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $communityServiceMember = CommunityServiceMember::factory()->create();

    $response = get(route('community-service-members.show', $communityServiceMember));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CommunityServiceMemberController::class,
        'update',
        \App\Http\Requests\CommunityServiceMemberUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $communityServiceMember = CommunityServiceMember::factory()->create();
    $user = User::factory()->create();
    $community_service = CommunityService::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);

    $response = put(route('community-service-members.update', $communityServiceMember), [
        'user_id' => $user->id,
        'community_service_id' => $community_service->id,
        'position' => $position,
    ]);

    $communityServiceMember->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($user->id)->toEqual($communityServiceMember->user_id);
    expect($community_service->id)->toEqual($communityServiceMember->community_service_id);
    expect($position)->toEqual($communityServiceMember->position);
});


test('destroy deletes and responds with', function (): void {
    $communityServiceMember = CommunityServiceMember::factory()->create();

    $response = delete(route('community-service-members.destroy', $communityServiceMember));

    $response->assertNoContent();

    assertModelMissing($communityServiceMember);
});

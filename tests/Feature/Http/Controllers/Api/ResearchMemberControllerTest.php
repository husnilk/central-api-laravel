<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Research;
use App\Models\ResearchMember;
use App\Models\User;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $researchMembers = ResearchMember::factory()->count(3)->create();

    $response = get(route('research-members.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ResearchMemberController::class,
        'store',
        \App\Http\Requests\ResearchMemberStoreRequest::class
    );

test('store saves', function (): void {
    $user = User::factory()->create();
    $research = Research::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);

    $response = post(route('research-members.store'), [
        'user_id' => $user->id,
        'research_id' => $research->id,
        'position' => $position,
    ]);

    $researchMembers = ResearchMember::query()
        ->where('user_id', $user->id)
        ->where('research_id', $research->id)
        ->where('position', $position)
        ->get();
    expect($researchMembers)->toHaveCount(1);
    $researchMember = $researchMembers->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $researchMember = ResearchMember::factory()->create();

    $response = get(route('research-members.show', $researchMember));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ResearchMemberController::class,
        'update',
        \App\Http\Requests\ResearchMemberUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $researchMember = ResearchMember::factory()->create();
    $user = User::factory()->create();
    $research = Research::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);

    $response = put(route('research-members.update', $researchMember), [
        'user_id' => $user->id,
        'research_id' => $research->id,
        'position' => $position,
    ]);

    $researchMember->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($user->id)->toEqual($researchMember->user_id);
    expect($research->id)->toEqual($researchMember->research_id);
    expect($position)->toEqual($researchMember->position);
});


test('destroy deletes and responds with', function (): void {
    $researchMember = ResearchMember::factory()->create();

    $response = delete(route('research-members.destroy', $researchMember));

    $response->assertNoContent();

    assertModelMissing($researchMember);
});

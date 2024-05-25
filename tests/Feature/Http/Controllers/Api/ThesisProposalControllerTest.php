<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\GradedBy;
use App\Models\Thesis;
use App\Models\ThesisProposal;
use App\Models\User;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisProposals = ThesisProposal::factory()->count(3)->create();

    $response = get(route('thesis-proposals.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisProposalController::class,
        'store',
        \App\Http\Requests\ThesisProposalStoreRequest::class
    );

test('store saves', function (): void {
    $thesis = Thesis::factory()->create();
    $datetime = Carbon::parse(fake()->dateTime());
    $graded_by = GradedBy::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $user = User::factory()->create();

    $response = post(route('thesis-proposals.store'), [
        'thesis_id' => $thesis->id,
        'datetime' => $datetime->toDateTimeString(),
        'graded_by' => $graded_by->id,
        'status' => $status,
        'user_id' => $user->id,
    ]);

    $thesisProposals = ThesisProposal::query()
        ->where('thesis_id', $thesis->id)
        ->where('datetime', $datetime)
        ->where('graded_by', $graded_by->id)
        ->where('status', $status)
        ->where('user_id', $user->id)
        ->get();
    expect($thesisProposals)->toHaveCount(1);
    $thesisProposal = $thesisProposals->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisProposal = ThesisProposal::factory()->create();

    $response = get(route('thesis-proposals.show', $thesisProposal));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisProposalController::class,
        'update',
        \App\Http\Requests\ThesisProposalUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisProposal = ThesisProposal::factory()->create();
    $thesis = Thesis::factory()->create();
    $datetime = Carbon::parse(fake()->dateTime());
    $graded_by = GradedBy::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $user = User::factory()->create();

    $response = put(route('thesis-proposals.update', $thesisProposal), [
        'thesis_id' => $thesis->id,
        'datetime' => $datetime->toDateTimeString(),
        'graded_by' => $graded_by->id,
        'status' => $status,
        'user_id' => $user->id,
    ]);

    $thesisProposal->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis->id)->toEqual($thesisProposal->thesis_id);
    expect($datetime)->toEqual($thesisProposal->datetime);
    expect($graded_by->id)->toEqual($thesisProposal->graded_by);
    expect($status)->toEqual($thesisProposal->status);
    expect($user->id)->toEqual($thesisProposal->user_id);
});


test('destroy deletes and responds with', function (): void {
    $thesisProposal = ThesisProposal::factory()->create();

    $response = delete(route('thesis-proposals.destroy', $thesisProposal));

    $response->assertNoContent();

    assertModelMissing($thesisProposal);
});

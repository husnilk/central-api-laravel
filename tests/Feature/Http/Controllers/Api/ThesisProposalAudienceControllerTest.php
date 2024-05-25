<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Student;
use App\Models\ThesisProposal;
use App\Models\ThesisProposalAudience;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisProposalAudiences = ThesisProposalAudience::factory()->count(3)->create();

    $response = get(route('thesis-proposal-audiences.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisProposalAudienceController::class,
        'store',
        \App\Http\Requests\ThesisProposalAudienceStoreRequest::class
    );

test('store saves', function (): void {
    $student = Student::factory()->create();
    $thesis_proposal = ThesisProposal::factory()->create();

    $response = post(route('thesis-proposal-audiences.store'), [
        'student_id' => $student->id,
        'thesis_proposal_id' => $thesis_proposal->id,
    ]);

    $thesisProposalAudiences = ThesisProposalAudience::query()
        ->where('student_id', $student->id)
        ->where('thesis_proposal_id', $thesis_proposal->id)
        ->get();
    expect($thesisProposalAudiences)->toHaveCount(1);
    $thesisProposalAudience = $thesisProposalAudiences->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisProposalAudience = ThesisProposalAudience::factory()->create();

    $response = get(route('thesis-proposal-audiences.show', $thesisProposalAudience));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisProposalAudienceController::class,
        'update',
        \App\Http\Requests\ThesisProposalAudienceUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisProposalAudience = ThesisProposalAudience::factory()->create();
    $student = Student::factory()->create();
    $thesis_proposal = ThesisProposal::factory()->create();

    $response = put(route('thesis-proposal-audiences.update', $thesisProposalAudience), [
        'student_id' => $student->id,
        'thesis_proposal_id' => $thesis_proposal->id,
    ]);

    $thesisProposalAudience->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($student->id)->toEqual($thesisProposalAudience->student_id);
    expect($thesis_proposal->id)->toEqual($thesisProposalAudience->thesis_proposal_id);
});


test('destroy deletes and responds with', function (): void {
    $thesisProposalAudience = ThesisProposalAudience::factory()->create();

    $response = delete(route('thesis-proposal-audiences.destroy', $thesisProposalAudience));

    $response->assertNoContent();

    assertModelMissing($thesisProposalAudience);
});

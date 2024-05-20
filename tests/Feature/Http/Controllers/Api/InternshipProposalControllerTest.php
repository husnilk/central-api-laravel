<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Company;
use App\Models\InternshipCompany;
use App\Models\InternshipProposal;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $internshipProposals = InternshipProposal::factory()->count(3)->create();

    $response = get(route('internship-proposals.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipProposalController::class,
        'store',
        \App\Http\Requests\InternshipProposalStoreRequest::class
    );

test('store saves', function (): void {
    $company = Company::factory()->create();
    $title = fake()->sentence(4);
    $start_at = Carbon::parse(fake()->date());
    $end_at = Carbon::parse(fake()->date());
    $status = fake()->randomElement(/** enum_attributes **/);
    $active = fake()->numberBetween(-10000, 10000);
    $internship_company = InternshipCompany::factory()->create();

    $response = post(route('internship-proposals.store'), [
        'company_id' => $company->id,
        'title' => $title,
        'start_at' => $start_at->toDateString(),
        'end_at' => $end_at->toDateString(),
        'status' => $status,
        'active' => $active,
        'internship_company_id' => $internship_company->id,
    ]);

    $internshipProposals = InternshipProposal::query()
        ->where('company_id', $company->id)
        ->where('title', $title)
        ->where('start_at', $start_at)
        ->where('end_at', $end_at)
        ->where('status', $status)
        ->where('active', $active)
        ->where('internship_company_id', $internship_company->id)
        ->get();
    expect($internshipProposals)->toHaveCount(1);
    $internshipProposal = $internshipProposals->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $internshipProposal = InternshipProposal::factory()->create();

    $response = get(route('internship-proposals.show', $internshipProposal));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipProposalController::class,
        'update',
        \App\Http\Requests\InternshipProposalUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $internshipProposal = InternshipProposal::factory()->create();
    $company = Company::factory()->create();
    $title = fake()->sentence(4);
    $start_at = Carbon::parse(fake()->date());
    $end_at = Carbon::parse(fake()->date());
    $status = fake()->randomElement(/** enum_attributes **/);
    $active = fake()->numberBetween(-10000, 10000);
    $internship_company = InternshipCompany::factory()->create();

    $response = put(route('internship-proposals.update', $internshipProposal), [
        'company_id' => $company->id,
        'title' => $title,
        'start_at' => $start_at->toDateString(),
        'end_at' => $end_at->toDateString(),
        'status' => $status,
        'active' => $active,
        'internship_company_id' => $internship_company->id,
    ]);

    $internshipProposal->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($company->id)->toEqual($internshipProposal->company_id);
    expect($title)->toEqual($internshipProposal->title);
    expect($start_at)->toEqual($internshipProposal->start_at);
    expect($end_at)->toEqual($internshipProposal->end_at);
    expect($status)->toEqual($internshipProposal->status);
    expect($active)->toEqual($internshipProposal->active);
    expect($internship_company->id)->toEqual($internshipProposal->internship_company_id);
});


test('destroy deletes and responds with', function (): void {
    $internshipProposal = InternshipProposal::factory()->create();

    $response = delete(route('internship-proposals.destroy', $internshipProposal));

    $response->assertNoContent();

    assertModelMissing($internshipProposal);
});

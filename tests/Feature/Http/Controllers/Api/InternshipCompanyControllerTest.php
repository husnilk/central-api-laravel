<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\InternshipCompany;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $internshipCompanies = InternshipCompany::factory()->count(3)->create();

    $response = get(route('internship-companies.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipCompanyController::class,
        'store',
        \App\Http\Requests\InternshipCompanyStoreRequest::class
    );

test('store saves', function (): void {
    $name = fake()->name();
    $address = fake()->text();

    $response = post(route('internship-companies.store'), [
        'name' => $name,
        'address' => $address,
    ]);

    $internshipCompanies = InternshipCompany::query()
        ->where('name', $name)
        ->where('address', $address)
        ->get();
    expect($internshipCompanies)->toHaveCount(1);
    $internshipCompany = $internshipCompanies->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $internshipCompany = InternshipCompany::factory()->create();

    $response = get(route('internship-companies.show', $internshipCompany));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipCompanyController::class,
        'update',
        \App\Http\Requests\InternshipCompanyUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $internshipCompany = InternshipCompany::factory()->create();
    $name = fake()->name();
    $address = fake()->text();

    $response = put(route('internship-companies.update', $internshipCompany), [
        'name' => $name,
        'address' => $address,
    ]);

    $internshipCompany->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($name)->toEqual($internshipCompany->name);
    expect($address)->toEqual($internshipCompany->address);
});


test('destroy deletes and responds with', function (): void {
    $internshipCompany = InternshipCompany::factory()->create();

    $response = delete(route('internship-companies.destroy', $internshipCompany));

    $response->assertNoContent();

    assertModelMissing($internshipCompany);
});

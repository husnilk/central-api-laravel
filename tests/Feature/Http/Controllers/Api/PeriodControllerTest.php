<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Period;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $periods = Period::factory()->count(3)->create();

    $response = get(route('periods.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\PeriodController::class,
        'store',
        \App\Http\Requests\PeriodStoreRequest::class
    );

test('store saves', function (): void {
    $year = fake()->numberBetween(-10000, 10000);
    $semester = fake()->numberBetween(-10000, 10000);
    $active = fake()->boolean();

    $response = post(route('periods.store'), [
        'year' => $year,
        'semester' => $semester,
        'active' => $active,
    ]);

    $periods = Period::query()
        ->where('year', $year)
        ->where('semester', $semester)
        ->where('active', $active)
        ->get();
    expect($periods)->toHaveCount(1);
    $period = $periods->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $period = Period::factory()->create();

    $response = get(route('periods.show', $period));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\PeriodController::class,
        'update',
        \App\Http\Requests\PeriodUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $period = Period::factory()->create();
    $year = fake()->numberBetween(-10000, 10000);
    $semester = fake()->numberBetween(-10000, 10000);
    $active = fake()->boolean();

    $response = put(route('periods.update', $period), [
        'year' => $year,
        'semester' => $semester,
        'active' => $active,
    ]);

    $period->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($year)->toEqual($period->year);
    expect($semester)->toEqual($period->semester);
    expect($active)->toEqual($period->active);
});


test('destroy deletes and responds with', function (): void {
    $period = Period::factory()->create();

    $response = delete(route('periods.destroy', $period));

    $response->assertNoContent();

    assertModelMissing($period);
});

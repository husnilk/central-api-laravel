<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Thesis;
use App\Models\ThesisSeminar;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisSeminars = ThesisSeminar::factory()->count(3)->create();

    $response = get(route('thesis-seminars.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisSeminarController::class,
        'store',
        \App\Http\Requests\ThesisSeminarStoreRequest::class
    );

test('store saves', function (): void {
    $thesis = Thesis::factory()->create();
    $method = fake()->numberBetween(-10000, 10000);
    $status = fake()->numberBetween(-10000, 10000);

    $response = post(route('thesis-seminars.store'), [
        'thesis_id' => $thesis->id,
        'method' => $method,
        'status' => $status,
    ]);

    $thesisSeminars = ThesisSeminar::query()
        ->where('thesis_id', $thesis->id)
        ->where('method', $method)
        ->where('status', $status)
        ->get();
    expect($thesisSeminars)->toHaveCount(1);
    $thesisSeminar = $thesisSeminars->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisSeminar = ThesisSeminar::factory()->create();

    $response = get(route('thesis-seminars.show', $thesisSeminar));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisSeminarController::class,
        'update',
        \App\Http\Requests\ThesisSeminarUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisSeminar = ThesisSeminar::factory()->create();
    $thesis = Thesis::factory()->create();
    $method = fake()->numberBetween(-10000, 10000);
    $status = fake()->numberBetween(-10000, 10000);

    $response = put(route('thesis-seminars.update', $thesisSeminar), [
        'thesis_id' => $thesis->id,
        'method' => $method,
        'status' => $status,
    ]);

    $thesisSeminar->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis->id)->toEqual($thesisSeminar->thesis_id);
    expect($method)->toEqual($thesisSeminar->method);
    expect($status)->toEqual($thesisSeminar->status);
});


test('destroy deletes and responds with', function (): void {
    $thesisSeminar = ThesisSeminar::factory()->create();

    $response = delete(route('thesis-seminars.destroy', $thesisSeminar));

    $response->assertNoContent();

    assertModelMissing($thesisSeminar);
});

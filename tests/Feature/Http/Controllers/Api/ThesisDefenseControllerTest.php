<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Thesis;
use App\Models\ThesisDefense;
use App\Models\ThesisRubric;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisDefenses = ThesisDefense::factory()->count(3)->create();

    $response = get(route('thesis-defenses.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisDefenseController::class,
        'store',
        \App\Http\Requests\ThesisDefenseStoreRequest::class
    );

test('store saves', function (): void {
    $thesis = Thesis::factory()->create();
    $thesis_rubric = ThesisRubric::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $registered_at = Carbon::parse(fake()->dateTime());
    $method = fake()->numberBetween(-10000, 10000);
    $room = Room::factory()->create();

    $response = post(route('thesis-defenses.store'), [
        'thesis_id' => $thesis->id,
        'thesis_rubric_id' => $thesis_rubric->id,
        'status' => $status,
        'registered_at' => $registered_at->toDateTimeString(),
        'method' => $method,
        'room_id' => $room->id,
    ]);

    $thesisDefenses = ThesisDefense::query()
        ->where('thesis_id', $thesis->id)
        ->where('thesis_rubric_id', $thesis_rubric->id)
        ->where('status', $status)
        ->where('registered_at', $registered_at)
        ->where('method', $method)
        ->where('room_id', $room->id)
        ->get();
    expect($thesisDefenses)->toHaveCount(1);
    $thesisDefense = $thesisDefenses->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisDefense = ThesisDefense::factory()->create();

    $response = get(route('thesis-defenses.show', $thesisDefense));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisDefenseController::class,
        'update',
        \App\Http\Requests\ThesisDefenseUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisDefense = ThesisDefense::factory()->create();
    $thesis = Thesis::factory()->create();
    $thesis_rubric = ThesisRubric::factory()->create();
    $status = fake()->numberBetween(-10000, 10000);
    $registered_at = Carbon::parse(fake()->dateTime());
    $method = fake()->numberBetween(-10000, 10000);
    $room = Room::factory()->create();

    $response = put(route('thesis-defenses.update', $thesisDefense), [
        'thesis_id' => $thesis->id,
        'thesis_rubric_id' => $thesis_rubric->id,
        'status' => $status,
        'registered_at' => $registered_at->toDateTimeString(),
        'method' => $method,
        'room_id' => $room->id,
    ]);

    $thesisDefense->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis->id)->toEqual($thesisDefense->thesis_id);
    expect($thesis_rubric->id)->toEqual($thesisDefense->thesis_rubric_id);
    expect($status)->toEqual($thesisDefense->status);
    expect($registered_at)->toEqual($thesisDefense->registered_at);
    expect($method)->toEqual($thesisDefense->method);
    expect($room->id)->toEqual($thesisDefense->room_id);
});


test('destroy deletes and responds with', function (): void {
    $thesisDefense = ThesisDefense::factory()->create();

    $response = delete(route('thesis-defenses.destroy', $thesisDefense));

    $response->assertNoContent();

    assertModelMissing($thesisDefense);
});

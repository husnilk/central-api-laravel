<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CreatedBy;
use App\Models\Lecturer;
use App\Models\Thesis;
use App\Models\ThesisSupervisor;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $thesisSupervisors = ThesisSupervisor::factory()->count(3)->create();

    $response = get(route('thesis-supervisors.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisSupervisorController::class,
        'store',
        \App\Http\Requests\ThesisSupervisorStoreRequest::class
    );

test('store saves', function (): void {
    $thesis = Thesis::factory()->create();
    $lecturer = Lecturer::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);
    $status = fake()->numberBetween(-10000, 10000);
    $created_by = CreatedBy::factory()->create();

    $response = post(route('thesis-supervisors.store'), [
        'thesis_id' => $thesis->id,
        'lecturer_id' => $lecturer->id,
        'position' => $position,
        'status' => $status,
        'created_by' => $created_by->id,
    ]);

    $thesisSupervisors = ThesisSupervisor::query()
        ->where('thesis_id', $thesis->id)
        ->where('lecturer_id', $lecturer->id)
        ->where('position', $position)
        ->where('status', $status)
        ->where('created_by', $created_by->id)
        ->get();
    expect($thesisSupervisors)->toHaveCount(1);
    $thesisSupervisor = $thesisSupervisors->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $thesisSupervisor = ThesisSupervisor::factory()->create();

    $response = get(route('thesis-supervisors.show', $thesisSupervisor));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\ThesisSupervisorController::class,
        'update',
        \App\Http\Requests\ThesisSupervisorUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $thesisSupervisor = ThesisSupervisor::factory()->create();
    $thesis = Thesis::factory()->create();
    $lecturer = Lecturer::factory()->create();
    $position = fake()->numberBetween(-10000, 10000);
    $status = fake()->numberBetween(-10000, 10000);
    $created_by = CreatedBy::factory()->create();

    $response = put(route('thesis-supervisors.update', $thesisSupervisor), [
        'thesis_id' => $thesis->id,
        'lecturer_id' => $lecturer->id,
        'position' => $position,
        'status' => $status,
        'created_by' => $created_by->id,
    ]);

    $thesisSupervisor->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($thesis->id)->toEqual($thesisSupervisor->thesis_id);
    expect($lecturer->id)->toEqual($thesisSupervisor->lecturer_id);
    expect($position)->toEqual($thesisSupervisor->position);
    expect($status)->toEqual($thesisSupervisor->status);
    expect($created_by->id)->toEqual($thesisSupervisor->created_by);
});


test('destroy deletes and responds with', function (): void {
    $thesisSupervisor = ThesisSupervisor::factory()->create();

    $response = delete(route('thesis-supervisors.destroy', $thesisSupervisor));

    $response->assertNoContent();

    assertModelMissing($thesisSupervisor);
});

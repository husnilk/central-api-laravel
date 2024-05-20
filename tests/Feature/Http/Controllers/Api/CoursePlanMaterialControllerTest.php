<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CoursePlan;
use App\Models\CoursePlanMaterial;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $coursePlanMaterials = CoursePlanMaterial::factory()->count(3)->create();

    $response = get(route('course-plan-materials.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanMaterialController::class,
        'store',
        \App\Http\Requests\CoursePlanMaterialStoreRequest::class
    );

test('store saves', function (): void {
    $course_plan = CoursePlan::factory()->create();
    $topic = fake()->word();

    $response = post(route('course-plan-materials.store'), [
        'course_plan_id' => $course_plan->id,
        'topic' => $topic,
    ]);

    $coursePlanMaterials = CoursePlanMaterial::query()
        ->where('course_plan_id', $course_plan->id)
        ->where('topic', $topic)
        ->get();
    expect($coursePlanMaterials)->toHaveCount(1);
    $coursePlanMaterial = $coursePlanMaterials->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $coursePlanMaterial = CoursePlanMaterial::factory()->create();

    $response = get(route('course-plan-materials.show', $coursePlanMaterial));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\CoursePlanMaterialController::class,
        'update',
        \App\Http\Requests\CoursePlanMaterialUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $coursePlanMaterial = CoursePlanMaterial::factory()->create();
    $course_plan = CoursePlan::factory()->create();
    $topic = fake()->word();

    $response = put(route('course-plan-materials.update', $coursePlanMaterial), [
        'course_plan_id' => $course_plan->id,
        'topic' => $topic,
    ]);

    $coursePlanMaterial->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($course_plan->id)->toEqual($coursePlanMaterial->course_plan_id);
    expect($topic)->toEqual($coursePlanMaterial->topic);
});


test('destroy deletes and responds with', function (): void {
    $coursePlanMaterial = CoursePlanMaterial::factory()->create();

    $response = delete(route('course-plan-materials.destroy', $coursePlanMaterial));

    $response->assertNoContent();

    assertModelMissing($coursePlanMaterial);
});

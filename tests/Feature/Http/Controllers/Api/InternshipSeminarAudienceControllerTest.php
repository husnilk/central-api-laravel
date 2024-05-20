<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Internship;
use App\Models\InternshipSeminarAudience;
use App\Models\Student;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $internshipSeminarAudiences = InternshipSeminarAudience::factory()->count(3)->create();

    $response = get(route('internship-seminar-audiences.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipSeminarAudienceController::class,
        'store',
        \App\Http\Requests\InternshipSeminarAudienceStoreRequest::class
    );

test('store saves', function (): void {
    $internship = Internship::factory()->create();
    $student = Student::factory()->create();
    $role = fake()->randomElement(/** enum_attributes **/);

    $response = post(route('internship-seminar-audiences.store'), [
        'internship_id' => $internship->id,
        'student_id' => $student->id,
        'role' => $role,
    ]);

    $internshipSeminarAudiences = InternshipSeminarAudience::query()
        ->where('internship_id', $internship->id)
        ->where('student_id', $student->id)
        ->where('role', $role)
        ->get();
    expect($internshipSeminarAudiences)->toHaveCount(1);
    $internshipSeminarAudience = $internshipSeminarAudiences->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $internshipSeminarAudience = InternshipSeminarAudience::factory()->create();

    $response = get(route('internship-seminar-audiences.show', $internshipSeminarAudience));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipSeminarAudienceController::class,
        'update',
        \App\Http\Requests\InternshipSeminarAudienceUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $internshipSeminarAudience = InternshipSeminarAudience::factory()->create();
    $internship = Internship::factory()->create();
    $student = Student::factory()->create();
    $role = fake()->randomElement(/** enum_attributes **/);

    $response = put(route('internship-seminar-audiences.update', $internshipSeminarAudience), [
        'internship_id' => $internship->id,
        'student_id' => $student->id,
        'role' => $role,
    ]);

    $internshipSeminarAudience->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($internship->id)->toEqual($internshipSeminarAudience->internship_id);
    expect($student->id)->toEqual($internshipSeminarAudience->student_id);
    expect($role)->toEqual($internshipSeminarAudience->role);
});


test('destroy deletes and responds with', function (): void {
    $internshipSeminarAudience = InternshipSeminarAudience::factory()->create();

    $response = delete(route('internship-seminar-audiences.destroy', $internshipSeminarAudience));

    $response->assertNoContent();

    assertModelMissing($internshipSeminarAudience);
});

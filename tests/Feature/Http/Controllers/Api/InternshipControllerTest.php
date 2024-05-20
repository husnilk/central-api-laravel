<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Internship;
use App\Models\InternshipProposal;
use App\Models\Lecturer;
use App\Models\SeminarRoom;
use App\Models\Student;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index behaves as expected', function (): void {
    $internships = Internship::factory()->count(3)->create();

    $response = get(route('internships.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipController::class,
        'store',
        \App\Http\Requests\InternshipStoreRequest::class
    );

test('store saves', function (): void {
    $internship_proposal = InternshipProposal::factory()->create();
    $student = Student::factory()->create();
    $status = fake()->randomElement(/** enum_attributes **/);
    $start_at = Carbon::parse(fake()->date());
    $seminar_room = SeminarRoom::factory()->create();
    $lecturer = Lecturer::factory()->create();

    $response = post(route('internships.store'), [
        'internship_proposal_id' => $internship_proposal->id,
        'student_id' => $student->id,
        'status' => $status,
        'start_at' => $start_at->toDateString(),
        'seminar_room_id' => $seminar_room->id,
        'lecturer_id' => $lecturer->id,
    ]);

    $internships = Internship::query()
        ->where('internship_proposal_id', $internship_proposal->id)
        ->where('student_id', $student->id)
        ->where('status', $status)
        ->where('start_at', $start_at)
        ->where('seminar_room_id', $seminar_room->id)
        ->where('lecturer_id', $lecturer->id)
        ->get();
    expect($internships)->toHaveCount(1);
    $internship = $internships->first();

    $response->assertCreated();
    $response->assertJsonStructure([]);
});


test('show behaves as expected', function (): void {
    $internship = Internship::factory()->create();

    $response = get(route('internships.show', $internship));

    $response->assertOk();
    $response->assertJsonStructure([]);
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\Api\InternshipController::class,
        'update',
        \App\Http\Requests\InternshipUpdateRequest::class
    );

test('update behaves as expected', function (): void {
    $internship = Internship::factory()->create();
    $internship_proposal = InternshipProposal::factory()->create();
    $student = Student::factory()->create();
    $status = fake()->randomElement(/** enum_attributes **/);
    $start_at = Carbon::parse(fake()->date());
    $seminar_room = SeminarRoom::factory()->create();
    $lecturer = Lecturer::factory()->create();

    $response = put(route('internships.update', $internship), [
        'internship_proposal_id' => $internship_proposal->id,
        'student_id' => $student->id,
        'status' => $status,
        'start_at' => $start_at->toDateString(),
        'seminar_room_id' => $seminar_room->id,
        'lecturer_id' => $lecturer->id,
    ]);

    $internship->refresh();

    $response->assertOk();
    $response->assertJsonStructure([]);

    expect($internship_proposal->id)->toEqual($internship->internship_proposal_id);
    expect($student->id)->toEqual($internship->student_id);
    expect($status)->toEqual($internship->status);
    expect($start_at)->toEqual($internship->start_at);
    expect($seminar_room->id)->toEqual($internship->seminar_room_id);
    expect($lecturer->id)->toEqual($internship->lecturer_id);
});


test('destroy deletes and responds with', function (): void {
    $internship = Internship::factory()->create();

    $response = delete(route('internships.destroy', $internship));

    $response->assertNoContent();

    assertModelMissing($internship);
});

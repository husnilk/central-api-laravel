<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassCourse extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meeting_verified' => 'boolean',
        'period_id' => 'integer',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function coursePlan(): BelongsTo
    {
        return $this->belongsTo(CoursePlan::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function classClecturers(): HasMany
    {
        return $this->hasMany(ClassClecturer::class);
    }

    public function studyPlanDetails(): HasMany
    {
        return $this->hasMany(StudyPlanDetail::class);
    }

    public function classMeetings(): HasMany
    {
        return $this->hasMany(ClassMeeting::class);
    }
}

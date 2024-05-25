<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassAttendance extends Model
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
        'lattitude' => 'double',
        'longitude' => 'double',
    ];

    public function studyPlanDetail(): BelongsTo
    {
        return $this->belongsTo(StudyPlanDetail::class);
    }

    public function classMeeting(): BelongsTo
    {
        return $this->belongsTo(ClassMeeting::class);
    }
}

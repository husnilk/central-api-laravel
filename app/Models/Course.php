<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function coursePlans(): HasMany
    {
        return $this->hasMany(CoursePlan::class);
    }

    public function coursePlanRequirements(): HasMany
    {
        return $this->hasMany(CoursePlanRequirement::class);
    }

    public function classCourses(): HasMany
    {
        return $this->hasMany(ClassCourse::class);
    }

    public function curriculumIndicatorCourses(): HasMany
    {
        return $this->hasMany(CurriculumIndicatorCourse::class);
    }
}

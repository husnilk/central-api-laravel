<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thesis extends Model
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
        'start_at' => 'date',
        'grade_by' => 'integer',
        'thesis_topic_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function thesisTopic(): BelongsTo
    {
        return $this->belongsTo(ThesisTopic::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ThesisTopic::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thesisProposals(): HasMany
    {
        return $this->hasMany(ThesisProposal::class);
    }

    public function thesisSupervisors(): HasMany
    {
        return $this->hasMany(ThesisSupervisor::class);
    }

    public function thesisLogbooks(): HasMany
    {
        return $this->hasMany(ThesisLogbook::class);
    }

    public function thesisSeminars(): HasMany
    {
        return $this->hasMany(ThesisSeminar::class);
    }

    public function thesisTrials(): HasMany
    {
        return $this->hasMany(ThesisTrial::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveClass extends Model
{
    protected $fillable = [
        'title',
        'description',
        'course_id',
        'instructor_id',
        'meeting_id',
        'start_time',
        'end_time',
        'status',
        'is_recorded',
        'recording_url',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_recorded' => 'boolean',
    ];

    /**
     * Get the course that owns the live class.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the instructor that owns the live class.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the enrollments for the live class.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(LiveClassEnrollment::class);
    }

    /**
     * Check if the live class is currently live.
     */
    public function isLive(): bool
    {
        return $this->status === 'live' || 
               ($this->start_time->isPast() && $this->end_time->isFuture());
    }

    /**
     * Check if the live class is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->status === 'scheduled' && $this->start_time->isFuture();
    }

    /**
     * Check if the live class is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed' || $this->end_time->isPast();
    }
}

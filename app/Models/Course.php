<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'is_published',
        'hero_image_url',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the route key name for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the lessons for the course.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('display_order');
    }

    /**
     * Get the published lessons for the course.
     */
    public function publishedLessons(): HasMany
    {
        return $this->hasMany(Lesson::class)
            ->where('is_published', true)
            ->orderBy('display_order');
    }

    /**
     * Get the free preview lessons for the course.
     */
    public function freePreviewLessons(): HasMany
    {
        return $this->hasMany(Lesson::class)
            ->where('is_published', true)
            ->where('is_free_preview', true)
            ->orderBy('display_order');
    }

    /**
     * Scope a query to only include published courses.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * Scope a query to order courses by display order.
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('display_order');
    }

    /**
     * Get the total duration of the course in seconds.
     */
    public function getTotalDurationSeconds(): int
    {
        return $this->publishedLessons()->sum('duration_seconds');
    }

    /**
     * Get the total duration of the course in a human-readable format.
     */
    public function getTotalDurationFormatted(): string
    {
        $totalSeconds = $this->getTotalDurationSeconds();
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }

    /**
     * Get the total number of lessons in the course.
     */
    public function getTotalLessonsCount(): int
    {
        return $this->publishedLessons()->count();
    }

    /**
     * Check if the course has any free preview lessons.
     */
    public function hasFreePreview(): bool
    {
        return $this->freePreviewLessons()->exists();
    }

    /**
     * Get the first lesson of the course.
     */
    public function getFirstLesson()
    {
        return $this->publishedLessons()->first();
    }

    /**
     * Get the course URL.
     */
    public function getUrlAttribute(): string
    {
        return route('courses.show', $this->slug);
    }
}

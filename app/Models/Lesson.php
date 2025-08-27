<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Lesson extends Model
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
        'content',
        'youtube_url',
        'youtube_id',
        'google_drive_url',
        'video_type', // 'youtube' or 'google_drive'
        'duration_seconds',
        'display_order',
        'is_free_preview',
        'is_published',
        'course_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_free_preview' => 'boolean',
        'is_published' => 'boolean',
        'duration_seconds' => 'integer',
        'display_order' => 'integer',
    ];

    /**
     * Get the course that owns the lesson.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope a query to only include published lessons.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * Scope a query to only include free preview lessons.
     */
    public function scopeFreePreview(Builder $query): void
    {
        $query->where('is_free_preview', true);
    }

    /**
     * Scope a query to order lessons by display order.
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('display_order');
    }

    /**
     * Get the duration of the lesson in a human-readable format.
     */
    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration_seconds) {
            return 'N/A';
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Get the video embed URL based on video type.
     */
    public function getVideoEmbedUrlAttribute(): string
    {
        if ($this->video_type === 'google_drive' && $this->google_drive_url) {
            return $this->getGoogleDriveEmbedUrl();
        }
        
        return $this->getYoutubeEmbedUrl();
    }

    /**
     * Get the YouTube embed URL.
     */
    public function getYoutubeEmbedUrlAttribute(): string
    {
        if (!$this->youtube_id) {
            return '';
        }
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }

    /**
     * Get the Google Drive embed URL.
     */
    public function getGoogleDriveEmbedUrlAttribute(): string
    {
        if (!$this->google_drive_url) {
            return '';
        }
        
        // Convert Google Drive sharing URL to embed URL
        $driveId = $this->extractGoogleDriveId($this->google_drive_url);
        if ($driveId) {
            return "https://drive.google.com/file/d/{$driveId}/preview";
        }
        
        return '';
    }

    /**
     * Get the video thumbnail URL.
     */
    public function getVideoThumbnailAttribute(): string
    {
        if ($this->video_type === 'google_drive') {
            return $this->getGoogleDriveThumbnail();
        }
        
        return $this->getYoutubeThumbnail();
    }

    /**
     * Get the YouTube thumbnail URL.
     */
    public function getYoutubeThumbnailAttribute(): string
    {
        if (!$this->youtube_id) {
            return '';
        }
        return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
    }

    /**
     * Get the YouTube thumbnail URL (medium size).
     */
    public function getYoutubeThumbnailMediumAttribute(): string
    {
        if (!$this->youtube_id) {
            return '';
        }
        return "https://img.youtube.com/vi/{$this->youtube_id}/mqdefault.jpg";
    }

    /**
     * Get the Google Drive thumbnail URL.
     */
    public function getGoogleDriveThumbnailAttribute(): string
    {
        if (!$this->google_drive_url) {
            return '';
        }
        
        // Google Drive doesn't provide direct thumbnails, so we'll use a default
        return asset('images/google-drive-video-thumbnail.jpg');
    }

    /**
     * Get the lesson URL.
     */
    public function getUrlAttribute(): string
    {
        return route('courses.lessons.show', [$this->course->slug, $this->slug]);
    }

    /**
     * Check if this is the first lesson in the course.
     */
    public function isFirstLesson(): bool
    {
        return $this->display_order === 1;
    }

    /**
     * Check if this is the last lesson in the course.
     */
    public function isLastLesson(): bool
    {
        $maxOrder = $this->course->publishedLessons()->max('display_order');
        return $this->display_order === $maxOrder;
    }

    /**
     * Get the next lesson in the course.
     */
    public function getNextLesson()
    {
        return $this->course->publishedLessons()
            ->where('display_order', '>', $this->display_order)
            ->orderBy('display_order')
            ->first();
    }

    /**
     * Get the previous lesson in the course.
     */
    public function getPreviousLesson()
    {
        return $this->course->publishedLessons()
            ->where('display_order', '<', $this->display_order)
            ->orderBy('display_order', 'desc')
            ->first();
    }

    /**
     * Extract YouTube ID from URL.
     */
    public static function extractYoutubeId(string $url): ?string
    {
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Extract Google Drive ID from URL.
     */
    public static function extractGoogleDriveId(string $url): ?string
    {
        $patterns = [
            '/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/',
            '/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/',
            '/drive\.google\.com\/uc\?id=([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Determine video type from URL.
     */
    public static function determineVideoType(string $url): string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return 'youtube';
        }
        
        if (str_contains($url, 'drive.google.com')) {
            return 'google_drive';
        }
        
        return 'unknown';
    }

    /**
     * Check if lesson has a valid video.
     */
    public function hasVideo(): bool
    {
        if ($this->video_type === 'youtube') {
            return !empty($this->youtube_url) && !empty($this->youtube_id);
        }
        
        if ($this->video_type === 'google_drive') {
            return !empty($this->google_drive_url);
        }
        
        return false;
    }

    /**
     * Get video source for display.
     */
    public function getVideoSourceAttribute(): string
    {
        if ($this->video_type === 'google_drive') {
            return 'Google Drive';
        }
        
        return 'YouTube';
    }
}

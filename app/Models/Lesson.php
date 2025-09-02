<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
    'local_filename',
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
     * The attributes that should be appended to arrays.
     */
    protected $appends = [
        'has_valid_video'
    ];

    /**
     * Get the route key name for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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
     * Get the video embed URL (Google Drive only).
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if ($this->video_type === 'youtube' && !empty($this->youtube_url)) {
            return $this->getYouTubeEmbedUrl();
        } elseif ($this->video_type === 'google_drive' && !empty($this->google_drive_url)) {
            return $this->getGoogleDriveEmbedUrlWithFallback($this->getGoogleDriveId());
        } elseif ($this->video_type === 'local' && !empty($this->local_filename)) {
            // Local files are served via signed streaming route; URL is generated in controller/view
            return null;
        }
        
        return null;
    }

    private function getYouTubeEmbedUrl(): string
    {
        $videoId = $this->getYouTubeId();
        if (!$videoId) {
            return '';
        }
        
        // Build YouTube embed URL with security parameters
        $params = [
            'modestbranding' => '1',
            'rel' => '0',
            'playsinline' => '1',
            'disablekb' => '1',
            'controls' => '1'
        ];
        
        $queryString = http_build_query($params);
        return "https://www.youtube.com/embed/{$videoId}?{$queryString}";
    }

    public function getYouTubeId(): ?string
    {
        if (empty($this->youtube_url)) {
            return null;
        }

        // Handle various YouTube URL formats
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]+)/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->youtube_url, $matches)) {
                return $matches[1];
            }
        }

        return null;
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
            // Try multiple embed formats for better compatibility
            return $this->getGoogleDriveEmbedUrlWithFallback($driveId);
        }
        
        return '';
    }

    /**
     * Get Google Drive embed URL with restricted parameters.
     */
    private function getGoogleDriveEmbedUrlWithFallback(string $driveId): string
    {
        // Use the standard working Google Drive embed format
        // This format is known to work and display videos properly
        return "https://drive.google.com/file/d/{$driveId}/preview";
    }

    /**
     * Get the video thumbnail URL (Google Drive only).
     */
    public function getVideoThumbnailAttribute(): string
    {
        return $this->getGoogleDriveThumbnail();
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
            '/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)\/view\?usp=([a-zA-Z0-9_-]+)/',
            '/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)\/view\?usp=drive_link/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Determine video type from URL (Google Drive only).
     */
    public static function determineVideoType(string $url): string
    {
        if (str_contains($url, 'drive.google.com')) {
            return 'google_drive';
        }
        
        return 'unknown';
    }

    /**
     * Check if the lesson has a valid video (computed attribute)
     */
    protected function hasValidVideo(): Attribute
    {
        return Attribute::make(
            get: fn () => (bool) (
                // Accept if we have a YouTube ID (stored) or can extract one from the URL,
                // or if a Google Drive URL or direct mp4 URL exists.
                !empty($this->youtube_id) ||
                (method_exists($this, 'getYouTubeId') && !empty($this->getYouTubeId())) ||
                !empty($this->google_drive_url) ||
                !empty($this->mp4_url) ||
                (!empty($this->local_filename) && $this->video_type === 'local')
            )
        );
    }

    /**
     * Get video source for display.
     */
    public function getVideoSourceAttribute(): string
    {
        return 'Google Drive';
    }

    /**
     * Get secure YouTube embed URL with restricted parameters.
     */
    public function getSecureYoutubeEmbedUrlAttribute(): string
    {
        if (!$this->youtube_id) {
            return '';
        }
        
        // Build secure embed URL with restricted parameters
        $params = [
            'rel' => '0',                    // No related videos
            'modestbranding' => '1',         // Minimal YouTube branding
            'controls' => '1',               // Show player controls
            'disablekb' => '1',              // Disable keyboard controls
            'fs' => '1',                     // Allow fullscreen
            'iv_load_policy' => '3',         // Hide video annotations
            'cc_load_policy' => '0',         // Hide closed captions by default
            'showinfo' => '0',               // Hide video title and uploader info
            'color' => 'white',              // White player color
            'playsinline' => '1',            // Play inline on mobile
        ];
        
        $queryString = http_build_query($params);
        return "https://www.youtube.com/embed/{$this->youtube_id}?{$queryString}";
    }

    /**
     * Get YouTube video duration in seconds (if available).
     */
    public function getYoutubeDurationAttribute(): ?int
    {
        // This would require YouTube API integration for actual duration
        // For now, return the stored duration_seconds
        return $this->duration_seconds;
    }

    /**
     * Get YouTube video privacy status (assumed unlisted for security).
     */
    public function getYoutubePrivacyStatusAttribute(): string
    {
        // This would require YouTube API integration
        // For security, assume all videos are unlisted
        return 'unlisted';
    }

    /**
     * Check if video is securely configured.
     */
    public function isSecurelyConfigured(): bool
    {
        if ($this->video_type === 'youtube') {
            // Ensure YouTube video is properly configured
            return !empty($this->youtube_id) && !empty($this->youtube_url);
        }
        
        if ($this->video_type === 'google_drive') {
            // Ensure Google Drive video is properly configured
            return !empty($this->google_drive_url);
        }
        
        return false;
    }

    /**
     * Get security recommendations for this lesson.
     */
    public function getSecurityRecommendations(): array
    {
        $recommendations = [];
        
        if ($this->video_type === 'youtube') {
            if (empty($this->youtube_id)) {
                $recommendations[] = 'YouTube video ID not properly extracted';
            }
            
            if (str_contains($this->youtube_url ?? '', 'youtube.com/watch')) {
                $recommendations[] = 'Consider using unlisted YouTube videos for better security';
            }
        }
        
        if ($this->video_type === 'google_drive') {
            if (empty($this->google_drive_url)) {
                $recommendations[] = 'Google Drive URL not properly configured';
            }
            
            $recommendations[] = 'Ensure Google Drive sharing is set to "Anyone with the link can view"';
        }
        
        return $recommendations;
    }
}

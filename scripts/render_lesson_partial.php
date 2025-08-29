<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;
use App\Models\Lesson;

$course = Course::where('slug', 'test-course')->first();
$lesson = Lesson::where('slug', '1-pengenalan')->first();
if (!$course || !$lesson) {
    echo "NOTFOUND\n";
    exit(0);
}

$course->load(['publishedLessons' => function ($query) {
    $query->where('is_published', true)->orderBy('display_order');
}]);

$currentLessonIndex = $course->publishedLessons->search(function ($item) use ($lesson) {
    return $item->id === $lesson->id;
});

$nextLesson = null;
$previousLesson = null;
if ($currentLessonIndex !== false) {
    if ($currentLessonIndex < $course->publishedLessons->count() - 1) {
        $nextLesson = $course->publishedLessons[$currentLessonIndex + 1];
    }
    if ($currentLessonIndex > 0) {
        $previousLesson = $course->publishedLessons[$currentLessonIndex - 1];
    }
}

$totalLessons = $course->publishedLessons->count();
$currentPosition = $currentLessonIndex !== false ? $currentLessonIndex + 1 : 0;
$progressPercentage = $totalLessons > 0 ? round(($currentPosition / $totalLessons) * 100) : 0;

// Render the inner content manually
$view = <<<'BLADE'

    <!-- Video Player Section - Outside the card for larger display -->
    @if($lesson->has_valid_video)
        <section class="lesson-player-shell video-iphone-safe">
            <div class="lesson-player-wrap">
                @if($lesson->video_type === 'youtube')
                    <x-youtube-protected 
                        playerId="lesson-{{ $lesson->id }}" 
                        videoId="{{ $lesson->getYouTubeId() }}" 
                        title="{{ $lesson->title }}" 
                    />
                @elseif($lesson->video_type === 'google_drive')
                    <x-video-protected 
                        videoUrl="{{ $lesson->video_embed_url }}" 
                        title="{{ $lesson->title }}" 
                    />
                @endif
                
                <div class="lesson-player-tip" role="note" aria-label="Fullscreen tip">
                    <span class="tip-dot" aria-hidden="true"></span>
                    <div class="flex-1">
                        <strong class="text-gray-800">💡 Pro Tip:</strong> 
                        <span class="text-gray-700">For the best fullscreen experience, use Google Chrome or a desktop PC.</span>
                    </div>
                </div>
            </div>
        </section>
    @endif

BLADE;

echo view(['template' => $view], compact('course','lesson','nextLesson','previousLesson','progressPercentage'))->render();

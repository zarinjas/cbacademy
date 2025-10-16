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

echo view('lessons.show', compact('course', 'lesson', 'nextLesson', 'previousLesson', 'progressPercentage'))->render();

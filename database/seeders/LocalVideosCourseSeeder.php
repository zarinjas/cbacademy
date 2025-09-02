<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lesson;

class LocalVideosCourseSeeder extends Seeder
{
    public function run()
    {
        // Create demo course
        $course = Course::create([
            'title' => 'Demo Local Videos Course',
            'slug' => 'demo-local-videos',
            'description' => 'A demo course with local video files for testing.',
            'is_published' => true,
        ]);

        // Create 7 lessons each pointing to 1.mp4 .. 7.mp4
        for ($i = 1; $i <= 7; $i++) {
            Lesson::create([
                'course_id' => $course->id,
                'title' => "Lesson {$i}",
                'slug' => "lesson-{$i}",
                'content' => "This lesson plays local file {$i}.mp4",
                'video_type' => 'local',
                'local_filename' => "{$i}.mp4",
                'duration_seconds' => null,
                'display_order' => $i,
                'is_published' => true,
            ]);
        }
    }
}

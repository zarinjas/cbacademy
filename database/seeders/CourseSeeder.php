<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo cooking course
        $course = Course::create([
            'title' => 'Master the Art of Malaysian Cuisine',
            'slug' => 'master-malaysian-cuisine',
            'description' => 'Learn the secrets of authentic Malaysian cooking with this comprehensive course. From traditional street food to sophisticated restaurant dishes, master the techniques, spices, and methods that make Malaysian cuisine unique and delicious.',
            'is_published' => true,
            'hero_image_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop&crop=center',
            'display_order' => 1,
        ]);

        // Create 10 lessons for the course
        $lessons = [
            [
                'title' => 'Introduction to Malaysian Spices and Ingredients',
                'slug' => 'introduction-malaysian-spices',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder
                'youtube_id' => 'dQw4w9WgXcQ',
                'duration_seconds' => 1800, // 30 minutes
                'display_order' => 1,
                'is_free_preview' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Perfect Rice Cooking Techniques',
                'slug' => 'perfect-rice-cooking-techniques',
                'youtube_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0', // Placeholder
                'youtube_id' => '9bZkp7q19f0',
                'duration_seconds' => 1200, // 20 minutes
                'display_order' => 2,
                'is_free_preview' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Making Authentic Curry Paste from Scratch',
                'slug' => 'authentic-curry-paste-scratch',
                'youtube_url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk', // Placeholder
                'youtube_id' => 'kJQP7kiw5Fk',
                'duration_seconds' => 2400, // 40 minutes
                'display_order' => 3,
                'is_free_preview' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Nasi Lemak: Malaysia\'s National Dish',
                'slug' => 'nasi-lemak-national-dish',
                'youtube_url' => 'https://www.youtube.com/watch?v=ZZ5LpwO-An4', // Placeholder
                'youtube_id' => 'ZZ5LpwO-An4',
                'duration_seconds' => 2700, // 45 minutes
                'display_order' => 4,
                'is_free_preview' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Char Kway Teow: Wok Mastery',
                'slug' => 'char-kway-teow-wok-mastery',
                'youtube_url' => 'https://www.youtube.com/watch?v=oHg5SJYRHA0', // Placeholder
                'youtube_id' => 'oHg5SJYRHA0',
                'duration_seconds' => 2100, // 35 minutes
                'display_order' => 5,
                'is_free_preview' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Rendang: The Art of Slow Cooking',
                'slug' => 'rendang-art-slow-cooking',
                'youtube_url' => 'https://www.youtube.com/watch?v=ZZ5LpwO-An4', // Placeholder
                'youtube_id' => 'ZZ5LpwO-An4',
                'duration_seconds' => 3600, // 60 minutes
                'display_order' => 6,
                'is_free_preview' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Satay: Grilling and Peanut Sauce',
                'slug' => 'satay-grilling-peanut-sauce',
                'youtube_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0', // Placeholder
                'youtube_id' => '9bZkp7q19f0',
                'duration_seconds' => 1950, // 32.5 minutes
                'display_order' => 7,
                'is_free_preview' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Laksa: Complex Broth Building',
                'slug' => 'laksa-complex-broth-building',
                'youtube_url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk', // Placeholder
                'youtube_id' => 'kJQP7kiw5Fk',
                'duration_seconds' => 3300, // 55 minutes
                'display_order' => 8,
                'is_free_preview' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Desserts: Kuih and Sweet Treats',
                'slug' => 'desserts-kuih-sweet-treats',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder
                'youtube_id' => 'dQw4w9WgXcQ',
                'duration_seconds' => 1500, // 25 minutes
                'display_order' => 9,
                'is_free_preview' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Advanced Plating and Presentation',
                'slug' => 'advanced-plating-presentation',
                'youtube_url' => 'https://www.youtube.com/watch?v=oHg5SJYRHA0', // Placeholder
                'youtube_id' => 'oHg5SJYRHA0',
                'duration_seconds' => 1800, // 30 minutes
                'display_order' => 10,
                'is_free_preview' => false,
                'is_published' => true,
            ],
        ];

        // Create lessons for the course
        foreach ($lessons as $lessonData) {
            $course->lessons()->create($lessonData);
        }

        // Create a second course (unpublished for admin testing)
        $course2 = Course::create([
            'title' => 'Advanced Southeast Asian Techniques',
            'slug' => 'advanced-southeast-asian-techniques',
            'description' => 'Take your cooking skills to the next level with advanced techniques from across Southeast Asia. This course covers complex preparations, advanced knife skills, and sophisticated flavor combinations.',
            'is_published' => false,
            'hero_image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop&crop=center',
            'display_order' => 2,
        ]);

        // Create a few lessons for the unpublished course
        $course2->lessons()->create([
            'title' => 'Advanced Knife Skills and Techniques',
            'slug' => 'advanced-knife-skills',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 2400,
            'display_order' => 1,
            'is_free_preview' => false,
            'is_published' => false,
        ]);

        $this->command->info('Demo courses and lessons created successfully!');
        $this->command->info("Created course: {$course->title} with {$course->lessons()->count()} lessons");
        $this->command->info("Created unpublished course: {$course2->title} for admin testing");
    }
}

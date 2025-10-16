<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(4);
        
        // Generate a realistic YouTube video ID (11 characters)
        $youtubeId = $this->faker->regexify('[A-Za-z0-9_-]{11}');
        $youtubeUrl = "https://www.youtube.com/watch?v={$youtubeId}";
        
        return [
            'course_id' => Course::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'youtube_url' => $youtubeUrl,
            'youtube_id' => $youtubeId,
            'duration_seconds' => $this->faker->numberBetween(300, 3600), // 5-60 minutes
            'display_order' => $this->faker->numberBetween(1, 50),
            'is_free_preview' => $this->faker->boolean(20), // 20% chance of being free preview
            'is_published' => $this->faker->boolean(90), // 90% chance of being published
        ];
    }

    /**
     * Indicate that the lesson is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    /**
     * Indicate that the lesson is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }

    /**
     * Indicate that the lesson is a free preview.
     */
    public function freePreview(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free_preview' => true,
            'is_published' => true,
        ]);
    }

    /**
     * Indicate that the lesson is not a free preview.
     */
    public function notFreePreview(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free_preview' => false,
        ]);
    }

    /**
     * Create a lesson with a specific course.
     */
    public function forCourse(Course $course): static
    {
        return $this->state(fn (array $attributes) => [
            'course_id' => $course->id,
        ]);
    }
}

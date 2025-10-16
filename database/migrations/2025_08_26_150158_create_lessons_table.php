<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->string('youtube_url');
            $table->string('youtube_id');
            $table->integer('duration_seconds')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_free_preview')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            
            // Unique constraint per course
            $table->unique(['course_id', 'slug']);
            
            // Unique constraint for display order per course
            $table->unique(['course_id', 'display_order']);
            
            // Indexes for performance
            $table->index('youtube_id');
            $table->index('is_published');
            $table->index('is_free_preview');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};

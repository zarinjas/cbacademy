<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // add only if missing
            if (!Schema::hasColumn('lessons', 'google_drive_url')) {
                $table->string('google_drive_url')->nullable()->after('youtube_id');
            }

            if (!Schema::hasColumn('lessons', 'video_type')) {
                $table->enum('video_type', ['youtube', 'google_drive'])
                      ->default('youtube')
                      ->after('google_drive_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'video_type')) {
                $table->dropColumn('video_type');
            }
            if (Schema::hasColumn('lessons', 'google_drive_url')) {
                $table->dropColumn('google_drive_url');
            }
        });
    }
};

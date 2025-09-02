<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add local_filename and make youtube columns nullable; extend enum
        Schema::table('lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('lessons', 'local_filename')) {
                $table->string('local_filename')->nullable()->after('google_drive_url');
            }
        });

        // Make youtube_url and youtube_id nullable
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('youtube_url')->nullable()->change();
            $table->string('youtube_id')->nullable()->change();
        });

        // Update enum to include 'local' if using MySQL/Postgres; use raw SQL to alter
        // This migration attempts to support common DBs; if your DB doesn't support
        // modifying enums via ALTER TYPE, adjust accordingly.
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE lessons MODIFY COLUMN video_type ENUM('youtube','google_drive','local') NOT NULL DEFAULT 'youtube'");
        } elseif ($driver === 'pgsql') {
            // For postgres we create a new type, alter, then drop old
            DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'video_type_enum_new') THEN CREATE TYPE video_type_enum_new AS ENUM ('youtube','google_drive','local'); END IF; END$$;");
            DB::statement("ALTER TABLE lessons ALTER COLUMN video_type TYPE video_type_enum_new USING video_type::text::video_type_enum_new");
        } else {
            // sqlite and others: leave as-is; validation will accept 'local' and new rows will work
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'local_filename')) {
                $table->dropColumn('local_filename');
            }
        });

        // Revert youtube columns to not null if desired (skip here to be safe)
    }
};

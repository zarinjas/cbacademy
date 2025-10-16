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
        Schema::table('users', function (Blueprint $table) {
            // Remove the old is_admin field
            $table->dropColumn('is_admin');
            
            // Add the new role enum field
            $table->enum('role', ['admin', 'learner'])->default('learner')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the role field
            $table->dropColumn('role');
            
            // Add back the is_admin field
            $table->boolean('is_admin')->default(false)->after('email');
        });
    }
};

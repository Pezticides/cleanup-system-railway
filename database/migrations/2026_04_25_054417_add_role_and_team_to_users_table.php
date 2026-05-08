<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('citizen');

            $table->foreignId('clean_up_team_id')
                  ->nullable()
                  ->constrained('clean_up_teams')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['clean_up_team_id']);
            $table->dropColumn(['role', 'clean_up_team_id']);
        });
    }
};
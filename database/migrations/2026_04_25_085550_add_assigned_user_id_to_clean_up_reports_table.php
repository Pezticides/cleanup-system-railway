<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clean_up_reports', function (Blueprint $table) {
            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clean_up_reports', function (Blueprint $table) {
            $table->dropForeign(['assigned_user_id']);
            $table->dropColumn('assigned_user_id');
        });
    }
};
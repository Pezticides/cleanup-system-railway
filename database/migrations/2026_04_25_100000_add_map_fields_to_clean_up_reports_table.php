<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clean_up_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('clean_up_reports', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('location');
            }
            if (!Schema::hasColumn('clean_up_reports', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clean_up_reports', function (Blueprint $table) {
            if (Schema::hasColumn('clean_up_reports', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('clean_up_reports', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('conversations', function (Blueprint $table) { $table->id(); $table->string('name')->nullable(); $table->boolean('is_group')->default(false); $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamps(); });
        Schema::create('conversation_user', function (Blueprint $table) { $table->id(); $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete(); $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); $table->timestamps(); $table->unique(['conversation_id', 'user_id']); });
    }
    public function down(): void { Schema::dropIfExists('conversation_user'); Schema::dropIfExists('conversations'); }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->json('target_roles');  // ['all'] or ['siswa','guru','ortu',...]
            $table->enum('priority', ['normal', 'penting', 'urgent'])->default('normal');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_published', 'published_at', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image_path')->nullable();
            $table->string('canonical_url')->nullable();
            $table->text('robots_txt')->nullable();
            $table->timestamps();
        });

        Schema::create('url_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('source_url')->unique();
            $table->string('target_url');
            $table->integer('status_code')->default(301); // 301 or 302
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('url_redirects');
        Schema::dropIfExists('seo_settings');
    }
};

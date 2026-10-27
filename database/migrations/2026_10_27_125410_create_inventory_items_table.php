<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('inventory_category_id')->nullable()->constrained('inventory_categories')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('unit'); // unit, set, buah, paket, dll.
            $table->enum('condition', ['good', 'light_damage', 'heavy_damage', 'lost'])->default('good');
            $table->string('source_of_fund')->nullable(); // Dana BOS, BOP, Komite, Hibah, dll.
            $table->year('procurement_year')->nullable();
            $table->decimal('price_per_unit', 15, 2)->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};

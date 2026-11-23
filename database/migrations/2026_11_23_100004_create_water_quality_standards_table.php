<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_quality_standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fish_id')->constrained('fishes')->cascadeOnDelete();
            $table->string('parameter', 30);
            $table->decimal('min_value', 8, 3)->nullable();
            $table->decimal('max_value', 8, 3)->nullable();
            $table->string('unit', 30)->nullable();
            $table->enum('ammonia_type', ['nh3', 'nh4', 'tan'])->nullable();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->unique(['fish_id', 'parameter']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_quality_standards');
    }
};

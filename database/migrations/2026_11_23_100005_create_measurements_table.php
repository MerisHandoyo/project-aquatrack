<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fish_id')->constrained('fishes')->cascadeOnDelete();
            $table->dateTime('measured_at');
            $table->decimal('temperature_c', 5, 2);
            $table->decimal('dissolved_oxygen_mg_l', 5, 2);
            $table->decimal('ph', 4, 2);
            $table->decimal('ammonia_mg_l', 6, 3);
            $table->enum('ammonia_type', ['nh3', 'nh4', 'tan'])->default('nh3');
            $table->decimal('alkalinity_mg_l', 6, 2);
            $table->enum('source', ['manual', 'iot', 'simulation'])->default('manual');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['pond_id', 'measured_at']);
            $table->index(['fish_id', 'measured_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pond_fish', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fish_id')->constrained('fishes')->cascadeOnDelete();
            $table->date('stock_date')->nullable();
            $table->unsignedInteger('population')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['pond_id', 'fish_id', 'stock_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pond_fish');
    }
};

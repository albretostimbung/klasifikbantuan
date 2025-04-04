<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('predicts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_evaluation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('citizen_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_eligible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predicts');
    }
};

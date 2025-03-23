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
        Schema::create('model_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('accuracy')->nullable();
            $table->text('conf_matrix')->nullable();
            $table->float('model_precision')->nullable();
            $table->float('model_recall')->nullable();
            $table->float('model_f1_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_evaluations');
    }
};

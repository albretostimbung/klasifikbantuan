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
        Schema::create('citizens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age')->index();
            $table->string('income')->index();
            $table->string('occupation')->index();
            $table->string('number_of_dependent');
            $table->string('residence_status')->index();
            $table->string('last_education');
            $table->boolean('marital_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizens');
    }
};

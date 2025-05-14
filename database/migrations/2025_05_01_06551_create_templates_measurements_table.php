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
        Schema::create('templates_measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('measurements_id');
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('templates')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('measurements_id')->references('id')->on('templates_measurements')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates_measurements');
    }
};

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
        Schema::create('design_details', function (Blueprint $table) {
            $table->id();
            $table->enum('body_section', ['Upper', 'Lower']);
            $table->enum('gender', ['m','f','o']);
           $table->foreignId('body_part_id')->references('id')->on('body_part_value')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('value');
            $table->text('image');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_details');
    }
};

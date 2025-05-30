<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('name');
            $table->enum('gender', ["m", "f", "o"]);
            $table->string('country_code')->nullable();
            $table->string('phone');
            $table->string('email')->nullable()->unique();
            $table->date('dob')->nullable();
            $table->json('address');
            $table->json('base_measurements')->nullable();
            $table->json('notes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

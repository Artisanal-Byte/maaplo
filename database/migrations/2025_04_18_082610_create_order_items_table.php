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
        Schema::disableForeignKeyConstraints();

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('item_template_id')->constrained('templates')->cascadeOnUpdate();
            $table->string('name');
            $table->json('measurements');
            $table->json('design_details');
            $table->string('colors');
            $table->string('material');
            $table->json("notes")->nullable();
            $table->date('trial_dates');
            $table->decimal('price', 10);
            $table->enum('status', ["created", "in process", "processed", "delivered", "completed", "cancelled"])->default("created");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

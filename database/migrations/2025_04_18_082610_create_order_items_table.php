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
            // $table->foreignId('template_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('template_id')->nullable()->constrained('templates')->cascadeOnUpdate()->nullOnDelete();
            $table->json('measurements');
            $table->json('design_detail');
            $table->string('colors');
            $table->string('work_type')->nullable()->after('material_code');
            $table->string('material_type')->nullable();
            $table->string('material_code')->nullable();
            $table->text('refrence_dress')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->decimal('material_cost', 10, 2)->nullable();
            $table->decimal('stiching_cost', 10, 2)->nullable();
            $table->decimal('altering_cost', 10, 2)->nullable();
            $table->decimal('item_cost', 10, 2)->nullable();
            $table->json("notes")->nullable();
            $table->date('trial_dates');
            $table->date('delivery_date');
            $table->enum('status', ["created", "in process", "processed", "delivered", "completed", "cancelled"])->default("created");
            $table->text('cloth_img1')->nullable();
            $table->text('cloth_img2')->nullable();
            $table->text('Pattern_img1')->nullable();
            $table->text('Pattern_img2')->nullable();
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

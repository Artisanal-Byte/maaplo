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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnUpdate();
            $table->string('order_number')->unique();
            $table->enum('status', ["created", "in process", "processed", "delivered", "completed", "cancelled"])->default("created");
            $table->decimal('total_amount', 10);
            $table->decimal('advance_paid', 10)->default(0);
            $table->date('delivery_date');
            $table->date('close_date')->nullable();
            $table->json('notes');
            $table->timestamps();
            $table->softDeletes();

            //-- unique constraint
            $table->unique(['user_id','order_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

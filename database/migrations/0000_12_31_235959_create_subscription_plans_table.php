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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_title');
            $table->text('plan_description')->nullable();
            $table->decimal('plan_price', 10, 2)->default(0.00);
            $table->string('plan_currency')->default('INR');
            $table->json('features')->nullable();
            $table->boolean('visibility')->default(true);
            $table->unsignedInteger('user_limit')->default(5);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};

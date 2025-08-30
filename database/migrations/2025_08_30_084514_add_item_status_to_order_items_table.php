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
        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('item_status', [
                "created",
                "in_process",
                "closed",
                "processed",
                "delivered",
                "completed",
                "cancelled",
                "trial_done",
                "in_alteration",
                "ready_for_delivery"
            ])->default("created")->after('measurements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('item_status');
        });
    }
};

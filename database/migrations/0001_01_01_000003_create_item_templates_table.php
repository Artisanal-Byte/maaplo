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

        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->nullable(true);
            $table->string('name');
            $table->text('svg_logo');
            $table->enum('gender', ["m", "f", "o"]);
            $table->enum('body_part', ["upper", "lower"]);
            // $table->enum('global_scope', ["y","n"])->default("n");
            // $table->json('required_measurements');
            // $table->boolean("custom_template")->default(false);
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
        Schema::dropIfExists('item_templates');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement(
            "SELECT setval(pg_get_serial_sequence('templates','id'), COALESCE((SELECT MAX(id) FROM templates), 1), true)"
        );
    }

    public function down(): void
    {
        // No-op: sequence fix only.
    }
};

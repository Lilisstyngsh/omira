<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('fiscal_year_targets')
            ->where('year', 2026)
            ->where('scope_key', 'all')
            ->exists();

        if (! $exists) {
            DB::table('fiscal_year_targets')->insert([
                'year' => 2026,
                'scope_key' => 'all',
                'line_id' => null,
                'target_qty' => 855,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Data target operasional tidak dihapus saat rollback agar update OMD tetap aman.
    }
};

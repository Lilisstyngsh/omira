<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {

            $table->foreignId('handed_over_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('handed_over_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {

            $table->dropForeign([
                'handed_over_by'
            ]);

            $table->dropColumn([
                'handed_over_by',
                'handed_over_at'
            ]);
        });
    }
};

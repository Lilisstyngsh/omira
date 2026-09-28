<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->unsignedInteger('order_year')->after('line_id');
            $table->unsignedInteger('sequence')->after('order_year');

            $table->unique(
                ['line_id', 'order_year', 'sequence'],
                'repair_orders_line_year_sequence_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->dropUnique('repair_orders_line_year_sequence_unique');
            $table->dropColumn(['order_year', 'sequence']);
        });
    }
};
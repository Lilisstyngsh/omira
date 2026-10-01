<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repair_order_items', function (Blueprint $table) {
            $table->foreignId('after_product_id')
                ->nullable()
                ->after('product_id')
                ->constrained('products')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('repair_order_items', function (Blueprint $table) {
            $table->dropForeign(['after_product_id']);
            $table->dropColumn('after_product_id');
        });
    }
};

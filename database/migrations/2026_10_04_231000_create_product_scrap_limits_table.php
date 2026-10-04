<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_scrap_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->unsignedInteger('limit_qty');
            $table->timestamp('effective_from');
            $table->timestamp('effective_to')->nullable();
            $table->string('source', 20)->default('omd');
            $table->text('note')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->index(
                ['product_id', 'effective_from', 'effective_to'],
                'product_scrap_limits_effective_idx'
            );
            $table->index(
                ['product_id', 'source'],
                'product_scrap_limits_source_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_scrap_limits');
    }
};

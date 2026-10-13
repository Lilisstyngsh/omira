<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_scrap_limit_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('line_id')->constrained('lines');
            $table->unsignedInteger('limit_qty');
            $table->timestamp('started_at');
            $table->timestamp('reached_at')->nullable();
            $table->unsignedInteger('reached_qty')->nullable();
            $table->string('decision', 16)->nullable();
            $table->text('decision_reason')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('reset_at')->nullable();
            $table->foreignId('reset_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reset_reason')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'product_id', 'reset_at'], 'scrap_cycles_scope_idx');
        });
    }
    public function down(): void { Schema::dropIfExists('user_scrap_limit_cycles'); }
};

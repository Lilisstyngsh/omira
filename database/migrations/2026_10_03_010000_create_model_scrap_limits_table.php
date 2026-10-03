<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_scrap_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_model_id')
                ->constrained('master_models')
                ->cascadeOnDelete();
            $table->unsignedInteger('limit_qty');
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(
                ['master_model_id', 'effective_from'],
                'model_scrap_limits_model_start_unique'
            );

            $table->index(
                ['master_model_id', 'effective_from', 'effective_to'],
                'model_scrap_limits_effective_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_scrap_limits');
    }
};

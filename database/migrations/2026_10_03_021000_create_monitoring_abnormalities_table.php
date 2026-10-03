<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_abnormalities', function (Blueprint $table) {
            $table->id();
            $table->string('metric', 50)->default('target_fy');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->string('scope_key', 50);
            $table->foreignId('line_id')
                ->nullable()
                ->constrained('lines')
                ->nullOnDelete();
            $table->foreignId('fiscal_year_target_id')
                ->nullable()
                ->constrained('fiscal_year_targets')
                ->nullOnDelete();
            $table->unsignedInteger('actual_qty');
            $table->unsignedInteger('threshold_qty');
            $table->text('reason');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(
                ['metric', 'year', 'month', 'scope_key'],
                'monitoring_abnormalities_period_scope_unique'
            );

            $table->index(
                ['year', 'month', 'line_id'],
                'monitoring_abnormalities_period_line_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_abnormalities');
    }
};

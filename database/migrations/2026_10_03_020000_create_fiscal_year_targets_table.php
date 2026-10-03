<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_year_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->string('scope_key', 50);
            $table->foreignId('line_id')
                ->nullable()
                ->constrained('lines')
                ->nullOnDelete();
            $table->unsignedInteger('target_qty')->default(0);
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
                ['year', 'scope_key'],
                'fiscal_year_targets_year_scope_unique'
            );

            $table->index(
                ['line_id', 'year'],
                'fiscal_year_targets_line_year_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_year_targets');
    }
};

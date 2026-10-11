<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_monthly_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedInteger('order_qty')->default(0);
            $table->unsignedInteger('ok_qty')->default(0);
            $table->unsignedInteger('scrap_qty')->default(0);
            $table->unsignedInteger('target_qty')->default(0);
            $table->string('source', 80)->default('legacy_chart');
            $table->timestamps();

            $table->unique(['year', 'month'], 'repair_monthly_snapshots_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_monthly_snapshots');
    }
};

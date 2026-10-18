<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_scrap_limit_cycles', function (Blueprint $table) {
            // A confirmation cursor prevents same-second reset/confirmation double counting.
            $table->unsignedBigInteger('baseline_confirmation_id')->nullable();
        });
        Schema::create('additional_box_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->uuid('submission_token')->unique();
            $table->string('purpose', 100)->default('Penggantian scrap box');
            $table->timestamps();
        });
        Schema::create('additional_box_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('additional_box_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_scrap_limit_cycle_id')->unique()->constrained();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('model_name', 100);
            $table->string('product_name', 150);
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_box_request_items');
        Schema::dropIfExists('additional_box_requests');
        Schema::table('user_scrap_limit_cycles', fn (Blueprint $table) => $table->dropColumn('baseline_confirmation_id'));
    }
};

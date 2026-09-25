<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repair_orders', function (Blueprint $table) {


            $table->id();


            $table->string('order_number')
                ->unique();



            $table->foreignId('user_id')
                ->constrained('users');



            $table->foreignId('line_id')
                ->constrained('lines');



            $table->foreignId('master_model_id')
                ->nullable()
                ->constrained('master_models')
                ->nullOnDelete();



            $table->foreignId('product_id')
                ->constrained('products');



            $table->foreignId('ng_type_id')
                ->constrained('ng_types');



            $table->date('order_date');


            $table->unsignedInteger('quantity');


            $table->text('description')
                ->nullable();


            $table->string('status')
                ->default('draft')
                ->index();


            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('verified_at')
                ->nullable();


            $table->timestamp('repair_started_at')
                ->nullable();


            $table->timestamp('repair_completed_at')
                ->nullable();


            $table->timestamps();
        });
        Schema::create('repair_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->unique()->constrained('repair_orders')->cascadeOnDelete();
            $table->unsignedInteger('ok_qty')->default(0);
            $table->unsignedInteger('scrap_qty')->default(0);
            $table->unsignedInteger('ng_qty')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('repair_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->unique()->constrained('repair_orders')->cascadeOnDelete();
            $table->foreignId('confirmed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->foreignId('line_id')
                ->nullable()
                ->constrained('lines')
                ->nullOnDelete();
            $table->unsignedInteger('target_qty')->default(0);
            $table->timestamps();
            $table->unique(['year', 'month', 'line_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('targets');
        Schema::dropIfExists('repair_confirmations');
        Schema::dropIfExists('repair_results');
        Schema::dropIfExists('repair_orders');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tps_repair_orders', function (Blueprint $table) {

            $table->id();

            $table->string('order_number')
                ->unique();


            $table->foreignId('user_id')
                ->constrained('users');


            $table->foreignId('line_id')
                ->nullable()
                ->constrained('lines')
                ->nullOnDelete();


            $table->date('reported_date');


            $table->string('tool_name');


            $table->text('problem_description');


            $table->string('status')
                ->default('submitted')
                ->index();


            $table->foreignId('leader_checked_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('leader_checked_at')
                ->nullable();


            $table->foreignId('member_verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('member_verified_at')
                ->nullable();


            $table->dateTime('scheduled_at')
                ->nullable();


            $table->foreignId('repaired_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('repaired_at')
                ->nullable();


            $table->text('repair_result')
                ->nullable();


            $table->foreignId('confirmed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('confirmed_at')
                ->nullable();


            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tps_repair_orders');
    }
};

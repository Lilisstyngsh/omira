<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('form_number_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_number_id')->constrained('form_numbers');
            $table->string('action', 30);
            $table->string('old_number', 20)->nullable();
            $table->string('new_number', 20)->nullable();
            $table->string('old_description', 255)->nullable();
            $table->string('new_description', 255)->nullable();
            $table->boolean('old_is_active')->nullable();
            $table->boolean('new_is_active')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Preserve the form number previously hardcoded in both History exports.
        $now = now();
        $id = DB::table('form_numbers')->insertGetId([
            'number' => 'FRM-PSD-P3-057-00',
            'description' => 'Form Order Repair Box NG',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('form_number_histories')->insert([
            'form_number_id' => $id,
            'action' => 'created',
            'new_number' => 'FRM-PSD-P3-057-00',
            'new_description' => 'Form Order Repair Box NG',
            'new_is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('form_number_histories');
        Schema::dropIfExists('form_numbers');
    }
};

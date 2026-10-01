<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->unsignedInteger('scrap_limit')
                ->default(20)
                ->after('target_qty');
        });
    }

    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn('scrap_limit');
        });
    }
};

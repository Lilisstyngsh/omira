<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {


        /*
        |--------------------------------------------------------------------------
        | PLANTS
        |--------------------------------------------------------------------------
        */

        Schema::create('plants', function (Blueprint $table) {

            $table->id();

            $table->string('name', 100);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });



        /*
        |--------------------------------------------------------------------------
        | LINES
        |--------------------------------------------------------------------------
        */

        Schema::create('lines', function (Blueprint $table) {

            $table->id();


            $table->foreignId('plant_id')
                ->constrained('plants')
                ->cascadeOnDelete();


            $table->string('name', 100);


            $table->boolean('is_active')
                ->default(true);


            $table->timestamps();
        });





        /*
        |--------------------------------------------------------------------------
        | MASTER MODEL
        |--------------------------------------------------------------------------
        */


        Schema::create('master_models', function (Blueprint $table) {


            $table->id();


            $table->foreignId('line_id')
                ->constrained('lines')
                ->cascadeOnDelete();



            $table->unsignedInteger('number')
                ->nullable();



            $table->string('model', 100);



            $table->boolean('is_active')
                ->default(true);



            $table->timestamps();
        });






        /*
        |--------------------------------------------------------------------------
        | PRODUCT
        |--------------------------------------------------------------------------
        */


        Schema::create('products', function (Blueprint $table) {


            $table->id();



            $table->foreignId('master_model_id')
                ->constrained('master_models')
                ->cascadeOnDelete();



            $table->string('name', 150);



            $table->string('code', 50)
                ->nullable();



            $table->boolean('is_active')
                ->default(true);



            $table->timestamps();
        });






        /*
        |--------------------------------------------------------------------------
        | NG TYPE
        |--------------------------------------------------------------------------
        */


        Schema::create('ng_types', function (Blueprint $table) {


            $table->id();


            $table->string('code', 10)
                ->unique();



            $table->string('name');



            $table->text('description')
                ->nullable();



            $table->boolean('is_active')
                ->default(true);



            $table->timestamps();
        });







        /*
        |--------------------------------------------------------------------------
        | PRODUCT NG TYPE
        |--------------------------------------------------------------------------
        */


        Schema::create('product_ng_types', function (Blueprint $table) {


            $table->id();



            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();



            $table->foreignId('ng_type_id')
                ->constrained('ng_types')
                ->cascadeOnDelete();



            $table->timestamps();
        });
    }





    public function down(): void
    {

        Schema::dropIfExists('product_ng_types');

        Schema::dropIfExists('ng_types');

        Schema::dropIfExists('products');

        Schema::dropIfExists('master_models');

        Schema::dropIfExists('lines');

        Schema::dropIfExists('plants');
    }
};

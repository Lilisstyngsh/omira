<?php

namespace Database\Seeders;


use App\Models\Plant;
use App\Models\Line;
use App\Models\NgType;
use App\Models\Target;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;



class DatabaseSeeder extends Seeder
{

    public function run(): void
    {


        /*
        |--------------------------------------------------------------------------
        | PLANT
        |--------------------------------------------------------------------------
        */


        $unit = Plant::firstOrCreate(

            [
                'name' => 'Unit'
            ],

            [
                'is_active' => true
            ]

        );



        $body = Plant::firstOrCreate(

            [
                'name' => 'Body'
            ],

            [
                'is_active' => true
            ]

        );



        $electric = Plant::firstOrCreate(

            [
                'name' => 'Electric'
            ],

            [
                'is_active' => true
            ]

        );





        /*
        |--------------------------------------------------------------------------
        | LINE
        |--------------------------------------------------------------------------
        */


        $lines = [];



        foreach (

            [

                // UNIT

                [
                    'plant_id' => $unit->id,
                    'name' => 'PPIC Unit'
                ],

                [
                    'plant_id' => $unit->id,
                    'name' => 'AS Unit'
                ],

                [
                    'plant_id' => $unit->id,
                    'name' => 'MA'
                ],



                // BODY

                [
                    'plant_id' => $body->id,
                    'name' => 'PPIC Body'
                ],

                [
                    'plant_id' => $body->id,
                    'name' => 'AS Body'
                ],

                [
                    'plant_id' => $body->id,
                    'name' => 'PT'
                ],

                [
                    'plant_id' => $body->id,
                    'name' => 'INJ'
                ],



                // ELECTRIC

                [
                    'plant_id' => $electric->id,
                    'name' => 'PPIC Electric'
                ],

                [
                    'plant_id' => $electric->id,
                    'name' => 'AS Electric'
                ],


            ]

            as $data

        ) {


            $line = Line::firstOrCreate(

                [

                    'plant_id' => $data['plant_id'],

                    'name' => $data['name']

                ],

                [

                    'is_active' => true

                ]

            );



            $lines[$data['name']] = $line;
        }







        /*
        |--------------------------------------------------------------------------
        | NG TYPE
        |--------------------------------------------------------------------------
        */


        foreach (

            [

                [
                    'code' => 'P',
                    'name' => 'NG Part'
                ],

                [
                    'code' => 'H',
                    'name' => 'NG Holder'
                ],

                [
                    'code' => 'C',
                    'name' => 'NG Cover'
                ],


            ]

            as $ng

        ) {


            NgType::firstOrCreate(

                [

                    'code' => $ng['code']

                ],

                [

                    'name' => $ng['name'],

                    'is_active' => true

                ]

            );
        }







        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */


        $ppicLine = $lines['PPIC Unit'];




        User::updateOrCreate(

            [

                'email' => 'user@omd.local'

            ],

            [

                'name' => 'Leader PPIC',

                'password' => Hash::make('password'),

                'role' => 'user',

                'line_id' => $ppicLine->id

            ]

        );






        User::updateOrCreate(

            [

                'email' => 'produksi@omd.local'

            ],

            [

                'name' => 'Leader Produksi',

                'password' => Hash::make('password'),

                'role' => 'user',

                'line_id' => null

            ]

        );







        User::updateOrCreate(

            [

                'email' => 'member@omd.local'

            ],

            [

                'name' => 'OMD Member',

                'password' => Hash::make('password'),

                'role' => 'omd_member',

                'line_id' => null

            ]

        );







        User::updateOrCreate(

            [

                'email' => 'leader@omd.local'

            ],

            [

                'name' => 'OMD',

                'password' => Hash::make('password'),

                'role' => 'omd_leader',

                'line_id' => null

            ]

        );







        /*
        |--------------------------------------------------------------------------
        | TARGET
        |--------------------------------------------------------------------------
        */


        foreach (

            [

                4 => 950,

                5 => 855,

                6 => 855,

                7 => 855,

                8 => 855,

                9 => 855

            ]

            as $month => $qty

        ) {


            Target::updateOrCreate(

                [

                    'year' => 2026,

                    'month' => $month,

                    'line_id' => $ppicLine->id

                ],

                [

                    'target_qty' => $qty

                ]

            );
        }
    }
}

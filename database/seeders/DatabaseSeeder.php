<?php

namespace Database\Seeders;


use App\Models\Plant;
use App\Models\Line;
use App\Models\NgType;
use App\Models\Target;
use App\Models\User;
use App\Models\MasterModel;
use App\Models\Product;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public const DEFAULT_OMD_PASSWORD = 'password';
    public const DEFAULT_USER_PASSWORD = 'aiia';

    public function run(): void
    {

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

                [
                    'plant_id' => $unit->id,
                    'name' => 'DC'
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
        | MASTER MODEL & PRODUCT
        |--------------------------------------------------------------------------
        */

        $modelProducts = [
            /*
            |--------------------------------------------------------------------------
            | PLANT BODY - PPIC BODY
            |--------------------------------------------------------------------------
            */
            'PPIC Body' => [
                '660' => [
                    'Handle',
                    'Frame FR R',
                    'Frame FR L',
                    'Frame RR R',
                    'Frame RR L',
                    'Cap',
                    'Garnish',
                    'Pad',
                ],
                '560' => [
                    'Handle',
                    'Frame FR R',
                    'Frame FR L',
                    'Frame RR R',
                    'Frame RR L',
                    'Garnish',
                ],
                '4L45W / 5P45' => [
                    'Handle',
                    'Frame R',
                    'Frame L',
                    'Cap',
                    'Pad',
                ],
                'TBINA' => [
                    'Slide R',
                    'Slide L',
                    'Reclining R',
                    'Reclining L',
                    'Tilt R',
                    'Tilt L',
                    'Handle',
                    'Pad Frame',
                ],
                'TTI' => [
                    'Slide R',
                    'Slide L',
                    'Reclining R',
                    'Reclining L',
                    'Tilt R',
                    'Tilt L',
                ],
                'HINO' => [
                    'Handle',
                ],
                'ADM KAP' => [
                    'Backdoor',
                ],
                '230' => [
                    '230'
                ],
                '800A' => [
                    '800A'
                ],
                'SUZUKI' => [
                    'Handle YHA',
                    'Handle YTB',
                ],
                'DOWA' => [],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT BODY - AS BODY
            |--------------------------------------------------------------------------
            */
            'AS Body' => [
                'SUZUKI' => [
                    'Case YHA/YTB',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT BODY - PT
            |--------------------------------------------------------------------------
            */
            'PT' => [
                '660 / 230' => [
                    'Handle',
                    'Cap',
                ],
                '560' => [
                    'Handle',
                ],
                '4L45W / 5P45' => [
                    'Handle',
                    'Cap',
                ],
                'SUZUKI' => [
                    'Handle YHA/YTB',
                    'Cap YHA/YTB',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT BODY - INJ
            |--------------------------------------------------------------------------
            */
            'INJ' => [
                'ALL MODEL' => [
                    'Handle No 2 / Frame (Box TP 332)',
                    'Garnish (Box TP 362)',
                ],
                'HINO' => [
                    'Case Hino',
                    'Handle Hino',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT UNIT - PPIC UNIT
            |--------------------------------------------------------------------------
            */
            'PPIC Unit' => [
                'D98E (NR)' => [
                    'TCC',
                    'CSH',
                ],
                'ISZ/K3' => [
                    'WP',
                ],
                '1SZ/3SZ' => [
                    'OP',
                ],
                '889F' => [
                    'TCC',
                    'OPN',
                ],
                'D72F/D73F' => [
                    'TCC',
                    'OPN',
                ],
                'D13E' => [
                    'TCC',
                ],
                '922F' => [
                    'OPN',
                ],
                'D18E' => [
                    'TCC',
                ],
                'D41E' => [
                    'TCC',
                    'OPN',
                ],
                'D05E' => [
                    'TCC',
                    'OPN',
                    'CSH',
                ],
                '4A91' => [
                    'TCC',
                ],
                '5P45' => [
                    'TCC',
                ],
                'TNGA' => [
                    'TCC No 1',
                    'TCC No 2',
                ],
                'ALL MODEL' => [
                    'Komponen OPN',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT UNIT - AS UNIT
            |--------------------------------------------------------------------------
            */
            'AS Unit' => [
                'Water Pump' => [
                    'WPNR',
                    'WP D05E',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT UNIT - MA
            |--------------------------------------------------------------------------
            */
            'MA' => [
                'ALL MODEL' => [
                    'TCC',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT UNIT - DC
            |--------------------------------------------------------------------------
            */
            'DC' => [
                'All Model Kecuali TNGA' => [
                    'TCC',
                    'OPN',
                ],
                'TNGA' => [
                    'TCC No 1',
                    'TCC No 2',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT ELECTRIC - PPIC ELECTRIC
            |--------------------------------------------------------------------------
            */
            'PPIC Electric' => [
                '4WD IMV' => [
                    '4WD IMV'
                ],
                'PBD 582D/737D/840D' => [
                    'PBD 582D/737D/840D'
                ],
                'PBD 5P45' => [
                    'PBD 5P45'
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PLANT ELECTRIC - AS ELECTRIC
            |--------------------------------------------------------------------------
            */
            'AS Electric' => [
                'EWP EF160' => [
                    'EWP EF160'
                ],
                'EWP GA35' => [
                    'EWP GA35'
                ],
                'OP T431' => [
                    'OP T431'
                ],
                'EWP EF160 Toyota' => [
                    'EWP EF160 Toyota'
                ],
                '4WD 5F00/5K45' => [
                    '4WD 5F00/5K45'
                ],
                'PBD Y17' => [
                    'PBD Y17'
                ],
            ],
        ];

        foreach ($modelProducts as $lineName => $models) {
            if (!isset($lines[$lineName])) {
                continue;
            }

            $line = $lines[$lineName];

            foreach ($models as $modelName => $products) {
                $masterModel = MasterModel::firstOrCreate(
                    [
                        'line_id' => $line->id,
                        'model' => $modelName
                    ],
                    [
                        'is_active' => true
                    ]
                );

                foreach ($products as $productName) {
                    Product::firstOrCreate(
                        [
                            'master_model_id' => $masterModel->id,
                            'name' => $productName
                        ],
                        [
                            'is_active' => true
                        ]
                    );
                }
            }
        }

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

                [
                    'code' => 'S',
                    'name' => 'NG Scrap'
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

        User::updateOrCreate(
            [
                'email' => 'dwi.h@aiia.co.id'
            ],
            [
                'name' => 'Dwi Haryanto',
                'password' => Hash::make(self::DEFAULT_OMD_PASSWORD),
                'role' => 'omd',
                'line_id' => null
            ]
        );

        $userAccounts = [
            [
                'name' => 'Ramanda',
                'email' => 'ramanda_fe@aiia.co.id',
                'plant' => 'BODY',
                'line' => 'PPIC BODY',
            ],

            [
                'name' => 'Ubaydillah',
                'email' => 'ubaydillah@aiia.co.id',
                'plant' => 'BODY',
                'line' => 'AS BODY',
            ],

            [
                'name' => 'Marcellino',
                'email' => 'marcellino.reyhan@aiia.co.id',
                'plant' => 'BODY',
                'line' => 'PT',
            ],

            [
                'name' => 'Marcellino',
                'email' => 'marcellino.reyhan@aiia.co.id',
                'plant' => 'BODY',
                'line' => 'INJ',
            ],

            [
                'name' => 'Taufik',
                'email' => 'taufik.widodo@aiia.co.id',
                'plant' => 'UNIT',
                'line' => 'PPIC UNIT',
            ],

            [
                'name' => 'Teddy',
                'email' => 'teddy@aiia.co.id',
                'plant' => 'UNIT',
                'line' => 'AS UNIT',
            ],

            [
                'name' => 'Anhar',
                'email' => 'anhar.kurniaji@aiia.co.id',
                'plant' => 'UNIT',
                'line' => 'MA',
            ],

            [
                'name' => 'Ade F',
                'email' => 'ade.firmansyah@aiia.co.id',
                'plant' => 'UNIT',
                'line' => 'DC',
            ],

            [
                'name' => 'Saiful',
                'email' => 'saiful.safari@aiia.co.id',
                'plant' => 'ELECTRIC',
                'line' => 'PPIC ELECTRIC',
            ],

            [
                'name' => 'Widiyan',
                'email' => 'widiyan@aiia.co.id',
                'plant' => 'ELECTRIC',
                'line' => 'AS ELECTRIC',
            ],
        ];

        foreach ($userAccounts as $account) {

            $plant = Plant::where('name', $account['plant'])
                ->where('is_active', true)
                ->firstOrFail();

            $line = Line::where('name', $account['line'])
                ->where('plant_id', $plant->id)
                ->where('is_active', true)
                ->firstOrFail();

            User::updateOrCreate(
                [
                    'email' => strtolower(trim($account['email'])),
                ],
                [
                    'name' => trim($account['name']),
                    'password' => Hash::make(self::DEFAULT_USER_PASSWORD),
                    'role' => 'user',
                    'line_id' => $line->id,
                ]
            );
        }

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

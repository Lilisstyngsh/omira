<?php

namespace Database\Seeders;


use App\Models\Plant;
use App\Models\Line;
use App\Models\NgType;
use App\Models\Target;
use App\Models\FiscalYearTarget;
use App\Models\User;

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
        |
        | Master yang terkait Scrap Limit menggunakan satu source of truth:
        | ScrapLimitSeedData. Plant/Line mengikuti naming OMIRA, sedangkan
        | Model/Product mengikuti penulisan file Excel terbaru.
        |
        */

        $this->call(ScrapLimitMasterDataSeeder::class);

        // Initial Scrap Limit per Product berdasarkan DATA QTY LIMIT BOX SCRAP.
        // Idempotent: tidak menimpa limit terbaru yang dibuat OMD.
        $this->call(ProductScrapLimitSeeder::class);

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
                'plant' => 'Body',
                'line' => 'PPIC Body',
            ],

            [
                'name' => 'Ubaydillah',
                'email' => 'ubaydillah@aiia.co.id',
                'plant' => 'Body',
                'line' => 'AS Body',
            ],

            [
                'name' => 'Marcellino',
                'email' => 'marcellino.reyhan@aiia.co.id',
                'plant' => 'Body',
                'line' => 'PT',
            ],

            [
                'name' => 'Marcellino',
                'email' => 'marcellino.reyhan@aiia.co.id',
                'plant' => 'Body',
                'line' => 'INJ',
            ],

            [
                'name' => 'Taufik',
                'email' => 'taufik.widodo@aiia.co.id',
                'plant' => 'Unit',
                'line' => 'PPIC Unit',
            ],

            [
                'name' => 'Teddy',
                'email' => 'teddy@aiia.co.id',
                'plant' => 'Unit',
                'line' => 'AS Unit',
            ],

            [
                'name' => 'Anhar',
                'email' => 'anhar.kurniaji@aiia.co.id',
                'plant' => 'Unit',
                'line' => 'MA',
            ],

            [
                'name' => 'Ade F',
                'email' => 'ade.firmansyah@aiia.co.id',
                'plant' => 'Unit',
                'line' => 'DC',
            ],

            [
                'name' => 'Saiful',
                'email' => 'saiful.safari@aiia.co.id',
                'plant' => 'Electric',
                'line' => 'PPIC Electric',
            ],

            [
                'name' => 'Widiyan',
                'email' => 'widiyan@aiia.co.id',
                'plant' => 'Electric',
                'line' => 'AS Electric',
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
        | TARGET FY GLOBAL
        |--------------------------------------------------------------------------
        | Baseline 2026 = 855 untuk seluruh Plant & Line. firstOrCreate dipakai
        | agar db:seed berikutnya tidak menimpa target terbaru yang sudah diubah OMD.
        */
        FiscalYearTarget::firstOrCreate(
            [
                'year' => 2026,
                'scope_key' => FiscalYearTarget::scopeKey(null),
            ],
            [
                'line_id' => null,
                'target_qty' => 855,
                'created_by' => null,
                'updated_by' => null,
            ]
        );

        foreach (range(1, 12) as $month) {
            Target::firstOrCreate(
                [
                    'year' => 2026,
                    'month' => $month,
                    'line_id' => null,
                ],
                [
                    'target_qty' => 855,
                ]
            );
        }
    }
}

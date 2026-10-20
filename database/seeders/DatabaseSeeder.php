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
    public const DEFAULT_OMD_PASSWORD = 'psdomd';
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

        // Normalize existing Line names before firstOrCreate to preserve their IDs
        // even when an operator runs db:seed before the rename migration.
        foreach ([
            'Body' => ['AS Body' => 'Assy Body', 'INJ' => 'Injection', 'PT' => 'Painting'],
            'Electric' => ['AS Electric' => 'Assy Electric'],
            'Unit' => ['DC' => 'Die Casting', 'MA' => 'Machining', 'AS Unit' => 'Assy Unit'],
        ] as $plantName => $renames) {
            $plantId = Plant::where('name', $plantName)->value('id');

            foreach ($renames as $oldName => $newName) {
                $legacy = Line::where('plant_id', $plantId)->where('name', $oldName)->first();
                $existing = Line::where('plant_id', $plantId)->where('name', $newName)->first();

                if ($legacy && ! $existing) {
                    $legacy->update(['name' => $newName]);
                } elseif ($legacy && $existing && $legacy->id !== $existing->id) {
                    throw new \RuntimeException(
                        "Line [{$oldName}] dan [{$newName}] sama-sama ada di Plant [{$plantName}]."
                    );
                }
            }
        }

        $lines = [];

        foreach (

            [

                [
                    'plant_id' => $unit->id,
                    'name' => 'PPIC Unit'
                ],

                [
                    'plant_id' => $unit->id,
                    'name' => 'Assy Unit'
                ],

                [
                    'plant_id' => $unit->id,
                    'name' => 'Machining'
                ],

                [
                    'plant_id' => $unit->id,
                    'name' => 'Die Casting'
                ],

                [
                    'plant_id' => $body->id,
                    'name' => 'PPIC Body'
                ],

                [
                    'plant_id' => $body->id,
                    'name' => 'Assy Body'
                ],

                [
                    'plant_id' => $body->id,
                    'name' => 'Painting'
                ],

                [
                    'plant_id' => $body->id,
                    'name' => 'Injection'
                ],

                [
                    'plant_id' => $electric->id,
                    'name' => 'PPIC Electric'
                ],

                [
                    'plant_id' => $electric->id,
                    'name' => 'Assy Electric'
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

        $this->call(ScrapLimitMasterDataSeeder::class);
        $this->call(ProductScrapLimitSeeder::class);

        foreach (
            [
                [
                    'code' => 'P',
                    'name' => 'Partisi'
                ],

                [
                    'code' => 'H',
                    'name' => 'Holder'
                ],

                [
                    'code' => 'C',
                    'name' => 'Cover'
                ],

                [
                    'code' => 'S',
                    'name' => 'Scrap'
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
                'line_id' => null,
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
                'line' => 'Assy Body',
            ],

            [
                'name' => 'Marcel',
                'email' => 'marcellino@aiia.co.id',
                'plant' => 'Body',
                'line' => 'Painting',
            ],

            [
                'name' => 'Aden',
                'email' => 'aden@aiia.co.id',
                'plant' => 'Body',
                'line' => 'Injection',
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
                'line' => 'Assy Unit',
            ],

            [
                'name' => 'Anhar',
                'email' => 'anhar.kurniaji@aiia.co.id',
                'plant' => 'Unit',
                'line' => 'Machining',
            ],

            [
                'name' => 'Ade F',
                'email' => 'ade.firmansyah@aiia.co.id',
                'plant' => 'Unit',
                'line' => 'Die Casting',
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
                'line' => 'Assy Electric',
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

        $this->call(RepairMonthlySnapshotSeeder::class);
    }
}

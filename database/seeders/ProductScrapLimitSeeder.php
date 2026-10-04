<?php

namespace Database\Seeders;

use App\Models\Line;
use App\Models\Product;
use App\Models\ProductScrapLimit;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductScrapLimitSeeder extends Seeder
{
    private const BASELINE_EFFECTIVE_FROM = '2000-01-01 00:00:00';

    public function run(): void
    {
        // Supaya command ini aman dijalankan langsung pada database existing.
        // Master Plant/Line/Model/Product yang dibutuhkan disinkronkan terlebih dahulu.
        $this->call(ScrapLimitMasterDataSeeder::class);

        foreach (ScrapLimitSeedData::rows() as [$plantName, $lineName, $modelName, $productName, $limitQty]) {
            $line = Line::query()
                ->where('name', $lineName)
                ->whereHas('plant', function ($query) use ($plantName) {
                    $query
                        ->where('name', $plantName)
                        ->where('is_active', true);
                })
                ->where('is_active', true)
                ->first();

            if (! $line) {
                throw new RuntimeException(
                    "Scrap Limit Seeder: Line [{$lineName}] pada Plant [{$plantName}] tidak ditemukan."
                );
            }

            $product = Product::query()
                ->where('name', $productName)
                ->where('is_active', true)
                ->whereHas('masterModel', function ($query) use ($line, $modelName) {
                    $query
                        ->where('line_id', $line->id)
                        ->where('model', $modelName)
                        ->where('is_active', true);
                })
                ->first();

            if (! $product) {
                throw new RuntimeException(
                    "Scrap Limit Seeder: Product [{$productName}] pada Model [{$modelName}] / Line [{$lineName}] tidak ditemukan."
                );
            }

            $seedLimit = ProductScrapLimit::query()
                ->where('product_id', $product->id)
                ->where('source', ProductScrapLimit::SOURCE_SEED)
                ->orderBy('effective_from')
                ->orderBy('id')
                ->first();

            $hasOmdLimit = ProductScrapLimit::query()
                ->where('product_id', $product->id)
                ->where('source', ProductScrapLimit::SOURCE_OMD)
                ->exists();

            if ($seedLimit) {
                // Baseline boleh diselaraskan dengan Excel selama Product tersebut
                // belum pernah mempunyai limit operasional dari OMD.
                if (! $hasOmdLimit && (int) $seedLimit->limit_qty !== (int) $limitQty) {
                    $seedLimit->update([
                        'limit_qty' => (int) $limitQty,
                    ]);
                }

                continue;
            }

            $firstOperationalLimit = ProductScrapLimit::query()
                ->where('product_id', $product->id)
                ->where('source', ProductScrapLimit::SOURCE_OMD)
                ->orderBy('effective_from')
                ->orderBy('id')
                ->first();

            ProductScrapLimit::create([
                'product_id' => $product->id,
                'limit_qty' => (int) $limitQty,
                'effective_from' => self::BASELINE_EFFECTIVE_FROM,
                'effective_to' => $firstOperationalLimit
                    ? $firstOperationalLimit->effective_from->copy()->subSecond()
                    : null,
                'source' => ProductScrapLimit::SOURCE_SEED,
                'note' => null,
                'created_by' => null,
            ]);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $lineIds = DB::table('lines')
            ->whereIn('name', [
                'PPIC Body',
                'AS Body',
                'PT',
                'INJ',
                'PPIC Unit',
                'AS Unit',
                'MA',
                'DC',
                'PPIC Electric',
                'AS Electric',
            ])
            ->pluck('id', 'name');

        if ($lineIds->isEmpty()) {
            return;
        }

        // Nama master harus mengikuti file DATA QTY LIMIT BOX SCRAP persis.
        $this->renameProduct($lineIds, 'PPIC Body', 'TBINA', 'Pad Frame', 'Pad,Frame');

        $this->mergeProducts($lineIds, 'PPIC Body', 'TTI', 'Slide R', 'Slide L', 'Slide R/L');
        $this->mergeProducts($lineIds, 'PPIC Body', 'TTI', 'Reclining R', 'Reclining L', 'Reclining R/L');
        $this->mergeProducts($lineIds, 'PPIC Body', 'TTI', 'Tilt R', 'Tilt L', 'Tilt R /L');

        $this->renameModelAndProduct($lineIds, 'PPIC Unit', 'ISZ/K3', 'WP', 'WP', '1SZ/K3');
        $this->renameModelAndProduct($lineIds, 'PPIC Unit', '1SZ/3SZ', 'OP', 'OP', '1SZ/3SZ');
        $this->renameProduct($lineIds, 'PPIC Unit', 'TNGA', 'TCC No 2', 'Tcc No 2');
        $this->renameModel($lineIds, 'DC', 'All Model Kecuali TNGA', 'ALL MODEL kecuali TNGA');

        // Data Electric pada seeder lama tertukar line-nya. ID model/product tetap dipertahankan.
        foreach (['4WD IMV', 'PBD 582D/737D/840D', 'PBD 5P45'] as $modelName) {
            $this->moveModel($lineIds, 'PPIC Electric', 'AS Electric', $modelName);
        }

        foreach (['EWP EF160', 'EWP GA35', 'OP T431', 'EWP EF160 Toyota', '4WD 5F00/5K45', 'PBD Y17'] as $modelName) {
            $this->moveModel($lineIds, 'AS Electric', 'PPIC Electric', $modelName);
        }

        // Pastikan data yang sebelumnya kosong tetap tersedia.
        $this->ensureProduct($lineIds, 'PPIC Body', 'DOWA', 'CSH');
    }

    public function down(): void
    {
        // Data migration sengaja tidak di-reverse agar referensi transaksi historis tetap aman.
    }

    private function modelId($lineIds, string $lineName, string $modelName): ?int
    {
        $lineId = $lineIds[$lineName] ?? null;

        if (! $lineId) {
            return null;
        }

        $id = DB::table('master_models')
            ->where('line_id', $lineId)
            ->where('model', $modelName)
            ->value('id');

        return $id ? (int) $id : null;
    }

    private function renameModel($lineIds, string $lineName, string $oldName, string $newName): void
    {
        $modelId = $this->modelId($lineIds, $lineName, $oldName);

        if (! $modelId) {
            return;
        }

        DB::table('master_models')
            ->where('id', $modelId)
            ->update([
                'model' => $newName,
                'updated_at' => now(),
            ]);
    }

    private function renameProduct($lineIds, string $lineName, string $modelName, string $oldName, string $newName): void
    {
        $modelId = $this->modelId($lineIds, $lineName, $modelName);

        if (! $modelId) {
            return;
        }

        DB::table('products')
            ->where('master_model_id', $modelId)
            ->where('name', $oldName)
            ->update([
                'name' => $newName,
                'updated_at' => now(),
            ]);
    }

    private function renameModelAndProduct(
        $lineIds,
        string $lineName,
        string $oldModelName,
        string $oldProductName,
        string $newModelName,
        string $newProductName
    ): void {
        $modelId = $this->modelId($lineIds, $lineName, $oldModelName);

        if (! $modelId) {
            return;
        }

        DB::table('master_models')
            ->where('id', $modelId)
            ->update([
                'model' => $newModelName,
                'updated_at' => now(),
            ]);

        DB::table('products')
            ->where('master_model_id', $modelId)
            ->where('name', $oldProductName)
            ->update([
                'name' => $newProductName,
                'updated_at' => now(),
            ]);
    }

    private function moveModel($lineIds, string $fromLine, string $toLine, string $modelName): void
    {
        $fromLineId = $lineIds[$fromLine] ?? null;
        $toLineId = $lineIds[$toLine] ?? null;

        if (! $fromLineId || ! $toLineId) {
            return;
        }

        DB::table('master_models')
            ->where('line_id', $fromLineId)
            ->where('model', $modelName)
            ->update([
                'line_id' => $toLineId,
                'updated_at' => now(),
            ]);
    }

    private function mergeProducts(
        $lineIds,
        string $lineName,
        string $modelName,
        string $keepName,
        string $mergeName,
        string $finalName
    ): void {
        $modelId = $this->modelId($lineIds, $lineName, $modelName);

        if (! $modelId) {
            return;
        }

        $keepId = DB::table('products')
            ->where('master_model_id', $modelId)
            ->where('name', $keepName)
            ->value('id');

        $mergeId = DB::table('products')
            ->where('master_model_id', $modelId)
            ->where('name', $mergeName)
            ->value('id');

        if (! $keepId) {
            return;
        }

        if ($mergeId && (int) $mergeId !== (int) $keepId) {
            DB::table('repair_orders')->where('product_id', $mergeId)->update(['product_id' => $keepId]);
            DB::table('repair_order_items')->where('product_id', $mergeId)->update(['product_id' => $keepId]);
            DB::table('repair_order_items')->where('after_product_id', $mergeId)->update(['after_product_id' => $keepId]);
            DB::table('product_ng_types')->where('product_id', $mergeId)->update(['product_id' => $keepId]);
            DB::table('products')->where('id', $mergeId)->delete();
        }

        DB::table('products')
            ->where('id', $keepId)
            ->update([
                'name' => $finalName,
                'updated_at' => now(),
            ]);
    }

    private function ensureProduct($lineIds, string $lineName, string $modelName, string $productName): void
    {
        $modelId = $this->modelId($lineIds, $lineName, $modelName);

        if (! $modelId) {
            return;
        }

        $exists = DB::table('products')
            ->where('master_model_id', $modelId)
            ->where('name', $productName)
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('products')->insert([
            'master_model_id' => $modelId,
            'name' => $productName,
            'code' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};

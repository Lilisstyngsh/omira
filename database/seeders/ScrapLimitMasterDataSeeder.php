<?php

namespace Database\Seeders;

use App\Models\Line;
use App\Models\MasterModel;
use App\Models\Plant;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ScrapLimitMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->normalizeLegacyPlantAndLineNames();
        $this->normalizeLegacyModelAndProductNames();

        $plants = [];
        $lines = [];
        $models = [];

        foreach (ScrapLimitSeedData::rows() as [$plantName, $lineName, $modelName, $productName]) {
            $plant = $plants[$plantName] ??= $this->ensurePlant($plantName);

            $lineKey = $plant->id . '|' . $lineName;
            $line = $lines[$lineKey] ??= $this->ensureLine($plant, $lineName);

            $modelKey = $line->id . '|' . $modelName;
            $model = $models[$modelKey] ??= $this->ensureModel($line, $modelName);

            $this->ensureProduct($model, $productName);
        }

        // Produk gabungan dari versi seeder lama tidak sesuai Excel terbaru.
        // Jangan dihapus agar histori transaksi lama tetap aman; cukup nonaktifkan.
        $this->deactivateLegacyProduct('PPIC Body', 'TTI', 'Slide R / L');
        $this->deactivateLegacyProduct('PPIC Body', 'TTI', 'Reclining R /L');
        $this->deactivateLegacyProduct('PPIC Body', 'TTI', 'Reclining R / L');
        $this->deactivateLegacyProduct('PPIC Body', 'TTI', 'Tilt R /L');
        $this->deactivateLegacyProduct('PPIC Body', 'TTI', 'Tilt R / L');

    }

    private function ensurePlant(string $name): Plant
    {
        $plant = Plant::query()->get()->first(fn (Plant $item) => $item->name === $name);

        if (! $plant) {
            $plant = Plant::create([
                'name' => $name,
                'is_active' => true,
            ]);
        } elseif (! $plant->is_active) {
            $plant->update(['is_active' => true]);
        }

        return $plant;
    }

    private function ensureLine(Plant $plant, string $name): Line
    {
        $line = Line::query()
            ->where('plant_id', $plant->id)
            ->get()
            ->first(fn (Line $item) => $item->name === $name);

        if (! $line) {
            $line = Line::create([
                'plant_id' => $plant->id,
                'name' => $name,
                'is_active' => true,
            ]);
        } elseif (! $line->is_active) {
            $line->update(['is_active' => true]);
        }

        return $line;
    }

    private function ensureModel(Line $line, string $name): MasterModel
    {
        $model = MasterModel::query()
            ->where('line_id', $line->id)
            ->get()
            ->first(fn (MasterModel $item) => $item->model === $name);

        if (! $model) {
            $model = MasterModel::create([
                'line_id' => $line->id,
                'model' => $name,
                'is_active' => true,
            ]);
        } elseif (! $model->is_active) {
            $model->update(['is_active' => true]);
        }

        return $model;
    }

    private function ensureProduct(MasterModel $model, string $name): Product
    {
        $product = Product::query()
            ->where('master_model_id', $model->id)
            ->get()
            ->first(fn (Product $item) => $item->name === $name);

        if (! $product) {
            $product = Product::create([
                'master_model_id' => $model->id,
                'name' => $name,
                'code' => null,
                'is_active' => true,
            ]);
        } elseif (! $product->is_active) {
            $product->update(['is_active' => true]);
        }

        return $product;
    }

    private function normalizeLegacyPlantAndLineNames(): void
    {
        foreach ([
            'BODY' => 'Body',
            'UNIT' => 'Unit',
            'ELECTRIC' => 'Electric',
        ] as $oldName => $newName) {
            $this->renamePlantIfSafe($oldName, $newName);
        }

        foreach ([
            'Body' => [
                'PPIC BODY' => 'PPIC Body',
                'AS BODY' => 'AS Body',
            ],
            'Unit' => [
                'PPIC UNIT' => 'PPIC Unit',
                'AS UNIT' => 'AS Unit',
            ],
            'Electric' => [
                'AS ELECTRIC' => 'AS Electric',
                'PPIC ELECTRIC' => 'PPIC Electric',
            ],
        ] as $plantName => $lineNames) {
            foreach ($lineNames as $oldName => $newName) {
                $this->renameLineIfSafe($plantName, $oldName, $newName);
            }
        }
    }

    private function normalizeLegacyModelAndProductNames(): void
    {
        // TBINA: Excel terbaru memakai "Pad, Frame".
        $this->renameProductIfSafe('PPIC Body', 'TBINA', 'Pad Frame', 'Pad, Frame');
        $this->renameProductIfSafe('PPIC Body', 'TBINA', 'Pad,Frame', 'Pad, Frame');

        // PPIC Unit: nama Model/Product mengikuti Excel terbaru.
        $this->renameModelAndProductIfSafe(
            'PPIC Unit',
            'ISZ/K3',
            'WP',
            'WP',
            '1SZ/K3'
        );

        $this->renameModelAndProductIfSafe(
            'PPIC Unit',
            '1SZ/3SZ',
            'OP',
            'OP',
            '1SZ/3SZ'
        );

        $this->renameProductIfSafe('PPIC Unit', 'TNGA', 'TCC No 2', 'Tcc No 2');
        $this->renameModelIfSafe('DC', 'All Model Kecuali TNGA', 'ALL MODEL kecuali TNGA');

        // Model Electric pada versi lama sempat tertukar Line.
        // Bila tujuan belum ada, pindahkan record yang sama agar ID/histori tetap terjaga.
        foreach (['4WD IMV', 'PBD 582D/737D/840D', 'PBD 5P45'] as $modelName) {
            $this->moveModelIfSafe('PPIC Electric', 'AS Electric', $modelName);
        }

        foreach (['EWP EF160', 'EWP GA35', 'OP T431', 'EWP EF160 Toyota', '4WD 5F00/5K45', 'PBD Y17'] as $modelName) {
            $this->moveModelIfSafe('AS Electric', 'PPIC Electric', $modelName);
        }
    }

    private function renamePlantIfSafe(string $oldName, string $newName): void
    {
        $plants = Plant::query()->get();
        $old = $plants->first(fn (Plant $item) => $item->name === $oldName);

        if (! $old) {
            return;
        }

        $target = $plants->first(fn (Plant $item) => $item->name === $newName);

        if (! $target) {
            $old->update(['name' => $newName]);
            return;
        }

        if ($target->id !== $old->id) {
            $old->update(['is_active' => false]);
            Line::query()
                ->where('plant_id', $old->id)
                ->update(['is_active' => false]);
        }
    }

    private function renameLineIfSafe(
        string $plantName,
        string $oldName,
        string $newName
    ): void {
        $plant = Plant::query()->get()->first(fn (Plant $item) => $item->name === $plantName);

        if (! $plant) {
            return;
        }

        $lines = Line::query()->where('plant_id', $plant->id)->get();
        $old = $lines->first(fn (Line $item) => $item->name === $oldName);

        if (! $old) {
            return;
        }

        $target = $lines->first(fn (Line $item) => $item->name === $newName);

        if (! $target) {
            $old->update(['name' => $newName]);
            return;
        }

        if ($target->id !== $old->id) {
            $old->update(['is_active' => false]);
        }
    }

    private function renameModelIfSafe(string $lineName, string $oldName, string $newName): void
    {
        $line = $this->findLineExact($lineName);

        if (! $line) {
            return;
        }

        $models = MasterModel::query()->where('line_id', $line->id)->get();
        $old = $models->first(fn (MasterModel $item) => $item->model === $oldName);

        if (! $old) {
            return;
        }

        $target = $models->first(fn (MasterModel $item) => $item->model === $newName);

        if (! $target) {
            $old->update(['model' => $newName]);
            return;
        }

        if ($target->id !== $old->id) {
            $old->update(['is_active' => false]);
        }
    }

    private function renameProductIfSafe(
        string $lineName,
        string $modelName,
        string $oldName,
        string $newName
    ): void {
        $model = $this->findModelExact($lineName, $modelName);

        if (! $model) {
            return;
        }

        $products = Product::query()->where('master_model_id', $model->id)->get();
        $old = $products->first(fn (Product $item) => $item->name === $oldName);

        if (! $old) {
            return;
        }

        $target = $products->first(fn (Product $item) => $item->name === $newName);

        if (! $target) {
            $old->update(['name' => $newName]);
            return;
        }

        if ($target->id !== $old->id) {
            $old->update(['is_active' => false]);
        }
    }

    private function renameModelAndProductIfSafe(
        string $lineName,
        string $oldModelName,
        string $oldProductName,
        string $newModelName,
        string $newProductName
    ): void {
        $line = $this->findLineExact($lineName);

        if (! $line) {
            return;
        }

        $models = MasterModel::query()->where('line_id', $line->id)->get();
        $oldModel = $models->first(fn (MasterModel $item) => $item->model === $oldModelName);

        if (! $oldModel) {
            return;
        }

        $targetModel = $models->first(fn (MasterModel $item) => $item->model === $newModelName);

        if ($targetModel && $targetModel->id !== $oldModel->id) {
            $oldModel->update(['is_active' => false]);
            return;
        }

        if (! $targetModel) {
            $oldModel->update(['model' => $newModelName]);
            $targetModel = $oldModel->fresh();
        }

        $products = Product::query()
            ->where('master_model_id', $targetModel->id)
            ->get();

        $oldProduct = $products->first(fn (Product $item) => $item->name === $oldProductName);
        $targetProduct = $products->first(fn (Product $item) => $item->name === $newProductName);

        if ($oldProduct && ! $targetProduct) {
            $oldProduct->update(['name' => $newProductName]);
        } elseif ($oldProduct && $targetProduct && $oldProduct->id !== $targetProduct->id) {
            $oldProduct->update(['is_active' => false]);
        }
    }

    private function deactivateLegacyProduct(
        string $lineName,
        string $modelName,
        string $productName
    ): void {
        $model = $this->findModelExact($lineName, $modelName);

        if (! $model) {
            return;
        }

        Product::query()
            ->where('master_model_id', $model->id)
            ->get()
            ->filter(fn (Product $item) => $item->name === $productName)
            ->each(fn (Product $item) => $item->update(['is_active' => false]));
    }

    private function moveModelIfSafe(
        string $fromLineName,
        string $toLineName,
        string $modelName
    ): void {
        $fromLine = $this->findLineExact($fromLineName);
        $toLine = $this->findLineExact($toLineName);

        if (! $fromLine || ! $toLine) {
            return;
        }

        $fromModel = MasterModel::query()
            ->where('line_id', $fromLine->id)
            ->get()
            ->first(fn (MasterModel $item) => $item->model === $modelName);

        if (! $fromModel) {
            return;
        }

        $targetModel = MasterModel::query()
            ->where('line_id', $toLine->id)
            ->get()
            ->first(fn (MasterModel $item) => $item->model === $modelName);

        if (! $targetModel) {
            $fromModel->update([
                'line_id' => $toLine->id,
                'is_active' => true,
            ]);
            return;
        }

        if ($targetModel->id !== $fromModel->id) {
            $fromModel->update(['is_active' => false]);
        }
    }

    private function findLineExact(string $lineName): ?Line
    {
        return Line::query()
            ->with('plant')
            ->get()
            ->first(fn (Line $item) => $item->name === $lineName && $item->plant?->is_active);
    }

    private function findModelExact(string $lineName, string $modelName): ?MasterModel
    {
        $line = $this->findLineExact($lineName);

        if (! $line) {
            return null;
        }

        return MasterModel::query()
            ->where('line_id', $line->id)
            ->get()
            ->first(fn (MasterModel $item) => $item->model === $modelName);
    }
}

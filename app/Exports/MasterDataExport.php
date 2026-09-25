<?php

namespace App\Exports;

use App\Models\MasterModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MasterDataExport implements FromView
{
    public function __construct(private readonly string $scope)
    {
    }

    public function view(): View
    {
        $models = MasterModel::query()
            ->where('scope', $this->scope)
            ->with(['products' => fn ($query) => $query->orderBy('name')])
            ->orderBy('number')
            ->get();

        return view('omd.master.exports.excel', [
            'models' => $models,
            'scopeLabel' => strtoupper($this->scope),
        ]);
    }
}

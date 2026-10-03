<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelScrapLimit extends Model
{
    protected $fillable = [
        'master_model_id',
        'limit_qty',
        'effective_from',
        'effective_to',
        'note',
        'created_by',
    ];

    protected $casts = [
        'limit_qty' => 'integer',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function masterModel(): BelongsTo
    {
        return $this->belongsTo(MasterModel::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActiveOn(Builder $query, string $date): Builder
    {
        return $query
            ->whereDate('effective_from', '<=', $date)
            ->where(function (Builder $query) use ($date) {
                $query
                    ->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $date);
            });
    }
}

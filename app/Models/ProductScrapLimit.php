<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductScrapLimit extends Model
{
    public const SOURCE_SEED = 'seed';
    public const SOURCE_OMD = 'omd';

    protected $fillable = [
        'product_id',
        'limit_qty',
        'effective_from',
        'effective_to',
        'source',
        'note',
        'created_by',
    ];

    protected $casts = [
        'limit_qty' => 'integer',
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActiveAt(Builder $query, $dateTime): Builder
    {
        return $query
            ->where('effective_from', '<=', $dateTime)
            ->where(function (Builder $query) use ($dateTime) {
                $query
                    ->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $dateTime);
            });
    }
}

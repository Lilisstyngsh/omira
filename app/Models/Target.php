<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Target extends Model
{
    protected $fillable = [
        'year',
        'month',
        'line_id',
        'target_qty',
        'scrap_limit',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'line_id' => 'integer',
        'target_qty' => 'integer',
        'scrap_limit' => 'integer',
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class);
    }
}

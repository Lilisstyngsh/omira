<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Target extends Model
{
    protected $fillable = ['year', 'month', 'area_id', 'target_qty'];
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}

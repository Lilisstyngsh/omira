<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalYearTarget extends Model
{
    protected $fillable = [
        'year',
        'scope_key',
        'line_id',
        'target_qty',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'line_id' => 'integer',
        'target_qty' => 'integer',
    ];

    public static function scopeKey(?int $lineId): string
    {
        return $lineId ? 'line:' . $lineId : 'all';
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function abnormalities(): HasMany
    {
        return $this->hasMany(MonitoringAbnormality::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringAbnormality extends Model
{
    public const METRIC_TARGET_FY = 'target_fy';

    protected $fillable = [
        'metric',
        'year',
        'month',
        'scope_key',
        'line_id',
        'fiscal_year_target_id',
        'actual_qty',
        'threshold_qty',
        'reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'line_id' => 'integer',
        'fiscal_year_target_id' => 'integer',
        'actual_qty' => 'integer',
        'threshold_qty' => 'integer',
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class);
    }

    public function fiscalYearTarget(): BelongsTo
    {
        return $this->belongsTo(FiscalYearTarget::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

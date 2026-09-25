<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairResult extends Model
{
    protected $fillable = ['repair_order_id', 'ok_qty', 'scrap_qty', 'ng_qty', 'notes', 'processed_by'];

    protected $casts = [
        'ok_qty' => 'integer',
        'scrap_qty' => 'integer',
        'ng_qty' => 'integer',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class, 'repair_order_id');
    }
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

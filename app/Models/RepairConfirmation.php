<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairConfirmation extends Model
{
    protected $fillable = ['repair_order_id', 'confirmed_by_user_id', 'confirmed_at'];
    protected $casts = ['confirmed_at' => 'datetime'];
    public function order(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class, 'repair_order_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by_user_id');
    }
}

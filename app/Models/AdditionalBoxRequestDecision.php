<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalBoxRequestDecision extends Model
{
    protected $fillable = [
        'additional_box_request_id', 'user_scrap_limit_cycle_id', 'product_id',
        'model_name', 'product_name', 'scrap_qty', 'limit_qty',
        'decision', 'reason', 'quantity',
    ];

    protected $casts = [
        'scrap_qty' => 'integer',
        'limit_qty' => 'integer',
        'quantity' => 'integer',
    ];

    public function request()
    {
        return $this->belongsTo(AdditionalBoxRequest::class, 'additional_box_request_id');
    }
}

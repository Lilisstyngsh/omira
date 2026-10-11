<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairMonthlySnapshot extends Model
{
    protected $fillable = [
        'year',
        'month',
        'order_qty',
        'ok_qty',
        'scrap_qty',
        'target_qty',
        'source',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'order_qty' => 'integer',
        'ok_qty' => 'integer',
        'scrap_qty' => 'integer',
        'target_qty' => 'integer',
    ];
}

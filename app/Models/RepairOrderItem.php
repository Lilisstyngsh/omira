<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairOrderItem extends Model
{
    protected $fillable = [
        'repair_order_id',
        'master_model_id',
        'product_id',
        'ng_type_id',
        'before_qty',
        'after_qty',
        'mismatch_note',
    ];

    protected $casts = [
        'before_qty' => 'integer',
        'after_qty' => 'integer',
    ];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(
            RepairOrder::class,
            'repair_order_id'
        );
    }

    public function masterModel(): BelongsTo
    {
        return $this->belongsTo(
            MasterModel::class,
            'master_model_id'
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }

    public function ngType(): BelongsTo
    {
        return $this->belongsTo(
            NgType::class,
            'ng_type_id'
        );
    }
}

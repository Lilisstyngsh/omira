<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RepairOrder extends Model
{
    protected $fillable = ['order_number', 'user_id', 'area_id', 'product_id', 'master_model_id', 'model', 'ng_type_id', 'order_date', 'quantity', 'description', 'status', 'verified_by', 'verified_at', 'repair_started_at', 'repair_completed_at'];
    protected $casts = ['order_date' => 'date', 'verified_at' => 'datetime', 'repair_started_at' => 'datetime', 'repair_completed_at' => 'datetime'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function masterModel(): BelongsTo
    {
        return $this->belongsTo(MasterModel::class, 'master_model_id');
    }
    public function ngType(): BelongsTo
    {
        return $this->belongsTo(NgType::class);
    }
    public function omdVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    public function result(): HasOne
    {
        return $this->hasOne(RepairResult::class);
    }
    public function confirmation(): HasOne
    {
        return $this->hasOne(RepairConfirmation::class);
    }
    public function getStatusLabelAttribute(): string
    {
        return ['draft' => 'Draft', 'submitted' => 'Submitted', 'verified' => 'Verified', 'in_repair' => 'In Repair', 'completed' => 'Completed', 'confirmed' => 'Confirmed'][$this->status] ?? ucfirst($this->status);
    }
}

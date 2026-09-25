<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TpsRepairOrder extends Model
{
    protected $fillable = ['order_number', 'user_id', 'area_id', 'reported_date', 'tool_name', 'problem_description', 'status', 'leader_checked_by', 'leader_checked_at', 'member_verified_by', 'member_verified_at', 'scheduled_at', 'repaired_by', 'repaired_at', 'repair_result', 'confirmed_by_user_id', 'confirmed_at'];
    protected $casts = ['reported_date' => 'date', 'leader_checked_at' => 'datetime', 'member_verified_at' => 'datetime', 'scheduled_at' => 'datetime', 'repaired_at' => 'datetime', 'confirmed_at' => 'datetime'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_checked_by');
    }
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_verified_by');
    }
    public function repairer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'repaired_by');
    }
    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by_user_id');
    }
    public function getStatusLabelAttribute(): string
    {
        return ['submitted' => 'Submitted', 'leader_checked' => 'Leader Checked', 'verified' => 'Verified', 'scheduled' => 'Scheduled', 'in_repair' => 'In Repair', 'completed' => 'Completed', 'confirmed' => 'Confirmed'][$this->status] ?? ucfirst($this->status);
    }
}

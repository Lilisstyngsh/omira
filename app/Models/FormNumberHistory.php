<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormNumberHistory extends Model
{
    protected $fillable = [
        'form_number_id', 'action', 'old_number', 'new_number',
        'old_description', 'new_description', 'old_is_active',
        'new_is_active', 'changed_by',
    ];

    protected $casts = [
        'old_is_active' => 'boolean',
        'new_is_active' => 'boolean',
    ];

    public function formNumber(): BelongsTo
    {
        return $this->belongsTo(FormNumber::class)->withTrashed();
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormNumber extends Model
{
    use SoftDeletes;

    protected $fillable = ['number', 'description', 'is_active', 'created_by'];

    protected $casts = ['is_active' => 'boolean'];

    public static function activeNumber(): string
    {
        $number = static::query()->where('is_active', true)->value('number');
        abort_if($number === null, 503, 'Belum ada Nomor Form aktif. Hubungi OMD.');
        return $number;
    }

    public function histories(): HasMany
    {
        return $this->hasMany(FormNumberHistory::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

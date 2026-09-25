<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Plant extends Model
{

    protected $fillable = [
        'name',
        'is_active',
    ];


    protected $casts = [
        'is_active' => 'boolean',
    ];



    public function lines(): HasMany
    {
        return $this->hasMany(
            Line::class
            );
    }
}

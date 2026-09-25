<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class NgType extends Model
{


    protected $fillable = [

        'code',
        'name',
        'description',
        'is_active',

    ];



    protected $casts = [

        'is_active' => 'boolean',

    ];





    public function products(): BelongsToMany
    {

        return $this->belongsToMany(
            Product::class,
            'product_ng_types'
        );
    }
}

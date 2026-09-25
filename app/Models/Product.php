<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Product extends Model
{


    protected $fillable = [

        'master_model_id',
        'name',
        'code',
        'is_active',

    ];



    protected $casts = [

        'is_active' => 'boolean',

    ];





    public function masterModel(): BelongsTo
    {

        return $this->belongsTo(
            MasterModel::class,
            'master_model_id'
        );
    }





    public function orders(): HasMany
    {

        return $this->hasMany(
            RepairOrder::class
        );
    }
}

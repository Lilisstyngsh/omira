<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class MasterModel extends Model
{


    protected $table = 'master_models';



    protected $fillable = [

        'line_id',
        'number',
        'model',
        'is_active',

    ];




    protected $casts = [

        'number' => 'integer',

        'is_active' => 'boolean',

    ];





    public function line(): BelongsTo
    {

        return $this->belongsTo(
            Line::class
        );
    }




    public function products(): HasMany
    {

        return $this->hasMany(
            Product::class,
            'master_model_id'
        );
    }
}

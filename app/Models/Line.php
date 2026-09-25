<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Line extends Model
{

    protected $fillable = [

        'plant_id',

        'name',

        'is_active'

    ];



    public function plant()
    {

        return $this->belongsTo(
            Plant::class
        );

    }



    public function masterModels()
    {

        return $this->hasMany(
            MasterModel::class
        );

    }

}
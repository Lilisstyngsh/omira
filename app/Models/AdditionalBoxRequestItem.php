<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalBoxRequestItem extends Model
{
    protected $fillable = ['additional_box_request_id', 'user_scrap_limit_cycle_id', 'product_id', 'model_name', 'product_name', 'quantity'];
    protected $casts = ['quantity' => 'integer'];
    public function request() { return $this->belongsTo(AdditionalBoxRequest::class, 'additional_box_request_id'); }
}

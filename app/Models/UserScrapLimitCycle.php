<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserScrapLimitCycle extends Model
{
    protected $fillable = ['user_id','product_id','line_id','limit_qty','started_at','reached_at','reached_qty','decision','decision_reason','decided_at','reset_at','reset_by','reset_reason'];
    protected $casts = ['started_at'=>'datetime','reached_at'=>'datetime','decided_at'=>'datetime','reset_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function line() { return $this->belongsTo(Line::class); }
    public function resetter() { return $this->belongsTo(User::class, 'reset_by'); }
}

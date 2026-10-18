<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalBoxRequest extends Model
{
    public const PURPOSE = 'Penggantian scrap box';
    protected $fillable = ['user_id', 'submission_token', 'purpose'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(AdditionalBoxRequestItem::class)->orderBy('id');
    }
    public function getDocumentNumberAttribute(): string
    {
        return 'AB-' . $this->created_at->format('Y') . '-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }
}

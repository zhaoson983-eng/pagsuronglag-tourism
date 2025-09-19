<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'comment'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'room_number',
        'room_type',
        'price_per_night',
        'capacity',
        'description',
        'image',
        'is_available',
        'amenities'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'is_available' => 'boolean',
        'amenities' => 'array'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}

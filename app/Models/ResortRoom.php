<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResortRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'room_number',
        'room_type',
        'price_per_night',
        'capacity',
        'size',
        'beds',
        'description',
        'image',
        'is_available',
        'amenities'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'size' => 'decimal:1',
        'is_available' => 'boolean',
        'amenities' => 'array'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the galleries for this room.
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'room_id')->where('room_type', 'resort');
    }
}

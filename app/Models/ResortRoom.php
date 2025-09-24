<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResortRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resort_id',
        'room_name',
        'room_type',
        'price_per_night',
        'capacity',
        'description',
        'is_available',
        'amenities'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'is_available' => 'boolean'
        // Removed 'amenities' => 'array' to allow manual string-to-array conversion
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'resort_id', 'id');
    }

    /**
     * Get the galleries for this room.
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'room_id')->where('room_type', 'resort');
    }

    /**
     * Get the images for this room (alias for galleries).
     */
    public function images()
    {
        return $this->galleries();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomImage extends Model
{
    protected $fillable = [
        'room_id',
        'image_path',
        'is_primary',
        'caption',
        'order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the room that owns the image.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}

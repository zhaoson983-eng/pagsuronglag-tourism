<?php
// [file name]: Resort.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resort extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'short_info',
        'full_info',
        'cover_photo',
        'gallery_images',
        'room_details',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'room_details' => 'array',
    ];
}
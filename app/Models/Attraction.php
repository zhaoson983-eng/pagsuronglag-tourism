<?php
// [file name]: Attraction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attraction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'short_info',
        'full_info',
        'cover_photo',
        'gallery_images',
        'has_entrance_fee',
        'entrance_fee',
        'additional_info',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'has_entrance_fee' => 'boolean',
    ];
}
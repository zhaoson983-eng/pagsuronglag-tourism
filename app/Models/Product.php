<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'price',
        'image',
        'average_rating',
        'total_ratings',
        'stock_limit',
        'current_stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'average_rating' => 'decimal:1',
        'total_ratings' => 'integer',
        'stock_limit' => 'integer',
        'current_stock' => 'integer',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all ratings for the product.
     */
    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    /**
     * Get all comments for the product.
     */
    public function comments()
    {
        return $this->hasMany(ProductComment::class)->with('user');
    }

    /**
     * Get all likes for the product.
     */
    public function likes()
    {
        return $this->hasMany(ProductLike::class);
    }

    /**
     * Check if the authenticated user has liked the product.
     */
    public function isLikedByUser()
    {
        if (!auth()->check()) {
            return false;
        }
        
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    /**
     * Check if a specific user has liked the product
     */
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Update the product's rating based on all its ratings.
     */
    public function updateRating()
    {
        $ratings = $this->ratings();
        $this->total_ratings = $ratings->count();
        $this->average_rating = $ratings->avg('rating') ?? 0;
        $this->save();
    }

    /**
     * Get the URL for the product's image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : asset('images/default-product.png');
    }

    /**
     * Get all feedback/ratings for the product.
     */
    public function feedbacks()
    {
        return $this->ratings();
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->current_stock > 0;
    }

    /**
     * Check if product is out of stock
     */
    public function isOutOfStock()
    {
        return $this->current_stock <= 0;
    }

    /**
     * Decrease stock when order is placed
     */
    public function decreaseStock($quantity)
    {
        if ($this->current_stock >= $quantity) {
            $this->current_stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Increase stock (for returns or restocking)
     */
    public function increaseStock($quantity)
    {
        $this->current_stock += $quantity;
        $this->save();
    }

    /**
     * Get stock status text
     */
    public function getStockStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        } elseif ($this->current_stock <= 10) {
            return 'Low Stock';
        }
        return 'In Stock';
    }

    /**
     * Get stock status color class
     */
    public function getStockColorAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'text-red-600 bg-red-100';
        } elseif ($this->current_stock <= 10) {
            return 'text-yellow-600 bg-yellow-100';
        }
        return 'text-green-600 bg-green-100';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_profile_id',
        'image_path',
        'caption',
        'is_featured',
        'type',
        'video_url',
        'sort_order',
        'room_id',
        'cottage_id',
        'room_type'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Relationships
    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class);
    }

    public function cottage()
    {
        return $this->belongsTo(Cottage::class);
    }

    public function resortRoom()
    {
        return $this->belongsTo(ResortRoom::class, 'room_id');
    }

    // Scopes
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Helper Methods
    public function getImageUrl()
    {
        if ($this->type === 'video') {
            return $this->getVideoThumbnailUrl();
        }
        return $this->image_path ? asset('storage/' . $this->image_path) : asset('images/default-gallery.jpg');
    }

    public function getVideoThumbnailUrl()
    {
        if ($this->type === 'video' && $this->video_url) {
            // For YouTube
            if (str_contains($this->video_url, 'youtube.com') || str_contains($this->video_url, 'youtu.be')) {
                $videoId = $this->extractYoutubeId($this->video_url);
                return $videoId ? "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg" : null;
            }
            
            // For Vimeo
            if (str_contains($this->video_url, 'vimeo.com')) {
                $videoId = $this->extractVimeoId($this->video_url);
                if ($videoId) {
                    $vimeo = json_decode(file_get_contents("https://vimeo.com/api/v2/video/{$videoId}.json"));
                    return $vimeo[0]->thumbnail_large ?? null;
                }
            }
        }
        return null;
    }

    public function getVideoEmbedUrl()
    {
        if ($this->type !== 'video' || !$this->video_url) {
            return null;
        }

        // YouTube
        if (str_contains($this->video_url, 'youtube.com') || str_contains($this->video_url, 'youtu.be')) {
            $videoId = $this->extractYoutubeId($this->video_url);
            return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
        }

        // Vimeo
        if (str_contains($this->video_url, 'vimeo.com')) {
            $videoId = $this->extractVimeoId($this->video_url);
            return $videoId ? "https://player.vimeo.com/video/{$videoId}" : null;
        }

        return $this->video_url;
    }

    // Helper methods for video ID extraction
    private function extractYoutubeId($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    private function extractVimeoId($url)
    {
        $pattern = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/';
        preg_match($pattern, $url, $matches);
        return $matches[5] ?? null;
    }

    // Feature/Unfeature
    public function feature()
    {
        // Unfeature all other featured items first
        if ($this->businessProfile) {
            $this->businessProfile->galleries()->where('id', '!=', $this->id)->update(['is_featured' => false]);
        }
        
        $this->update(['is_featured' => true]);
    }

    public function unfeature()
    {
        $this->update(['is_featured' => false]);
    }

    // Reordering
    public function moveUp()
    {
        $previous = $this->businessProfile->galleries()
            ->where('sort_order', '<', $this->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previous) {
            $currentOrder = $this->sort_order;
            $this->update(['sort_order' => $previous->sort_order]);
            $previous->update(['sort_order' => $currentOrder]);
        }
    }

    public function moveDown()
    {
        $next = $this->businessProfile->galleries()
            ->where('sort_order', '>', $this->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $currentOrder = $this->sort_order;
            $this->update(['sort_order' => $next->sort_order]);
            $next->update(['sort_order' => $currentOrder]);
        }
    }
}

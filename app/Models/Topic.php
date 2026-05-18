<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content',
        'video_url',
        'file_path',
        'file_name',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the module that owns the topic.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // Topic.php
    public function contents(){
        return $this->hasMany(TopicContent::class);
    }
    public function quiz(){
        return $this->morphOne(Quiz::class, 'related_to');
    }


    /**
     * Get the file URL for display.
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $videoId = $this->extractYoutubeVideoId($this->video_url);
        if (! $videoId) {
            return null;
        }

        return "https://www.youtube-nocookie.com/embed/{$videoId}";
    }

    private function extractYoutubeVideoId(string $url): ?string
    {
        $url = trim($url);

        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
            return $url;
        }

        $parts = parse_url($url);
        if (! $parts || empty($parts['host'])) {
            return null;
        }

        $host = strtolower($parts['host']);
        $host = preg_replace('/^www\./', '', $host);
        $path = trim($parts['path'] ?? '', '/');
        $videoId = null;

        if ($host === 'youtu.be') {
            $videoId = explode('/', $path)[0] ?? null;
        } elseif ($host === 'youtube.com' || $host === 'm.youtube.com' || str_ends_with($host, '.youtube.com')) {
            if ($path === 'watch') {
                parse_str($parts['query'] ?? '', $query);
                $videoId = $query['v'] ?? null;
            } elseif (str_starts_with($path, 'embed/')) {
                $videoId = explode('/', substr($path, 6))[0] ?? null;
            } elseif (str_starts_with($path, 'shorts/')) {
                $videoId = explode('/', substr($path, 7))[0] ?? null;
            } elseif (str_starts_with($path, 'live/')) {
                $videoId = explode('/', substr($path, 5))[0] ?? null;
            }
        }

        if ($videoId && preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId)) {
            return $videoId;
        }

        return null;
    }

    /**
     * Scope active topics.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered topics.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }
}

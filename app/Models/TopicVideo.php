<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'url',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the topic that owns this video.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the YouTube embed URL.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (! $this->url) {
            return null;
        }

        $videoId = $this->extractYoutubeVideoId($this->url);
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
}

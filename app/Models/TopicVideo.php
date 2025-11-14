<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'video_url',
    ];

    /**
     * A video belongs to a topic.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'file_path',
        'content',
    ];

    /**
     * A topic article belongs to a topic.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}

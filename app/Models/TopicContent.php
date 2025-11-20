<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicContent extends Model
{
    //
    protected $fillable=['topic_id','type','body','file_path','file_name','order'];

    public function topic(){
        return $this->belongsTo(Topic::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($topicContent) {
            // Delete associated file if it exists
            if ($topicContent->file_path) {
                \Illuminate\Support\Facades\Storage::delete($topicContent->file_path);
            }
        });
    }

    public function getBodyAttribute($value)
    {
        if ($this->type === 'text') {
            return $value;
        } elseif (in_array($this->type, ['image', 'video', 'pdf'])) {
            return url('storage/' . $this->file_path);
        }
        return null;
    }
}

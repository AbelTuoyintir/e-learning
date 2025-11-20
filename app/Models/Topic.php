<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // Quiz.php
    public function questions(){
        return $this->hasMany(Question::class);
    }

    /**
     * Get the file URL for display.
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
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

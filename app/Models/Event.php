<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $table = 'event';

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'category_id',
        'organization_id',
        'user_id',
        'image',
        'status',
        'is_online',
        'registration_link',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'is_online' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

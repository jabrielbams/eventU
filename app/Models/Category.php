<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'email',
        'phone',
        'address',
        'logo',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}

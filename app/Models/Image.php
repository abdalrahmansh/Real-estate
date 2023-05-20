<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $guarded = [];

    
    public function houses()
    {
        return $this->hasMany(Post::class);
    }

    public function cars()
    {
        return $this->hasMany(Post::class);
    }

    public function lands()
    {
        return $this->hasMany(Post::class);
    }

    // public function imageable()
    // {
    //     return $this->morphTo();
    // }
}

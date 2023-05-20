<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->morphMany(Post::class, 'postsable');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public $guarded = [];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    public function post()
    {
        return $this->morphMany(Post::class, 'postsable');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public $guarded = [];

    // protected $appends = ['imageable_type'];
    
    // public function getImageableTypeAttribute()
    // {
    //     return $this->imageable_type;
    // }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}

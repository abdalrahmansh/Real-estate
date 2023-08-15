<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    public function postUsers()
    {
        return $this->morphMany(PostUser::class, 'postsable');
    }
    
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostUser extends Model
{
    protected $table = 'post_user';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    protected $guarded = [];
    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}


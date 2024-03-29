<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostUser extends Model
{
    use SoftDeletes;

    protected $table = 'post_user';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postsable()
    {
        return $this->morphTo();
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


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\Operator;

class Post extends Model
{
    use HasFactory;

    public $guarded = [];

    public function postable()
    {
        return $this->morphTo();
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id');
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class);
    }

}

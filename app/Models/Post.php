<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\Operator;

class Post extends Model
{
    use HasFactory;

    public $guarded = [];

    public function postsable()
    {
        return $this->morphTo();
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'post_user');
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class, 'post_user');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'postsable_id',
        'postsable_type'
    ];

}

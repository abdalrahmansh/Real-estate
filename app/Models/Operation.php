<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    public function postUsers()
    {
        return $this->belongsToMany(PostUser::class,);
    }
    public $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}

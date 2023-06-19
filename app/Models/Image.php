<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $guarded = [];

    public function house()
{
    return $this->belongsTo(House::class, 'imageable_id');
}

public function car()
{
    return $this->belongsTo(Car::class, 'imageable_id');
}

public function land()
{
    return $this->belongsTo(Land::class, 'imageable_id');
}
    // public function imageable()
    // {
    //     return $this->morphTo();
    // }
}

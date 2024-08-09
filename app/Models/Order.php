<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function car_makes()
    {
        return $this->belongsToMany(CarMake::class);
    }


}

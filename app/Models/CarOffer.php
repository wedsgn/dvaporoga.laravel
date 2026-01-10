<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'title',
        'price_from',
        'price_old',
        'currency',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}

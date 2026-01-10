<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'one_side',
        'set',
        'thickness_id',
        'steel_type_id',
        'type_id',
        'size_id'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function steel_type()
    {
        return $this->belongsTo(SteelType::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function thickness()
    {
        return $this->belongsTo(Thickness::class);
    }
}

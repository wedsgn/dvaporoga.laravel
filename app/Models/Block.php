<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'title',
        'images',
        'items',
    ];

    protected $casts = [
        'images' => 'array',
        'items' => 'array',
    ];

    public static $blocks_routes = [
        'admin.blocks.index',
        'admin.blocks.edit',
        'admin.blocks.update',
    ];
}

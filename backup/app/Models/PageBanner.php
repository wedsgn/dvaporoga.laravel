<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'title',
        'image_desktop',
        'image_mobile',
        'sort_order',
        'is_active',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}

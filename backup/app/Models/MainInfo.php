<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainInfo extends Model
{
    use HasFactory;

    protected $fillable = [
      'company_title',
      'company_details',
      'phone',
      'whats_app',
      'telegram',
      'company_image',
  ];

}

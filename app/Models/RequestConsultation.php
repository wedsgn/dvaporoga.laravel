<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RequestConsultation extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'form_id',
    ];
    public static $request_consultations_routes = [
      'admin.request_consultations.index',
      'admin.request_consultations.search',
      'admin.request_consultations.show',
      'admin.request_consultations.edit',
      'admin.request_consultations.create'
    ];
    public function scopeFilter($items)
    {
        if (request('search') !== null) {
            $items->where('id', 'ilike', '%' . request('search') . '%')
            ->orWhere('phone', 'ilike', '%' . request('search') . '%')
            ->orWhere('name', 'ilike', '%' . request('search') . '%');
        }
        return $items;
    }
}


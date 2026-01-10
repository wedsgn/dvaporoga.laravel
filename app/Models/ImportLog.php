<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $table = 'import_logs';

    protected $fillable = [
        'import_run_id',
        'level',
        'message',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function run()
    {
        return $this->belongsTo(ImportRun::class, 'import_run_id');
    }
}

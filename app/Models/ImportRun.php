<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportRun extends Model
{
    protected $fillable = [
        'type','status','original_name','stored_path','file_hash',
        'total_rows','processed_rows','current_row','chunk_size',
        'last_error','started_at','finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(ImportLog::class);
    }

    public function progressPercent(): int
    {
        if ($this->total_rows <= 0) return 0;
        return (int)floor(($this->processed_rows / $this->total_rows) * 100);
    }
}

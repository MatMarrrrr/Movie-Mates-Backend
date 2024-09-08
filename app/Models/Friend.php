<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Friend extends Model
{
    protected $fillable = [
        'friend1_id',
        'friend2_id',
        'status',
    ];

    public function friend1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend1_id');
    }

    public function friend2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend2_id');
    }
}

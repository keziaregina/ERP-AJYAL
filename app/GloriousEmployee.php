<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GloriousEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'created_by',
        'month',
        'year'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}

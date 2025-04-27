<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SifExportCounter extends Model
{
    use HasFactory;

    protected $fillable = ['count', 'date', 'business_id'];

    public function business(): BelongsTo {
        return $this->belongsTo(Business::class);
    }
}

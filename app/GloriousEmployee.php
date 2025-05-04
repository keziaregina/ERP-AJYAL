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

    public static function getGloriousEmployeeByMonth($businessId, $month) {
        return self::where('business_id', $businessId)
            ->where('month', $month)
            ->where('year', date('Y'))
            ->get();
    }

    public static function isGloriousEmployee($businessId, $month, $userId) {
        return self::where('month', $month)
            ->where('year', date('Y'))
            ->where('user_id', $userId)
            ->exists();
            // GloriousEmployee::where('month', date('m', strtotime($month)))
            // ->where('year', date('Y', strtotime($month)))
            // ->where('user_id', $user_id)
            // ->first();
    }
}

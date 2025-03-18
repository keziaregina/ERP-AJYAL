<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSettings extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'type', 'interval', 'business_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function getUsers($business_id)
    {
        return ReportSettings::with('user')
            ->where('business_id', $business_id)
            ->get()
            ->map(function ($reportSetting) {
                return [
                    'id' => $reportSetting->id,
                    'user_name' => $reportSetting->user->first_name,
                    'type' => $reportSetting->type,
                    'interval' => $reportSetting->interval,
                ];
            });
    }
}

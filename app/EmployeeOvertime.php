<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeOvertime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'created_by',
        'day',
        'month',
        'year',
        'total_hour',
        'status',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    CONST OVERTIME_HOURS = [
        "0" => "None",
        "0.30" => "30 Minutes",
        "1" => "1 Hours",
        "1.30" => "1.30 Hours",
        "2" => "2 Hours",
        "2.30" => "2.30 Hours",
        "3" => "3 Hours",
        "3.30" => "3.30 Hours",
        "4" => "4 Hours",
        "4.30" => "4.30 Hours",
        "5" => "5 Hours",
        "5.30" => "5.30 Hours",
        "6" => "6 Hours",
        "6.30" => "6.30 Hours",
        "7" => "7 Hours",
        "7.30" => "7.30 Hours",
        "8" => "8 Hours",
        "8.30" => "8.30 Hours",
        "9" => "9 Hours",
        "9.30" => "9.30 Hours",
        "10" => "10 Hours",
        "10.30" => "10.30 Hours",
        "11" => "11 Hours",
        "11.30" => "11.30 Hours",
        "12" => "12 Hours",
        "12.30" => "12.30 Hours",
        "13" => "13 Hours",
        "13.30" => "13.30 Hours",
        "14" => "14 Hours",
        "14.30" => "14.30 Hours",
        "15" => "15 Hours",
        "15.30" => "15.30 Hours",
        "16" => "16 Hours",
        "16.30" => "16.30 Hours",
        "17" => "17 Hours",
        "17.30" => "17.30 Hours",
        "18" => "18 Hours",
        "18.30" => "18.30 Hours",
        "19" => "19 Hours",
        "19.30" => "19.30 Hours",
        "20" => "20 Hours",
        "20.30" => "20.30 Hours",
        "21" => "21 Hours",
        "21.30" => "21.30 Hours",
        "22" => "22 Hours",
        "22.30" => "22.30 Hours",
        "23" => "23 Hours",
        "23.30" => "23.30 Hours",
        "24" => "24 Hours",
        "A" => "Absent",
        "VL" => "Vacation Leave",
        "GE" => "Glorious Employee Allowance",
        "SL" => "Sick Leave"
    ];

    CONST ATTENDANCE_STATUS = [
        'A' => 'Absent',
        'VL' => 'Vacation Leave',
        'GE' => 'Glorious Employee Allowance',
        'SL' => 'Sick Leave'
    ];
}

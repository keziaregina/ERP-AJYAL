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
        'type'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    CONST OVERTIME_HOURS = [
        "0"    => "None",
        "0.5"  => "30 Minutes",
        "1"    => "1 Hours",
        "1.5"  => "1.30 Hours",
        "2"    => "2 Hours",
        "2.5"  => "2.30 Hours",
        "3"    => "3 Hours",
        "3.5"  => "3.30 Hours",
        "4"    => "4 Hours",
        "4.5"  => "4.30 Hours",
        "5"    => "5 Hours",
        "5.5"  => "5.30 Hours",
        "6"    => "6 Hours",
        "6.5"  => "6.30 Hours",
        "7"    => "7 Hours",
        "7.5"  => "7.30 Hours",
        "8"    => "8 Hours",
        "8.5"  => "8.30 Hours",
        "9"    => "9 Hours",
        "9.5"  => "9.30 Hours",
        "10"   => "10 Hours",
        "10.5" => "10.30 Hours",
        "11"   => "11 Hours",
        "11.5" => "11.30 Hours",
        "12"   => "12 Hours",
        "12.5" => "12.30 Hours",
        "13"   => "13 Hours",
        "13.5" => "13.30 Hours",
        "14"   => "14 Hours",
        "14.5" => "14.30 Hours",
        "15"   => "15 Hours",
        "15.5" => "15.30 Hours",
        "16"   => "16 Hours",
        "16.5" => "16.30 Hours",
        "17"   => "17 Hours",
        "17.5" => "17.30 Hours",
        "18"   => "18 Hours",
        "18.5" => "18.30 Hours",
        "19"   => "19 Hours",
        "19.5" => "19.30 Hours",
        "20"   => "20 Hours",
        "20.5" => "20.30 Hours",
        "21"   => "21 Hours",
        "21.5" => "21.30 Hours",
        "22"   => "22 Hours",
        "22.5" => "22.30 Hours",
        "23"   => "23 Hours",
        "23.5" => "23.30 Hours",
        "24"   => "24 Hours",
        "A"    => "Absent",
        "VL"   => "Vacation Leave",
        "GE"   => "Glorious Employee Allowance",
        "SL"   => "Sick Leave"
    ];

    CONST ATTENDANCE_STATUS = [
        'A'  => 'Absent',
        'VL' => 'Vacation Leave',
        'GE' => 'Glorious Employee Allowance',
        'SL' => 'Sick Leave'
    ];


    CONST TYPES = [
        'M'  => 'Manual Overtime',
        'LR' => 'Leave Request',
        'A'  => 'Auto',
    ];
}

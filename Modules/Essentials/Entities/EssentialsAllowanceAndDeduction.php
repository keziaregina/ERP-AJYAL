<?php

namespace Modules\Essentials\Entities;

use App\Utils\Util;
use Illuminate\Database\Eloquent\Model;

class EssentialsAllowanceAndDeduction extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'essentials_allowances_and_deductions';

    public function employees()
    {
        return $this->belongsToMany(\App\User::class, 'essentials_user_allowance_and_deductions', 'allowance_deduction_id', 'user_id');
    }

    public static function forDropdown($business_id)
    {
        $ads = EssentialsAllowanceAndDeduction::whereNull('applicable_date')
                    ->where('business_id', $business_id)
                    ->select('id', 'description', 'type', 'amount', 'amount_type')
                    ->get();

        $util = new Util();
        $pay_components = [];
        foreach ($ads as $ad) {
            if ($ad->amount_type != 'percent') {
                $amount = $util->num_f($ad->amount, true);
            } else {
                $amount = $util->num_f($ad->amount);
                $amount .= '%';
            }

            $pay_components[$ad->id] = $ad->description.' ('.$amount.' '.__('essentials::lang.'.$ad->type).')';
        }

        return $pay_components;
    }

    // public static function getAllAllowanceAndDeductionsPerEmployee($businessId, $userId, $startDate = null, $endDate = null) {
    //     $query = self::where('business_id', $businessId)
    //         ->whereHas('employees', function ($query) use ($userId) {
    //             $query->where('user_id', $userId);
    //         });
    //         // ->whereHas('employees', function ($query) use ($userId) {
    //         //     $query->where('user_id', $userId);
    //         // });

    //     if ($startDate && $endDate) {
    //         $query->whereBetween('applicable_date', [$startDate, $endDate]);
    //     }

    //     return $query->get();
    // }
}

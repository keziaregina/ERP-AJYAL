<?php

namespace Modules\Essentials\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Essentials\Entities\EssentialsLeave;

class EssentialsLeaveType extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function forDropdown($business_id)
    {
        $leave_types = EssentialsLeaveType::where('business_id', $business_id)
                                    ->pluck('leave_type', 'id');

        return $leave_types;
    }

    public function Leave()
    {
        return $this->belongsTo(EssentialsLeave::class);
    }
}

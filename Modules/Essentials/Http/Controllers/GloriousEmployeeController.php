<?php

namespace Modules\Essentials\Http\Controllers;

use App\GloriousEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class GloriousEmployeeController extends Controller
{
    function store(Request $request) {

        try {
            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);

            $gloriousEmployee = GloriousEmployee::updateOrCreate([
                'month'      => date('m'),
                'year'       => date('Y'),
                'created_by' => auth()->id()
            ],[
                'user_id'    => $request->user_id,
            ]);

            return redirect()->back()->with('success','Created Successfully.');

        } catch (\Exception $e) {
            Log::error("Error storing glorious employee : " . $e->getMessage());
            throw $e;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\Media;
use App\EmployeeBicCode;
use App\SalaryFrequency;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | UserController
    |--------------------------------------------------------------------------
    |
    | This controller handles the manipualtion of user
    |
    */

    /**
     * All Utils instance.
     */
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Shows profile of logged in user
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfile()
    {
        $businessId = Auth::user()->business_id;
        $bicCode = EmployeeBicCode::where('business_id', $businessId)->get();
        $user_id = request()->session()->get('user.id');
        $user = User::where('id', $user_id)->with(['media'])->first();
        $config_languages = config('constants.langs');
        $languages = [];
        $salaryCode = SalaryFrequency::where('business_id', $businessId)->get();
        foreach ($config_languages as $key => $value) {
            $languages[$key] = $value['full_name'];
        }

        return view('user.profile', compact('user', 'languages', 'bicCode', 'salaryCode'));
    }

    /**
     * updates user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        // dd($request->all());
        //Disable in demo
        $notAllowed = $this->moduleUtil->notAllowedInDemo();
        if (! empty($notAllowed)) {
            return $notAllowed;
        }

        try {
            $user_id = $request->session()->get('user.id');
            $input = $request->only(['surname', 'first_name', 'last_name', 'email', 'language', 'marital_status',
                'blood_group', 'contact_number', 'fb_link', 'twitter_link', 'social_media_1',
                'social_media_2', 'permanent_address', 'current_address',
                'guardian_name', 'custom_field_1', 'custom_field_2',
                'custom_field_3', 'custom_field_4', 'id_proof_name', 'id_proof_number', 'gender', 'family_number', 'alt_number', ]);

            if (! empty($request->input('dob'))) {
                $input['dob'] = $this->moduleUtil->uf_date($request->input('dob'));
            }
            if (! empty($request->input('bank_details'))) {
                $input['bank_details'] = json_encode($request->input('bank_details'));
            }

            $salary = SalaryFrequency::find($request->salary_code);
            if ($salary) {
                $input['salary_id'] = $request->salary_code;
            } else {
                $newSalary = SalaryFrequency::create([
                    'name' => $request->salary_code,
                    'business_id' => Auth::user()->business_id,
                ]);
                $input['salary_id'] = $newSalary->id;
            }

            $bic = EmployeeBicCode::find($request->bic_code);
            if ($bic) {
                $input['bic_id'] = $request->bic_code;
            } else {
                $newBic = EmployeeBicCode::create([
                    'name' => $request->bic_code,
                    'business_id' => Auth::user()->business_id,
                ]);
                $input['bic_id'] = $newBic->id;
            }

            
            $user = User::find($user_id);
            $user->update($input);
            
            Media::uploadMedia($user->business_id, $user, request(), 'profile_photo', true);
            
            //update session
            $input['id'] = $user_id;
            $business_id = request()->session()->get('user.business_id');
            $input['business_id'] = $business_id;
            session()->put('user', $input);

            $output = ['success' => 1,
                'msg' => __('lang_v1.profile_updated_successfully'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect('user/profile')->with('status', $output);
    }

    /**
     * updates user password
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        //Disable in demo
        $notAllowed = $this->moduleUtil->notAllowedInDemo();
        if (! empty($notAllowed)) {
            return $notAllowed;
        }

        try {
            $user_id = $request->session()->get('user.id');
            $user = User::where('id', $user_id)->first();

            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
                $user->save();
                $output = ['success' => 1,
                    'msg' => __('lang_v1.password_updated_successfully'),
                ];
            } else {
                $output = ['success' => 0,
                    'msg' => __('lang_v1.u_have_entered_wrong_password'),
                ];
            }
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect('user/profile')->with('status', $output);
    }
}

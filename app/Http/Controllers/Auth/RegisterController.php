<?php

namespace App\Http\Controllers\Auth;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'allow_export' => [''],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'allow' => ['required','digits_between:0,3'],
            'brand_id' => ['array'],
            'country_id' => ['array'],
            'partner_id' => ['array'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $allow = (!empty($data['allow_export'])) ? true : false;
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'allow_export' => $allow,
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if(!empty($request['role'])){
            $role = Role::where('name', $request['role'])->first();
            if (!empty($role)) {
                $user->roles()->save($role);
            }
        }else{
            $role = Role::where('name', 'user')->first();
            if (!empty($role)) {
                $user->roles()->save($role);
            }
        }

        if ($request['allow'] == 1) {
            $user->partners()->attach($request['partner_id']);
        } else if ($request['allow'] == 2) {
            $user->countries()->attach($request['country_id']);
        } else if ($request['allow'] == 3) {
            $user->brands()->attach($request['brand_id']);
        }

        Log::info('user', [$user['name'] => 'Has be created','userId'=>Auth::id()]);
        return redirect()->back();
    }
}

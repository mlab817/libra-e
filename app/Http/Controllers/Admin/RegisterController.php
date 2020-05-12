<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\AdminRole;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


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
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
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
            'username' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'admin_role_id' => ['required', 'integer'],
            'status' => ['required', 'integer'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        return Admin::create([
            'username' => $data['username'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'admin_role_id' => $data['admin_role_id'],
            'status' => 1,
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register_form() 
    {
        $admin_roles = AdminRole::where('status', 1)->get();

        return view('admin.adminRegister')->with('admin_roles', $admin_roles);
    }

    public function register(Request $request) 
    {
        $this->validator($request->all());
        $this->create($request->all());
        return redirect('admin/login');
    }
}

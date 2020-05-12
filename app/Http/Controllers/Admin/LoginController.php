<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

//use Illuminate\Https\Request;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard/main_dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        foreach ($this->guard()->admin()->roles as $role) {
            if ($role->description == 'Head Librarian') {
                return redirect('admin/dashboard/main_dashboard');
            }
            elseif ($role->description == 'Assist. Librarian') {
                return redirect('admin/dashboard/main_dashboard');
            }
            elseif ($role->description == 'Student Assistant') {
                return redirect('admin/dashboard/main_dashboard');
            }
        }
    }

    public function login_form() 
    {
        return view('admin.adminLogin');
    }
    
    public function guard()
    {
        return Auth::guard('admin');
    }
    
    public function login(Request $request) 
    {
        $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8'],
        ]);


        if (Auth::guard('admin')
                ->attempt([
                    'username' => $request->username, 
                    'password' => $request->password, 
                    'status' => 1
                ])) 
            {
            // Authentication passed...
            return redirect()->intended('admin/dashboard/main_dashboard');
            
        }else{
            
            $request->session()->flash('invalid_login', 'true');
            return redirect('admin/login');

        }

    }
}

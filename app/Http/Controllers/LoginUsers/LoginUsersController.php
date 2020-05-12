<?php

namespace App\Http\Controllers\LoginUsers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LoginUsersController extends Controller
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
     
    
    
    /*
    public function login(Request $request) 
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);


        if (Auth::guard('student')
                ->attempt([
                    'email_add' => $request->email, 
                    'password' => $request->password, 
                    'status' => 1
                ])) 
            {

            session(['guard_login' => 'students' ]);

            // Authentication passed...
            return redirect()->intended('/');
            
        }else if (Auth::guard('staff_coach')
                ->attempt([
                    'email_add' => $request->email, 
                    'password' => $request->password, 
                    'status' => 1
                ])) 
            {
                
            session(['guard_login' => 'staff_coaches' ]);
                
            // Authentication passed...
            return redirect()->intended('/');
            
        }{
            
            $request->session()->flash('invalid_login', 'true');
            return redirect('/');

        }

    }
    */
    
}

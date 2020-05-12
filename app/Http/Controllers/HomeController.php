<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\NoAccession; 

use App\Student; 
use App\StaffCoach; 


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get_loggedIn_user_data()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user_ref_id  = $user->user_ref_id;
            $user_type  = $user->user_type;

            if($user_type == 1){

                $user = Student::where('id', $user_ref_id)->first();
                
            }else if($user_type == 2){
                
                $user = StaffCoach::where('id', $user_ref_id)->first();

            }

            session(['loggedIn_user_data' => $user ]);
            session(['loggedIn_user_ref_id' => $user_ref_id ]);
            session(['loggedIn_user_type' => $user_type ]);
            
        }
    }
    
    public function index()
    {
        $this->get_loggedIn_user_data();
        return view('home');
    }

    public function try()
    {
        return view('try');
    }

    public function try_api()
    {
        $aquisition = NoAccession::where('status', 1)->get();

        return json_decode($aquisition);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}

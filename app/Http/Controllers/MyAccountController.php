<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\User; 
use App\Student; 
use App\StaffCoach; 

class MyAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function get_loggedIn_user_data()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user_id  = $user->id;
            $user_ref_id  = $user->user_ref_id;
            $user_type  = $user->user_type;

            if($user_type == 1){

                $user = Student::where('id', $user_ref_id)->first();
                
            }else if($user_type == 2){
                
                $user = StaffCoach::where('id', $user_ref_id)->first();

            }

            session(['loggedIn_user_data' => $user ]);
            session(['loggedIn_user_id' => $user_id ]);
            session(['loggedIn_user_ref_id' => $user_ref_id ]);
            session(['loggedIn_user_type' => $user_type ]);
            
        }
    }

    public function my_account()
    {
        $this->get_loggedIn_user_data();
        
        $all_user_data = $this->get_all_user_data();
        
        return view('libraE.my_account.my_account')->with('all_user_data', $all_user_data);    
        
    }
    
    public function get_all_user_data()
    {
        $user_id = session()->get('loggedIn_user_id');

        $user_type = session()->get('loggedIn_user_type');

        $user_ref_id = session()->get('loggedIn_user_ref_id');
        
        if($user_type == 1){

            $student = $this->get_user_query($user_type);

            $student = $student->where('students.id', $user_ref_id)->first();

            return $student;
            
        }else if($user_type == 2){
            
            $coach = $this->get_user_query($user_type);
            
            $coach = $coach->where('staff_coaches.id', $user_ref_id)->first();
            
            return $coach;
            
        }
    }

    public function get_user_query($user_type)
    {
        if($user_type == 1){
            
            $student = Student::join('programs', 'students.program_id', '=', 'programs.id')
                ->join('sections', 'students.section_id', '=', 'sections.id')
                ->select(
                    'students.*', 
                    'students.id AS student_id', 
                    'programs.code AS program_code', 
                    'programs.section_code AS program_section_code', 
                    'programs.type AS program_type', 
                    'programs.name AS program_name', 
                    'sections.code AS section_code', 
                    'students.status AS student_status'
                )
                ->selectRaw('CONCAT(f_name, " " , m_name, " " , l_name) AS full_name');

            return $student;

        }else if($user_type == 2){
            
            $coach = StaffCoach::join('departments', 'staff_coaches.department_id', '=', 'departments.id')
                ->select(
                    'staff_coaches.*', 
                    'staff_coaches.id AS staff_coach_id', 
                    'departments.name AS department_name', 
                    'staff_coaches.status AS staff_coach_status'
                )
                ->selectRaw('CONCAT(f_name, " " , m_name, " " , l_name) AS full_name');

            return $coach;

        }
    }

    public function edit_my_account()
    {
        $this->get_loggedIn_user_data();

        $all_user_data = $this->get_all_user_data();
        
        return view('libraE.my_account.edit_my_account')->with('all_user_data', $all_user_data);    
        
    }

    public function update_my_account(Request $request)
    {
        $this->get_loggedIn_user_data();
        
        if($request->isMethod('put')){

            $request->validate([
                'f_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'm_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'l_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'contact_no' => 'required|digits:10',
                'email_add' => 'required',
                'address' => 'required',
                'pic_file' => 'nullable|image|mimes:jpeg,bmp,png',
            ]);
            
            $user_id = session()->get('loggedIn_user_id');

            $user_type = session()->get('loggedIn_user_type');

            $user_ref_id = session()->get('loggedIn_user_ref_id');
            
            if($user_type == 1){

                $user_account = Student::find($user_ref_id);
                
            }else if($user_type == 2){
                
                $user_account = StaffCoach::find($user_ref_id);
                    
            }
            

            $user_account->f_name = ltrim(ucfirst($request->f_name));

            $user_account->m_name = ltrim(ucfirst($request->m_name));

            $user_account->l_name = ltrim(ucfirst($request->l_name));

            $user_account->contact_no = $request->contact_no;
            
            $student_email = $request->email_add;
        
            if($request->isMethod('put')){
                
                $user_account->email_add = $student_email;
                
                $user = User::where([
                    ['user_ref_id', $user_ref_id],
                    ['user_type', $user_type]
                ])->update(['email' => $student_email]);
                
            }else{
                
                $user_account->email_add = $student_email;
                    
            }
            
            $user_account->address = ltrim(ucfirst($request->address));
            
            // Handle File Upload
            if($request->hasFile('pic_file')){
                // Get Filename with the extension
                $filenameWithExt = $request->file('pic_file')->getClientOriginalName();
                // Get Just Filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get Just Ext
                $extension = $request->file('pic_file')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload Image

                if($user_type == 1){

                    $path = $request->file('pic_file')->storeAs('public/images/student_images', $fileNameToStore);
                    
                }else if($user_type == 2){
                    
                    $path = $request->file('pic_file')->storeAs('public/images/staff_coach_images', $fileNameToStore);
                        
                }

            }else{
                
                if($request->isMethod('put')){

                    $fileNameToStore = $request->pic_name;

                }else{

                    $fileNameToStore = 'noimage.png';
                    
                }
            }
            
            $user_account->pic_url = $fileNameToStore; 

            $user_account->save();

            session()->flash('success_status', 'Account Updated!');

            return redirect()->route('libraE.my_account');
            
        }else{
            
            return redirect()->route('libraE.my_account');

        }
    }
    
    public function update_my_account_password(Request $request)
    {
        $this->get_loggedIn_user_data();
        
        if($request->isMethod('put')){

            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8',
                'rep_new_password' => 'required|string|min:8',
            ]);
            
            $input_current_password = $request->current_password;

            $current_user = User::where('id',session()->get('loggedIn_user_id'))->first();

            $user_current_password = $current_user['password'];

            if(Hash::check($input_current_password, $user_current_password)){

                if($request->new_password != $request->rep_new_password){
                
                    session()->flash('error_status', 'Password does not match!');
    
                    return redirect()->route('libraE.edit_my_account');
                    
                }else{
    
                    $user_id = session()->get('loggedIn_user_id');
        
                    $user_type = session()->get('loggedIn_user_type');
        
                    $user_ref_id = session()->get('loggedIn_user_ref_id');
        
                    $user_account = User::find($user_id);
                    
                    $user_account->password = Hash::make(ltrim($request->new_password));
        
                    $user_account->save();
                    
                    session()->flash('success_status', 'Account Password Updated!');
    
                    return redirect()->route('libraE.my_account');
    
                }
                
            }else{
                
                session()->flash('error_status', 'Current Password does not match!');

                return redirect()->route('libraE.edit_my_account');

            }
        }
    }
}

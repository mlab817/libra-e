<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;

use App\StaffCoach;
use App\Department;

class StaffCoachController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function staff_coaches_view()
    {
        $staff_coaches = $this->get_all_staff_coaches();

        $all_count = $this->get_all_count();
        
        return view('admin.accounts.staff_coaches.staff_coaches')->with('staff_coaches', $staff_coaches)
            ->with('all_count', $all_count);        

    }

    public function get_all_count()
    {
        $employed = StaffCoach::where('status', 1)->count();

        $un_employed = StaffCoach::where('status', 2)->count();

        $all = StaffCoach::count();

        $all_count = [
            'employed' => $employed,
            'un_employed' => $un_employed,
            'all' => $all
        ];

        return $all_count;
        
    }

    public function check_session_queries()
    {
        if(session()->has('staff_coaches_toOrder') != true){

            session(['staff_coaches_toOrder' => 'created_at' ]);
            
        }

        if(session()->has('staff_coaches_orderBy') != true){

            session(['staff_coaches_orderBy' => 'desc' ]);
            
        }

        if (session()->has('staff_coaches_per_page') != true) {

            session(['staff_coaches_per_page' => 5 ]);
            
        }
    }
    
    public function get_all_staff_coaches()
    {
        $this->check_session_queries();

        $staff_coaches_query = $this->get_staff_coach_query();

        if(session()->has('staff_coaches_getAll')){

            if(session()->get('staff_coaches_getAll') != 'all'){

                $staff_coaches_query = $staff_coaches_query->where('staff_coaches.status', session()->get('staff_coaches_getAll'));

            }else{
                    
                session(['staff_coaches_getAll' => 'all' ]);
            }
            
        }else{

            session(['staff_coaches_getAll' => 'all' ]);
            
        }
        
        $staff_coaches = $staff_coaches_query->orderBy(session()->get('staff_coaches_toOrder'), session()->get('staff_coaches_orderBy'))
            ->paginate(session()->get('staff_coaches_per_page'));

        if($staff_coaches->count() > 0){
            
            return $staff_coaches;
            
        }else{
            
            session()->flash('error_status', 'No Staff/Coaches Yet!');
            return $staff_coaches;

        }
    }

    public function staff_coaches_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200 || 500){
            session(['staff_coaches_per_page' => $per_page ]);
        }else{
            session(['staff_coaches_per_page' => 5 ]);
        }

        return redirect()->route('admin.accounts.staff_coaches');

    }

    public function staff_coaches_toOrder($ToOrder = 'created_at') 
    {

        if($ToOrder == 'lib_card_no' || 'emp_id_no' || 'name' || 'program_code' || 'grade_year' || 'created_at' || 'updated_at'){
            
            session(['staff_coaches_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['staff_coaches_toOrder' => 'created_at' ]);

        }

        return redirect()->route('admin.accounts.staff_coaches');

    }

    public function staff_coaches_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || 'desc' ){
            
            session(['staff_coaches_orderBy' => $orderBy ]);
            
        }else{
            
            session(['staff_coaches_orderBy' => 'desc' ]);

        }

        return redirect()->route('admin.accounts.staff_coaches');

    }

    public function filter_staff_coaches($filter = 'all') 
    {
        
        if($filter == 'all' || $filter == 0 || $filter == 1 || $filter == 2 || $filter == 3){
            
            session(['staff_coaches_getAll' => $filter ]);
            
        }else{
            
            session(['staff_coaches_getAll' => 'all' ]);

        }

        return redirect()->route('admin.accounts.staff_coaches');

    }

    public function get_staff_coach_query()
    {
        $staff_coaches_query = StaffCoach::join('departments', 'staff_coaches.department_id', '=', 'departments.id')
            ->select(
                'staff_coaches.*', 
                'staff_coaches.id AS staff_coach_id', 
                'departments.name AS department_name', 
                'staff_coaches.status AS staff_coach_status'
            )
            ->selectRaw('CONCAT(f_name, " " , m_name, " " , l_name) AS full_name');

        return $staff_coaches_query;

    }

    public function search_staff_coach($search = '')
    {
        $this->check_session_queries();
        
        $staff_coaches_query = $this->get_staff_coach_query();

        $staff_coaches_query->where('staff_coaches.lib_card_no', 'like', '%'.$search.'%')
            ->orWhere('staff_coaches.emp_id_no', 'like', '%'.$search.'%')
            ->orWhere('staff_coaches.f_name', 'like', '%'.$search.'%')
            ->orWhere('staff_coaches.m_name', 'like', '%'.$search.'%')
            ->orWhere('staff_coaches.l_name', 'like', '%'.$search.'%')
            ->orWhere('departments.name', 'like', '%'.$search.'%')
            ->orWhere('staff_coaches.school_year', 'like', '%'.$search.'%');
            
        $count = $staff_coaches_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);
        session(['staff_coaches_getAll' => 'all' ]);
        
        if($count > 0){

            $staff_coaches = $staff_coaches_query->orderBy(session()->get('staff_coaches_toOrder'), session()->get('staff_coaches_orderBy'))
                ->paginate(session()->get('staff_coaches_per_page'));

            $all_count = $this->get_all_count();

            return view('admin.accounts.staff_coaches.staff_coaches')->with('staff_coaches', $staff_coaches)
                ->with('all_count', $all_count);     

        }else{
            
            session()->flash('error_status', 'No data found!');
            
            $staff_coaches = $staff_coaches_query->paginate(session()->get('staff_coaches_per_page'));

            $all_count = $this->get_all_count();
        
            return view('admin.accounts.staff_coaches.staff_coaches')->with('staff_coaches', $staff_coaches)
                ->with('all_count', $all_count);     
            
        }
    }


    public function view_staff_coach($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $staff_coach = $this->get_staff_coach_data($id);
            
            if($staff_coach){
                
                return view('admin.accounts.staff_coaches.view_staff_coach')->with('staff_coach', $staff_coach);
                
            }else{
                
                return redirect()->route('admin.accounts.staff_coaches');
                
            }
            
        }else{
            
            return redirect()->route('admin.accounts.staff_coaches');

        }
    }
    
    public function add_staff_coach_view()
    {
        $file_maintenance = $this->get_file_maintenance();
        return view('admin.accounts.staff_coaches.add_staff_coach')->with('file_maintenance', $file_maintenance);
    }

    public function get_file_maintenance()
    {
        $last_lib_card_no = $this->get_last_lib_card_no();
        
        $new_lib_card_no = $this->to_string_add_lib_card_no($last_lib_card_no);

        $departments = Department::orderBy('name', 'asc')->get();

        return $file_maintenance = [
            'new_lib_card_no' => $new_lib_card_no,
            'departments' => $departments
        ];

    }

    public function to_string_add_lib_card_no($staff_coach_no)
    {
        $add = true;
        
        while($add){
            
            $staff_coach_no = (string)$staff_coach_no;

            $staff_coach_no++;
            
            $exist = StaffCoach::where('lib_card_no', $staff_coach_no)->exists();
            
            if($exist != true){
                
                $add = false;
                
            }
            
        }

        return $this->to_string_lib_card_no($staff_coach_no);
        
    }

    public function to_string_lib_card_no($staff_coach_no)
    {
        $num = $staff_coach_no;
        $num = (string)$num;
        $num_length = strlen($num);


        for($i = $num_length; $i < 6; $i++){
            $num = "0" . $num;
        }

        return $num;

    }

    public function get_last_lib_card_no()
    {
        $count = StaffCoach::count();
        
        if($count == 0){
            
            return 0;

        }else{
            
            $last_staff_coach_no = StaffCoach::orderBy('lib_card_no', 'desc')->select('lib_card_no')->first();
            return $last_staff_coach_no->lib_card_no;
            
        }
    }

    public function store_staff_coach(Request $request)
    {
        //return $request->all();
        
        if($request->isMethod('put')){
            
            $request->validate([
                'lib_card_no' => 'required|numeric',
                'emp_id_no' => 'required|digits:11',
                'pic_file' => 'nullable|image|mimes:jpeg,bmp,png',
                'email_add' => 'required',
            ]);
            
        }else{
            
            $request->validate([
                'lib_card_no' => 'required|unique:staff_coaches,emp_id_no',
                'emp_id_no' => 'required|unique:staff_coaches,emp_id_no|digits:11',
                'email_add' => 'required|unique:users,email',
                'pic_file' => 'required|image|mimes:jpeg,bmp,png',
            ]);
            
        }
        
        $request->validate([
            'f_name' => 'required|regex:/^[a-z ñ\-]+$/i',
            'm_name' => 'required|regex:/^[a-z ñ\-]+$/i',
            'l_name' => 'required|regex:/^[a-z ñ\-]+$/i',
            'gender' => 'required|numeric',
            'address' => 'required',
            'contact_no' => 'required|digits:10',
            'department' => 'required|numeric',
            'school_year' => 'required|digits:4',
            'status' => 'required|numeric'
        ]);
        
        if($request->isMethod('put')){

            $stud_id_exist = $this->check_staff_coach_id_exist($request->id, $request->emp_id_no);
    
            if($stud_id_exist){
                
                session()->flash('error_status', 'Staff Coach ID No Already Exist!');
                return redirect()->route('admin.accounts.edit_staff_coach', [$request->id]);
                
            }
            
            $staff_coach_email = $request->email_add;
            
            $email_exists = $this->check_user_email_exist($request->id, $staff_coach_email);
            
            if($email_exists){
                
                session()->flash('error_status', 'Staff Coach Email Already Exist!');
                return redirect()->route('admin.accounts.edit_staff_coach', [$request->id]);
    
            }
        }

        $staff_coach = $request->isMethod('put') ? StaffCoach::findOrFail($request->id) : new StaffCoach;
        
        $staff_coach->lib_card_no = $request->lib_card_no;

        $staff_coach->emp_id_no = $request->emp_id_no;

        $staff_coach->f_name = ltrim(ucfirst($request->f_name));

        $staff_coach->m_name = ltrim(ucfirst($request->m_name));

        $staff_coach->l_name = ltrim(ucfirst($request->l_name));

        $staff_coach->gender = $request->gender;

        $staff_coach->address = ltrim(ucfirst($request->address));

        $staff_coach_email = $request->email_add;
        
        if($request->isMethod('put')){
            
            $staff_coach->email_add = $staff_coach_email;
            
            $user = User::where([
                ['user_ref_id', $request->id],
                ['user_type', 2]
            ])->update(['email' => $staff_coach_email]);
            
        }else{
            
            $staff_coach->email_add = $staff_coach_email;
            
        }

        $staff_coach->contact_no = $request->contact_no;

        $staff_coach->department_id = $request->department;

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
            $path = $request->file('pic_file')->storeAs('public/images/staff_coach_images', $fileNameToStore);
        }else{
            
            if($request->isMethod('put')){

                $fileNameToStore = $request->pic_name;

            }else{

                $fileNameToStore = 'noimage.png';
                
            }
        }

        $staff_coach->pic_url = $fileNameToStore; 

        $staff_coach->school_year = $request->school_year;

        $staff_coach->status = $request->status;

        $staff_coach->save();
        
        $inserted_id = $staff_coach->id;
        
        if($request->isMethod('put')){
            
            $request->session()->flash('success_status', 'Staff Coach Updated!');
            
        }else{

             $this->add_staff_coach_to_users($inserted_id, $staff_coach_email);
            $request->session()->flash('success_status', 'Staff Coach Added!');

        }
            
        return redirect()->route('admin.accounts.staff_coaches');
        
    }

    public function check_staff_coach_id_exist($staff_coach_id, $emp_id_no)
    {
        $same_staff_coach = StaffCoach::where([
            ['id', $staff_coach_id],
            ['emp_id_no', $emp_id_no]
        ])->exists();
        
        if($same_staff_coach){
            
            return false;
            
        }else{
            
            $existing_staff_coach = StaffCoach::where('emp_id_no', $emp_id_no)->exists();

            if($existing_staff_coach){
                
                return true;

            }else{
                
                return false;
                
            }
        }
        
    }

    public function check_user_email_exist($user_ref_id, $email)
    {
        $same_coach = StaffCoach::where([
            ['id', $user_ref_id],
            ['email_add', $email]
        ])->exists();
        
        if($same_coach){
            
            return false;
            
        }else{
            
            $existing_email = User::where('email', $email)->exists();

            if($existing_email){
                
                return true;

            }else{
                
                return false;
                
            }
        }
    }

    public function edit_staff_coach_view($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $staff_coach = $this->get_staff_coach_data($id);
            
            if($staff_coach){

                $file_maintenance = $this->get_file_maintenance();
                
                return view('admin.accounts.staff_coaches.edit_staff_coach')->with('staff_coach', $staff_coach)
                    ->with('file_maintenance', $file_maintenance);

            }else{
                
                return redirect()->route('admin.accounts.staff_coaches');

            }
            
        }else{
            
            return redirect()->route('admin.accounts.staff_coaches');

        }
    }

    public function get_staff_coach_data($staff_coach_id)
    {

        $exist = StaffCoach::where('staff_coaches.id', $staff_coach_id)->exists();

        if($exist == false){
            return 0;
        }
        
        $staff_coach_query = $this->get_staff_coach_query();
        
        $staff_coach = $staff_coach_query->where('staff_coaches.id', $staff_coach_id)->first();
            
        return $staff_coach;
                
    }

    public function add_staff_coach_to_users($staff_coach_id, $staff_coach_email)
    {
        $user = new User;
        
        $user->email = $staff_coach_email;
        
        $user->password = Hash::make("pass1234");

        $user->user_ref_id = $staff_coach_id;

        $user->user_type = 2;

        $user->save();

        $inserted_id = $user->id;

        StaffCoach::where('id', $staff_coach_id)->update(['user_id' => $inserted_id]);
    }


    public function import_excell_staff_coaches(Request $request) 
    {
        $request->validate([
            'excell_staff_coaches' => 'required|file',
        ]);

        $extension = $request->file('excell_staff_coaches')->getClientOriginalExtension();
        
        if($extension == 'xlsx' || $extension == 'csv'){
            
            Excel::import(new StaffCoachImport, request()->file('excell_staff_coaches'));

        }else{
            
            session()->flash('error_status', 'Invalid Input! Must be xlsx or csv file only!');

        }
        
        session()->flash('success_status', 'Staff/Coach Added!');

        return redirect()->route('admin.accounts.staff_coaches');

    }

    public function delete_staff_coach($id)
    {
        $staff_coach = StaffCoach::findOrFail($id);

        if($staff_coach->delete()){
            
            session()->flash('success_status', 'Staff/Coach Deleted!');
            
            return redirect()->route('admin.accounts.staff_coaches');

        }
    }
}

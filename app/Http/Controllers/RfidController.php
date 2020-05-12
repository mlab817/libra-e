<?php

namespace App\Http\Controllers;

use App\RfidUser; 

use App\User; 
use App\Student; 
use App\StaffCoach; 

use Illuminate\Http\Request;

class RfidController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function all_rfid($type)
    {
        $all_types = $this->get_all_rfid_types();
        
        if(in_array($type, $all_types) == false){
            
            $type = 'all_students';

        }

        $rfids = $this->get_all_rfid($type);
        
        $all_count_data = $this->get_all_data_count($type);
        
        $info_data = $this->get_info_data($type);

        return view('admin.rfid.all_users')->with('rfids', $rfids)
            ->with('all_count_data', $all_count_data)
            ->with('type', $type)
            ->with('info_data', $info_data);
            
    }
  
    public function get_all_rfid_types()
    {
        $all_types = ['all_students', 'all_coaches', 'all_books'];
        
        return $all_types;
        
    }

    public function get_info_data($type)
    {
        $all_data_toOrder = $this->get_all_toOrder($type, true);
            
        $all_data_filter = $this->get_all_filter($type, true);

        if($type == 'all_students'){

            $title = 'Students';

            $sidebar_nav_lev_2 = '';

            $all_table_columns = ['User', 'Type', 'Actions']; 

            $info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_toOrder' => $all_data_toOrder['all_toOrder'],
                'all_toOrder_name' => $all_data_toOrder['all_toOrder_name'],
                'all_filter' => $all_data_filter['all_filter'],
                'all_filter_name' => $all_data_filter['all_filter_name'],
                'all_table_columns' => $all_table_columns,
            ];

            
        }else if($type == 'all_coaches'){

            $title = 'Staff/Coaches';

            $sidebar_nav_lev_2 = '';

            $all_table_columns = ['User', 'Actions']; 

            $info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_toOrder' => $all_data_toOrder['all_toOrder'],
                'all_toOrder_name' => $all_data_toOrder['all_toOrder_name'],
                'all_table_columns' => $all_table_columns,
            ];

        }

        return $info_data;

    }

    public function get_all_users()
    {
        $users = User::all();
    
        $students = Student::all();
    
        $staff_coach = StaffCoach::all();
        
        $all_users = [
            'users' => $users,
            'students' => $students,
            'staff_coach' => $staff_coach,
        ];
        
        return $all_users;

    }

    public function check_session_queries($type)
    {
        $session_name_order = $type . '_rfid_toOrder'; 
            
        $session_name_orderBy = $type . '_rfid_orderBy'; 

        $session_name_per_page = $type . '_rfid_per_page'; 
        
        if(session()->has($session_name_order) != true){

            session([$session_name_order => 'updated_at' ]);

        }

        if(session()->has($session_name_orderBy) != true){

            session([$session_name_orderBy => 'desc' ]);
            
        }

        if (session()->has($session_name_per_page) != true) {

            session([$session_name_per_page => 10 ]);
            
        }
    }

    public function get_all_rfid($type)
    {
        $this->check_session_queries($type);
        
        $rfid_query = $this->get_rfid_query($type);
            
        $rfid_query = $this->add_order_queries($type, $rfid_query, false); 
        
        return $rfid_query;
        
    }

    public function add_order_queries($type, $rfid_query, $search_on)
    {
        $session_get_all = $type . '_rfid_getAll';
        
        $session_toOrder = $type . '_rfid_toOrder';

        $session_orderBy = $type . '_rfid_orderBy';

        $session_per_page = $type . '_rfid_per_page';
        
        if(session()->has($type . '_rfid_getAll')){

            if(session()->get($type . '_rfid_getAll') != 'all'){

                if($type == 'all_students'){

                    $rfid_query = $rfid_query->where('programs.type', session()->get($session_get_all));
                   
                }
            }
            
        }else{
                
            session([$type . '_rfid_getAll' => 'all' ]);

        }

        if($search_on){

            $count = $rfid_query->count();
            
            session()->flash('admin_search_count', $count);
            
        }

        $rfid = $rfid_query->orderBy(session()->get($session_toOrder), session()->get($session_orderBy))
            ->paginate(session()->get($session_per_page));
            
        
        if($rfid->count() <= 0){
            
            session()->flash('error_status', 'No Data Found!');
            
        }

        return $rfid;

    }

    public function get_rfid_query($type)
    {
        if($type == 'all_students'){

            $rfid_query = RfidUser::join('students', 'rfid_users.user_id', 'students.user_id')
                ->join('programs', 'students.program_id', 'programs.id')
                ->select(
                    'rfid_users.*',
                    'students.f_name',
                    'students.m_name',
                    'students.l_name',
                    'students.email_add',
                    'students.pic_url',
                    'programs.type'
                )
                ->where('rfid_users.status', 1);


        }else if($type == 'all_coaches'){
            
            $rfid_query = RfidUser::join('staff_coaches', 'rfid_users.user_id', 'staff_coaches.user_id')
                ->select(
                    'rfid_users.*',
                    'staff_coaches.f_name',
                    'staff_coaches.m_name',
                    'staff_coaches.l_name',
                    'staff_coaches.email_add',
                    'staff_coaches.pic_url'
                )
                ->where('rfid_users.status', 1);

        }

        return $rfid_query;

    }

    public function get_all_data_count($type)
    {
        if($type == 'all_students'){

            $shs = RfidUser::join('students', 'rfid_users.user_id', 'students.user_id')
                ->join('programs', 'students.program_id', 'programs.id')
                ->where([
                    ['rfid_users.status', 1],
                    ['programs.type', 0],
                ])->count();

            $tertiary = RfidUser::join('students', 'rfid_users.user_id', 'students.user_id')
                ->join('programs', 'students.program_id', 'programs.id')
                ->where([
                    ['rfid_users.status', 1],
                    ['programs.type', 1],
                ])->count();

            $all = RfidUser::join('students', 'rfid_users.user_id', 'students.user_id')
                ->join('programs', 'students.program_id', 'programs.id')
                ->where('rfid_users.status', 1)
                ->count();

            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'shs' => [
                    'count' => $shs,
                    'name' => 'SeniorHigh',
                    'color' => 'text-info'
                ], 
                'tertiary' => [
                    'count' => $tertiary,
                    'name' => 'Teritary',
                    'color' => 'text-primary'
                ]
            ];
            
        }else if($type == 'all_coaches'){

            $all = RfidUser::join('staff_coaches', 'rfid_users.user_id', 'staff_coaches.user_id')
                ->where('rfid_users.status', 1)
                ->count();

            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ]
            ];

        }
        
        return $all_count_data;
        
    }


    public function rfid_per_page($type = "all_students", $per_page = 10) 
    {
        $all_types = $this->get_all_rfid_types();
        
        if(in_array($type, $all_types)){
            
            $session_per_page = $type . '_rfid_per_page';

        }else{

            $session_per_page = 'all_students_rfid_per_page';
            
            $type = "all_students";
            
        }
        
        $per_page_array = [5,10,20,50,100,200,500];
        
        if(in_array($per_page, $per_page_array)){
            
            session([$session_per_page => $per_page]);
            
        }else{
            
            session([$session_per_page => 5]);

        }

        return redirect()->route('admin.rfid.all_users', $type);

    }

    public function rfid_toOrder($type = "all_students", $toOrder = 'updated_at') 
    {
        $all_types = $this->get_all_rfid_types();
        
        if(in_array($type, $all_types)){
            
            $session_toOrder = $type . '_rfid_toOrder';

        }else{

            $session_toOrder = 'all_students_rfid_toOrder';
            
            $type = "all_students";
            
        }

        $all_toOrder = $this->get_all_toOrder($type, false);
        
        if(in_array($toOrder, $all_toOrder)){
            
            session([$session_toOrder => $toOrder]);
            
        }else{
            
            session([$session_toOrder => 'updated_at' ]);

        }

        return redirect()->route('admin.rfid.all_users', $type);

    }

    public function get_all_toOrder($type, $get_name)
    {
        if($type == 'all_students' || $type == 'all_coaches'){

            $all_toOrder = ['l_name', 'updated_at'];

            if($get_name){
                
                $all_toOrder_name = ['User','Latest'];

                $all_data = [
                    'all_toOrder' => $all_toOrder,
                    'all_toOrder_name' => $all_toOrder_name
                ];

                return $all_data;
                
            }
        
        }

        return $all_toOrder;
        
    }

    public function rfid_orderBy($type = "all_students", $orderBy = 'desc') 
    {
        $all_types = $this->get_all_rfid_types();
        
        if(in_array($type, $all_types)){
            
            $session_orderBy = $type . '_rfid_orderBy';

        }else{

            $session_orderBy = 'all_students_rfid_orderBy';
            
            $type = "all_students";
            
        }

        if($orderBy == 'asc' || $orderBy == 'desc' ){
            
            session([$session_orderBy => $orderBy ]);
            
        }else{
            
            session([$session_orderBy => 'desc' ]);

        }

        return redirect()->route('admin.rfid.all_users', $type);

    }

    public function filter_rfid($type = "all_students", $filter = 'all') 
    {
        $all_types = $this->get_all_rfid_types();
        
        if(in_array($type, $all_types)){
            
            $session_getAll = $type . '_rfid_getAll';

        }else{

            $session_getAll = 'all_students_rfid_getAll';
            
            $type = "all_students";
            
        }

        $all_filter = $this->get_all_filter($type, false);
        
        if(in_array($filter, $all_filter)){
            
            session([$session_getAll => $filter]);
            
        }else{
            
            session([$session_getAll => 'all']);

        }

        return redirect()->route('admin.rfid.all_users', $type);

    }

    public function get_all_filter($type, $get_name)
    {
        if($type == 'all_students'){

            $all_filter = ['all', 0, 1];
            
            if($get_name){
                
                $all_filter_name = ['All', 'SeniorHigh', 'Tertiary'];

                $all_filter_data = [
                    'all_filter' => $all_filter,
                    'all_filter_name' => $all_filter_name
                ];
                
                return $all_filter_data;
                
            }

        }else if($type == 'all_coaches'){            
            
            $all_filter = ['all', 0, 1];
            
        }

        return $all_filter;
        
    }

    public function search_rfid($type, $search = '')
    {
        $all_types = $this->get_all_rfid_types();
        
        if(in_array($type, $all_types) == false){
            
            $type = 'all_students';

        }
        
        $this->check_session_queries($type);
        
        $rfid_query = $this->get_rfid_query($type);
        
        $rfid_query = $this->add_reports_search_query($type, $rfid_query, $search);

        session()->flash('admin_search', $search);
        session([$type . '_rfid_getAll' => 'all' ]);

        $rfids = $this->add_order_queries($type, $rfid_query, true); 
        
        $all_count_data = $this->get_all_data_count($type);
        
        $info_data = $this->get_info_data($type);
        
        $count = $rfids->count();

        if($count <= 0){

            session()->flash('error_status', 'No data found!');
            
        }
            
        return view('admin.rfid.all_users')->with('rfids', $rfids)
            ->with('all_count_data', $all_count_data)
            ->with('type', $type)
            ->with('info_data', $info_data);
    }

    public function add_reports_search_query($type, $rfid_query, $search)
    {
        if($type == 'all_students'){

            $rfid_query->where('students.f_name', 'like', '%'.$search.'%')
                ->orWhere('students.m_name', 'like', '%'.$search.'%')
                ->orWhere('students.l_name', 'like', '%'.$search.'%')
                ->orWhere('students.email_add', 'like', '%'.$search.'%');
                
        }else if($type == 'all_coaches'){
            
            $rfid_query->where('staff_coaches.f_name', 'like', '%'.$search.'%')
                ->orWhere('staff_coaches.m_name', 'like', '%'.$search.'%')
                ->orWhere('staff_coaches.l_name', 'like', '%'.$search.'%')
                ->orWhere('staff_coaches.email_add', 'like', '%'.$search.'%');

        }

        return $rfid_query;

    }
    
    
    // Add Rfid to users

    public function scan_rfid($type)
    {
        $all_types = $this->get_all_rfid_types();
        
        if(in_array($type, $all_types)){

            $info_data = $this->get_info_data($type);

            return view('admin.rfid.scan_rfid')->with('type', $type)
                ->with('info_data', $info_data);

        }else{

            return redirect()->route('admin.rfid.all_users', 'all_students');
            
        }
    }

    public function check_rfid(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'rfid_id' => 'required|unique:rfid_users,rfid_id|digits:10',
        ]);
        
        $info_data = $this->get_info_data($request->type);

        if($request->type == 'all_students'){

            $all_users = $this->get_all_students();

        }else if($request->type == 'all_coaches'){
            
            $all_users = $this->get_all_coaches();
            
        }
        
        return view('admin.rfid.bind_rfid')->with('type', $request->type)
            ->with('rfid_id', $request->rfid_id)
            ->with('all_users', $all_users)
            ->with('info_data', $info_data);

    }

    public function get_all_students()
    {
        $all_students = Student::join('programs', 'students.program_id', 'programs.id')
            ->select(
                'students.*',
                'programs.code'
            )
            ->where('students.status', 1)->get();
        
        return $all_students;
        
    }
    
    public function get_all_coaches()
    {
        $all_coaches = StaffCoach::join('departments', 'staff_coaches.department_id', 'departments.id')
            ->select(
                'staff_coaches.*',
                'departments.name'
            )
            ->where('staff_coaches.status', 1)->get();
        
        return $all_coaches;

    }

    public function add_rfid(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'user_id' => 'required|numeric',
            'rfid_id' => 'required|unique:rfid_users,rfid_id|digits:10',
        ]);
        
        $check_user = $this->check_if_user_already_has_rfid($request->user_id);
        
        if($check_user){

            session()->flash('error_status', 'User Already had RFID!');

            if($request->type == 'all_students'){

                $all_users = $this->get_all_students();
    
            }else if($request->type == 'all_coaches'){
                
                $all_users = $this->get_all_coaches();
                
            }
            
            $info_data = $this->get_info_data($request->type);

            return view('admin.rfid.bind_rfid')->with('type', $request->type)
                ->with('rfid_id', $request->rfid_id)
                ->with('all_users', $all_users)
                ->with('info_data', $info_data);
            
        }else{
            
            $rfid_user = new RfidUser;
            
            $rfid_user->rfid_id = $request->rfid_id;

            $rfid_user->user_id = $request->user_id;

            $rfid_user->status = 1;
            
            $rfid_user->save();

            session()->flash('success_status', 'RFID to user Added!');
            
            return redirect()->route('admin.rfid.all_users', $request->type);
            
        }
        
    }

    public function check_if_user_already_has_rfid($user_id)
    {
        $user_count = RfidUser::where([
            ['user_id', $user_id],
            ['status', 1],
        ])->count();

        if($user_count <= 0){

            return false;
            
        }else{

            return true;
            
        }
        
    }

    public function scan_change_rfid($type, $user_id)
    {
        $info_data = $this->get_info_data($type);

        return view('admin.rfid.change_rfid')->with('type', $type)
                ->with('info_data', $info_data)
                ->with('user_id', $user_id);
    
    }

    public function change_rfid(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'user_id' => 'required|numeric',
            'rfid_id' => 'required|unique:rfid_users,rfid_id|digits:10'
        ]);
        
        $user_exist = RfidUser::where('user_id', $request->user_id)->exists();

        if($user_exist){
            
            RfidUser::where('user_id', $request->user_id)->update(['status' => 0]);

            $rfid_user = new RfidUser;
            
            $rfid_user->rfid_id = $request->rfid_id;

            $rfid_user->user_id = $request->user_id;

            $rfid_user->status = 1;
            
            $rfid_user->save();

            session()->flash('success_status', 'User RFID Changed!');
            
            return redirect()->route('admin.rfid.all_users', $request->type);
            
        }else{
            
            session()->flash('error_status', 'Invalid User!');
            
            return redirect()->route('admin.rfid.all_users', $request->type);
            
        }
    }

}

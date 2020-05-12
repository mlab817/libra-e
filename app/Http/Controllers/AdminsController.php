<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Admin;
use App\AdminRole;

class AdminsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function admins()
    {
        $admins = $this->get_all_admins();

        $all_count = $this->get_all_count();
        
        return view('admin.accounts.admins.admins')->with('admins', $admins)
            ->with('all_count', $all_count);

    }

    public function get_all_count()
    {
        $head_librarian = Admin::where('admin_role_id', 1)->count();

        $assist_librarian = Admin::where('admin_role_id', 2)->count();

        $student_assistant = Admin::where('admin_role_id', 3)->count();

        $active = Admin::where('status', 1)->count();

        $in_active = Admin::where('status', 0)->count();

        $all = Admin::count();

        $all_count = [
            'head_librarian' => $head_librarian,
            'assist_librarian' => $assist_librarian,
            'student_assistant' => $student_assistant,
            'active' => $active,
            'in_active' => $in_active,
            'all' => $all
        ];

        return $all_count;
        
    }
    
    public function check_session_queries()
    {
        if(session()->has('admins_toOrder') != true){

            session(['admins_toOrder' => 'updated_at' ]);
            
        }

        if(session()->has('admins_orderBy') != true){

            session(['admins_orderBy' => 'desc' ]);
            
        }

        if (session()->has('admins_per_page') != true) {

            session(['admins_per_page' => 5 ]);
            
        }
    }
    
    public function get_all_admins()
    {
        $this->check_session_queries();

        $admins_query = $this->get_admin_query();

        if(session()->has('admins_getAll')){

            if(session()->get('admins_getAll') != 'all'){

                $admins_query = $admins_query->where('admins.status', session()->get('admins_getAll'));

            }else{
                    
                session(['admins_getAll' => 'all' ]);
            }
            
        }else{

            session(['admins_getAll' => 'all' ]);
            
        }

        if(session()->has('admins_roles_getAll')){

            if(session()->get('admins_roles_getAll') != 'all'){

                $admins_query = $admins_query->where('admins.admin_role_id', session()->get('admins_roles_getAll'));

            }else{
                    
                session(['admins_roles_getAll' => 'all' ]);
            }
            
        }else{

            session(['admins_roles_getAll' => 'all' ]);
            
        }
        
        $admins = $admins_query->orderBy(session()->get('admins_toOrder'), session()->get('admins_orderBy'))
            ->paginate(session()->get('admins_per_page'));

        if($admins->count() > 0){
            
            return $admins;
            
        }else{
            
            session()->flash('error_status', 'No Admins Yet!');
            return $admins;

        }
    }

    public function admins_per_page($per_page = 10) 
    {
        $per_page_array = [5,10,20,50,100,200,500];
        
        if(in_array($per_page, $per_page_array)){
            
            session(['admins_per_page' => $per_page ]);
            
        }else{
            
            session(['admins_per_page' => 5 ]);

        }

        return redirect()->route('admin.accounts.admins');

    }

    public function admins_toOrder($ToOrder = 'updated_at') 
    {
        $all_to_order = ['username', 'last_name', 'email_add', 'role_description', 'updated_at'];
        
        if(in_array($ToOrder, $all_to_order)){
            
            session(['admins_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['admins_toOrder' => 'updated_at' ]);

        }

        return redirect()->route('admin.accounts.admins');

    }

    public function admins_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || $orderBy == 'desc'){
            
            session(['admins_orderBy' => $orderBy ]);
            
        }else{
            
            session(['admins_orderBy' => 'desc' ]);

        }

        return redirect()->route('admin.accounts.admins');

    }

    public function filter_admins($filter = 'all') 
    {
        
        if($filter == 'all' || $filter == 0 || $filter == 1){
            
            session(['admins_getAll' => $filter ]);
            
        }else{
            
            session(['admins_getAll' => 'all' ]);

        }

        return redirect()->route('admin.accounts.admins');

    }

    public function roles_admins($roles = 'all') 
    {
        
        if($roles == 'all' || $roles == 1 || $roles == 2 || $roles == 3){
            
            session(['admins_roles_getAll' => $roles ]);
            
        }else{
            
            session(['admins_roles_getAll' => 'all' ]);

        }

        return redirect()->route('admin.accounts.admins');

    }

    public function get_admin_query()
    {
        $admins_query = Admin::join('admin_roles', 'admins.admin_role_id', 'admin_roles.id')
            ->select(
                'admins.*', 
                'admin_roles.description AS role_description' 
            );

        return $admins_query;

    }

    public function search_admin($search = '')
    {
        $this->check_session_queries();
        
        $admins_query = $this->get_admin_query();

        $admins_query->where('admins.username', 'like', '%'.$search.'%')
            ->orWhere('admins.first_name', 'like', '%'.$search.'%')
            ->orWhere('admins.middle_name', 'like', '%'.$search.'%')
            ->orWhere('admins.last_name', 'like', '%'.$search.'%')
            ->orWhere('admin_roles.description', 'like', '%'.$search.'%');
            
        $count = $admins_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);
        session(['admins_getAll' => 'all' ]);
        session(['admins_roles_getAll' => 'all' ]);
        
        if($count > 0){

            $admins = $admins_query->orderBy(session()->get('admins_toOrder'), session()->get('admins_orderBy'))
                ->paginate(session()->get('admins_per_page'));

                $all_count = $this->get_all_count();
        
                return view('admin.accounts.admins.admins')->with('admins', $admins)
                    ->with('all_count', $all_count);

        }else{
            
            session()->flash('error_status', 'No data found!');
            $admins = $admins_query->paginate(session()->get('admins_per_page'));

            $all_count = $this->get_all_count();
        
            return view('admin.accounts.admins.admins')->with('admins', $admins)
                ->with('all_count', $all_count);
            
        }
    }
    
    public function add_admin_view()
    {
        $admin_roles = AdminRole::where('status', 1)->get();

        return view('admin.accounts.admins.add_admin')->with('admin_roles', $admin_roles);

    }

    public function store_admin(Request $request)
    {
        if($request->isMethod('put')){
            
            $request->validate([
                'id' => 'required|numeric',
                'username' => 'required|string',
                'first_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'middle_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'last_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'email_add' => 'required|email',
                'admin_role_id' => 'required|numeric',
                'status' => 'nullable|numeric'
            ]);
            
        }else{
            
            $request->validate([
                'username' => 'required|string|unique:admins,username',
                'first_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'middle_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'last_name' => 'required|regex:/^[a-z ñ\-]+$/i',
                'email_add' => 'required|unique:admins,email_add|email',
                'admin_role_id' => 'required|numeric',
                'status' => 'required|numeric'
            ]);
        }

        $admin_username = $request->username;
        
        if($request->isMethod('put')){
            
            $username_exists = $this->check_admin_username_exist($request->id, $admin_username);
    
            if($username_exists){
                
                session()->flash('error_status', 'Admin Username Already Exist!');
                return redirect()->route('admin.accounts.admins.edit_admin', [$request->id]);
    
            }
        }        

        $admin_email = $request->email_add;
        
        if($request->isMethod('put')){
            
            $email_exists = $this->check_admin_email_exist($request->id, $admin_email);
    
            if($email_exists){
                
                session()->flash('error_status', 'Admin Email Already Exist!');
                return redirect()->route('admin.accounts.admins.edit_admin', [$request->id]);
    
            }

        }        

        $admin = $request->isMethod('put') ? Admin::findOrFail($request->id) : new Admin;
        
        $admin->username =  ltrim($admin_username);

        $admin->email_add = ltrim($admin_email);

        $admin->first_name = ltrim(ucfirst($request->first_name));

        $admin->middle_name = ltrim(ucfirst($request->middle_name));

        $admin->last_name = ltrim(ucfirst($request->last_name));

        $admin->admin_role_id = $request->admin_role_id;

        if($request->isMethod('post')){
            
            $admin->password = Hash::make('pass1234');

            $admin->pin_code = 1234;

        }
        
        if($request->status == '' || null){
            
            $admin->status = 0;
            
        }else{
            
            $admin->status = 1;

        }

        $admin->save();

        if($request->isMethod('put')){
            
            session()->flash('success_status', 'Admin Updated!');
            
        }else{
            
            session()->flash('success_status', 'Admin Added!');

        }
            
        return redirect()->route('admin.accounts.admins');
        
    }

    public function check_admin_username_exist($admin_id, $username)
    {
        $same_admin = Admin::where([
            ['id', $admin_id],
            ['username', $username]
        ])->exists();
        
        if($same_admin){
            
            return false;
            
        }else{
            
            $existing_username = Admin::where('username', $username)->exists();

            if($existing_username){
                
                return true;

            }else{
                
                return false;
                
            }
        }
    }

    public function check_admin_email_exist($admin_id, $email)
    {
        $same_admin = Admin::where([
            ['id', $admin_id],
            ['email_add', $email]
        ])->exists();
        
        if($same_admin){
            
            return false;
            
        }else{
            
            $existing_email = Admin::where('email_add', $email)->exists();

            if($existing_email){
                
                return true;

            }else{
                
                return false;
                
            }
        }
    }

    public function edit_admin_view($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $admin = $this->get_admin_data($id);
            
            if($admin){

                $admin_roles = AdminRole::where('status', 1)->get();

                return view('admin.accounts.admins.edit_admin')->with('admin', $admin)
                    ->with('admin_roles', $admin_roles);

            }else{
                
                return redirect()->route('admin.accounts.admins');

            }
            
        }else{
            
            return redirect()->route('admin.accounts.admins');

        }
    }

    public function get_admin_data($admin_id)
    {

        $exist = Admin::where('admins.id', $admin_id)->exists();

        if($exist == false){
            return 0;
        }
        
        $admin_query = $this->get_admin_query();
        
        $admin = $admin_query->where('admins.id', $admin_id)->first();
            
        return $admin;
                
    }


    public function delete_admin($id)
    {
        $admin = Admin::findOrFail($id);

        if($admin->delete()){
            
            session()->flash('success_status', 'Admin Deleted!');
            
            return redirect()->route('admin.accounts.admins');

        }
    }

    
    
}

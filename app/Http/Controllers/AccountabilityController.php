<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\User; 
use App\Student; 
use App\StaffCoach; 

use App\Accession;
use App\NoAccession;

use App\BorrowedBook;

use App\Author;

use App\Accountability;
use App\Invoice;

class AccountabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    
    public function all_students()
    {
        $accountabilities = $this->get_all_accountabilities('all_students');
        
        $get_all_count = $this->get_all_count(1);
        
        return view('admin.accountabilities.all_users.all_students')->with('accountabilities', $accountabilities)
            ->with('count', $get_all_count);
            
    }

    public function all_coaches()
    {
        $accountabilities = $this->get_all_accountabilities('all_coaches');
        
        $get_all_count = $this->get_all_count(2);
        
        return view('admin.accountabilities.all_users.all_coaches')->with('accountabilities', $accountabilities)
            ->with('count', $get_all_count);
            
    }

    public function student_books()
    {
        $accountabilities = $this->get_all_accountabilities('student_books');
        
        $get_all_count = $this->get_all_count(1);
        
        return view('admin.accountabilities.books.student_books')->with('accountabilities', $accountabilities)
            ->with('count', $get_all_count);
        
    }

    public function coach_books()
    {
        $accountabilities = $this->get_all_accountabilities('coach_books');
        
        $get_all_count = $this->get_all_count(2);
        
        return view('admin.accountabilities.books.coach_books')->with('accountabilities', $accountabilities)
            ->with('count', $get_all_count);
        
    }

    public function get_all_users($get, $id)
    {
        if($get == 'all'){
            
            $users = User::all();

            $students = Student::all();
    
            $staff_coach = StaffCoach::all();
            
            $all_users = [
                'users' => $users,
                'students' => $students,
                'staff_coach' => $staff_coach,
            ];
            
            return $all_users;

        }else if ($get == 'single'){
            
            $reservation = BorrowedBook::where('id', $id)->first();
            
            $user_id = $reservation['user_id'];

            $user_ref = User::where('id', $user_id)->first();

            $user_type = $user_ref->user_type;
            
            if($user_type == 1){
                
                $user = Student::where('id', $user_ref->user_ref_id)->first();

            }else if($user_type == 2){
                
                $user = StaffCoach::where('id', $user_ref->user_ref_id)->first();

            }

            $user_data = [
                'user_type' => $user_type,
                'user' => $user
            ];

            return $user_data;

        }else if($get == 'single_user_id'){
            
            $user_ref = User::where('id', $id)->first();

            $user_type = $user_ref->user_type;
            
            if($user_type == 1){
                
                $user = Student::where('id', $user_ref->user_ref_id)->first();

            }else if($user_type == 2){
                
                $user = StaffCoach::where('id', $user_ref->user_ref_id)->first();

            }

            $user_data = [
                'user_type' => $user_type,
                'user' => $user
            ];

            return $user_data;

        }
    }

    public function get_all_count($user_type)
    {
        
        if($user_type == 'all'){
            
            $unpaid = Accountability::where([
                ['status', 1],
                ['accountability_type', 2]
            ])->count();

            $unsettled = Accountability::where([
                ['status', 1],
                ['accountability_type', 3]
            ])->count();

            $all_unsettled_unpaid = $unpaid + $unsettled; 

            $cleared = Accountability::where('status', 2)->count();

            //$unclaimed = Accountability::where('accountability_type', 1)->count();

            $overdue = Accountability::where('accountability_type', 2)->count();

            $damaged_lost = Accountability::where('accountability_type', 3)->count();

            $all = Accountability::count();

        }else{

            $unpaid = Accountability::join('users', 'accountabilities.user_id', 'users.id')
                ->where([
                    ['status', 1],
                    ['accountability_type', 2],
                    ['user_type', $user_type]
                ])->count();

            $unsettled = Accountability::join('users', 'accountabilities.user_id', 'users.id')
                ->where([
                    ['status', 1],
                    ['accountability_type', 3],
                    ['user_type', $user_type]
                ])->count();

            $all_unsettled_unpaid = $unpaid + $unsettled;

            $cleared = Accountability::join('users', 'accountabilities.user_id', 'users.id')
                ->where([
                    ['status', 2],
                    ['user_type', $user_type],
                ])->count();

            $unclaimed = Accountability::join('users', 'accountabilities.user_id', 'users.id')
                ->where([
                    ['accountability_type', 1],
                    ['user_type', $user_type],
                ])->count();

            $overdue = Accountability::join('users', 'accountabilities.user_id', 'users.id')
                ->where([
                    ['accountability_type', 2],
                    ['user_type', $user_type],
                ])->count();

            $damaged_lost = Accountability::join('users', 'accountabilities.user_id', 'users.id')
                ->where([
                    ['accountability_type', 3],
                    ['user_type', $user_type],
                ])->count();

            $all = Accountability::join('users', 'accountabilities.user_id', 'users.id')
                ->where('user_type', $user_type)->count();;
            
        } 
        

        $all_count = [
            'unpaid' => $unpaid, 
            'unsettled' => $unsettled, 
            'all_unsettled_unpaid' => $all_unsettled_unpaid, 
            'cleared' => $cleared, 
            //'unclaimed' => $unclaimed, 
            'overdue' => $overdue, 
            'damaged_lost' => $damaged_lost, 
            'all' => $all 
        ];

        return $all_count;
    }

    
    public function check_session_queries($type, $account_type)
    {
        $session_name_order = $type . '_'.$account_type.'_toOrder'; 
            
        $session_name_orderBy = $type . '_'.$account_type.'_orderBy'; 

        $session_name_per_page = $type . '_'.$account_type.'_per_page'; 
        
        if(session()->has($session_name_order) != true){

            if($account_type == 'invoices'){
                
                session([$session_name_order => 'date_of_payment' ]);

            }else{

                session([$session_name_order => 'updated_at' ]);

            }
        }

        if(session()->has($session_name_orderBy) != true){

            session([$session_name_orderBy => 'desc' ]);
            
        }

        if (session()->has($session_name_per_page) != true) {

            session([$session_name_per_page => 10 ]);
            
        }
    }
    
    public function get_all_accountabilities($type)
    {
        $this->check_session_queries($type, 'accountabilities');
        
        $accountabilities_query = $this->get_accountabilities_query($type);
        
        $all_accountabilities_query = $this->add_order_queries($accountabilities_query, $type);
            
        return $all_accountabilities_query;
        
    }
    
    public function get_accountabilities_query($type)
    {
        if($type == 'all_students' || $type == 'all_coaches'){
            
            if($type == 'all_students'){
                
                $accountabilities_query = Accountability::join('students', 'accountabilities.user_id', 'students.user_id');
                
            }else if($type == 'all_coaches'){
                
                $accountabilities_query = Accountability::join('staff_coaches', 'accountabilities.user_id', 'staff_coaches.user_id');

            }
            
            $accountabilities_query = $accountabilities_query->select('accountabilities.*', 'accountabilities.user_id', 'accountability_type', DB::raw('Count(accountabilities.user_id) as user_count'));

            if($type == 'all_students'){
                
                $accountabilities_query = $accountabilities_query->addSelect(
                    'students.f_name AS student_f_name',
                    'students.m_name AS student_m_name',
                    'students.l_name AS student_l_name',
                    'students.email_add AS student_email_add'
                )
                ->groupBy('accountabilities.user_id');
                
            }else if($type == 'all_coaches'){
                
                $accountabilities_query = $accountabilities_query->addSelect(
                    'staff_coaches.f_name AS coach_f_name',
                    'staff_coaches.m_name AS coach_m_name',
                    'staff_coaches.l_name AS coach_l_name',
                    'staff_coaches.email_add AS coach_email_add'
                )->groupBy('accountabilities.user_id');

            }

            $accountabilities_query->where('accountabilities.status', 1);

        }else if($type == 'student_books' || $type == 'coach_books'){

            $accountabilities_query = Accountability::join('users', 'accountabilities.user_id', 'users.id')
                ->join('borrowed_books', 'accountabilities.borrowed_book_id', 'borrowed_books.id')
                ->join('no_accessions', 'borrowed_books.accession_no_id', 'no_accessions.id')
                ->join('accessions', 'no_accessions.accession_id', 'accessions.id')
                ->join('authors', 'accessions.author_id', 'authors.id');
            
            if($type == 'student_books'){
                
                $accountabilities_query->join('students', 'accountabilities.user_id', 'students.user_id');
                
            }else if($type == 'coach_books'){
                
                $accountabilities_query->join('staff_coaches', 'accountabilities.user_id', 'staff_coaches.user_id');

            }

            $accountabilities_query->select(
                'users.user_type',
                'accountabilities.*',
                'borrowed_books.transaction_no',
                'borrowed_books.start_date',
                'borrowed_books.due_date',
                'borrowed_books.return_date',
                'borrowed_books.status AS borrowed_book_status',
                'borrowed_books.notes AS borrowed_book_notes',
                'no_accessions.accession_no',
                'no_accessions.accession_id',
                'accessions.book_title',
                'accessions.pic_url',
                'authors.author_name'
            );
            
            if($type == 'student_books'){
                
                $accountabilities_query->addSelect(
                    'students.f_name AS student_f_name',
                    'students.m_name AS student_m_name',
                    'students.l_name AS student_l_name',
                    'students.email_add AS student_email_add'
                );
                
            }else if($type == 'coach_books'){
                
                $accountabilities_query->addSelect(
                    'staff_coaches.f_name AS coach_f_name',
                    'staff_coaches.m_name AS coach_m_name',
                    'staff_coaches.l_name AS coach_l_name',
                    'staff_coaches.email_add AS coach_email_add'
                );
            }
        }
        
        return $accountabilities_query->WhereIn('accountability_type', [2,3]);
            
    }

    public function add_order_queries($accountabilities_query, $type)
    {
        $session_get_all_status = $type . '_accountabilities_getAll_status';

        $session_get_all_type = $type . '_accountabilities_getAll_type';

        $session_toOrder = $type . '_accountabilities_toOrder';

        $session_orderBy = $type . '_accountabilities_orderBy';

        $session_per_page = $type . '_accountabilities_per_page';
        
        if(session()->has($session_get_all_status)){

            if(session()->get($session_get_all_status) != 'all'){

                $accountabilities_query = $accountabilities_query->where('accountabilities.status', session()->get($session_get_all_status));

            }else{
                    
                session([$session_get_all_status => 'all' ]);
            }
            
        }else{

            session([$session_get_all_status => 'all' ]);
            
        }

        if(session()->has($session_get_all_type)){

            if(session()->get($session_get_all_type) != 'all'){

                $accountabilities_query = $accountabilities_query->where('accountabilities.accountability_type', session()->get($session_get_all_type));

            }else{
                    
                session([$session_get_all_type => 'all' ]);
            }
            
        }else{

            session([$session_get_all_type => 'all' ]);
            
        }

        $all_accountabilities_query = $accountabilities_query->orderBy(session()->get($session_toOrder), session()->get($session_orderBy))
            ->paginate(session()->get($session_per_page));


        if($all_accountabilities_query->count() > 0){
            
            return $all_accountabilities_query;
            
        }else{

            session()->flash('error_status', 'No Accountabilities/s Yet!');
            return $all_accountabilities_query;

        }
    }

    public function accountabilities_per_page($type = 'all_students', $per_page = 10) 
    {
        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){
            
            $session_per_page = $type . '_accountabilities_per_page';

        }
        
        if($per_page == 5 || $per_page == 10 || $per_page == 20 || $per_page == 50 || $per_page == 100 || $per_page == 200 || $per_page == 500){
            
            session([$session_per_page => $per_page]);
            
        }else{
            
            session([$session_per_page => 10]);

        }
        
        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){
            
            return redirect()->route('admin.accountabilities.' . $type );
            
        }else{
            
            return redirect()->route('admin.accountabilities.all_students');

        }
    }

    public function accountabilities_toOrder($type = 'all_students', $ToOrder = 'updated_at') 
    {
        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){
            
            $session_toOrder = $type . '_accountabilities_toOrder';

        }else{
            
            $session_toOrder = 'user_accountabilities_toOrder';
            
        }
        
        if(($type == 'all_students' || $type == 'all_coaches' ) && ($ToOrder == 'l_name' || $ToOrder == 'user_count' || $ToOrder == 'updated_at')){
            
            session([$session_toOrder => $ToOrder]);
            
        }else if(($type == 'student_books' || $type == 'coach_books') 
            && ($ToOrder == 'transaction_no' || $ToOrder == 'book_title' || $ToOrder == 'author_name' || $ToOrder == 'accession_no' || $ToOrder == 'l_name' || $ToOrder == 'updated_at')){

            session([$session_toOrder => $ToOrder ]);
            
        }else{
            
            session([$session_toOrder => 'updated_at']);

        }

        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){
            
            return redirect()->route('admin.accountabilities.' . $type );
            
        }else{
            
            return redirect()->route('admin.accountabilities.all_students');

        }

    }

    public function accountabilities_orderBy($type = 'all_students', $orderBy = 'desc') 
    {
        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){
            
            $session_orderBy = $type . '_accountabilities_orderBy';

        }
        
        if($orderBy == 'asc' || $orderBy == 'desc' ){
            
            session([$session_orderBy => $orderBy]);
            
        }else{
            
            session([$session_orderBy => 'desc']);

        }

        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){
            
            return redirect()->route('admin.accountabilities.' . $type );
            
        }else{
            
            return redirect()->route('admin.accountabilities.all_students');

        }

    }

    public function filter_type_accountabilities($type = 'student_book', $filter = 'all') 
    {
        if($type == 'student_books' || $type == 'coach_books'){
            
            $session_getAll_type = $type . '_accountabilities_getAll_type';

        }

        
        if(($type == 'student_books' || $type == 'coach_books') && $filter == 'all' || $filter == 1 || $filter == 2 || $filter == 3 ){
            
            session([$session_getAll_type => $filter]);
            
        }else{
            
            session([$session_getAll_type => 'all']);

        }

        if($type == 'student_books' || $type == 'coach_books'){
            
            return redirect()->route('admin.accountabilities.' . $type );
            
        }else{
            
            return redirect()->route('admin.accountabilities.student_books');

        }

    }
    
    public function filter_status_accountabilities($type = 'all_students', $filter = 'all') 
    {
        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){
            
            $session_getAll_status = $type . '_accountabilities_getAll_status';

        }

        
        if($filter == 'all' || $filter == 1 || $filter == 2 ){
            
            session([$session_getAll_status => $filter ]);
            
        }else{
            
            session([$session_getAll_status => 'all' ]);

        }

        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){
            
            return redirect()->route('admin.accountabilities.' . $type );
            
        }else{
            
            return redirect()->route('admin.accountabilities.all_students');

        }

    }

    public function search_book_accountabilities($type =  '' , $search = '')
    {
        if($type == 'all_students' || $type == 'all_coaches' || $type == 'student_books' || $type == 'coach_books'){

            $book_accountabilities_query = $this->get_accountabilities_query($type);
            
            $book_accountabilities_query = $this->get_search_query($book_accountabilities_query, $type, $search);

            $count = $book_accountabilities_query->count();
    
            session()->flash('admin_search', $search);
            session()->flash('admin_search_count', $count);
            
            session([$type . '_accountabilities_getAll_type' => 'all' ]);
            session([$type . '_accountabilities_getAll_status' => 'all' ]);
    
            $all_book_accountabilities = $this->add_order_queries($book_accountabilities_query, $type);
            
            $accountabilities = $all_book_accountabilities;

            if($count <= 0){
    
                session()->flash('error_status', 'No Accountabilities found!');
                
            }
    
            if($type == 'all_students'){
                
                $ul_level_2 = 'all_users';
    
                $get_count = 1;
                
            }else if($type == 'all_coaches'){
                
                $ul_level_2 = 'all_users';
                
                $get_count = 2;
    
            }else if($type == 'student_books'){
    
                $ul_level_2 = 'books';
                
                $get_count = 1;
                
            }else if($type == 'coach_books'){
                
                $ul_level_2 = 'books';
    
                $get_count = 2;
    
            }
    
            $get_all_count = $this->get_all_count($get_count);
            
            return view('admin.accountabilities.'. $ul_level_2 . '.' . $type )->with('accountabilities', $accountabilities)
            ->with('count', $get_all_count);
            
        }else{

            return redirect()->route('admin.accountabilities.all_students');

        }
    }
    
    public function get_search_query($book_accountabilities_query, $type, $search)
    {
        $all_users_id = $this->search_users($search);

        if($type == 'all_students' || $type == 'all_coaches'){

            $user_id_count = $this->search_user_count($search);

            $book_accountabilities_query->WhereIn('accountabilities.user_id', $all_users_id['student_user_ids'])
                ->orWhereIn('accountabilities.user_id', $all_users_id['staff_coach_ids'])
                ->orWhereIn('accountabilities.user_id', $user_id_count);
            
        }else if($type == 'student_books' || $type == 'coach_books'){

            $book_accountabilities_query->where('borrowed_books.transaction_no', 'like', '%'.$search.'%')
                ->orWhereIn('borrowed_books.user_id', $all_users_id['student_user_ids'])
                ->orWhereIn('borrowed_books.user_id', $all_users_id['staff_coach_ids'])
                ->orWhere('accessions.book_title', 'like', '%'.$search.'%')
                ->orWhere('authors.author_name', 'like', '%'.$search.'%')
                ->orWhere('no_accessions.accession_no', 'like', '%'.$search.'%');
                
        }

        
        return $book_accountabilities_query;

    }

    public function search_users($search)
    {
        // Students
        $search_student = Student::where('f_name' ,'like', '%'.$search.'%')
            ->orWhere('f_name', 'like', '%'.$search.'%')
            ->orWhere('m_name', 'like', '%'.$search.'%')
            ->orWhere('l_name', 'like', '%'.$search.'%')
            ->orWhere('email_add', 'like', '%'.$search.'%')
            ->distinct()->get();

        $student_ids = [];

        foreach ($search_student as $student) {
            
            array_push($student_ids, $student->id);

        }

        $student_users = User::whereIn('user_ref_id', $student_ids)
            ->where('user_type', 1)->distinct()->get();

        $student_user_ids = [];
    
        foreach ($student_users as $student_user) {
        
            array_push($student_user_ids, $student_user->id);

        }
        
        // Staff/Coach
        $search_staff_coach = StaffCoach::where('f_name' ,'like', '%'.$search.'%')
            ->orWhere('f_name', 'like', '%'.$search.'%')
            ->orWhere('m_name', 'like', '%'.$search.'%')
            ->orWhere('l_name', 'like', '%'.$search.'%')
            ->orWhere('email_add', 'like', '%'.$search.'%')
            ->distinct()->get();

        $staff_coach_ids = [];
    
        foreach ($search_staff_coach as $staff_coach) {
            
            array_push($staff_coach_ids, $staff_coach->id);

        }

        $staff_coach_users = User::whereIn('user_ref_id', $staff_coach_ids)
            ->where('user_type', 2)->distinct()->get();
        
        $staff_coach_ids = [];

        foreach ($staff_coach_users as $staff_coach_user) {
        
            array_push($staff_coach_ids, $staff_coach_user->id);

        }

        $all_users_id = [
            'student_user_ids' => $student_user_ids,
            'staff_coach_ids' => $staff_coach_ids
        ];

        return $all_users_id;
        
    }

    public function search_user_count($search)
    {
        $all_accountabilities = Accountability::get();

        $all_user_id = [];
        
        foreach ($all_accountabilities as $user_accountability) {
            
            $user_id = $user_accountability->user_id;
            
            $get_count = Accountability::select('user_id')->where('user_id', $user_id)->count();
            
            if($search == $get_count){

                array_push($all_user_id, $user_accountability->user_id);

            }
            
        }

        return array_unique($all_user_id);
        
    }

    public function view_user_accountability($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $accountabilities = $this->get_user_accountabilities($id);

            if($accountabilities == false){

                return redirect()->route('admin.accountabilities.all_students');
                
            }else{

                if($accountabilities->count() != 0){
    
                    return view('admin.accountabilities.all_users.print_user_accountability')->with('accountabilities', $accountabilities)
                        ->with('user_id', $id);
    
                }else{
                    
                    return redirect()->route('admin.accountabilities.all_students');
                    
                }

            }
            
        }else{
            
            return redirect()->route('admin.accountabilities.all_students');

        }
    }

    public function get_user_accountabilities($user_id)
    {
        $get_user = User::select('user_type')->where('id', $user_id);
        
        if($get_user->exists()){
            
            $get_user = $get_user->first();
    
            if($get_user->user_type == 1){
                
                $query = 'student_books';
                
            }else if($get_user->user_type == 2){
                
                $query = 'coach_books';
    
            }
            
            $accountability_query = $this->get_accountabilities_query($query);
            
            $user_accountabilities = $accountability_query->where([
                ['accountabilities.user_id', $user_id],
                ['accountabilities.status', 1]
            ])->get();
            
            return $user_accountabilities;

        }else{
            
            return false;
            
        }
    }

    public function print_receipt($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $receipts = $this->get_user_accountabilities($id);
            
            if($receipts == false){

                return redirect()->route('admin.accountabilities.all_students');
                
            }else{
                
                if($receipts->count() != 0){
    
                    $user_data = $this->get_all_users('single_user_id', $id);
    
                    return view('inc.prints.accountability_print')->with('receipts', $receipts)
                        ->with('user_data', $user_data);
    
                }else{
                    
                    return redirect()->route('admin.accountabilities.all_students');
                    
                }
            }
            
        }else{
            
            return redirect()->route('admin.accountabilities.all_students');

        }
    }
    
    public function mark_paid(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'invoice_no' => 'required|digits:15',
            'pin_code' => 'required|digits:4',
        ]);
        
        $exists = $this->check_invoice_no($request->invoice_no);
        
        if($exists){

            session()->flash('error_status', 'Invoice already marked as paid!');

            return redirect()->route('admin.accountabilities.view_user_accountability', ['id' => $request->user_id]);

        }else{

            $invoice_data = $this->get_data_on_invoice($request->invoice_no);

            if($invoice_data['user_id'] == $request->user_id){
                
                $accountability_query = $this->get_accountability_query(false);
    
                $unreturned_book = $accountability_query->where([
                    ['accountabilities.user_id', $request->user_id],
                    ['accountabilities.status', 1],
                    ['borrowed_books.status', 10]
                ])
                ->whereDate('due_date', '<=' , $invoice_data['date_time'])->exists();
    
                if($unreturned_book){
    
                    session()->flash('error_status', "Can't be mark as paid! User Borrowed books is unreturned Yet!");
    
                    return redirect()->route('admin.accountabilities.view_user_accountability', ['id' => $request->user_id]);
    
                }else{

                    $accountability_query = $this->get_accountability_query(false);

                    $accountabilities = $accountability_query->where([
                        ['accountabilities.user_id', $request->user_id],
                        ['accountabilities.status', 1]
                    ])
                    ->whereIn('borrowed_books.status', [5, 11])
                    ->whereDate('return_date', '<=' , $invoice_data['date_time']);

                    if($accountabilities->count() < 0){
                        
                        session()->flash('error_status', 'Invalid Invoice No!');

                        return redirect()->route('admin.accountabilities.view_user_accountability', ['id' => $request->user_id]);
                        
                    }else{

                        $invoice_id = $this->add_invoice($request->invoice_no);

                        $accountabilities = $accountabilities->get();

                        foreach ($accountabilities as $accountability) {

                            $accountability_update = Accountability::find($accountability->id);
                            
                            $accountability_update->invoice_id = $invoice_id;

                            $accountability_update->status = 2;
                            
                            
                            if($accountability->borrowed_book_status == 11){
                                
                                $penalty_price = $this->get_penalty_price($accountability);
                                
                                $accountability_update->payment_price = $penalty_price;
                                
                            }
                            
                            $accountability_update->save();

                        }

                        session()->flash('success_status', 'Marked as paid!');

                        return redirect()->route('admin.accountabilities.all_students');

                    }
                }
                
            }else{
                
                session()->flash('error_status', 'Invalid Invoice No!');

                return redirect()->route('admin.accountabilities.view_user_accountability', ['id' => $request->user_id]);

            }
        }
    }

    public function check_invoice_no($invoice_no)
    {
        $exists = Invoice::where([
            ['invoice_no', $invoice_no],
            ['status', 2]
        ])->exists();

        if($exists){
            
            return true;
            
        }else{
            
            return false;

        }
    }
    
    public function get_data_on_invoice($invoice_no)
    {
        $date = substr($invoice_no,0,10);

        $year = substr($invoice_no,0,4);

        $month = substr($invoice_no,4,2);

        $day = substr($invoice_no,6,2);

        $hour = substr($invoice_no,8,2);
        
        $date_string = $year."-".$month."-".$day." ".$hour.":00:00";
        
        $get_date = date_create($date_string);

        $date_time = date_format($get_date,"Y-m-d H:i:s");

        $user_id = substr($invoice_no,10);
        
        $invoice_data = [
            'date' => $date,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'date_time' => $date_time,
            'user_id' => $user_id
        ];

        return $invoice_data;
        
    }
    
    public function get_accountability_query($invoice_id)
    {
        $accountability_query = Accountability::join('users', 'accountabilities.user_id', 'users.id')
            ->join('borrowed_books', 'accountabilities.borrowed_book_id', 'borrowed_books.id')
            ->join('no_accessions', 'borrowed_books.accession_no_id', 'no_accessions.id')
            ->join('accessions', 'no_accessions.accession_id', 'accessions.id')
            ->join('authors', 'accessions.author_id', 'authors.id')
            ->select(
                'users.user_type',
                'accountabilities.*',
                'borrowed_books.transaction_no',
                'borrowed_books.start_date',
                'borrowed_books.due_date',
                'borrowed_books.return_date',
                'borrowed_books.status AS borrowed_book_status',
                'no_accessions.accession_no',
                'no_accessions.accession_id',
                'accessions.book_title',
                'accessions.pic_url',
                'authors.author_name'
            );

        if($invoice_id != false){
            
            $accountability_query->where('accountabilities.invoice_id', $invoice_id);

        }
        

        return $accountability_query;
    }

    public function get_penalty_price($accountability)
    {
        $total_days = strtotime($accountability->return_date) - strtotime($accountability->due_date);

        $days = round($total_days / (60 * 60 * 24));

        $base_payment = 10;

        $penalty_price = $base_payment + ($days * 5);

        return $penalty_price;
    }
    
    public function add_invoice($invoice_no)
    {
        $invoice_data = $this->get_data_on_invoice($invoice_no);
        
        $invoice = new Invoice;

        $invoice->invoice_no = $invoice_no;
        
        $invoice->user_id = $invoice_data['user_id'];

        $invoice->date_of_payment = $invoice_data['date_time'];

        $invoice->status = 2;

        $invoice->save();

        $invoice_id = $invoice->id; 
        
        return $invoice_id;
        
    }

    public function mark_settled(Request $request)
    {
        $request->validate([
            'accouuntability_id' => 'required|numeric'
        ]);
        
        Accountability::where('id', $request->accouuntability_id)->update(['status' => 2]);
        
        session()->flash('success_status', 'Marked as settled!');

        return redirect()->route('admin.accountabilities.all_students');
        
    }
    
    
    

    // Invoice Module

    public function invoices($type)
    {
        if($type != 'students' && $type != 'coaches'){
            
            return redirect()->route('admin.accountabilities.invoices', 'students');
            
        }

        $invoices = $this->get_all_invoices($type);

        $invoice_count = Invoice::count();

        return view('admin.accountabilities.invoices.invoices')->with('invoices', $invoices)
            ->with('count', $invoice_count)
            ->with('type', $type);
    }

    public function get_all_invoices($type)
    {
        $this->check_session_queries($type, 'invoices');

        $invoices_query = $this->get_invoices_query($type);
        
        $all_invoices_query = $this->add_order_queries_invoice($invoices_query, $type);
            
        return $all_invoices_query;

    }

    public function get_invoices_query($type)
    {
        if($type == 'students'){
                
            $invoices_query = Invoice::join('students', 'invoices.user_id', 'students.user_id');
            
        }else if($type == 'coaches'){
            
            $invoices_query = Invoice::join('staff_coaches', 'invoices.user_id', 'staff_coaches.user_id');

        }

        $invoices_query = $invoices_query->select('invoices.*');

        if($type == 'students'){
                
            $invoices_query = $invoices_query->addSelect(
                'students.f_name',
                'students.m_name',
                'students.l_name',
                'students.email_add'
            );
            
        }else if($type == 'coaches'){
            
            $invoices_query = $invoices_query->addSelect(
                'staff_coaches.f_name',
                'staff_coaches.m_name',
                'staff_coaches.l_name',
                'staff_coaches.email_add'
            );
        }
        
        return $invoices_query;
        
    }


    public function add_order_queries_invoice($invoices_query, $type)
    {
        $session_toOrder = $type . '_invoices_toOrder';

        $session_orderBy = $type . '_invoices_orderBy';

        $session_per_page = $type . '_invoices_per_page';
        
        $all_invoices_query = $invoices_query->orderBy(session()->get($session_toOrder), session()->get($session_orderBy))
            ->paginate(session()->get($session_per_page));


        if($all_invoices_query->count() > 0){
            
            return $all_invoices_query;
            
        }else{

            session()->flash('error_status', 'No Invoice/s Yet!');
            return $all_invoices_query;

        }
    }
    
    public function invoices_per_page($type = 'students', $per_page = 10) 
    {
        if($type == 'students' || $type == 'coaches'){
            
            $session_per_page = $type . '_invoices_per_page';
            
        }else{
            
            $session_per_page = 'students_invoices_per_page';

        }
        
        $per_pages_array = [5, 10, 20, 50, 100, 200, 500];

        if(in_array($per_page, $per_pages_array)){
            
            session([$session_per_page => $per_page]);
            
        }else{
            
            session([$session_per_page => 10]);

        }
        
        if($type == 'students' || $type == 'coaches'){
            
            return redirect()->route('admin.accountabilities.invoices', $type);
            
        }else{
            
            return redirect()->route('admin.accountabilities.invoices', 'students');

        }
    }

    public function invoices_toOrder($type = 'students', $ToOrder = 'date_of_payment') 
    {
        if($type == 'students' || $type == 'coaches'){
            
            $session_toOrder = $type . '_invoices_toOrder';

        }else{
            
            $session_toOrder = 'students_invoices_toOrder';
            
        }
        
        $types_array = ['invoice_no', 'l_name', 'date_of_payment'];
        
        if(in_array($ToOrder, $types_array)){
            
            session([$session_toOrder => $ToOrder]);
            
        }else{
            
            session([$session_toOrder => 'date_of_payment']);

        }

        if($type == 'students' || $type == 'coaches'){
            
            return redirect()->route('admin.accountabilities.invoices', $type );
            
        }else{
            
            return redirect()->route('admin.accountabilities.invoice', 'students');

        }

    }

    public function invoices_orderBy($type = 'students', $orderBy = 'desc') 
    {
        if($type == 'students' || $type == 'coaches'){
            
            $session_orderBy = $type . '_invoices_orderBy';

        }else{
            
            $session_toOrder = 'students_invoices_orderBy';
            
        }
        
        if($orderBy == 'asc' || $orderBy == 'desc' ){
            
            session([$session_orderBy => $orderBy]);
            
        }else{
            
            session([$session_orderBy => 'desc']);

        }

        if($type == 'students' || $type == 'coaches'){
            
            return redirect()->route('admin.accountabilities.invoices', $type );
            
        }else{
            
            return redirect()->route('admin.accountabilities.invoice', 'students');
            
        }
    }
    
    public function view_invoice($id = 0)
    {
        if(is_numeric($id) && $id > 0){

            return view('admin.accountabilities.invoices.view_invoice')->with('invoice_id', $id);

        }else{
            
            return redirect()->route('admin.accountabilities.invoice', 'students');

        }
    }
    
    
    public function print_invoice($id)
    {
        if(is_numeric($id) && $id > 0){

            $accountability_query = $this->get_accountability_query($id);

            if($accountability_query->count() > 0){

                $invoice = Invoice::where('id', $id)->first();

                $user_data = $this->get_all_users('single_user_id', $invoice['user_id']);
        
                return view('inc.prints.print_invoice')->with('receipts', $accountability_query->get())
                    ->with('invoice', $invoice)
                    ->with('user_data', $user_data);

            }else{

                return redirect()->route('admin.accountabilities.invoice', 'students');

            }
            
        }else{
            
            return redirect()->route('admin.accountabilities.invoice', 'students');

        }
    }

    public function search_invoices($type =  '' , $search = '')
    {
        if($type == 'students' || $type == 'coaches'){

            $invoices_query = $this->get_invoices_query($type);
        
            $invoice_query = $this->get_invoice_search_query($invoices_query, $type, $search);

            $count = $invoice_query->count();
    
            session()->flash('admin_search', $search);
            session()->flash('admin_search_count', $count);
            
            $all_invoices_query = $this->add_order_queries_invoice($invoices_query, $type);
            
            $invoices = $all_invoices_query;

            if($count <= 0){
    
                session()->flash('error_status', 'No Invoice/s found!');
                
            }

            $invoice_count = Invoice::count();

            return view('admin.accountabilities.invoices.invoices')->with('invoices', $invoices)
                ->with('count', $invoice_count)
                ->with('type', $type);
            
        }else{

            return redirect()->route('admin.accountabilities.invoice', 'students');

        }
    }

    public function get_invoice_search_query($invoice_query, $type, $search)
    {
        $all_users_id = $this->search_users($search);

        $invoice_query->WhereIn('invoices.user_id', $all_users_id['student_user_ids'])
            ->orWhere('invoice_no', 'like', '%'.$search.'%')
            ->orWhere('date_of_payment', 'like', '%'.$search.'%');
        
        return $invoice_query;

    }
    
    
    
}

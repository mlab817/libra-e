<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; 
use App\Student; 
use App\StaffCoach; 

use App\Accountability; 

use App\AquisitionBook;

use App\Accession;
use App\NoAccession;

use App\BorrowedBook;
use App\BorrowedEvent;

use App\Category;
use App\Author;
use App\Publisher;
use App\Illustration;
use App\Tag;

class AdminBookReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    // Borrowing Books

    public function get_all_book_reservations($status_type)
    {
        $all_status_type = $this->get_all_array_status_types();
        
        if(in_array($status_type, $all_status_type)){
            
            $status_array_data = $this->check_status_type($status_type);
            
        }else{
            
            $status_type = 'all_transactions';

            $status_array_data = [
                'status' => 'all',
                'get_all' => true,
                'get_all_count' => 'all_books',
                'title' => 'All Transactions',
            ];

        }
        
        $books = $this->get_admin_book_reservations($status_array_data['status'], $status_array_data['get_all']);
        
        $all_users = $this->get_all_users('all', 0);

        if($status_type == 'all_transactions' || $status_type == 'all_events'){

            $get_all_count = $this->get_all_total($status_array_data['get_all_count']);
            
        }else{

            $get_all_count = $this->get_all_count($status_array_data['get_all_count']);
            
        }

        return view('admin.borrowing.all_book_reservations')->with('books' ,$books)
            ->with('count', $get_all_count)
            ->with('all_users', $all_users)
            ->with('title', $status_array_data['title'])
            ->with('status_type', $status_type);
            
    }

    public function get_all_array_status_types()
    {
        $all_status_type = [
            'pending','approved','borrowed',
            'returned','damage_lost','denied',
            'cancelled','overdue','returned_overdue',
            'all_transactions','all_events'
        ];
        
        return $all_status_type;
        
    }

    public function check_status_type($status_type)
    {
        switch ($status_type) {
            case 'pending':
                $status = 1;
                $get_all = false;
                $get_all_count = 1;
                $title = 'Pending';
                break;

            case 'approved':
                $status = 2;
                $get_all = false;
                $get_all_count = 2;
                $title = 'Approved';
                break;

            case 'borrowed':
                $status = 3;
                $get_all = false;
                $get_all_count = 3;
                $title = 'Borrowed';
                break;

            case 'returned':
                $status = 4;
                $get_all = false;
                $get_all_count = 4;
                $title = 'Returned';
                break;

            case 'damage_lost':
                $status = 5;
                $get_all = false;
                $get_all_count = 5;
                $title = 'Damage/Lost';
                break;

            case 'denied':
                $status = 8;
                $get_all = false;
                $get_all_count = 8;
                $title = 'Denied';
                break;

            case 'cancelled':
                $status = 9;
                $get_all = false;
                $get_all_count = 9;
                $title = 'Cancelled';
                break;

            case 'overdue':
                $status = 10;
                $get_all = false;
                $get_all_count = 10;
                $title = 'Overdue';
                break;

            case 'returned_overdue':
                $status = 11;
                $get_all = false;
                $get_all_count = 11;
                $title = 'Returned & Overdue';
                break;

            case 'all_transactions':
                $status = 'all';
                $get_all = true;
                $get_all_count = 'all_books';
                $title = 'All Transactions';
            break;
            
            case 'all_events':
                $status = 'all_events';
                $get_all = true;
                $get_all_count = 'all_events';
                $title = 'All Events';
                break;
            
            default:
                $status = 'all';
                $get_all = true;
                $get_all_count = 'all_books';
                $title = 'All Transactions';
                break;
        }
        
        $status_array_data = [
            'status' => $status,
            'get_all' => $get_all,
            'get_all_count' => $get_all_count,
            'title' => $title 
        ];
        
        return $status_array_data;
                
    }

    // Filter & Order, Get All Reservations by Status

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

        }
    }

    public function get_all_count($type)
    {
        if($type == 'all'){
            
            $count = BorrowedBook::count();

        }else if($type == 'all_events'){

            $count = BorrowedEvent::count();
            
        }else{
            
            $count = BorrowedBook::where('status', $type)->count();
            
        }

        return $count; 
        
    }

    public function get_all_total($status)
    {
        if($status == 'all_books'){
            
            $pending = BorrowedBook::where('status', 1)->count();

            $approved = BorrowedBook::where('status', 2)->count();

            $borrowed = BorrowedBook::where('status', 3)->count();

            $returned = BorrowedBook::where('status', 4)->count();

            $damage_lost = BorrowedBook::where('status', 5)->count();

            $denied = BorrowedBook::where('status', 8)->count();

            $cancelled = BorrowedBook::where('status', 9)->count();

            $overdue = BorrowedBook::where('status', 10)->count();

            $returned_overdue = BorrowedBook::where('status', 11)->count();

            $all = BorrowedBook::count();

        }else if($status == 'all_events'){
            
            $pending = BorrowedEvent::where('status', 1)->count();

            $approved = BorrowedEvent::where('status', 2)->count();

            $borrowed = BorrowedEvent::where('status', 3)->count();

            $returned = BorrowedEvent::where('status', 4)->count();

            $damage_lost = BorrowedEvent::where('status', 5)->count();

            $denied = BorrowedEvent::where('status', 8)->count();

            $cancelled = BorrowedEvent::where('status', 9)->count();

            $overdue = BorrowedEvent::where('status', 10)->count();

            $returned_overdue = BorrowedEvent::where('status', 11)->count();
            
            $all = BorrowedEvent::count();

        }

        $all_count = [
            'pending' => $pending, 
            'approved' => $approved, 
            'borrowed' => $borrowed, 
            'returned' => $returned, 
            'damage_lost' => $damage_lost, 
            'denied' => $denied, 
            'cancelled' => $cancelled, 
            'overdue' => $overdue, 
            'returned_overdue' => $returned_overdue, 
            'all' => $all 
        ];

        return $all_count;
    }
    
    public function check_session_queries()
    {
        if(session()->has('admin_book_reservations_toOrder') != true){

            session(['admin_book_reservations_toOrder' => 'updated_at' ]);
            
        }

        if(session()->has('admin_book_reservations_orderBy') != true){

            session(['admin_book_reservations_orderBy' => 'desc' ]);
            
        }

        if (session()->has('admin_book_reservations_per_page') != true) {

            session(['admin_book_reservations_per_page' => 10 ]);
            
        }
    }

    public function get_admin_book_reservations($status, $get_all)
    {
        $this->check_session_queries();
        
        $admin_book_reservations_query = $this->get_admin_book_reservations_query($status, $get_all);
        
        if($status == 'all_events'){
            
            $get = 'all_events';
            
        }else{
            
            $get = 'all_books';
               
        }
        
        $all_user_admin_book_reservations = $this->add_order_queries($admin_book_reservations_query, $get_all, $get);
            
        return $all_user_admin_book_reservations;
        
    }

    public function get_admin_book_reservations_query($status, $get_all)
    {
        $this->check_borrowing_books();

        if($status == 'all_events'){
            
            $admin_book_reservations_query = BorrowedEvent::join('borrowed_books', 'borrowed_events.borrowed_book_id', 'borrowed_books.id')
                ->join('no_accessions', 'borrowed_books.accession_no_id', 'no_accessions.id')
                ->join('accessions', 'no_accessions.accession_id', 'accessions.id')
                ->join('authors', 'accessions.author_id', '=', 'authors.id')
                ->select(
                    'borrowed_events.*',
                    'borrowed_books.transaction_no',
                    'borrowed_books.id AS borrowed_books_id',
                    'borrowed_books.user_id',
                    'borrowed_books.accession_no_id',
                    'borrowed_books.book_type',
                    'no_accessions.accession_no',
                    'no_accessions.accession_id',
                    'accessions.book_title',
                    'accessions.pic_url',
                    'authors.author_name'
                );

        }else{

            $admin_book_reservations_query = BorrowedBook::join('no_accessions', 'borrowed_books.accession_no_id', 'no_accessions.id')
                ->join('accessions', 'no_accessions.accession_id', 'accessions.id')
                ->join('authors', 'accessions.author_id', '=', 'authors.id')
                ->select(
                    'borrowed_books.*',
                    'no_accessions.accession_no',
                    'no_accessions.accession_id',
                    'no_accessions.availability',
                    'accessions.book_title',
                    'accessions.pic_url',
                    'authors.author_name'
                );

        }
        
            
        if($get_all == false){

            $admin_book_reservations_query->where('borrowed_books.status', $status);

        }

        return $admin_book_reservations_query;
            
    }

    public function add_order_queries($admin_book_reservations_query, $get_all, $get)
    {
        if($get_all){

            if(session()->has('admin_book_reservations_getAll')){
    
                if(session()->get('admin_book_reservations_getAll') != 'all'){
    
                    if($get == 'all_books'){
                        
                        $admin_book_reservations_query = $admin_book_reservations_query->where('borrowed_books.status', session()->get('admin_book_reservations_getAll'));
                        
                    }else if($get == 'all_events'){
                        
                        $admin_book_reservations_query = $admin_book_reservations_query->where('borrowed_events.status', session()->get('admin_book_reservations_getAll'));

                    }
    
                }else{
                        
                    session(['admin_book_reservations_getAll' => 'all' ]);
                }
                
            }else{

                session(['admin_book_reservations_getAll' => 'all' ]);
                
            }
        }
        
        $all_user_admin_book_reservations = $admin_book_reservations_query->orderBy(session()->get('admin_book_reservations_toOrder'), session()->get('admin_book_reservations_orderBy'))
            ->paginate(session()->get('admin_book_reservations_per_page'));


        if($all_user_admin_book_reservations->count() > 0){
            
            return $all_user_admin_book_reservations;
            
        }else{

            session()->flash('error_status', 'No Reservation/s Yet!');
            return $all_user_admin_book_reservations;

        }
    }

    public function admin_book_reservations_per_page($status = 'all_transactions', $per_page = 10) 
    {
        $per_page_array = [5,10,20,50,100,200,500];
        
        if(in_array($per_page, $per_page_array)){
            
            session(['admin_book_reservations_per_page' => $per_page ]);
            
        }else{

            session(['admin_book_reservations_per_page' => 10 ]);
        }

        $all_status_type = $this->get_all_array_status_types();
        
        if(in_array($status, $all_status_type)){
            
            return redirect()->route('admin.borrowing.all_book_reservations', [$status]);
            
        }else{
            
            return redirect()->route('admin.borrowing.all_book_reservations', 'all_transactions');
            
        }
    }

    public function admin_book_reservations_toOrder($status = 'all_transactions', $ToOrder = 'updated_at') 
    {
        $to_order_array = ['transaction_no','book_title','author_name','accession_no','due_date','updated_at'];

        if(in_array($ToOrder, $to_order_array)){
            
            session(['admin_book_reservations_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['admin_book_reservations_toOrder' => 'updated_at' ]);

        }

        $all_status_type = $this->get_all_array_status_types();

        if(in_array($status, $all_status_type)){

            return redirect()->route('admin.borrowing.all_book_reservations', [$status]);
            
        }else{
            
            return redirect()->route('admin.borrowing.all_book_reservations', 'all_transactions');
            
        }
    }

    public function admin_book_reservations_orderBy($status = 'all_transactions', $orderBy = 'desc') 
    {

        if($orderBy == 'asc' || $orderBy ==  'desc' ){
            
            session(['admin_book_reservations_orderBy' => $orderBy ]);
            
        }else{
            
            session(['admin_book_reservations_orderBy' => 'desc' ]);

        }

        $all_status_type = $this->get_all_array_status_types();

        if(in_array($status, $all_status_type)){

            return redirect()->route('admin.borrowing.all_book_reservations', [$status]);
            
        }else{
            
            return redirect()->route('admin.borrowing.all_book_reservations', 'all_transactions');
            
        }
    }

    public function filter_admin_book_reservations($status = 'all_transactions' ,$filter = 'all') 
    {
        $filter_array = ['all',1, 2, 3, 4, 5, 8, 9, 10, 11];
        
        if(in_array($filter, $filter_array)){
            
            session(['admin_book_reservations_getAll' => $filter ]);
            
        }else{
            
            session(['admin_book_reservations_getAll' => 'all' ]);

        }

        if($status == 'all_events'){

            return redirect()->route('admin.borrowing.all_book_reservations', 'all_events');
            
        }else{
            
            return redirect()->route('admin.borrowing.all_book_reservations', 'all_transactions');
            
        }
    }

    // Search queries

    public function search_book_reservations($search, $type, $get_all)
    {
        $this->check_session_queries();
        
        $book_reservations_query = $this->get_admin_book_reservations_query($type, $get_all);
        
        $book_reservations_query = $this->get_search_query($book_reservations_query, $search);
        
        $count = $book_reservations_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);

        if($type == 'all'){

            session(['admin_book_reservations_getAll' => 'all' ]);

        }

        if($type == 'all_events'){
            
            $get = 'all_events';
            
        }else{
            
            $get = 'all_books';
            
        }

        $all_user_book_reservations = $this->add_order_queries($book_reservations_query, $get_all, $get);
        
        $books = $all_user_book_reservations;
        
        if($count <= 0){

            session()->flash('error_status', 'No Reservation/s found!');
            
        }

        return $books;
    }
    
    public function get_search_query($book_reservations_query, $search)
    {
        $all_users_id = $this->search_users($search);

        $book_reservations_query->where('borrowed_books.transaction_no', 'like', '%'.$search.'%')
            ->orWhereIn('borrowed_books.user_id', $all_users_id['student_user_ids'])
            ->orWhereIn('borrowed_books.user_id', $all_users_id['staff_coach_ids'])
            ->orWhere('accessions.book_title', 'like', '%'.$search.'%')
            ->orWhere('authors.author_name', 'like', '%'.$search.'%')
            ->orWhere('no_accessions.accession_no', 'like', '%'.$search.'%')
            ->orWhere('borrowed_books.start_date', 'like', '%'.$search.'%')
            ->orWhere('borrowed_books.due_date', 'like', '%'.$search.'%');
        
        return $book_reservations_query;
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

    // Search By status

    public function search_all_book_reservations($status_type, $search = '')
    {
        $all_status_type = $this->get_all_array_status_types();
        
        if(in_array($status_type, $all_status_type)){
            
            if($status_type != 'all_events'){

                $status_type = 'all_transactions';
                
            }

            $status_array_data = $this->check_status_type($status_type);
            
        }else{
            
            $status_type = 'all_transactions';

            $status_array_data = [
                'status' => 'all',
                'get_all' => true,
                'get_all_count' => 'all_books',
                'title' => 'All Transactions',
            ];

        }
        
        $books = $this->search_book_reservations($search, $status_array_data['status'], $status_array_data['get_all']);
        
        $all_users = $this->get_all_users('all', 0);

        if($status_type == 'all_events'){

            $get_all_count = $this->get_all_total($status_array_data['get_all_count']);
            
        }else{

            $get_all_count = $this->get_all_total('all_books');
            //$get_all_count = $this->get_all_count($status_array_data['get_all_count']);

        }

        return view('admin.borrowing.all_book_reservations')->with('books' ,$books)
            ->with('count', $get_all_count)
            ->with('all_users', $all_users)
            ->with('title', $status_array_data['title'])
            ->with('status_type', $status_type);
        

    }

    // View Single Reservation

    public function view_reservation($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $reservation = $this->get_reservation($id);
            
            if($reservation->count() == 1){
                
                $user_data = $this->get_all_users('single', $id);

                $reservation_data = $this->get_reservation_dynamic_data($reservation);

                return view('admin.borrowing.view_reservation')->with('reservation', $reservation->first())
                    ->with('user_data', $user_data)
                    ->with('reservation_data', $reservation_data);

            }else{
                
                return redirect()->route('admin.borrowing.all_transactions');
                
            }
            
        }else{
            
            return redirect()->route('admin.borrowing.all_transactions');

        }
    }

    public function get_reservation($id)
    {
        $reservation_query = $this->get_admin_book_reservations_query('all_books', true);

        $reservation = $reservation_query->where('borrowed_books.id', $id);

        return $reservation;

    }

    public function get_reservation_dynamic_data($reservation)
    {
        $get_reservation = $reservation->first();

        $status = $get_reservation['status'];
        
        if($status == 1){
    
            $point_arrow = 'pending';
            $color_class = 'text-warning';
            $url_back = route('admin.borrowing.all_book_reservations') . '/pending';
            $form_url = route('admin.borrowing.approve_reservation');
            
          }else if($status == 2){
            
            $point_arrow = 'approved';
            $color_class = 'text-info';
            $url_back = route('admin.borrowing.all_book_reservations') . '/approved';
            $form_url = route('admin.borrowing.claim_reservation');
      
          }else if($status == 3){
            
            $point_arrow = 'borrowed';
            $color_class = 'text-primary';
            $url_back = route('admin.borrowing.all_book_reservations') . '/borrowed';
            $form_url = route('admin.borrowing.return_reservation');
            
          }else if($status == 4){
            
            $point_arrow = 'returned';
            $color_class = 'text-success';
            $url_back = route('admin.borrowing.all_book_reservations') . '/returned';
            $form_url = route('admin.borrowing.unreturned_reservation');
            
          }else if($status == 5){
            
            $point_arrow = 'damage_lost';
            $color_class = 'text-danger';
            $url_back = route('admin.borrowing.all_book_reservations') . '/damage_lost';
            $form_url = route('admin.borrowing.unreturned_reservation');
            
          }else if($status == 8){
      
            $point_arrow = 'denied';
            $color_class = 'text-danger';
            $url_back = route('admin.borrowing.all_book_reservations') . '/denied';
            $form_url = route('admin.borrowing.approve_reservation');
            
          }else if($status == 9){
            
            $point_arrow = 'cancelled';
            $color_class = 'text-danger';
            $url_back = route('admin.borrowing.all_book_reservations') . '/cancelled';
            $form_url = route('admin.borrowing.approve_reservation');
            
          }else if($status == 10){
            
            $point_arrow = 'overdue';
            $color_class = 'text-danger';
            $url_back = route('admin.borrowing.all_book_reservations') . '/overdue';
            $form_url = route('admin.borrowing.return_overdue_reservation');
            
          }else if($status == 11){
            
            $point_arrow = 'returned_overdue';
            $color_class = 'text-danger';
            $url_back = route('admin.borrowing.all_book_reservations') . '/returned_overdue';
            $form_url = route('admin.borrowing.return_overdue_reservation');
            
          }

          $reservation_data = [
              'point_arrow' => $point_arrow,
              'color_class' => $color_class,
              'url_back' => $url_back,
              'form_url' => $form_url
          ];
          
          return $reservation_data;
        
    }

    public function approve_reservation(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|numeric',
            'accession_id' => 'required|numeric',
            'accession_no_id' => 'required|numeric',
            'due_date' => 'required',
        ]);

        $reservation = $this->get_reservation($request->borrow_id);
        
        /*
        $check_available = NoAccession::where([
            ['status', 1],
            ['availability', 1],
            ['id', $request->accession_no_id]
        ])->count();
        */

        $date_available = $this->check_if_date_available($request->accession_no_id, $request->due_date);

        if($date_available){
            
            $this->change_reservation_status($request->borrow_id, 'approved');

            session()->flash('success_status', 'Request Approved!');
            return redirect()->route('admin.borrowing.all_book_reservations', 'approved');

        }else{

            session()->flash('error_status', 'The Selected date is already unavailable!');
            return redirect()->route('admin.borrowing.view_reservation', [$request->borrow_id]);
            
        }
        
        /*
        $count = $this->count_available_books($request->accession_id);
        
        if($count > 1){
            
            

        }else{

            session()->flash('error_status', 'Insufficient Available books to be Borrowed!');
            return redirect()->route('admin.borrowing.view_reservation', [$request->borrow_id]);
            
        }
        */
    }

    public function count_available_books($accesion_id)
    {
        $count = NoAccession::where([
                ['status', 1],
                ['availability', 1],
                ['accession_id', $accesion_id]
            ])->count();

        return $count;
    }

    public function check_if_date_available($accession_no_id, $date)
    {
        $count = BorrowedBook::where('accession_no_id', $accession_no_id)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('due_date', '>=', $date)
            ->whereIn('status', [2, 3])
            ->count();

        if($count > 0){
            
            return false;

        }else{

            return true;
            
        }
    }

    public function change_reservation_status($borrowed_book_id, $type_status)
    {
        if($type_status == 'approved'){
            
            $borrow = BorrowedBook::where('id', $borrowed_book_id)->first();
            
            $date = date($borrow['due_date']);
    
            $start_date = $date;
            
            for ($i=1; $i < 3; $i++) { 
                
                $due_date = date('Y-m-d H:i:s', strtotime($date. ' + '.$i.' days'));
    
                $date_available_count = $this->check_if_date_available( $borrow['accession_no_id'], $due_date);
                
                if($date_available_count == 0){
                    
                    $i = 4;
    
                }

            }

            $given_date = strtotime($due_date);

            if(date('D', $given_date) == 'Sat') { 
                
                $due_date = date('Y-m-d H:i:s', strtotime($due_date. ' - 1 days'));
                
            }else if(date('D', $given_date) == 'Sun'){
                
                $due_date = date('Y-m-d H:i:s', strtotime($due_date. ' + 1 days'));

            }
            
            $borrow_book = BorrowedBook::find($borrowed_book_id);

            $borrow_book->start_date = $start_date;

            $borrow_book->due_date = $due_date;

            $borrow_book->status = 2;
            
            $borrow_book->save();
            
            /*BorrowedBook::where('id', $borrowed_book_id)
                ->update([
                    ['start_date' => $start_date],
                    ['due_date' => $due_date],
                    ['status' => 2]
                ]);
            */
    
            $this->add_book_event($borrowed_book_id, $type_status, '');
            
        }else if($type_status == 'borrowed'){
            
            

        }
        
    }

    public function add_book_event($borrow_id, $event, $notes)
    {
        $book_event = new BorrowedEvent;
        
        $book_event->borrowed_book_id = $borrow_id;
        
        $book = BorrowedBook::where('id', $borrow_id)->first();
        
        $book_event->start_date = $book['start_date'];

        $book_event->due_date = $book['due_date'];
        
        if($event == 'pending'){
            
            $book_event->status = 1;
            
        }else if($event == 'approved'){
            
            $book_event->status = 2;

        }else if($event == 'borrowed'){
            
            $book_event->status = 3;
            
        }else if($event == 'returned'){
            
            $book_event->status = 4;

            $book_event->return_date = $book['return_date'];
            
        }else if($event == 'damage_lost'){
            
            $book_event->notes = $notes;

            $book_event->status = 5;
            
        }else if($event == 'denied'){
            
            $book_event->notes = $notes;

            $book_event->status = 8;
            
        }else if($event == 'overdue'){
            
            $book_event->notes = $notes;

            $book_event->status = 10;
            
        }else if($event == 'returned_overdue'){
            
            $book_event->notes = $notes;

            $book_event->status = 11;

            $book_event->return_date = $book['return_date'];
            
        }

        $book_event->save();

    }

    public function claim_reservation(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|numeric',
            'accession_id' => 'required|numeric',
            'accession_no_id' => 'required|numeric',
            'start_date' => 'required',
            'due_date' => 'required',
        ]);
        
        $if_still_borrowed = NoAccession::where([
            ['id', $request->accession_no_id],
            ['availability', 2],
        ])->exists();

        if($if_still_borrowed){
            
            session()->flash('error_status', 'Book is still borrowed and not returned yet!');
            return redirect()->route('admin.borrowing.all_book_reservations', ['id' => $request->borrow_id]);

        }else{

            $reserve = BorrowedBook::where('id', $request->borrow_id)
                ->update(['status' => 3]);
    
            NoAccession::where('id', $request->accession_no_id)
                ->update(['availability' => 2]);
    
            $this->add_book_event($request->borrow_id, 'borrowed', '');
    
            session()->flash('success_status', 'Book Claimed!');
            return redirect()->route('admin.borrowing.all_book_reservations', 'borrowed');

        }
    }

    public function return_reservation(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|numeric',
            'accession_id' => 'required|numeric',
            'accession_no_id' => 'required|numeric',
            'start_date' => 'required',
            'due_date' => 'required',
        ]);

        $borrow = BorrowedBook::find($request->borrow_id);
        
        $current_date = date('Y-m-d H:i:s'); 
        
        $borrow->return_date = $current_date; 

        $borrow->status = 4;

        $borrow->save();
        
        NoAccession::where('id', $request->accession_no_id)
            ->update(['availability' => 1]);
        
        $this->add_book_event($request->borrow_id, 'returned', '');
        
        session()->flash('success_status', 'Book Returned!');
        return redirect()->route('admin.borrowing.all_book_reservations', 'returned');
        
    }

    public function damage_lost_reservation(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
            'acc_id' => 'required|numeric',
            'accession_no_id' => 'required|numeric'
        ]);

        $no_accession = NoAccession::findOrFail($request->accession_no_id);

        $reserve = BorrowedBook::findOrFail($id);
        
        $user_id = $reserve->user_id; 

        $borrowed_book_id = $reserve->id; 

        $accession_no_id = $reserve->accession_no_id; 

        $reserve->notes = $request->notes;

        $current_date = date('Y-m-d H:i:s'); 
        
        $reserve->start_date = $current_date;
        
        $reserve->status = 5;
        
        $reserve->save();

        $this->add_book_event($id, 'damage_lost', $request->notes);

        $this->add_accountabilities($user_id, $borrowed_book_id, 3);

        $no_accession = NoAccession::findOrFail($request->accession_no_id);

        $no_accession->availability = 0;

        $no_accession->status = 3;

        $no_accession->save();

        $this->add_aquisition_book($request->acc_id, 1, 3);

        session()->flash('success_status', 'Book added to Damage/Lost!');
        return redirect()->route('admin.borrowing.all_book_reservations', 'damage_lost');

    }

    public function add_aquisition_book($book, $quantity, $aquisition_type)
    {
        $aqusition_book = new AquisitionBook;

        $aqusition_book->acc_id = $book;

        $aqusition_book->quantity = $quantity;

        $aqusition_book->aquisition_type = $aquisition_type;

        $aqusition_book->save();
    }



    public function unreturned_reservation(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|numeric',
            'accession_id' => 'required|numeric',
            'accession_no_id' => 'required|numeric',
            'start_date' => 'required',
            'due_date' => 'required',
        ]);
        
        $back_event = BorrowedEvent::where([
            ['borrowed_book_id', $request->borrow_id],
            ['status', 4]
        ])->latest()->first();
            
        $borrow = BorrowedBook::find($request->borrow_id);

        $borrow->start_date = $back_event->start_date; 

        $borrow->status = 3;

        $borrow->save();

        NoAccession::where('id', $request->accession_no_id)
            ->update(['availability' => 2]);
        
        $this->add_book_event($request->borrow_id, 'borrowed', '');
        
        session()->flash('success_status', 'Book Unreturned!');
        return redirect()->route('admin.borrowing.all_book_reservations', 'borrowed');
        
    }

    public function deny_reservation(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
            'type' => 'required'
        ]);

        $reserve = BorrowedBook::findOrFail($id);

        $reserve->notes = $request->notes;

        if($request->type == 'deny_request'){
            
            $current_date = date('Y-m-d H:i:s'); 
            
            $reserve->start_date = $current_date;
            
        }else if($request->type == 'deny_approved'){
            
            $old_start_date = $reserve->start_date;
            
            $current_date = date('Y-m-d H:i:s'); 
            
            $reserve->start_date = $current_date;
    
            $reserve->due_date = $old_start_date;

        }
        
        $reserve->status = 8;
        
        $reserve->save();

        $this->add_book_event($id, 'denied', $request->notes);

        session()->flash('success_status', 'Reservation Denied!');
        return redirect()->route('admin.borrowing.all_book_reservations', 'denied');

    }

    public function return_overdue_reservation(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|numeric',
            'accession_id' => 'required|numeric',
            'accession_no_id' => 'required|numeric',
            'start_date' => 'required',
            'due_date' => 'required',
        ]);

        $borrow = BorrowedBook::find($request->borrow_id);
        
        $current_date = date('Y-m-d H:i:s'); 
        
        $borrow->return_date = $current_date; 

        $borrow->notes = 'Returned but overdue';

        $borrow->status = 11;

        $borrow->save();

        NoAccession::where('id', $request->accession_no_id)
            ->update(['availability' => 1]);
        
        $this->add_book_event($request->borrow_id, 'returned_overdue', 'Returned but overdue');
        
        session()->flash('success_status', 'Book Returned!');
        return redirect()->route('admin.borrowing.all_book_reservations', 'returned_overdue');
        
    }

    public function check_borrowing_books()
    {
        $this->check_approved_books();
        
        $this->check_borrowed_books();
    }

    public function check_approved_books()
    {
        $current_date = date('Y-m-d H:i:s'); 

        $current_date = date('Y-m-d H:i:s', strtotime($current_date. ' - 1 days'));

        $to_update = BorrowedBook::where([
            ['due_date', '<', $current_date],
            ['status', 2]
        ])->get();

        if($to_update->count() > 0){
            
            foreach ($to_update as $update) {

                $if_still_borrowed = NoAccession::where([
                    ['id', $update->accession_no_id],
                    ['availability', 2],
                ])->exists();

                if($if_still_borrowed){
                    
                    $notes = "The book is not yet returned from the last user who borrowed it!";
                    
                }else{
                    
                    $notes = "Did not claim the book in the Reserved date!";
                    
                }

                $update = BorrowedBook::findOrFail($update->id);

                $update->notes = $notes;
            
                $old_start_date = $update->start_date;
            
                $current_date = date('Y-m-d H:i:s'); 
                
                $update->start_date = $current_date;

                $update->due_date = $old_start_date;

                $update->status = 8;

                $update->save();
                
                $this->add_book_event($update->id, 'denied', $notes);

                //$this->add_accountabilities($update->user_id, $update->id, 1);
    
            }
        }
    }

    public function check_borrowed_books()
    {
        $current_date = date('Y-m-d H:i:s'); 
        
        $current_date = date('Y-m-d H:i:s', strtotime($current_date. ' - 1 days'));

        $to_update = BorrowedBook::where([
            ['due_date', '<', $current_date],
            ['status', 3]
        ])->get();

        if($to_update->count() > 0){
            
            foreach ($to_update as $update) {

                $update = BorrowedBook::findOrFail($update->id);

                $update->notes = 'Overdue to be returned!';
            
                $update->status = 10;

                $update->save();
                
                $this->add_book_event($update->id, 'overdue', 'Overdue to be returned!');

                $this->add_accountabilities($update->user_id, $update->id, 2);
    
            }
        }
    }

    public function add_accountabilities($user_id, $borrowed_book_id, $type)
    {
        $accountability = new Accountability;
        
        $accountability->user_id = $user_id;

        $accountability->borrowed_book_id = $borrowed_book_id;

        $accountability->accountability_type = $type;
        
        $accountability->status = 1;
        
        $accountability->save();

    }

}

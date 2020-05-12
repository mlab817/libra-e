<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Student; 
use App\StaffCoach; 

use App\Accession;
use App\NoAccession;

use App\BorrowedBook;
use App\BorrowedEvent;

use App\Category;
use App\Author;
use App\Publisher;
use App\Illustration;
use App\Tag;

class BookReservationController extends Controller
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

    public function reservation_view()
    {
        $this->get_loggedIn_user_data();
        return view('libraE.reservations');    
    }

    // Books Reservations
    
    public function reserve_books()
    {
        $this->get_loggedIn_user_data();
        
        $all_available_books = $this->get_all_available_books();

        return view('libraE.books.books')->with('all_books', $all_available_books['all_books'])
            ->with('available_book', $all_available_books['available_book']);

    }

    public function check_session_queries()
    {
        if(session()->has('available_books_toOrder') != true){

            session(['available_books_toOrder' => 'created_at' ]);
            
        }

        if(session()->has('available_books_orderBy') != true){

            session(['available_books_orderBy' => 'desc' ]);
            
        }

        if (session()->has('available_books_per_page') != true) {

            session(['available_books_per_page' => 10 ]);
            
        }
    }
    
    
    public function get_all_available_books()
    {
        $this->check_session_queries();
        
        $accessions_query = $this->get_accession_query();
        
        $all_books_count = $this->add_order_queries($accessions_query, 'index');
            
        return $all_books_count;
        
    }

    public function get_accession_query()
    {
        $this->check_borrowing_books();
        
        $accessions_query = Accession::join('authors', 'accessions.author_id', '=', 'authors.id')
        ->join('publishers', 'accessions.publisher_id', '=', 'publishers.id')
        ->select(
            'accessions.*',
            'accessions.id AS accession_id', 
            'accessions.pic_url', 
            'accessions.book_title', 
            'accessions.copyright', 
            'authors.author_name',
            'publishers.name AS publisher_name'
        )
        ->distinct()->where('accessions.status', 1);
                
        return $accessions_query;
        
    }

    public function add_order_queries($accessions_query)
    {
        $all_books = $accessions_query->orderBy(session()->get('available_books_toOrder'), session()->get('available_books_orderBy'))
            ->paginate(session()->get('available_books_per_page'));

        $available_book = $this->get_book_count();
        
        $all_books_count = [
            'all_books' => $all_books,
            'available_book' => $available_book,
        ];

        if($all_books->count() > 0){
            
            return $all_books_count;
            
        }else{

            return $all_books_count;

        }
    }

    public function get_book_count()
    {
        $available_book = NoAccession::select('accession_id', DB::raw('Count(accession_id) as count'))
            ->groupBy('accession_id')
            ->havingRaw('COUNT(accession_id) > 0')
            ->where('no_accessions.status', 1)
            ->whereIn('availability', [1, 2])
            ->get();

        return $available_book;
            
    }

    public function available_books_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200 || 500){
            session(['available_books_per_page' => $per_page ]);
        }else{
            session(['available_books_per_page' => 10 ]);
        }

        return redirect()->route('libraE.reservations.books');

    }

    public function available_books_toOrder($ToOrder = 'created_at') 
    {

        if($ToOrder == 'book_title' || 'author_name' || 'publisher_name' || 'created_at'){
            
            session(['available_books_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['available_books_toOrder' => 'created_at' ]);

        }

        return redirect()->route('libraE.reservations.books');

    }

    public function available_books_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || 'desc' ){
            
            session(['available_books_orderBy' => $orderBy ]);
            
        }else{
            
            session(['available_books_orderBy' => 'desc' ]);

        }

        return redirect()->route('libraE.reservations.books');

    }

    public function search_book($search = '')
    {
        $this->get_loggedIn_user_data();
        
        $this->check_session_queries();
        
        $accessions_query = $this->get_accession_query();

        $accessions_query->Where('accessions.book_title', 'like', '%'.$search.'%')
            ->orWhere('accessions.copyright', 'like', '%'.$search.'%')
            ->orWhere('authors.author_name', 'like', '%'.$search.'%')
            ->orWhere('publishers.name', 'like', '%'.$search.'%');
            
            
        $count = $accessions_query->count();

        session()->flash('libraE_search', $search);
        session()->flash('libraE_search_count', $count);
        session(['available_books_getAll' => 'all' ]);
        
        $all_available_books = $this->add_order_queries($accessions_query);

        if($count <= 0){

            session()->flash('error_status', 'No Book found!');
            
        }
        
        return view('libraE.books.books')->with('all_books', $all_available_books['all_books'])
            ->with('available_book', $all_available_books['available_book']);

    }

    public function view_book($id = 0)
    {
        $this->get_loggedIn_user_data();
        
        if(is_numeric($id) && $id > 0){
            
            $accession = $this->get_accession_book($id);
            
            if($accession){
                
                if($accession['category_id_2'] != null){

                    $category_name_2 = Category::where('id', $accession['category_id_2'])->select('name')->first();

                }else{

                    $category_name_2 = null;
                    
                }
                
                $count = $this->count_available_books($id);

                $no_accessions = $this->get_all_no_accession($id);

                return view('libraE.books.view_book')->with('book', $accession)
                    ->with('category_2', $category_name_2)
                    ->with('count', $count)
                    ->with('no_accessions', $no_accessions);
                
            }else{
                
                return redirect()->route('libraE.reservations.books');
                
            }
            
        }else{
            
            return redirect()->route('libraE.reservations.books');

        }
    }
    
    public function get_accession_book($accesion_id)
    {

        $exist = Accession::where('id', $accesion_id)->exists();

        if($exist == false){
            return 0;
        }
        
        $get_accession = Accession::where('id', $accesion_id)->first();

        $accession_query = Accession::join('authors', 'accessions.author_id', '=', 'authors.id')
            ->join('publishers', 'accessions.publisher_id', '=', 'publishers.id');

        if($get_accession['category_id'] != null){
            
            $accession_query = $accession_query->join('categories', 'accessions.category_id', '=', 'categories.id');

        }

        if($get_accession['illustration_id'] != null){
            
            $accession_query = $accession_query->join('illustrations', 'accessions.illustration_id', '=', 'illustrations.id');

        }


        $accession_query = $accession_query->select(
            'accessions.*', 
            'accessions.id AS accession_id', 
            'authors.author_name',
            'publishers.name AS publisher_name'
        );

        if($get_accession['category_id'] != null){
            
            $accession_query = $accession_query->addSelect('categories.name AS category_name');
            
        }
        
        if($get_accession['illustration_id'] != null){
            
            $accession_query = $accession_query->addSelect('illustrations.name AS illustration_name');

        }
        
        $accession = $accession_query->where('accessions.id', $accesion_id)->first();
            
        return $accession;
            
    }
    
    public function count_available_books($accesion_id)
    {
        $count = NoAccession::where([
                ['status', 1],
                ['accession_id', $accesion_id]
            ])
            ->whereIn('availability', [1, 2])
            ->count();

        return $count;
    }

    public function get_all_no_accession($accession_id)
    {
        $no_accessions = NoAccession::where([
                ['status', 1],
                ['accession_id', $accession_id]
            ])
            ->whereIn('availability', [1, 2])
            ->get();

        return $no_accessions;
    }
    
    public function reserve_book(Request $request)
    {
        //return $request->all();
        
        $this->get_loggedIn_user_data();
        
        $request->validate([
            'accession_id' => 'required|numeric',
            'accession_no_id' => 'required|numeric',
            'reserve_date' => 'required'
        ]);

        $too_many = $this->check_if_user_has_too_many_request();
        
        if($too_many){
            
            session()->flash('error_status', 'You have reached the maximum number of request!');
            return redirect()->route('libraE.books.view_book', [$request->accession_id]);
            
        }

        $count = $this->count_available_books($request->accession_id);
        
        if($count > 1){
            
            /*
            $check_available = NoAccession::where([
                ['status', 1],
                ['availability', 1],
                ['id', $request->accession_no_id]
            ])->count();
            */

            $date_available = $this->check_if_date_available($request->accession_no_id, $request->reserve_date);

            if($date_available){
                
                $this->add_borrowed_book_status($request->accession_no_id, 'pending', $request->reserve_date);

                session()->flash('success_status', 'Request Added!');
                return redirect()->route('libraE.my_reservations');

            }else{

                session()->flash('error_status', 'Your Selected date is already unavailable!');
                return redirect()->route('libraE.books.view_book', [$request->accession_id]);
                
            }

        }else{

            session()->flash('error_status', 'Insufficient Available books to be Borrowed!');
            return redirect()->route('libraE.books.view_book', [$request->accession_id]);
            
        }
    }

    public function check_if_user_has_too_many_request()
    {
        $count = BorrowedBook::where([
            ['user_id', session()->get('loggedIn_user_id')],
            ['status', 1],
        ])->count();

        if(session()->get('loggedIn_user_type') == 1){
            
            $maximum_no = 3;
            
        }else if(session()->get('loggedIn_user_type') == 2){
            
            $maximum_no = 5;
            
        }

        if($count >= $maximum_no){
            
            return true;
            
        }else{

            return false;
            
        }
        
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

    public function add_borrowed_book_status($accession_no_id, $type_status, $start_date)
    {
        if($type_status == 'pending'){

            $last_transaction_no = $this->get_last_transaction_no();
            
            $new_transaction_no = $this->to_string_add_transaction_no($last_transaction_no);
    
            $borrow = new BorrowedBook;
    
            $borrow->transaction_no = $new_transaction_no;
    
            $borrow->user_id = session()->get('loggedIn_user_id');
    
            $borrow->accession_no_id = $accession_no_id;
    
            $borrow->book_type = 1;
    
            $date = date('Y-m-d H:i:s'); 
    
            $borrow->start_date = $date;
            
            $due_date = date($start_date);
            
            $borrow->due_date = $due_date;
            
            $borrow->status = 1;
    
            $borrow->save();
    
            $borrow_id = $borrow->id; 
    
            $this->add_book_event($borrow_id, $type_status);
            
        }
    }

    public function get_last_transaction_no()
    {
        $count = BorrowedBook::count();
        
        if($count == 0){
            
            return 0;

        }else{
            
            $last_transaction_no = BorrowedBook::orderBy('transaction_no', 'desc')->select('transaction_no')->first();
            return $last_transaction_no->transaction_no;
            
        }
    }
    
    public function to_string_add_transaction_no($transaction_no)
    {
        $add = true;
        
        while($add){
            
            $transaction_no = (string)$transaction_no;

            $transaction_no++;
            
            $exist = BorrowedBook::where('transaction_no', $transaction_no)->exists();
            
            if($exist != true){
                
                $add = false;
                
            }
            
        }

        return $this->to_string_transaction_no($transaction_no);
        
    }

    public function to_string_transaction_no($transaction_no)
    {
        $num = $transaction_no;
        $num = (string)$num;
        $num_length = strlen($num);


        for($i = $num_length; $i < 8; $i++){
            $num = "0" . $num;
        }

        return $num;

    }

    public function add_book_event($borrow_id, $event)
    {
        $book_event = new BorrowedEvent;
        
        $book_event->borrowed_book_id = $borrow_id;
        
        $book = BorrowedBook::where('id', $borrow_id)->first();
        
        $book_event->start_date = $book['start_date'];

        $book_event->due_date = $book['due_date'];
        
        if($event = 'pending'){
            
            $book_event->status = 1;
            
        }

        $book_event->save();
    }
    
    public function change_book_availability($book, $change)
    {
        $book->update(['availability' => $change]);
    }
    

    // Checking Book Reservations

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

}

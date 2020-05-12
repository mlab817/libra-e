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

class MyBookReservationController extends Controller
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

    public function my_reservations()
    {
        $this->get_loggedIn_user_data();

        $books = $this->get_user_book_reservations();
        
        return view('libraE.my_reservations.my_reservations')->with('books' ,$books);    
    }

    public function check_session_queries()
    {
        if(session()->has('book_reservations_toOrder') != true){

            session(['book_reservations_toOrder' => 'updated_at' ]);
            
        }

        if(session()->has('book_reservations_orderBy') != true){

            session(['book_reservations_orderBy' => 'desc' ]);
            
        }

        if (session()->has('book_reservations_per_page') != true) {

            session(['book_reservations_per_page' => 10 ]);
            
        }
    }

    // MyBook Reservations

    public function get_user_book_reservations()
    {
        $this->check_session_queries();
        
        $book_reservations_query = $this->get_book_reservations_query();
        
        $all_user_book_reservations = $this->add_order_queries($book_reservations_query);
            
        return $all_user_book_reservations;
        
    }

    public function get_book_reservations_query()
    {
        $this->check_borrowing_books();
        
        $book_reservations_query = BorrowedBook::join('no_accessions', 'borrowed_books.accession_no_id', 'no_accessions.id')
            ->join('accessions', 'no_accessions.accession_id', 'accessions.id')
            ->join('authors', 'accessions.author_id', '=', 'authors.id')
            ->select(
                'borrowed_books.*',
                'no_accessions.accession_no',
                'no_accessions.availability',
                'accessions.book_title',
                'accessions.pic_url',
                'authors.author_name'
            )->where('user_id', session()->get('loggedIn_user_id'));


        return $book_reservations_query;
            
    }

    public function add_order_queries($book_reservations_query)
    {
        if(session()->has('book_reservations_getAll')){

            if(session()->get('book_reservations_getAll') != 'all'){

                $book_reservations_query = $book_reservations_query->where('borrowed_books.status', session()->get('book_reservations_getAll'));

            }else{
                    
                session(['book_reservations_getAll' => 'all' ]);
            }
            
        }else{
            session(['book_reservations_getAll' => 'all' ]);
        }
        
        
        $all_user_book_reservations = $book_reservations_query->orderBy(session()->get('book_reservations_toOrder'), session()->get('book_reservations_orderBy'))
            ->paginate(session()->get('book_reservations_per_page'));


        if($all_user_book_reservations->count() > 0){
            
            return $all_user_book_reservations;
            
        }else{

            session()->flash('error_status', 'No Reservation/s Yet!');
            return $all_user_book_reservations;

        }
    }

    public function book_reservations_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200 || 500){
            session(['book_reservations_per_page' => $per_page ]);
        }else{
            session(['book_reservations_per_page' => 10 ]);
        }

        return redirect()->route('libraE.my_reservations');

    }

    public function book_reservations_toOrder($ToOrder = 'updated_at') 
    {

        if($ToOrder == 'transaction_no' || 'book_title' || 'author_name' || 'accession_no' || 'due_date' || 'status' || 'updated_at'){
            
            session(['book_reservations_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['book_reservations_toOrder' => 'updated_at' ]);

        }

        return redirect()->route('libraE.my_reservations');

    }

    public function book_reservations_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || 'desc' ){
            
            session(['book_reservations_orderBy' => $orderBy ]);
            
        }else{
            
            session(['book_reservations_orderBy' => 'desc' ]);

        }

        return redirect()->route('libraE.my_reservations');

    }

    public function filter_book_reservations($filter = 'all') 
    {
        
        if($filter == 'all' || $filter == 0 || $filter == 1 || $filter == 2 || $filter == 3 || $filter == 4 || $filter == 5 || $filter == 8 || $filter == 9 || $filter == 10){
            
            session(['book_reservations_getAll' => $filter ]);
            
        }else{
            
            session(['book_reservations_getAll' => 'all' ]);

        }

        return redirect()->route('libraE.my_reservations');

    }

    public function search_book_reservations($search = '')
    {
        $this->check_session_queries();

        $book_reservations_query = $this->get_book_reservations_query();
        
        $book_reservations_query->where('borrowed_books.transaction_no', 'like', '%'.$search.'%')
            ->orWhere('accessions.book_title', 'like', '%'.$search.'%')
            ->orWhere('authors.author_name', 'like', '%'.$search.'%')
            ->orWhere('no_accessions.accession_no', 'like', '%'.$search.'%')
            ->orWhere('borrowed_books.start_date', 'like', '%'.$search.'%')
            ->orWhere('borrowed_books.due_date', 'like', '%'.$search.'%');
        
        $count = $book_reservations_query->count();

        session()->flash('libraE_search', $search);
        session()->flash('libraE_search_count', $count);
        session(['borrowed_books_getAll' => 'all' ]);

        $all_user_book_reservations = $this->add_order_queries($book_reservations_query, 'index');
        
        $books = $all_user_book_reservations;
        
        if($count <= 0){

            session()->flash('error_status', 'No Reservation/s found!');
            
        }
        
        return view('libraE.my_reservations.my_reservations')->with('books' ,$books);    

    }

    public function delete_book_reservations($id)
    {
        $reserve = BorrowedBook::findOrFail($id);
        
        if($reserve->status == 1){
            
            BorrowedEvent::where('borrowed_book_id', $id)
                ->delete();
            
            if($reserve->delete()){
                
                session()->flash('success_status', 'Reservation Deleted!');
                return redirect()->route('libraE.my_reservations');

            }

        }else if($reserve->status == 2){

            BorrowedBook::where('id', $id)
                ->update(['status' => 9]);
                
            $this->add_book_event($id, 9);

            session()->flash('success_status', 'Reservation Cancelled!');
            return redirect()->route('libraE.my_reservations');
            
        }else{
            
            session()->flash('error_status', 'Reservation Cannot be Removed!');
            return redirect()->route('libraE.my_reservations');
            
        }
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

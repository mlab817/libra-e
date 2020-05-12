<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ThesisBook;
use App\ThesisAuthor;
use App\ThesisType;
use App\ThesisCategory;
use App\ThesisSubject;
use App\Program;

use App\AquisitionThesis;

class ThesisBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function thesis_books_view()
    {
        $thesis_books = $this->get_thesis_books();
        $thesis_authors = $this->get_thesis_authors();
        return view('admin.thesis.thesis_books')->with('thesis_books', $thesis_books)
            ->with('thesis_authors', $thesis_authors);
    }
    
    public function check_session_queries()
    {
        if(session()->has('thesis_books_toOrder') != true){

            session(['thesis_books_toOrder' => 'acc_no' ]);
            
        }

        if(session()->has('thesis_books_orderBy') != true){

            session(['thesis_books_orderBy' => 'desc' ]);
            
        }

        if (session()->has('thesis_books_per_page') != true) {

            session(['thesis_books_per_page' => 5 ]);
            
        }
    }
    
    public function get_thesis_books()
    {
        $this->check_session_queries();

        $thesis_books_query = $this->get_thesis_book_query();

        if(session()->has('thesis_books_getAll')){

            if(session()->get('thesis_books_getAll') != 'all'){

                if(session()->get('thesis_books_getAll') == 'active'){

                    $thesis_books_query = $thesis_books_query->where('thesis_books.status', 1);

                }else if(session()->get('thesis_books_getAll') == 'inactive'){

                    $thesis_books_query = $thesis_books_query->where('thesis_books.status', 0);
                    
                }
            }
            
        }else{
                
            session(['thesis_books_getAll' => 'all' ]);

        }
        
        $thesis_books = $thesis_books_query->orderBy(session()->get('thesis_books_toOrder'), session()->get('thesis_books_orderBy'))
            ->paginate(session()->get('thesis_books_per_page'));

        if($thesis_books->count() > 0){
            
            return $thesis_books;
            
        }else{
            
            session()->flash('error_status', 'No Thesis Book Yet!');
            return $thesis_books;

        }
    }

    public function get_thesis_authors()
    {
        $thesis_authors = ThesisAuthor::select('thesis_id', 'name')->get();
        return $thesis_authors;
    }

    public function get_thesis_book_query()
    {
        $thesis_books_query = ThesisBook::join('thesis_subjects', 'thesis_books.thesis_subject_id', '=', 'thesis_subjects.id')
            ->join('programs', 'thesis_books.program_id', '=', 'programs.id')
            ->select(
                'thesis_books.*', 
                'thesis_subjects.name AS subject_name', 
                'programs.code AS code', 
                'thesis_books.status AS thesis_book_status'
            );

        return $thesis_books_query;
            
    }

    public function thesis_books_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200 || 500){
            session(['thesis_books_per_page' => $per_page ]);
        }else{
            session(['thesis_books_per_page' => 5 ]);
        }

        return redirect()->route('admin.thesis.thesis_books');

    }

    public function thesis_books_toOrder($ToOrder = 'created_at') 
    {

        if($ToOrder == 'acc_no' || 'call_no' || 'title' || 'subject_name' || 'copyright' || 'code' || 'created_at' || 'updated_at'){
            
            session(['thesis_books_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['thesis_books_toOrder' => 'created_at' ]);

        }

        return redirect()->route('admin.thesis.thesis_books');

    }

    public function thesis_books_orderBy($orderBy = 'asc') 
    {

        if($orderBy == 'asc' || 'desc' ){
            
            session(['thesis_books_orderBy' => $orderBy ]);
            
        }else{
            
            session(['thesis_books_orderBy' => 'asc' ]);

        }

        return redirect()->route('admin.thesis.thesis_books');

    }

    public function filter_thesis_books($filter = 'all') 
    {
        
        if($filter == 'all' || 'active' || 'inactive' ){
            
            session(['thesis_books_getAll' => $filter ]);
            
        }else{
            
            session(['thesis_books_getAll' => 'all' ]);

        }

        return redirect()->route('admin.thesis.thesis_books');

    }

    public function search_thesis_book($search = '')
    {
        $this->check_session_queries();
        
        $thesis_books_query = $this->get_thesis_book_query();

        $thesis_authors = ThesisAuthor::select('thesis_id')->where('name', 'like', '%'.$search.'%')->distinct()->get();
        
        $thesis_id_authors = [];
        
        foreach ($thesis_authors as $author) {
            
            array_push($thesis_id_authors, $author->thesis_id);

        }

        $thesis_books_query = $thesis_books_query->where('thesis_books.acc_no', 'like', '%'.$search.'%')
            ->orWhereIn('thesis_books.id', $thesis_id_authors)
            ->orWhere('thesis_books.call_no', 'like', '%'.$search.'%')
            ->orWhere('thesis_subjects.name', 'like', '%'.$search.'%')
            ->orWhere('programs.code', 'like', '%'.$search.'%')
            ->orWhere('thesis_books.copyright', 'like', '%'.$search.'%');
            
        $count = $thesis_books_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);
        session(['thesis_books_getAll' => 'all' ]);
        
        if($count > 0){

            $thesis_books = $thesis_books_query->orderBy(session()->get('thesis_books_toOrder'), session()->get('thesis_books_orderBy'))
                ->paginate(session()->get('thesis_books_per_page'));

            $thesis_authors = $this->get_thesis_authors();

            return view('admin.thesis.thesis_books')->with('thesis_books', $thesis_books)
                ->with('thesis_authors', $thesis_authors);

        }else{
            
            session()->flash('error_status', 'No data found!');

            $thesis_books = $thesis_books_query->paginate(session()->get('accessions_per_page'));
            
            $thesis_authors = $this->get_thesis_authors();
            
            return view('admin.thesis.thesis_books')->with('thesis_books', $thesis_books)
                ->with('thesis_authors', $thesis_authors);
            
        }
    }

    public function view_thesis_book($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $thesis_book_all = $this->get_thesis_book_data($id);
            
            if($thesis_book_all){

                $month_num = $thesis_book_all['get_thesis_book']->month;

                $month_name = $this->get_month_name($month_num);

                return view('admin.thesis.view_thesis_book')->with('thesis_book', $thesis_book_all['get_thesis_book'])
                    ->with('thesis_authors', $thesis_book_all['thesis_authors'])
                    ->with('month_name', $month_name);
                
            }else{
                
                return redirect()->route('admin.thesis.thesis_books');
                
            }
            
        }else{
            
            return redirect()->route('admin.thesis.thesis_books');

        }
    }
    
    public function add_thesis_book_view($authors = 4)
    {
        if($authors != 1 && $authors != 2 && $authors != 3 && $authors != 4 ){
            
            $authors = 4;

        }
        
        $file_maintenance = $this->get_file_maintenance();
        return view('admin.thesis.add_thesis_book')->with('file_maintenance', $file_maintenance)
            ->with('authors', $authors);
    }

    public function get_file_maintenance()
    {
        $count = ThesisBook::count();
        
        if($count == 0){
            
            $last_accession_no = 0;
            $last_call_no = 0;

        }else{

            $last_accession_no = ThesisBook::orderBy('acc_no', 'desc')->select('acc_no')->first();
            $last_accession_no = $last_accession_no->acc_no;

            $last_call_no = ThesisBook::orderBy('call_no', 'desc')->select('call_no')->first();
            $last_call_no = $last_call_no->call_no;

        }
        
        $new_accession_no = $this->to_string_add_thesis_no($last_accession_no);
        
        $new_call_no = $this->to_string_add_thesis_call_no($last_call_no);

        $thesis_types = ThesisType::orderBy('name', 'asc')->where('status', 1)->get();

        $thesis_categories = ThesisCategory::orderBy('name', 'asc')->where('status', 1)->get();

        $thesis_subjects = ThesisSubject::orderBy('name', 'asc')->where('status', 1)->get();

        $programs = Program::orderBy('code', 'asc')->where([
            ['type', 1],   
            ['status', 1]   
        ])->get();
        
        return $file_maintenance = [
            'new_acc_no' => $new_accession_no,
            'new_call_no' => $new_call_no,
            'thesis_types' => $thesis_types,
            'thesis_categories' => $thesis_categories,
            'thesis_subjects' => $thesis_subjects,
            'programs' => $programs,
        ];

    }

    public function to_string_add_thesis_no($accession_no)
    {
        $add = true;
        
        while($add){
            
            $accession_no = (string)$accession_no;

            $accession_no++;
            
            $exist = ThesisBook::where('acc_no', $accession_no)->exists();
            
            if($exist != true){
                
                $add = false;
                
            }
            
        }

        return $this->to_string_no($accession_no);
        
    }

    public function to_string_no($accession_no)
    {
        $num = $accession_no;
        $num = (string)$num;
        $num_length = strlen($num);


        for($i = $num_length; $i < 6; $i++){
        $num = "0" . $num;
        }

        return $num;

    }

    public function to_string_add_thesis_call_no($call_no)
    {
        $add = true;
        
        while($add){
            
            $call_no = (string)$call_no;

            $call_no++;
            
            $exist = ThesisBook::where('call_no', $call_no)->exists();
            
            if($exist != true){
                
                $add = false;
                
            }
            
        }

        return $call_no;
        
    }

    public function add_aquisition_thesis($book_id, $quantity, $aquisition_type)
    {
        $aqusition_thesis = new AquisitionThesis;

        $aqusition_thesis->thesis_book_id = $book_id;

        $aqusition_thesis->quantity = $quantity;

        $aqusition_thesis->aquisition_type = $aquisition_type;

        $aqusition_thesis->save();
    }


    public function store_thesis_book(Request $request)
    {
        //return $request->all();

        if($request->isMethod('put')){

            $request->validate([
                'acc_no' => 'required',
                'call_no' => 'required',
                'authors' => 'required|numeric',
                'title' => 'required|regex:/^[a-z0-9 ñ&.,?:!\-]+$/i',
                'month' => 'required|date',
                'system_type' => 'required',
                'thesis_category' => 'required',
                'thesis_subject' => 'required',
                'program' => 'required',
                'copyright' => 'required|numeric|min:1800|max:3000'
            ]);
            
        }else{
            
            $request->validate([
                'acc_no' => 'required|unique:thesis_books,acc_no|',
                'call_no' => 'required|unique:thesis_books,call_no|numeric',
                'authors' => 'required|numeric',
                'title' => 'required|regex:/^[a-z0-9 ñ&.,?:!\-]+$/i',
                'month' => 'required|date',
                'system_type' => 'required',
                'thesis_category' => 'required',
                'thesis_subject' => 'required',
                'program' => 'required',
                'copyright' => 'required|numeric|min:1800|max:3000'
            ]);
            
        }


        $author_1 = '';
        $author_2 = '';
        $author_3 = ''; 
        $author_4 = '';

        $error_no = 0;

        if($request->isMethod('put')){
            
            $count_authors = 4;

        }else{

            $count_authors = $request->authors;
        }
        
        for ($i=1; $i <= $count_authors; $i++) { 
            
            if($request->isMethod('put')){
            
                if($i <= $request->authors){

                    $request->validate([
                        'author_'.$i => 'required|regex:/^[a-z0-9ñ &.,\-]+$/i',
                    ]);
                    
                }else{
                    
                    $request->validate([
                        'author_'.$i => 'nullable|regex:/^[a-z0-9ñ &.,\-]+$/i',
                    ]);

                }
    
            }else{
    
                $request->validate([
                    'author_'.$i => 'required|regex:/^[a-z0-9ñ &.,\-]+$/i',
                ]);

            }
            

            if($i == 1){

                $author_1 = $request->author_1;
                $author_name = $request->author_1;
                
            }else if($i == 2){
                
                $author_2 = $request->author_2;
                $author_name = $request->author_2;
                
            }else if($i == 3){
                
                $author_3 = $request->author_3;
                $author_name = $request->author_3;
                
            }else if($i == 4){
                
                $author_4 = $request->author_4;
                $author_name = $request->author_4;
                
            }

            if(($author_name == $author_1) && ($i != 1)){
                
                $error_no = $i;
                $error = true;
                
            }else if(($author_name == $author_2) && ($i != 2)){
                
                $error_no = $i;
                $error = true;
                
            }else if(($author_name == $author_3) && ($i != 3)){
                
                $error_no = $i;
                $error = true;
                
            }else if(($author_name == $author_4) && ($i != 4)){
                
                $error_no = $i;
                $error = true;
                
            }else{
                
                $error = false;
                
            }
            
            
            if($error){
                
                session()->flash('error_status', 'Duplicate Author name Input Author '.$error_no.'!');
                
                if($request->isMethod('put')){
                    
                    return redirect()->route('admin.thesis.edit_thesis_book_view', [$request->id]);
                    
                }else{
                    
                    return redirect()->route('admin.thesis.add_thesis_book_view', [$request->authors]);

                }
            }
        }

        $thesis_book = $request->isMethod('put') ? ThesisBook::findOrFail($request->id) : new ThesisBook;

        $thesis_book->acc_no = $request->acc_no;

        $thesis_book->call_no = $request->call_no;

        $thesis_book->title = ltrim(ucfirst($request->title));
        
        $month = substr($request->month,5);

        $thesis_book->month = $month;

        $thesis_book->thesis_type_id = $request->system_type;

        $thesis_book->thesis_category_id = $request->thesis_category;

        $thesis_book->thesis_subject_id = $request->thesis_subject;

        $thesis_book->program_id = $request->program;

        $thesis_book->copyright = $request->copyright;

        $thesis_book->save();

        $thesis_id = $thesis_book->id;

        for ($i=1; $i <= $count_authors; $i++) { 

            if($request->isMethod('put')){
                
                $new_author = false;
                
                if(($i == 1) && ($i <= $request->authors)){
                    
                    $author_id = $request->author_id_1;
                    
                }else if(($i == 2) && ($i <= $request->authors)){
                    
                    $author_id = $request->author_id_2;
                    
                }else if(($i == 3) && ($i <= $request->authors)){
                    
                    $author_id = $request->author_id_3;
                    
                }else if(($i == 4) && ($i <= $request->authors)){

                    $author_id = $request->author_id_4;

                }else{
                    
                    $new_author = true;
                    
                }
                
                if($new_author){

                    if(($i == 1) && ($request->author_1 == '')){

                        continue;
                        
                    }else if(($i == 2) && ($request->author_2 == '')){
                        
                        continue;
                        
                    }else if(($i == 3) && ($request->author_3 == '')){
                        
                        continue;
                        
                    }else if(($i == 4) && ($request->author_4 == '')){
                        
                        continue;
                        
                    }else{
                        
                        $thesis_author = new ThesisAuthor;
        
                        $thesis_author->thesis_id = $thesis_id;
                        
                    }
                    
                }else{
                    
                    $thesis_author = ThesisAuthor::where('id', $author_id)->first();
                    
                }

            }else{
                
                $thesis_author = new ThesisAuthor;

                $thesis_author->thesis_id = $thesis_id;

            }
            

            if($i == 1){

                $author_name = $request->author_1;
                
            }else if($i == 2){
                
                $author_name = $request->author_2;
                
            }else if($i == 3){
                
                $author_name = $request->author_3;
                
            }else if($i == 4){
                
                $author_name = $request->author_4;
                
            }
            
            $thesis_author->name =  $author_name;

            $thesis_author->save();
            
        }


        if($request->isMethod('put')){
            
            $request->session()->flash('success_status', 'Thesis Book Updated!');
            
        }else{

            $this->add_aquisition_thesis($thesis_id, 1, 1);
            
            $request->session()->flash('success_status', 'Thesis Book Added!');

        }
        
        return redirect()->route('admin.thesis.thesis_books');

    }

    public function get_thesis_book_data($thesis_book_id)
    {

        $exist = ThesisBook::where('id', $thesis_book_id)->exists();

        if($exist == false){
            return 0;
        }
        
        $get_thesis_book = ThesisBook::join('thesis_types', 'thesis_books.thesis_type_id', '=', 'thesis_types.id')
            ->join('thesis_categories', 'thesis_books.thesis_category_id', '=', 'thesis_categories.id')
            ->join('thesis_subjects', 'thesis_books.thesis_subject_id', '=', 'thesis_subjects.id')
            ->join('programs', 'thesis_books.program_id', '=', 'programs.id')
            ->select(
                'thesis_books.*', 
                'thesis_types.name AS type_name', 
                'thesis_categories.name AS category_name', 
                'thesis_subjects.name AS subject_name', 
                'programs.name AS program_name', 
                'thesis_books.status AS thesis_book_status'
            )
            ->where('thesis_books.id', $thesis_book_id)
            ->first();

        $thesis_authors = ThesisAuthor::select('id','name')->where('thesis_id', $thesis_book_id)->get();

        $authors_count = $thesis_authors->count();

        $month = $get_thesis_book->month;

        if($month < 10){
            
            $month = $get_thesis_book->copyright . '-' . '0' . $month; 

        }

        $thesis_book_all = [
            'get_thesis_book' => $get_thesis_book,
            'thesis_authors' => $thesis_authors,
            'authors_count' => $authors_count,
            'month' => $month
        ];

        return $thesis_book_all;
        
    }

    public function get_month_name($month_num)
    {
        switch($month_num){
            case 01:
                $month_name = "January";
                break;
            case 02:
                $month_name = "February";
                break;
            case 03:
                $month_name = "March";
                break;
            case 04:
                $month_name = "April";
                break;
            case 05:
                $month_name = "May";
                break;
            case 06:
                $month_name = "June";
                break;
            case 07:
                $month_name = "July";
                break;
            case "08":
                $month_name = "August";
                break;
            case "09":
                $month_name = "September";
                break;
            case 10:
                $month_name = "October";
                break;
            case 11:
                $month_name = "November";
                break;
            case 12:
               $month_name = "December";
                break;
        }

        return $month_name;
        
    }

    public function edit_thesis_book_view($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $thesis_book_all = $this->get_thesis_book_data($id);
            
            if($thesis_book_all){

                $file_maintenance = $this->get_file_maintenance();

                return view('admin.thesis.edit_thesis_book')->with('file_maintenance', $file_maintenance)
                    ->with('thesis_book', $thesis_book_all['get_thesis_book'])
                    ->with('thesis_authors', $thesis_book_all['thesis_authors'])
                    ->with('authors_count', $thesis_book_all['authors_count'])
                    ->with('month', $thesis_book_all['month']);

            }else{
                
                return redirect()->route('admin.thesis.thesis_books');

            }
            
        }else{
            
            return redirect()->route('admin.thesis.thesis_books');

        }
    }

}

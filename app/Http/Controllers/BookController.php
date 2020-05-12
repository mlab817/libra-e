<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imports\AccessionImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Accession;
use App\NoAccession;

use App\AquisitionBook;

use App\Classification;
use App\Category;
use App\Author;
use App\Publisher;
use App\Illustration;
use App\Tag;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function accessioning_view()
    {
        
        $accessions = $this->get_all_accessions();
        return view('admin.books.accessioning')->with('accessions', $accessions);

    }

    public function check_session_queries()
    {
        if(session()->has('accessions_toOrder') != true){

            session(['accessions_toOrder' => 'accession_no' ]);
            
        }

        if(session()->has('accessions_orderBy') != true){

            session(['accessions_orderBy' => 'desc' ]);
            
        }

        if (session()->has('accessions_per_page') != true) {

            session(['accessions_per_page' => 5 ]);
            
        }
    }
    
    public function get_all_accessions()
    {
        $this->check_session_queries();

        $accessions_query = NoAccession::join('accessions', 'no_accessions.accession_id', '=', 'accessions.id')
            ->join('authors', 'accessions.author_id', '=', 'authors.id')
            ->join('publishers', 'accessions.publisher_id', '=', 'publishers.id')
            ->select(
                'no_accessions.*', 
                'no_accessions.id AS accession_no_id', 
                'accessions.pic_url', 
                'accessions.book_title', 
                'accessions.copyright', 
                'authors.author_name',
                'publishers.name AS publisher_name',
                'no_accessions.status AS accession_status'
            );
            

        if(session()->has('accessions_getAll')){

            if(session()->get('accessions_getAll') != 'all'){

                $accessions_query = $accessions_query->where('no_accessions.status', session()->get('accessions_getAll'));

            }else{
                    
                session(['accessions_getAll' => 'all' ]);
            }
            
        }else{
            session(['accessions_getAll' => 'all' ]);
        }
        
        $accessions = $accessions_query->orderBy(session()->get('accessions_toOrder'), session()->get('accessions_orderBy'))
            ->paginate(session()->get('accessions_per_page'));

        if($accessions->count() > 0){
            
            return $accessions;
            
        }else{
            
            session()->flash('error_status', 'No Accessions Yet!');
            return $accessions;

        }
    }

    public function accessions_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200 || 500){
            session(['accessions_per_page' => $per_page ]);
        }else{
            session(['accessions_per_page' => 5 ]);
        }

        return redirect()->route('admin.books.accessioning');

    }

    public function accessions_toOrder($ToOrder = 'accession_no') 
    {

        if($ToOrder == 'accession_no' || 'author' || 'title' || 'publisher' || 'copyright' || 'created_at'){
            
            session(['accessions_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['accessions_toOrder' => 'accession_no' ]);

        }

        return redirect()->route('admin.books.accessioning');

    }

    public function accessions_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || 'desc' ){
            
            session(['accessions_orderBy' => $orderBy ]);
            
        }else{
            
            session(['accessions_orderBy' => 'desc' ]);

        }

        return redirect()->route('admin.books.accessioning');

    }

    public function filter_accessions($filter = 'all') 
    {
        
        if($filter == 'all' || $filter == 0 || $filter == 1 || $filter == 2 || $filter == 3){
            
            session(['accessions_getAll' => $filter ]);
            
        }else{
            
            session(['accessions_getAll' => 'all' ]);

        }

        return redirect()->route('admin.books.accessioning');

    }

    public function search_accession($search = '')
    {
        $this->check_session_queries();
        
        $accessions_query = NoAccession::join('accessions', 'no_accessions.accession_id', '=', 'accessions.id')
            ->join('authors', 'accessions.author_id', '=', 'authors.id')
            ->join('publishers', 'accessions.publisher_id', '=', 'publishers.id')
            ->select(
                'no_accessions.*', 
                'no_accessions.id AS accession_no_id', 
                'accessions.book_title', 
                'accessions.pic_url', 
                'accessions.copyright', 
                'authors.author_name',
                'publishers.name AS publisher_name',
                'no_accessions.status AS accession_status'
            )
            ->where('no_accessions.accession_no', 'like', '%'.$search.'%')
            ->orWhere('accessions.book_title', 'like', '%'.$search.'%')
            ->orWhere('accessions.copyright', 'like', '%'.$search.'%')
            ->orWhere('authors.author_name', 'like', '%'.$search.'%')
            ->orWhere('publishers.name', 'like', '%'.$search.'%');
            
            
        $count = $accessions_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);
        session(['accessions_getAll' => 'all' ]);
        
        if($count > 0){

            $accessions = $accessions_query->orderBy(session()->get('accessions_toOrder'), session()->get('accessions_orderBy'))
                ->paginate(session()->get('accessions_per_page'));

            return view('admin.books.accessioning')->with('accessions', $accessions);

        }else{
            
            session()->flash('error_status', 'No data found!');
            $accessions = $accessions_query->paginate(session()->get('accessions_per_page'));
            return view('admin.books.accessioning')->with('accessions', $accessions);
            
        }
    }

    public function get_all_unique_accessions()
    {
        $accessions_all = Accession::join('authors', 'accessions.author_id', '=', 'authors.id')
        ->join('publishers', 'accessions.publisher_id', '=', 'publishers.id')
        ->select(
            'accessions.id AS accession_id', 
            'accessions.book_title', 
            'accessions.copyright', 
            'authors.author_name',
            'publishers.name AS publisher_name'
        )
        ->distinct()->orderBy('book_title', 'asc')->get();

        return $accessions_all;
        
    }

    public function view_accession($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $accession = $this->get_accession_book($id, 'get_all');
            
            if($accession){
                
                if($accession['category_id_2'] != null){

                    $category_name_2 = Category::where('id', $accession['category_id_2'])->select('name')->first();

                }else{

                    $category_name_2 = null;
                    
                }

                return view('admin.books.view_accession')->with('accession', $accession)
                    ->with('category_2', $category_name_2);
                
            }else{
                
                return redirect()->route('admin.books.accessioning');
                
            }
            
        }else{
            
            return redirect()->route('admin.books.accessioning');

        }
    }
    
    public function add_accession_view()
    {
        $file_maintenance = $this->get_file_maintenance();
        $accessions_all = $this->get_all_unique_accessions();
        return view('admin.books.add_accession')->with('file_maintenance', $file_maintenance)
            ->with('accessions_all', $accessions_all);
    }

    public function get_file_maintenance()
    {
        $last_accession_no = $this->get_last_accession_no();
        
        $new_accession_no = $this->to_string_add_accession_no($last_accession_no);

        $accessions = Accession::orderBy('book_title', 'asc')->get();

        $classifications = Classification::orderBy('name', 'asc')->get();

        $categories = Category::orderBy('code', 'asc')->get();
        
        $authors = Author::orderBy('author_name', 'asc')->get();

        $publishers = Publisher::orderBy('name', 'asc')->get();
        
        $illustrations = Illustration::orderBy('name', 'asc')->get();

        $tags = Tag::orderBy('name', 'asc')->get();

        return $file_maintenance = [
            'new_accession_no' => $new_accession_no,
            'accessions' => $accessions,
            'classifications' => $classifications,
            'categories' => $categories,
            'authors' => $authors,
            'publishers' => $publishers,
            'illustrations' => $illustrations,
            'tags' => $tags
        ];

    }

    public function to_string_add_accession_no($accession_no)
    {
        $add = true;
        
        while($add){
            
            $accession_no = (string)$accession_no;

            $accession_no++;
            
            $exist = NoAccession::where('accession_no', $accession_no)->exists();
            
            if($exist != true){
                
                $add = false;
                
            }
            
        }

        return $this->to_string_accession_no($accession_no);
        
    }

    public function to_string_accession_no($accession_no)
    {
        $num = $accession_no;
        $num = (string)$num;
        $num_length = strlen($num);


        for($i = $num_length; $i < 6; $i++){
            $num = "0" . $num;
        }

        return $num;

    }

    public function get_last_accession_no()
    {
        $count = NoAccession::count();
        
        if($count == 0){
            
            return 0;

        }else{
            
            $last_accession_no = NoAccession::orderBy('accession_no', 'desc')->select('accession_no')->first();
            return $last_accession_no->accession_no;
            
        }
    }

    public function add_new_accession(Request $request)
    {
        $request->validate([
            'accession_no' => 'required',
            'quantity' => 'required|numeric',
            'book' => 'required',
        ]);

        for($i = 1 ; $i <= $request->quantity ; $i++){

            $no_accession = new NoAccession;
    
            $last_accession_no = $this->get_last_accession_no();
            
            $new_accession_no = $this->to_string_add_accession_no($last_accession_no);
    
            $no_accession->accession_no = $new_accession_no;
    
            $no_accession->accession_id = $request->book;
    
            $no_accession->save();

        }

        $this->add_aquisition_book($request->book, $request->quantity, 1);
        
        $request->session()->flash('success_status', 'Accession Added!');
        
        return redirect()->route('admin.books.accessioning');
        
    }

    public function add_aquisition_book($book, $quantity, $aquisition_type)
    {
        $aqusition_book = new AquisitionBook;

        $aqusition_book->acc_id = $book;

        $aqusition_book->quantity = $quantity;

        $aqusition_book->aquisition_type = $aquisition_type;

        $aqusition_book->save();
    }

    public function store_accession(Request $request)
    {
        //return $request->all();
        
        $author_radio = $request->author_radio;
        $book_title_radio = $request->book_title_radio;
        $publisher_radio = $request->publisher_radio;
        $source_fund_radio = $request->source_fund_radio;
        
        if($author_radio == 1){
            
            $request->validate([
                'author' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|unique:authors,author_name|max:100'
            ]);
            

        }else if($author_radio == 2){
            
            $request->validate([
                'author_select' => 'required'
            ]);
            
        }

        if($publisher_radio == 1){
            
            $request->validate([
                'publisher' => 'required|regex:/^[a-z0-9 &ñ.,\-]+$/i|unique:publishers,name|max:200'
            ]);

        }else if($publisher_radio == 2){
            
            $request->validate([
                'publisher_select' => 'required'
            ]);
            
        }

        if($request->isMethod('put')){
            
            $request->validate([
                'isbn' => 'nullable|digits:13',
            ]);
                
            }else{
                
                $request->validate([
                'quantity' => 'required|numeric|min:1|max:999',
                'isbn' => 'nullable|unique:accessions,isbn|digits:13',
            ]);

        }
            
        $request->validate([
            'accession_no' => 'required',
            'author_radio' => 'required',
            'book_title' => 'required|regex:/^[a-z0-9 &.,?!ñ\-]+$/i',
            'publisher_radio' => 'required',
            'copyright' => 'required|digits:4',
            'pic_file' => 'nullable|image|mimes:jpeg,bmp,png',
            'classification' => 'nullable',
            'category' => 'nullable',
            'category_2' => 'nullable',
            'illustration' => 'nullable',
            'edition' => 'nullable|numeric|min:0|max:999',
            'volume' => 'nullable|numeric|min:0|max:999',
            'pages' => 'nullable|numeric|min:1|max:9999',
            'filipiniana_radio' => 'nullable',
            'source_fund_radio' => 'nullable',
        ]);

        if($source_fund_radio == 2){
            
            $request->validate([
                'cost_price' => 'required'
            ]);
            
        }
        

        if($author_radio == 1){
            
            $book_exists = $this->search_book($request->book_title, $request->author);

            $go_update = true;

            if($request->isMethod('put')){

                if($request->accession_id == $book_exists || $book_exists == 0){

                    $author_id = $this->store_author($request->author);
                    
                }else{

                    $go_update = false;
                    
                }

            }else{

                if($book_exists == 0){

                    $author_id = $this->store_author($request->author);

                }else{

                    $go_update = false;

                }
            }

            if($book_exists > 0 && $go_update == false){

                $accession = $this->get_accession_book($book_exists, 'get_data');
    
                session()->flash('book_exist', 'Book' . $accession['book_title'] . ' &nbsp;|'
                    . $accession['author_name'] . ' &nbsp;|' 
                    . $accession['publisher_name']);
    
                $file_maintenance = $this->get_file_maintenance();
                $accessions_all = $this->get_all_unique_accessions();
                return view('admin.books.add_accession')->with('file_maintenance', $file_maintenance)
                    ->with('accessions_all', $accessions_all);

            }

        }else if($author_radio == 2){
            
            $accession = Accession::join('authors', 'accessions.author_id', '=', 'authors.id')
            ->where([
                ['author_id', $request->author_select],
                ['book_title',  $request->book_title],
            ])->select('accessions.id', 'author_name')->first();

            $book_exists = $this->search_book($request->book_title, $accession['author_name']);
            
            $go_update = true;
            
            if($request->isMethod('put')){
                
                if($request->accession_id == $book_exists || $book_exists == 0){
                    
                    $author_id = $request->author_select;
                    
                }else{

                    $go_update = false;
                    
                }
                
            }else{

                if($book_exists == 0){

                    $author_id = $request->author_select;

                }else{
                    
                    $go_update = false;
                    
                }
            }

            if ($book_exists > 0 && $go_update == false){

                $accession = $this->get_accession_book($book_exists, 'get_data');
    
                session()->flash('book_exist', 'Book : ' . $accession['book_title'] . ' | '
                    . $accession['author_name'] . ' | ' 
                    . $accession['publisher_name'] . ' Already Exist!');
    
                $file_maintenance = $this->get_file_maintenance();
                $accessions_all = $this->get_all_unique_accessions();
                return view('admin.books.add_accession')->with('file_maintenance', $file_maintenance)
                    ->with('accessions_all', $accessions_all);
                    
            }
        }

        $accession = $request->isMethod('put') ? Accession::findOrFail($request->accession_id) : new Accession;
        
        $accession->author_id = $author_id;

        $accession->book_title = ltrim(ucfirst($request->book_title));

        if($publisher_radio == 1){
            
            $publisher = $request->publisher;
            $publisher_id = $this->store_publisher($publisher);

            $accession->publisher_id = $publisher_id;
            
        }else if($publisher_radio == 2){
            
            $accession->publisher_id = $request->publisher_select;
            
        }

        $accession->isbn = $request->isbn;

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
            $path = $request->file('pic_file')->storeAs('public/images/accession_images', $fileNameToStore);
        }else{
            
            if($request->isMethod('put')){

                $fileNameToStore = $request->pic_name;

            }else{

                $fileNameToStore = 'noimage.png';
                
            }
        }

        $accession->pic_url = $fileNameToStore; 

        $accession->classification_id = $request->classification;
        
        $accession->category_id = $request->category;

        $accession->category_id_2 = $request->category_2;

        $accession->illustration_id = $request->illustration;

        $accession->edition = $request->edition;

        $accession->volume = $request->volume;

        $accession->pages = $request->pages;
        
        $accession->pages = $request->pages;

        if($source_fund_radio == 2){
            
            $accession->cost_price = $request->cost_price;    
            
        }

        $accession->source_of_fund = $source_fund_radio; 
        
        $accession->copyright = $request->copyright;

        $accession->filipiniana = $request->filipiniana_radio;

        $accession->save();

        $inserted_id = $accession->id;

        if($request->isMethod('put')){
            
            $no_accession = NoAccession::findOrFail($request->accession_no_id);

            $no_accession->accession_no = $request->accession_no;
            
            $no_accession->status = 1;
    
            $no_accession->accession_id = $inserted_id;
            
            $no_accession->save();

        }else{
            
            for($i = 1 ; $i <= $request->quantity ; $i++){

                $no_accession = new NoAccession;
        
                $last_accession_no = $this->get_last_accession_no();
                
                $new_accession_no = $this->to_string_add_accession_no($last_accession_no);

                $no_accession->accession_no = $new_accession_no;
                
                $no_accession->status = 1;
        
                $no_accession->accession_id = $inserted_id;
                
                $no_accession->save();
            }
        }

        
        if($request->isMethod('put')){
            
            $request->session()->flash('success_status', 'Accession Updated!');
            
        }else{
            
            $this->add_aquisition_book($inserted_id, $request->quantity, 1);

            $request->session()->flash('success_status', 'Accession Added!');

        }
        
        return redirect()->route('admin.books.accessioning');
        
    }

    public function edit_accession_view($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $accession = $this->get_accession_book($id, 'get_all');
            
            if($accession){

                $file_maintenance = $this->get_file_maintenance();
                
                $accessions_all = $this->get_all_unique_accessions();
     
                return view('admin.books.edit_accession')->with('accession', $accession)
                    ->with('file_maintenance', $file_maintenance)->with('accessions_all', $accessions_all);

            }else{
                
                return redirect()->route('admin.books.accessioning');

            }
            
        }else{
            
            return redirect()->route('admin.books.accessioning');

        }
    }

    public function search_book($book_title, $author_name)
    {
        $author_exists = Author::where('author_name', $author_name)->exists();
        
        if($author_exists){
            
            $author_id = Author::where('author_name', $author_name)->select('id')->get();

            $accession_exists = Accession::where([
                ['author_id',  $author_id[0]->id],
                ['book_title', $book_title]
            ]);
            
            if($accession_exists->count() > 0){
                
                $accession_id = $accession_exists->select('id')->first();

                return $accession_id['id'];

            }else{

                return 0;

            }

        }else{
            
            return 0;
            
        }
    }

    public function get_accession_book($accesion_id, $to_get)
    {
        if($to_get == 'get_all'){

            $exist = NoAccession::where('no_accessions.id', $accesion_id)->exists();

            if($exist == false){
                return 0;
            }
            
            $get_accession = NoAccession::join('accessions', 'no_accessions.accession_id', '=', 'accessions.id')
                ->where('no_accessions.id', $accesion_id)
                ->first();

            $accession_query = NoAccession::join('accessions', 'no_accessions.accession_id', '=', 'accessions.id')
            ->join('authors', 'accessions.author_id', '=', 'authors.id')
            ->join('publishers', 'accessions.publisher_id', '=', 'publishers.id');

            if($get_accession['classification_id'] != null){
                
                $accession_query = $accession_query->join('classifications', 'accessions.classification_id', '=', 'classifications.id');

            }

            if($get_accession['category_id'] != null){
                
                $accession_query = $accession_query->join('categories', 'accessions.category_id', '=', 'categories.id');

            }

            if($get_accession['illustration_id'] != null){
                
                $accession_query = $accession_query->join('illustrations', 'accessions.illustration_id', '=', 'illustrations.id');

            }


            $accession_query = $accession_query->select(
                'no_accessions.*', 
                'no_accessions.id AS accession_no_id', 
                'accessions.*', 
                'authors.author_name',
                'publishers.name AS publisher_name',
                'no_accessions.status AS accession_status'
            );

            if($get_accession['classification_id'] != null){
                
                $accession_query = $accession_query->addSelect('classifications.name AS classification_name');
                
            }

            if($get_accession['category_id'] != null){
                
                $accession_query = $accession_query->addSelect('categories.name AS category_name');
                
            }
            
            if($get_accession['illustration_id'] != null){
                
                $accession_query = $accession_query->addSelect('illustrations.name AS illustration_name');

            }
            
            $accession = $accession_query->where('no_accessions.id', $accesion_id)->first();
                
            return $accession;
                
        }else if($to_get == 'get_data'){
            
            $exist = Accession::where('accessions.id', $accesion_id)->exists();

            if($exist == false){
                return 0;
            }
            
            $accession = Accession::join('authors', 'accessions.author_id', '=', 'authors.id')
            ->join('publishers', 'accessions.publisher_id', '=', 'publishers.id')
            ->select(
                'accessions.id AS accession_id', 
                'accessions.book_title', 
                'authors.author_name',
                'publishers.name AS publisher_name'
            )
            ->where('accessions.id', $accesion_id)
            ->first();

            return $accession;

        }
        
    }

    public function store_author($new_author)
    {
        $author_name =  ltrim(ucfirst($new_author));

        if($author_name == ''){
            $author_name = "Unknown Author";
        }

        $exists = Author::where('author_name', $author_name)->exists();
        
        if($exists){
            
            $author_id = Author::where('author_name', $author_name)->select('id')->first();
            return $author_id['id'];
            
        }else{
            
            $author = new Author;

            $author->author_name =  $author_name;

            $author->status = 1;
            
            $author->save();

            $inserted_id = $author->id;

            return $inserted_id ;

        }
    }
    
    public function store_publisher($new_publisher)
    {
        $publisher_name =  ltrim(ucfirst($new_publisher));

        if($publisher_name == ''){
            $publisher_name = "Unknown Publisher";
        }
        
        $exists = Publisher::where('name', $publisher_name)->exists();

        if($exists){
            
            $publisher_id = Publisher::where('name', $publisher_name)->select('id')->first();
            return $publisher_id['id'];
            
        }

        $publisher = new Publisher;

        $publisher->name =  $publisher_name;
        
        $publisher->status = 1;
        
        $publisher->save();

        $inserted_id = $publisher->id;

        return $inserted_id;
        
    }

    public function import_excell_accessions(Request $request) 
    {
        $request->validate([
            'excell_accessions' => 'required|file',
        ]);

        $extension = $request->file('excell_accessions')->getClientOriginalExtension();
        
        if($extension == 'xlsx' || $extension == 'csv'){
            
            Excel::import(new AccessionImport, request()->file('excell_accessions'));

        }else{
            
            session()->flash('error_status', 'Invalid Input! Must be xlsx or csv file only!');

        }
        
        session()->flash('success_status', 'Accessions Added!');

        return redirect()->route('admin.books.accessioning');

    }

    public function handle_accession_view($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $accession = $this->get_accession_book($id, 'get_all');
            
            if($accession){
                
                if($accession['category_id_2'] != null){

                    $category_name_2 = Category::where('id', $accession['category_id_2'])->select('name')->first();

                }else{

                    $category_name_2 = null;
                    
                }

                return view('admin.books.handle_accession')->with('accession', $accession)
                    ->with('category_2', $category_name_2);
                
            }else{
                
                return redirect()->route('admin.books.accessioning');
                
            }
            
        }else{
            
            return redirect()->route('admin.books.accessioning');

        }
    }

    public function move_accession(Request $request, $id)
    {
        $no_accession = NoAccession::findOrFail($id);

        if($request->type == 'Weeded'){
            
            $accession_no_status = 2;
            $aquisition_status = 2;
            
        }else if($request->type == 'Lost'){
            
            $accession_no_status = 3;
            $aquisition_status = 3;

        }
        
        $no_accession->availability = 0;
        
        $no_accession->status = $accession_no_status;

        $acc_id = $no_accession->accession_id;

        $this->add_aquisition_book($acc_id, 1,  $aquisition_status);

        $no_accession->save();

        if($request->type == 'Weeded'){
            
            session()->flash('success_status', 'Accession Added Weeded!');
            
        }else if($request->type == 'Lost'){
            
            session()->flash('success_status', 'Accession Added Lost!');

        }
            
        return redirect()->route('admin.books.accessioning');
        
    }

    public function delete_accession($id)
    {
        $no_accession = NoAccession::findOrFail($id);

        if($no_accession->delete()){
            
            session()->flash('success_status', 'Accession Deleted!');
            
            return redirect()->route('admin.books.accessioning');

        }
    }
}

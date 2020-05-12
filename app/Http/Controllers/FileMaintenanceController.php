<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classification;
use App\Category;
use App\Author;
use App\Publisher;
use App\Illustration;
use App\Tag;

use App\ThesisType;
use App\ThesisCategory;
use App\ThesisSubject;

use App\Department;
use App\Program;
use App\Section;

class FileMaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /****************************************************************************************************/
    
    ///////////////// Books Filemaintenance

    // Classifications
    public function classifications_view()
    {
        $classifications = $this->get_classifications();
        return view('admin.file_maintenance.books.classifications.classifications')->with('classifications', $classifications);
    }

    public function get_classifications()
    {
        if (session()->has('classifications_per_page')) {
            $classifications = Classification::orderBy('updated_at','desc')->paginate(session()->get('classifications_per_page'));
        }else{
            session(['classifications_per_page' => 5 ]);
            $classifications = Classification::orderBy('updated_at','desc')->paginate(session()->get('classifications_per_page'));
        }

        if($classifications->count() > 0){
            return $classifications;
        }else{
            session()->flash('error_status', 'No Classifications Yet!');
            return $classifications;
        }
    }

    public function classifications_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200){
            session(['classifications_per_page' => $per_page ]);
            $classifications = $this->get_classifications();
            return view('admin.file_maintenance.books.classifications.classifications')->with('classifications', $classifications);
        }else{
            session(['classifications_per_page' => 5 ]);
            $classifications = $this->get_classifications();
            return view('admin.file_maintenance.books.classifications.classifications')->with('classifications', $classifications);
        }
    }

    public function search_classification($search = '')
    {   
        session()->flash('admin_search', $search);
        
        $count = Classification::where('name', 'like', '%'.$search.'%')->count();

        session()->flash('admin_search_count', $count);
        
        if (session()->has('classifications_per_page')) {
            $classifications = Classification::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(session()->get('classifications_per_page'));
        }else{
            $classifications = Classification::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(10);
        }

        if($count > 0){
            return view('admin.file_maintenance.books.classifications.classifications')->with('classifications', $classifications);
        }else{
            session()->flash('error_status', 'No classification/s Found!');
            return view('admin.file_maintenance.books.classifications.classifications')->with('classifications', $classifications);
        }
    }

    public function filter_classifications($filter) 
    {
        if($filter == 1){
            
            $classifications = Classification::where('status', 1)
                ->orderBy('updated_at','desc')->paginate(session()->get('classifications_per_page'));

            if ($classifications->count() <= 0) {
            
                session()->flash('error_status', '0 Classification Found!');
                
            }
                
            return view('admin.file_maintenance.books.classifications.classifications')->with('classifications', $classifications);
    
        }

        else if($filter == 0){
            
            $classifications = Classification::where('status', 0)
                ->orderBy('updated_at','desc')->paginate(session()->get('classifications_per_page'));

            if ($classifications->count() <= 0) {
            
                session()->flash('error_status', '0 classification Found!');
                
            }

            return view('admin.file_maintenance.books.classifications.classifications')->with('classifications', $classifications);
            
        }else{
            
            session()->flash('error_status', '0 Classification Found!');
            $classifications = $this->get_classifications();
            return view('admin.file_maintenance.books.classifications.classifications')->with('classifications', $classifications);

        }
    }

    public function store_classification(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|max:150',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.ñ\-]+$/i|unique:classifications,name|max:150',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $classification = $request->isMethod('put') ? Classification::findOrFail($request->id) : new Classification;

        $classification->id = $request->id;

        $name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = Classification::where('name', $name)->count();
            $old_name = Classification::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Classification Already Exists!');
                    $classification = Classification::findOrFail($request->id);
                    return view('admin.file_maintenance.books.classifications.edit_classification')->with('classification', $classification);
                }
            }
        }

        $classification->name =  $name;
        
        if($request->status == '' || null){
            $classification->status = 0;
        }else{
            $classification->status = 1;
        }
        
        $classification->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Classification Updated!');
        }else{
            $request->session()->flash('success_status', 'Classification Added!');
        }

        return redirect()->route('admin.file_maintenance.classifications');
        
    }

    public function edit_classification_view($id)
    {
        $classification = Classification::findOrFail($id);
        return view('admin.file_maintenance.books.classifications.edit_classification')->with('classification', $classification);
    }

    public function delete_classification($id)
    {
        $classification = Classification::findOrFail($id);

        if($classification->delete()){
            session()->flash('success_status', 'Classification Deleted!');
            return redirect()->route('admin.file_maintenance.classifications');
        }
    }

    
    /****************************************************************************************************/

    
    // Categories
    
    public function categories_view()
    {
        $search_categories = [];
        
        $categories_all = $this->get_all_categories();
        return view('admin.file_maintenance.books.categories.categories')->with('categories_all', $categories_all)
            ->with('search_categories', $search_categories);
    }

    public function get_all_categories()
    {
        $first_categories = [];
        $second_categories = [];
        $third_categories = [];

        for ($f = 0; $f <= 9; $f++) {

            $first_code = $f . '00';

            $first_category = Category::where('code', $first_code)->get();

            array_push($first_categories, $first_category);
            
            for ($s = 0; $s <= 9; $s++){

                $second_code =  $f . $s . '0';

                $second_category = Category::where('code', $second_code)->get();

                $second = [
                    'parent_code' => $first_code,
                    'second_category' => $second_category,
                ];
                
                array_push($second_categories, $second);

                $third_category = Category::where('parent_code', $second_code)->get();
                
                $third = [
                    'parent_code' => $second_code,
                    'third_category' => $third_category,
                ];

                array_push($third_categories, $third);

            }
        }

        $categories_all = [
            'first' => $first_categories,
            'second' => $second_categories,
            'third' => $third_categories
        ];

        return $categories_all;
        
    }

    public function search_category(Request $request)
    {   
        session()->flash('admin_search', $request->search);
        
        $count = Category::where('name', 'like', '%'.$request->search.'%')
            ->orWhere('code', 'like', '%'.$request->search.'%')
            ->count();
            
        $search_categories = Category::where('name', 'like', '%'.$request->search.'%')
            ->orWhere('code', 'like', '%'.$request->search.'%')
            ->orderBy('updated_at','desc')->get();

        session()->flash('admin_search_count', $count);

        if($count > 0){
            
            $categories_all = $this->get_all_categories();
            return view('admin.file_maintenance.books.categories.categories')->with('categories_all', $categories_all)
                ->with('search_categories', $search_categories);
            
        }else{
            
            session()->flash('error_status', 'No Category Found!');
            $categories_all = $this->get_all_categories();
            return view('admin.file_maintenance.books.categories.categories')->with('categories_all', $categories_all)
                ->with('search_categories', $search_categories);

        }
    }

    public function store_category(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.ñ\-]+$/i|max:250',
            ]);
        }

        $category = $request->isMethod('put') ? Category::findOrFail($request->id) : new Category;

        $category->id = $request->id;

        $category_name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = Category::where('name', $category_name)->count();
            $old_name = Category::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($category_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Category Already Exists!');
                    $category = Category::findOrFail($request->id);
                    return view('admin.file_maintenance.books.categories.edit_category')->with('category', $category);
                }
            }
        }

        $category->name =  $category_name;
        
        $category->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Category Updated!');
        }

        return redirect()->route('admin.file_maintenance.categories');
        
    }

    public function edit_category_view($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.file_maintenance.books.categories.edit_category')->with('category', $category);
    }


    /****************************************************************************************************/

    // Authors
    public function authors_view()
    {
        $authors = $this->get_authors();
        return view('admin.file_maintenance.books.authors.authors')->with('authors', $authors);
    }

    public function get_authors()
    {
        if (session()->has('authors_per_page')) {
            $authors = Author::orderBy('updated_at','desc')->paginate(session()->get('authors_per_page'));
        }else{
            session(['authors_per_page' => 5 ]);
            $authors = Author::orderBy('updated_at','desc')->paginate(session()->get('authors_per_page'));
        }

        if($authors->count() > 0){
            return $authors;
        }else{
            session()->flash('error_status', 'No Authors Yet!');
            return $authors;
        }
    }

    public function authors_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200){
            session(['authors_per_page' => $per_page ]);
            $authors = $this->get_authors();
            return view('admin.file_maintenance.books.authors.authors')->with('authors', $authors);
        }else{
            session(['authors_per_page' => 5 ]);
            $authors = $this->get_authors();
            return view('admin.file_maintenance.books.authors.authors')->with('authors', $authors);
        }
    }

    public function search_author($search = '')
    {   
        session()->flash('admin_search', $search);
        
        $count = Author::where('author_name', 'like', '%'.$search.'%')->count();

        session()->flash('admin_search_count', $count);
        
        if (session()->has('authors_per_page')) {
            $authors = Author::where('author_name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(session()->get('authors_per_page'));
        }else{
            $authors = Author::where('author_name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(10);
        }

        if($count > 0){
            return view('admin.file_maintenance.books.authors.authors')->with('authors', $authors);
        }else{
            session()->flash('error_status', 'No Author/s Found!');
            return view('admin.file_maintenance.books.authors.authors')->with('authors', $authors);
        }
    }

    public function filter_authors($filter) 
    {
        if($filter == 1){
            
            $authors = Author::where('status', 1)
                ->orderBy('updated_at','desc')->paginate(session()->get('authors_per_page'));

            if ($authors->count() <= 0) {
            
                session()->flash('error_status', '0 Author Found!');
                
            }
                
            return view('admin.file_maintenance.books.authors.authors')->with('authors', $authors);
    
        }

        else if($filter == 0){
            
            $authors = Author::where('status', 0)
                ->orderBy('updated_at','desc')->paginate(session()->get('authors_per_page'));

            if ($authors->count() <= 0) {
            
                session()->flash('error_status', '0 Author Found!');
                
            }

            return view('admin.file_maintenance.books.authors.authors')->with('authors', $authors);
            
        }else{
            
            session()->flash('error_status', '0 Author Found!');
            $authors = $this->get_authors();
            return view('admin.file_maintenance.books.authors.authors')->with('authors', $authors);

        }
    }

    public function store_author(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'author_name' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|max:100',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'author_name' => 'required|regex:/^[a-z0-9 &.ñ\-]+$/i|unique:authors,author_name|max:100',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $author = $request->isMethod('put') ? Author::findOrFail($request->id) : new Author;

        $author->id = $request->id;

        $author_name =  ltrim(ucfirst($request->author_name));
        
        if($request->isMethod('put')){

            $count = Author::where('author_name', $author_name)->count();
            $old_name = Author::where('id', $request->id)->value('author_name');
            
            if($count > 0){

                if(strtolower($author_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Author Already Exists!');
                    $author = Author::findOrFail($request->id);
                    return view('admin.file_maintenance.books.authors.edit_author')->with('author', $author);
                }
            }
        }

        $author->author_name =  $author_name;
        
        if($request->status == '' || null){
            $author->status = 0;
        }else{
            $author->status = 1;
        }
        
        $author->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Author Updated!');
        }else{
            $request->session()->flash('success_status', 'Author Added!');
        }

        return redirect()->route('admin.file_maintenance.authors');
        
    }

    public function edit_author_view($id)
    {
        $author = Author::findOrFail($id);
        return view('admin.file_maintenance.books.authors.edit_author')->with('author', $author);
    }

    public function delete_author($id)
    {
        $author = Author::findOrFail($id);

        if($author->delete()){
            session()->flash('success_status', 'Author Deleted!');
            return redirect()->route('admin.file_maintenance.authors');
        }
    }

    
    /****************************************************************************************************/
    

    // Publishers
    public function publishers_view()
    {
        $publishers = $this->get_publishers();
        return view('admin.file_maintenance.books.publishers.publishers')->with('publishers', $publishers);
    }

    public function get_publishers()
    {
        if (session()->has('publishers_per_page')) {
            $publishers = Publisher::orderBy('updated_at','desc')->paginate(session()->get('publishers_per_page'));
        }else{
            session(['publishers_per_page' => 5 ]);
            $publishers = Publisher::orderBy('updated_at','desc')->paginate(session()->get('publishers_per_page'));
        }

        if($publishers->count() > 0){
            return $publishers;
        }else{
            session()->flash('error_status', 'No Publishers Yet!');
            return $publishers;
        }
    }

    public function publishers_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200){
            session(['publishers_per_page' => $per_page ]);
            $publishers = $this->get_publishers();
            return view('admin.file_maintenance.books.publishers.publishers')->with('publishers', $publishers);
        }else{
            session(['publishers_per_page' => 5 ]);
            $publishers = $this->get_publishers();
            return view('admin.file_maintenance.books.publishers.publishers')->with('publishers', $publishers);
        }
    }

     public function search_publisher($search = '')
    {   
        session()->flash('admin_search', $search);

        $count = Publisher::where('name', 'like', '%'.$search.'%')->count();

        session()->flash('admin_search_count', $count);

        if (session()->has('publishers_per_page')) {
            $publishers = Publisher::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(session()->get('publishers_per_page'));
        }else{
            $publishers = Publisher::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(10);
        }

        if($count > 0){
            return view('admin.file_maintenance.books.publishers.publishers')->with('publishers', $publishers);
        }else{
            session()->flash('error_status', 'No Publisher/s Found!');
            return view('admin.file_maintenance.books.publishers.publishers')->with('publishers', $publishers);
        }
    }

    public function filter_publishers($filter) 
    {
        if($filter == 1){
            
            $publishers = Publisher::where('status', 1)
                ->orderBy('updated_at','desc')->paginate(session()->get('publishers_per_page'));

            if ($publishers->count() <= 0) {
            
                session()->flash('error_status', '0 Publisher Found!');
                
            }
                
            return view('admin.file_maintenance.books.publishers.publishers')->with('publishers', $publishers);
    
        }

        else if($filter == 0){
            
            $publishers = Publisher::where('status', 0)
                ->orderBy('updated_at','desc')->paginate(session()->get('publishers_per_page'));

            if ($publishers->count() <= 0) {
            
                session()->flash('error_status', '0 Publisher Found!');
                
            }

            return view('admin.file_maintenance.books.publishers.publishers')->with('publishers', $publishers);
            
        }else{
            
            session()->flash('error_status', '0 Publisher Found!');
            $publishers = $this->get_publishers();
            return view('admin.file_maintenance.books.publishers.publishers')->with('publishers', $publishers);

        }
    }
    
    public function store_publisher(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|unique:publishers,name|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $publisher = $request->isMethod('put') ? Publisher::findOrFail($request->id) : new Publisher;

        $publisher->id = $request->id;

        $publisher_name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = Publisher::where('name', $publisher_name)->count();
            $old_name = Publisher::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($publisher_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Publisher Already Exists!');
                    $publisher = Publisher::findOrFail($request->id);
                    return view('admin.file_maintenance.books.publishers.edit_publisher')->with('publisher', $publisher);
                }
            }
        }

        $publisher->name =  $publisher_name;
        
        if($request->status == '' || null){
            $publisher->status = 0;
        }else{
            $publisher->status = 1;
        }
        
        $publisher->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Publisher Updated!');
        }else{
            $request->session()->flash('success_status', 'Publisher Added!');
        }

        return redirect()->route('admin.file_maintenance.publishers');
        
    }

    public function edit_publisher_view($id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('admin.file_maintenance.books.publishers.edit_publisher')->with('publisher', $publisher);
    }

    public function delete_publisher($id)
    {
        $publisher = Publisher::findOrFail($id);

        if($publisher->delete()){
            session()->flash('success_status', 'Publisher Deleted!');
            return redirect()->route('admin.file_maintenance.publishers');
        }
    }
    

    /****************************************************************************************************/

    
    // Illustrations Table
    
    public function illustrations_view()
    {
        $illustrations = $this->get_illustrations();
        return view('admin.file_maintenance.books.illustrations.illustrations')->with('illustrations', $illustrations);
    }

    public function get_illustrations()
    {
        $illustrations = Illustration::orderBy('updated_at','desc')->get();

        if($illustrations->count() > 0){
            return $illustrations;
        }else{
            session()->flash('error_status', 'No Illustrations Yet!');
            return $illustrations;
        }
    }
    
    public function store_illustration(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|max:100',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|unique:illustrations,name|max:100',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $illustration = $request->isMethod('put') ? Illustration::findOrFail($request->id) : new Illustration;

        $illustration->id = $request->id;

        $illustration_name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = Illustration::where('name', $illustration_name)->count();
            $old_name = Illustration::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($illustration_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Illustration Already Exists!');
                    $illustration = Illustration::findOrFail($request->id);
                    return view('admin.file_maintenance.books.illustrations.edit_illustration')->with('illustration', $illustration);
                }
            }
        }

        $illustration->name =  $illustration_name;
        
        if($request->status == '' || null){
            $illustration->status = 0;
        }else{
            $illustration->status = 1;
        }
        
        $illustration->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Illustration Updated!');
        }else{
            $request->session()->flash('success_status', 'Illustration Added!');
        }

        return redirect()->route('admin.file_maintenance.illustrations');
        
    }
    
    public function search_illustration(Request $request)
    {   
        session()->flash('admin_search', $request->search);
        
        $count = Illustration::where('name', 'like', '%'.$request->search.'%')->count();

        session()->flash('admin_search_count', $count);
        
        $illustrations = Illustration::where('name', 'like', '%'.$request->search.'%')
            ->orderBy('updated_at','desc')->get();

        if($count > 0){
            return view('admin.file_maintenance.books.illustrations.illustrations')->with('illustrations', $illustrations);
        }else{
            session()->flash('error_status', 'No Illustration/s Found!');
            return view('admin.file_maintenance.books.illustrations.illustrations')->with('illustrations', $illustrations);
        }
    }

    public function filter_illustrations($filter) 
    {
        if($filter == 1){

            $illustrations = Illustration::where('status', 1)
                ->orderBy('updated_at','desc')->get();
            
            if ($illustrations->count() <= 0) {
                
                session()->flash('error_status', '0 Illustration Found!');
                
            }
            
            return view('admin.file_maintenance.books.illustrations.illustrations')->with('illustrations', $illustrations);

        }

        else if($filter == 0){

            $illustrations = Illustration::where('status', 0)
                ->orderBy('updated_at','desc')->get();

            if ($illustrations->count() <= 0) {
            
                session()->flash('error_status', '0 Illustration Found!');
                
            }

            return view('admin.file_maintenance.books.illustrations.illustrations')->with('illustrations', $illustrations);
            

        }else{
            
            session()->flash('error_status', '0 Illustration Found!');
            $illustrations = $this->get_illustrations();
            return view('admin.file_maintenance.books.illustrations.illustrations')->with('illustrations', $illustrations);

        }
    }

    public function edit_illustration_view($id)
    {
        $illustration = Illustration::findOrFail($id);
        return view('admin.file_maintenance.books.illustrations.edit_illustration')->with('illustration', $illustration);
    }

    public function delete_illustration($id)
    {
        $illustration = Illustration::findOrFail($id);

        if($illustration->delete()){
            session()->flash('success_status', 'Illustration Deleted!');
            return redirect()->route('admin.file_maintenance.illustrations');
        }
    }

    /****************************************************************************************************/

    
    // Tags Table
    public function tags_view()
    {
        $tags = $this->get_tags();
        return view('admin.file_maintenance.books.tags.tags')->with('tags', $tags);
    }

    public function get_tags()
    {
        $tags = Tag::orderBy('name','asc')->get();

        if($tags->count() > 0){
            return $tags;
        }else{
            session()->flash('error_status', 'No Tags Yet!');
            return $tags;
        }
    }
    
    public function store_tag(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|max:100',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ\-]+$/i|unique:tags,name|max:100',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $tag = $request->isMethod('put') ? Tag::findOrFail($request->id) : new Tag;

        $tag->id = $request->id;

        $tag_name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = Tag::where('name', $tag_name)->count();
            $old_name = Tag::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($tag_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Tag Already Exists!');
                    $tag = Tag::findOrFail($request->id);
                    return view('admin.file_maintenance.books.tags.edit_tag')->with('tag', $tag);
                }
            }
        }

        $tag->name =  $tag_name;
        
        if($request->status == '' || null){
            $tag->status = 0;
        }else{
            $tag->status = 1;
        }
        
        $tag->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Tag Updated!');
        }else{
            $request->session()->flash('success_status', 'Tag Added!');
        }

        return redirect()->route('admin.file_maintenance.tags');

    }
    
    public function search_tag(Request $request)
    {   
        session()->flash('admin_search', $request->search);
        
        $count = Tag::where('name', 'like', '%'.$request->search.'%')->count();
        
        session()->flash('admin_search_count', $count);
        
        $tags = Tag::where('name', 'like', '%'.$request->search.'%')
            ->orderBy('name','asc')->get();

        if($count > 0){
            return view('admin.file_maintenance.books.tags.tags')->with('tags', $tags);
        }else{
            session()->flash('error_status', 'No Tag/s Found!');
            return view('admin.file_maintenance.books.tags.tags')->with('tags', $tags);
        }
    }

    public function filter_tags($filter) 
    {
        if($filter == 1){

            $tags = tag::where('status', 1)
                ->orderBy('name','asc')->get();
            
            if ($tags->count() <= 0) {
                
                session()->flash('error_status', '0 tag Found!');
                
            }
            
            return view('admin.file_maintenance.books.tags.tags')->with('tags', $tags);

        }

        else if($filter == 0){

            $tags = tag::where('status', 0)
                ->orderBy('name','asc')->get();

            if ($tags->count() <= 0) {
            
                session()->flash('error_status', '0 tag Found!');
                
            }

            return view('admin.file_maintenance.books.tags.tags')->with('tags', $tags);
            

        }else{
            
            session()->flash('error_status', '0 tag Found!');
            $tags = $this->get_tags();
            return view('admin.file_maintenance.books.tags.tags')->with('tags', $tags);

        }
    }

    public function edit_tag_view($id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.file_maintenance.books.tags.edit_tag')->with('tag', $tag);
    }

    public function delete_tag($id)
    {
        $tag = Tag::findOrFail($id);

        if($tag->delete()){
            session()->flash('success_status', 'tag Deleted!');
            return redirect()->route('admin.file_maintenance.tags');
        }
    }
    
    /****************************************************************************************************/

    ///////////////// Thesis Books Filemaintenance

    
    // System_types
    public function system_types_view()
    {
        $system_types = $this->get_system_types();
        return view('admin.file_maintenance.thesis_books.system_types.system_types')->with('system_types', $system_types);
    }

    public function get_system_types()
    {
        if (session()->has('system_types_per_page')) {
            $system_types = ThesisType::orderBy('updated_at','desc')->paginate(session()->get('system_types_per_page'));
        }else{
            session(['system_types_per_page' => 5 ]);
            $system_types = ThesisType::orderBy('updated_at','desc')->paginate(session()->get('system_types_per_page'));
        }

        if($system_types->count() > 0){
            return $system_types;
        }else{
            session()->flash('error_status', 'No System types Yet!');
            return $system_types;
        }
    }

    public function system_types_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200){
            session(['system_types_per_page' => $per_page ]);
            $system_types = $this->get_system_types();
            return view('admin.file_maintenance.thesis_books.system_types.system_types')->with('system_types', $system_types);
        }else{
            session(['system_types_per_page' => 5 ]);
            $system_types = $this->get_system_types();
            return view('admin.file_maintenance.thesis_books.system_types.system_types')->with('system_types', $system_types);
        }
    }

    public function search_system_type($search = '')
    {   
        session()->flash('admin_search', $search);

        $count = ThesisType::where('name', 'like', '%'.$search.'%')->count();

        session()->flash('admin_search_count', $count);

        if (session()->has('system_types_per_page')) {
            $system_types = ThesisType::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(session()->get('system_types_per_page'));
        }else{
            $system_types = ThesisType::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(10);
        }

        if($count > 0){
            return view('admin.file_maintenance.thesis_books.system_types.system_types')->with('system_types', $system_types);
        }else{
            session()->flash('error_status', 'No System Type/s Found!');
            return view('admin.file_maintenance.thesis_books.system_types.system_types')->with('system_types', $system_types);
        }
    }

    public function filter_system_types($filter) 
    {
        if($filter == 1){
            
            $system_types = ThesisType::where('status', 1)
                ->orderBy('updated_at','desc')->paginate(session()->get('system_types_per_page'));
            
            if ($system_types->count() <= 0) {
            
                session()->flash('error_status', '0 System type Found!');
                
            }
                
            return view('admin.file_maintenance.thesis_books.system_types.system_types')->with('system_types', $system_types);
            
        }

        else if($filter == 0){
            
            $system_types = ThesisType::where('status', 0)
                ->orderBy('updated_at','desc')->paginate(session()->get('system_types_per_page'));

            if ($system_types->count() <= 0) {
            
                session()->flash('error_status', '0 System type Found!');
                
            }

            return view('admin.file_maintenance.thesis_books.system_types.system_types')->with('system_types', $system_types);
            
        }else{
            
            session()->flash('error_status', '0 System type Found!');
            $system_types = $this->get_system_types();
            return view('admin.file_maintenance.thesis_books.system_types.system_types')->with('system_types', $system_types);

        }
    }

    public function store_system_type(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,()ñ\-]+$/i|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ()\-]+$/i|unique:thesis_types,name|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $system_type = $request->isMethod('put') ? ThesisType::findOrFail($request->id) : new ThesisType;

        $system_type->id = $request->id;

        $system_type_name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = ThesisType::where('name', $system_type_name)->count();
            $old_name = ThesisType::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($system_type_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'System type Already Exists!');
                    $system_type = ThesisType::findOrFail($request->id);
                    return view('admin.file_maintenance.thesis_books.system_types.edit_system_type')->with('system_type', $system_type);
                }
            }
        }

        $system_type->name =  $system_type_name;
        
        if($request->status == '' || null){
            $system_type->status = 0;
        }else{
            $system_type->status = 1;
        }
        
        $system_type->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'System Type Updated!');
        }else{
            $request->session()->flash('success_status', 'System Type Added!');
        }

        return redirect()->route('admin.file_maintenance.system_types');
        
    }

    public function edit_system_type_view($id)
    {
        $system_type = ThesisType::findOrFail($id);
        return view('admin.file_maintenance.thesis_books.system_types.edit_system_type')->with('system_type', $system_type);
    }

    public function delete_system_type($id)
    {
        $system_type = ThesisType::findOrFail($id);

        if($system_type->delete()){
            session()->flash('success_status', 'System type Deleted!');
            return redirect()->route('admin.file_maintenance.system_types');
        }
    }    
    
    
    /****************************************************************************************************/
    
    
    
    // Thesis Categories
    public function thesis_categories_view()
    {
        $thesis_categories = $this->get_thesis_categories();
        return view('admin.file_maintenance.thesis_books.thesis_categories.thesis_categories')->with('thesis_categories', $thesis_categories);
    }

    public function get_thesis_categories()
    {
        if (session()->has('thesis_categories_per_page')) {
            $thesis_categories = ThesisCategory::orderBy('updated_at','desc')->paginate(session()->get('thesis_categories_per_page'));
        }else{
            session(['thesis_categories_per_page' => 5 ]);
            $thesis_categories = ThesisCategory::orderBy('updated_at','desc')->paginate(session()->get('thesis_categories_per_page'));
        }

        if($thesis_categories->count() > 0){
            return $thesis_categories;
        }else{
            session()->flash('error_status', 'No Thesis Categories Yet!');
            return $thesis_categories;
        }
    }

    public function thesis_categories_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200){
            session(['thesis_categories_per_page' => $per_page ]);
            $thesis_categories = $this->get_thesis_categories();
            return view('admin.file_maintenance.thesis_books.thesis_categories.thesis_categories')->with('thesis_categories', $thesis_categories);
        }else{
            session(['thesis_categories_per_page' => 5 ]);
            $thesis_categories = $this->get_thesis_categories();
            return view('admin.file_maintenance.thesis_books.thesis_categories.thesis_categories')->with('thesis_categories', $thesis_categories);
        }
    }

    public function search_thesis_category($search = '')
    {   
        session()->flash('admin_search', $search);

        $count = ThesisCategory::where('name', 'like', '%'.$search.'%')->count();

        session()->flash('admin_search_count', $count);

        if (session()->has('thesis_categories_per_page')) {
            $thesis_categories = ThesisCategory::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(session()->get('thesis_categories_per_page'));
        }else{
            $thesis_categories = ThesisCategory::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(10);
        }

        if($count > 0){
            return view('admin.file_maintenance.thesis_books.thesis_categories.thesis_categories')->with('thesis_categories', $thesis_categories);
        }else{
            session()->flash('error_status', 'No Thesis Category/s Found!');
            return view('admin.file_maintenance.thesis_books.thesis_categories.thesis_categories')->with('thesis_categories', $thesis_categories);
        }
    }

    public function filter_thesis_categories($filter) 
    {
        if($filter == 1){
            
            $thesis_categories = ThesisCategory::where('status', 1)
                ->orderBy('updated_at','desc')->paginate(session()->get('thesis_categories_per_page'));
            
            if ($thesis_categories->count() <= 0) {
            
                session()->flash('error_status', '0 Thesis Category Found!');
                
            }
                
            return view('admin.file_maintenance.thesis_books.thesis_categories.thesis_categories')->with('thesis_categories', $thesis_categories);
            
        }

        else if($filter == 0){
            
            $thesis_categories = ThesisCategory::where('status', 0)
                ->orderBy('updated_at','desc')->paginate(session()->get('thesis_categories_per_page'));

            if ($thesis_categories->count() <= 0) {
            
                session()->flash('error_status', '0 Thesis Category Found!');
                
            }

            return view('admin.file_maintenance.thesis_books.thesis_categories.thesis_categories')->with('thesis_categories', $thesis_categories);
            
        }else{
            
            session()->flash('error_status', '0 Thesis Category Found!');
            $thesis_categories = $this->get_thesis_categories();
            return view('admin.file_maintenance.thesis_books.thesis_categories.thesis_categories')->with('thesis_categories', $thesis_categories);

        }
    }

    public function store_thesis_category(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,()ñ\-]+$/i|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,()ñ\-]+$/i|unique:thesis_categories,name|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $thesis_category = $request->isMethod('put') ? ThesisCategory::findOrFail($request->id) : new ThesisCategory;

        $thesis_category->id = $request->id;

        $thesis_category_name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = ThesisCategory::where('name', $thesis_category_name)->count();
            $old_name = ThesisCategory::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($thesis_category_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Thesis Category Already Exists!');
                    $thesis_category = ThesisCategory::findOrFail($request->id);
                    return view('admin.file_maintenance.thesis_books.thesis_categories.edit_thesis_category')->with('thesis_category', $thesis_category);
                }
            }
        }

        $thesis_category->name =  $thesis_category_name;
        
        if($request->status == '' || null){
            $thesis_category->status = 0;
        }else{
            $thesis_category->status = 1;
        }
        
        $thesis_category->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Thesis Category Updated!');
        }else{
            $request->session()->flash('success_status', 'Thesis Category Added!');
        }

        return redirect()->route('admin.file_maintenance.thesis_categories');
        
    }

    public function edit_thesis_category_view($id)
    {
        $thesis_category = ThesisCategory::findOrFail($id);
        return view('admin.file_maintenance.thesis_books.thesis_categories.edit_thesis_category')->with('thesis_category', $thesis_category);
    }

    public function delete_thesis_category($id)
    {
        $thesis_category = ThesisCategory::findOrFail($id);

        if($thesis_category->delete()){
            session()->flash('success_status', 'Thesis Category Deleted!');
            return redirect()->route('admin.file_maintenance.thesis_categories');
        }
    }   


 
    /****************************************************************************************************/
    

    // Thesis Subjects
    public function thesis_subjects_view()
    {
        $thesis_subjects = $this->get_thesis_subjects();
        return view('admin.file_maintenance.thesis_books.thesis_subjects.thesis_subjects')->with('thesis_subjects', $thesis_subjects);
    }

    public function get_thesis_subjects()
    {
        if (session()->has('thesis_subjects_per_page')) {
            $thesis_subjects = ThesisSubject::orderBy('updated_at','desc')->paginate(session()->get('thesis_subjects_per_page'));
        }else{
            session(['thesis_subjects_per_page' => 5 ]);
            $thesis_subjects = ThesisSubject::orderBy('updated_at','desc')->paginate(session()->get('thesis_subjects_per_page'));
        }

        if($thesis_subjects->count() > 0){
            return $thesis_subjects;
        }else{
            session()->flash('error_status', 'No Thesis Subjects Yet!');
            return $thesis_subjects;
        }
    }

    public function thesis_subjects_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200){
            session(['thesis_subjects_per_page' => $per_page ]);
            $thesis_subjects = $this->get_thesis_subjects();
            return view('admin.file_maintenance.thesis_books.thesis_subjects.thesis_subjects')->with('thesis_subjects', $thesis_subjects);
        }else{
            session(['thesis_subjects_per_page' => 5 ]);
            $thesis_subjects = $this->get_thesis_subjects();
            return view('admin.file_maintenance.thesis_books.thesis_subjects.thesis_subjects')->with('thesis_subjects', $thesis_subjects);
        }
    }

    public function search_thesis_subject($search = '')
    {   
        session()->flash('admin_search', $search);

        $count = ThesisSubject::where('name', 'like', '%'.$search.'%')->count();

        session()->flash('admin_search_count', $count);

        if (session()->has('thesis_subjects_per_page')) {
            $thesis_subjects = ThesisSubject::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(session()->get('thesis_subjects_per_page'));
        }else{
            $thesis_subjects = ThesisSubject::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(10);
        }

        if($count > 0){
            return view('admin.file_maintenance.thesis_books.thesis_subjects.thesis_subjects')->with('thesis_subjects', $thesis_subjects);
        }else{
            session()->flash('error_status', 'No Thesis Subject/s Found!');
            return view('admin.file_maintenance.thesis_books.thesis_subjects.thesis_subjects')->with('thesis_subjects', $thesis_subjects);
        }
    }

    public function filter_thesis_subjects($filter) 
    {
        if($filter == 1){
            
            $thesis_subjects = ThesisSubject::where('status', 1)
                ->orderBy('updated_at','desc')->paginate(session()->get('thesis_subjects_per_page'));
            
            if ($thesis_subjects->count() <= 0) {
            
                session()->flash('error_status', '0 Thesis Subject Found!');
                
            }
                
            return view('admin.file_maintenance.thesis_books.thesis_subjects.thesis_subjects')->with('thesis_subjects', $thesis_subjects);
            
        }

        else if($filter == 0){
            
            $thesis_subjects = ThesisSubject::where('status', 0)
                ->orderBy('updated_at','desc')->paginate(session()->get('thesis_subjects_per_page'));

            if ($thesis_subjects->count() <= 0) {
            
                session()->flash('error_status', '0 Thesis Subject Found!');
                
            }

            return view('admin.file_maintenance.thesis_books.thesis_subjects.thesis_subjects')->with('thesis_subjects', $thesis_subjects);
            
        }else{
            
            session()->flash('error_status', '0 Thesis Subject Found!');
            $thesis_subjects = $this->get_thesis_subjects();
            return view('admin.file_maintenance.thesis_books.thesis_subjects.thesis_subjects')->with('thesis_subjects', $thesis_subjects);

        }
    }

    public function store_thesis_subject(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,()ñ\-]+$/i|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,()ñ\-]+$/i|unique:thesis_subjects,name|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $thesis_subject = $request->isMethod('put') ? ThesisSubject::findOrFail($request->id) : new ThesisSubject;

        $thesis_subject->id = $request->id;

        $thesis_subject_name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = ThesisSubject::where('name', $thesis_subject_name)->count();
            $old_name = ThesisSubject::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($thesis_subject_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Thesis Subject Already Exists!');
                    $thesis_subject = ThesisSubject::findOrFail($request->id);
                    return view('admin.file_maintenance.thesis_books.thesis_subjects.edit_thesis_subject')->with('thesis_subject', $thesis_subject);
                }
            }
        }

        $thesis_subject->name =  $thesis_subject_name;
        
        if($request->status == '' || null){
            $thesis_subject->status = 0;
        }else{
            $thesis_subject->status = 1;
        }
        
        $thesis_subject->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Thesis Subject Updated!');
        }else{
            $request->session()->flash('success_status', 'Thesis Subject Added!');
        }

        return redirect()->route('admin.file_maintenance.thesis_subjects');
        
    }

    public function edit_thesis_subject_view($id)
    {
        $thesis_subject = ThesisSubject::findOrFail($id);
        return view('admin.file_maintenance.thesis_books.thesis_subjects.edit_thesis_subject')->with('thesis_subject', $thesis_subject);
    }

    public function delete_thesis_subject($id)
    {
        $thesis_subject = ThesisSubject::findOrFail($id);

        if($thesis_subject->delete()){
            session()->flash('success_status', 'Thesis Subject Deleted!');
            return redirect()->route('admin.file_maintenance.thesis_subjects');
        }
    }    
    
    /****************************************************************************************************/

    ///////////////// Thesis Books Filemaintenance

    // Departments
    public function departments_view()
    {
        $departments = $this->get_departments();
        return view('admin.file_maintenance.accounts.departments.departments')->with('departments', $departments);
    }

    public function get_departments()
    {
        if (session()->has('departments_per_page')) {
            $departments = Department::orderBy('updated_at','desc')->paginate(session()->get('departments_per_page'));
        }else{
            session(['departments_per_page' => 5 ]);
            $departments = Department::orderBy('updated_at','desc')->paginate(session()->get('departments_per_page'));
        }

        if($departments->count() > 0){
            return $departments;
        }else{
            session()->flash('error_status', 'No Departments Yet!');
            return $departments;
        }
    }

    public function departments_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200){
            session(['departments_per_page' => $per_page ]);
            $departments = $this->get_departments();
            return view('admin.file_maintenance.accounts.departments.departments')->with('departments', $departments);
        }else{
            session(['departments_per_page' => 5 ]);
            $departments = $this->get_departments();
            return view('admin.file_maintenance.accounts.departments.departments')->with('departments', $departments);
        }
    }

    public function search_department($search = '')
    {   
        session()->flash('admin_search', $search);

        $count = Department::where('name', 'like', '%'.$search.'%')->count();

        session()->flash('admin_search_count', $count);

        if (session()->has('departments_per_page')) {
            $departments = Department::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(session()->get('departments_per_page'));
        }else{
            $departments = Department::where('name', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc')->paginate(10);
        }

        if($count > 0){
            return view('admin.file_maintenance.accounts.departments.departments')->with('departments', $departments);
        }else{
            session()->flash('error_status', 'No Department/s Found!');
            return view('admin.file_maintenance.accounts.departments.departments')->with('departments', $departments);
        }
    }

    public function filter_departments($filter) 
    {
        if($filter == 1){
            
            $departments = Department::where('status', 1)
                ->orderBy('updated_at','desc')->paginate(session()->get('departments_per_page'));
            
            if ($departments->count() <= 0) {
            
                session()->flash('error_status', '0 Department Found!');
                
            }
                
            return view('admin.file_maintenance.accounts.departments.departments')->with('departments', $departments);
            
        }

        else if($filter == 0){
            
            $departments = Department::where('status', 0)
                ->orderBy('updated_at','desc')->paginate(session()->get('departments_per_page'));

            if ($departments->count() <= 0) {
            
                session()->flash('error_status', '0 Department Found!');
                
            }

            return view('admin.file_maintenance.accounts.departments.departments')->with('departments', $departments);
            
        }else{
            
            session()->flash('error_status', '0 Department Found!');
            $departments = $this->get_departments();
            return view('admin.file_maintenance.accounts.departments.departments')->with('departments', $departments);

        }
    }

    public function store_department(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,()ñ\-]+$/i|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }else{
            $request->validate([
                'name' => 'required|regex:/^[a-z0-9 &.,ñ()\-]+$/i|unique:departments,name|max:200',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $department = $request->isMethod('put') ? Department::findOrFail($request->id) : new Department;

        $department->id = $request->id;

        $department_name =  ltrim(ucfirst($request->name));
        
        if($request->isMethod('put')){

            $count = Department::where('name', $department_name)->count();
            $old_name = Department::where('id', $request->id)->value('name');
            
            if($count > 0){

                if(strtolower($department_name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Department Already Exists!');
                    $department = Department::findOrFail($request->id);
                    return view('admin.file_maintenance.accounts.departments.edit_department')->with('department', $department);
                }
            }
        }

        $department->name =  $department_name;
        
        if($request->status == '' || null){
            $department->status = 0;
        }else{
            $department->status = 1;
        }
        
        $department->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Department Updated!');
        }else{
            $request->session()->flash('success_status', 'Department Added!');
        }

        return redirect()->route('admin.file_maintenance.departments');
        
    }

    public function edit_department_view($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.file_maintenance.accounts.departments.edit_department')->with('department', $department);
    }

    public function delete_department($id)
    {
        $department = Department::findOrFail($id);

        if($department->delete()){
            session()->flash('success_status', 'Department Deleted!');
            return redirect()->route('admin.file_maintenance.departments');
        }
    }    


    /****************************************************************************************************/

    
    // Programs
    public function programs_view()
    {
        $programs = $this->get_programs();
        return view('admin.file_maintenance.accounts.programs.programs')->with('programs', $programs);
    }
    
    public function get_programs()
    {
        $programs = Program::orderBy('code','asc')->get();
    
        if($programs->count() > 0){
            return $programs;
        }else{
            session()->flash('error_status', 'No Course/Programs Yet!');
            return $programs;
        }
    }
    
    public function search_program(Request $request)
    {   
        session()->flash('admin_search', $request->search);
        
        $count = Program::where('code', 'like', '%'.$request->search.'%')
            ->orWhere('section_code', 'like', '%'.$request->search.'%')
            ->orWhere('name', 'like', '%'.$request->search.'%')
            ->count();
        
        session()->flash('admin_search_count', $count);
        
        $programs = Program::where('code', 'like', '%'.$request->search.'%')
            ->orWhere('section_code', 'like', '%'.$request->search.'%')
            ->orWhere('name', 'like', '%'.$request->search.'%')
            ->orderBy('name','asc')->get();

        if($count > 0){
            return view('admin.file_maintenance.accounts.programs.programs')->with('programs', $programs);
        }else{
            session()->flash('error_status', 'No Course/Program Found!');
            return view('admin.file_maintenance.accounts.programs.programs')->with('programs', $programs);
        }
    }

    public function filter_programs($filter) 
    {
        if($filter == 1){

            $programs = Program::where('status', 1)
                ->orderBy('name','asc')->get();
            
            if ($programs->count() <= 0) {
                
                session()->flash('error_status', '0 Course/Programs Found!');
                
            }
            
            return view('admin.file_maintenance.accounts.programs.programs')->with('programs', $programs);

        }

        else if($filter == 0){

            $programs = Program::where('status', 0)
                ->orderBy('name','asc')->get();

            if ($programs->count() <= 0) {
            
                session()->flash('error_status', '0 Course/Programs Found!');
                
            }

            return view('admin.file_maintenance.accounts.programs.programs')->with('programs', $programs);
            

        }else{
            
            session()->flash('error_status', '0 Course/Programs Found!');
            $programs = $this->get_programs();
            return view('admin.file_maintenance.accounts.programs.programs')->with('programs', $programs);

        }
    }
    
    public function store_program(Request $request)
    {
        if($request->isMethod('put')){
            $request->validate([
                'code' => 'required|regex:/^[a-z\-]+$/i|max:100',
                'section_code' => 'required|regex:/^[a-z0-9-\-]+$/i|max:100',
                'name' => 'required|regex:/^[a-z &\-]+$ñ/i|max:200',
                'type' => 'required',
                'status' => 'numeric|max:1|nullable',
                ]);
            }else{
                $request->validate([
                'code' => 'required|regex:/^[a-z\-]+$/i|unique:programs,code|max:100',
                'section_code' => 'required|regex:/^[a-z0-9-\-]+$/i|unique:programs,section_code|max:100',
                'name' => 'required|regex:/^[a-z &\-]+$ñ/i|unique:programs,name|max:200',
                'type' => 'required',
                'status' => 'numeric|max:1|nullable',
            ]);
        }

        $program = $request->isMethod('put') ? Program::findOrFail($request->id) : new Program;

        $program->id = $request->id;

        $name =  ltrim(ucfirst($request->name));
        $code =  ltrim(strtoupper($request->code));
        $section_code =  ltrim(strtoupper($request->section_code));
        
        if($request->isMethod('put')){

            $count = Program::where('name', $name)->count();
            $old_name = Program::where('id', $request->id)->value('name');
            
            if($count > 0){
                
                if(strtolower($name) != strtolower($old_name)){
                    $request->session()->flash('error_status', 'Course/Program Already Exists!');
                    $program = Program::findOrFail($request->id);
                    $sections = $this->get_sections($request->id);
                    return view('admin.file_maintenance.accounts.programs.edit_program')->with('program', $program)
                        ->with('sections', $sections);
                }
            }
            
            $count = Program::where('code', $code)->count();
            $old_code = Program::where('id', $request->id)->value('code');
            
            if($count > 0){
                
                if(strtoupper($code) != strtoupper($old_code)){
                    $request->session()->flash('error_status', 'Course/Program Code Already Exists!');
                    $program = Program::findOrFail($request->id);
                    $sections = $this->get_sections($request->id);
                    return view('admin.file_maintenance.accounts.programs.edit_program')->with('program', $program)
                        ->with('sections', $sections);
                }
            }

            $count = Program::where('section_code', $section_code)->count();
            $old_section_code = Program::where('id', $request->id)->value('section_code');
            
            if($count > 0){
                
                if(strtoupper($section_code) != strtoupper($old_section_code)){
                    $request->session()->flash('error_status', 'Section Code Already Exists!');
                    $program = Program::findOrFail($request->id);
                    $sections = $this->get_sections($request->id);
                    return view('admin.file_maintenance.accounts.programs.edit_program')->with('program', $program)
                        ->with('sections', $sections);
                }
            }
        }

        $program->name =  $name;
        $program->code =  $code;
        $program->section_code =  $section_code;
        $program->type =  $request->type;
        
        if($request->status == '' || null){
            $program->status = 0;
        }else{
            $program->status = 1;
        }
        
        $program->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Course/Program Updated!');
        }else{
            $request->session()->flash('success_status', 'Course/Program Added!');
        }

        return redirect()->route('admin.file_maintenance.programs');

    }

    public function edit_program_view($id)
    {
        $program = Program::findOrFail($id);
        $sections = $this->get_sections($id);
        return view('admin.file_maintenance.accounts.programs.edit_program')->with('program', $program)
            ->with('sections', $sections);
    }  

    public function delete_program($id)
    {
        $program = Program::findOrFail($id);

        if($program->delete()){
            session()->flash('success_status', 'Course/Program Deleted!');
            return redirect()->route('admin.file_maintenance.programs');
        }
    }
    
    /****************************************************************************************************/

    //Sections

    public function get_sections($id)
    {
        $sections = Section::where('program_id', $id)->orderBy('code','asc')->get();
    
        if($sections->count() > 0){
            return $sections;
        }else{
            session()->flash('error_status', 'No Section Yet!');
            return $sections;
        }
    }

    public function store_section(Request $request)
    {
        $request->validate([
            'program_id' => 'required|numeric|max:100',
            'code_section' => 'required|regex:/^[a-z0-9-\-ñ]+$/i|max:100',
            'status' => 'numeric|max:1|nullable',
        ]);

        $section = $request->isMethod('put') ? Section::findOrFail($request->id) : new Section;

        $section->id = $request->id;

        $section->program_id = $request->program_id;

        $code_section =  ltrim(strtoupper($request->code_section));
        
        $count = Section::where([
            ['program_id','=', $request->program_id],
            ['code', '=', $code_section]
        ])->count();
        
        $old_code = Section::where([
            ['id', '=', $request->id],
            ['program_id', '=', $request->program_id]
        ])->value('code');
        
        if($count > 0){
            
            if(strtolower($code_section) != strtolower($old_code)){
                $request->session()->flash('error_status', 'Section Already Exists!');
                $program = Program::findOrFail($request->program_id);
                $sections = $this->get_sections($request->program_id);

                if($request->isMethod('put')){
                    $section = Section::findOrFail($request->id);
                    return view('admin.file_maintenance.accounts.programs.edit_section')->with('section', $section);
                }else{
                    return view('admin.file_maintenance.accounts.programs.edit_program')->with('program', $program)
                        ->with('sections', $sections);
                }
            }
        }

        $section->code =  $code_section;
        
        if($request->status == '' || null){
            $section->status = 0;
        }else{
            $section->status = 1;
        }
        
        $section->save();
        
        if($request->isMethod('put')){
            $request->session()->flash('success_status', 'Section Updated!');
        }else{
            $request->session()->flash('success_status', 'Section Added!');
        }

        return redirect()->route('admin.file_maintenance.edit_program_view', [$request->program_id]);
    }

    public function edit_section_view($id)
    {
        $section = Section::findOrFail($id);
        return view('admin.file_maintenance.accounts.programs.edit_section')->with('section', $section);
    }  

    public function delete_section($id)
    {
        $section = Section::findOrFail($id);

        $program_id = $section->program_id; 

        if($section->delete()){
            session()->flash('success_status', 'Section Deleted!');
            return redirect()->route('admin.file_maintenance.edit_program_view', [$program_id]);
        }
    }
    
    /****************************************************************************************************/
    
}


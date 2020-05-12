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

class ThesisBookReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function thesis_book_reports_view()
    {
        $thesis_book_reports_all = $this->get_all_thesis_book_reports();

        $total = $this->get_thesis_book_reports_total();
        
        $thesis_authors = $this->get_thesis_authors();
        
        return view('admin.reports.thesis_book_reports')->with('thesis_book_reports', $thesis_book_reports_all)
            ->with('total', $total)
            ->with('thesis_authors', $thesis_authors);
            
    }

    public function get_all_thesis_book_reports()
    {
        $this->check_session_queries();
        
        $thesis_book_report_query = $this->get_thesis_book_reports_query();
            
        $thesis_book_report_query = $this->add_order_queries($thesis_book_report_query, false);
        
        return $thesis_book_report_query;
        
    }

    public function add_order_queries($thesis_book_report_query, $search_on)
    {
        if(session()->has('thesis_book_reports_getAll')){

            if(session()->get('thesis_book_reports_getAll') != 'all'){

                if(session()->get('thesis_book_reports_getAll') == 'addition'){

                    $thesis_book_report_query = $thesis_book_report_query->where('aquisition_theses.aquisition_type', 1);

                }else if(session()->get('thesis_book_reports_getAll') == 'weeded'){

                    $thesis_book_report_query = $thesis_book_report_query->where('aquisition_theses.aquisition_type', 2);
                    
                }else if(session()->get('thesis_book_reports_getAll') == 'lost'){

                    $thesis_book_report_query = $thesis_book_report_query->where('aquisition_theses.aquisition_type', 3);
                    
                }
            }
            
        }else{
                
            session(['thesis_book_reports_getAll' => 'all' ]);

        }

        if($search_on){

            $count = $thesis_book_report_query->count();
            
            session()->flash('admin_search_count', $count);
            
            
        }

        $thesis_book_reports = $thesis_book_report_query->orderBy(session()->get('thesis_book_reports_toOrder'), session()->get('thesis_book_reports_orderBy'))
            ->paginate(session()->get('thesis_book_reports_per_page'));
            
        if($thesis_book_reports->count() > 0){
            
            return $thesis_book_reports;
            
        }else{
            
            session()->flash('error_status', 'No Thesis Book Yet!');

            return $thesis_book_reports;

        }
    }
    

    public function get_thesis_authors()
    {
        $thesis_authors = ThesisAuthor::select('thesis_id', 'name')->get();
        return $thesis_authors;
    }

    public function get_thesis_book_reports_query()
    {
        $thesis_book_report_query = AquisitionThesis::join('thesis_books', 'aquisition_theses.thesis_book_id', '=', 'thesis_books.id')
        ->join('thesis_subjects', 'thesis_books.thesis_subject_id', '=', 'thesis_subjects.id')
        ->join('programs', 'thesis_books.program_id', '=', 'programs.id')
        ->select(
            'aquisition_theses.*', 
            'thesis_books.acc_no', 
            'thesis_books.call_no', 
            'thesis_books.title', 
            'thesis_subjects.name AS subject_name', 
            'thesis_books.copyright', 
            'programs.code' 
        );

        return $thesis_book_report_query;
    }

    public function get_thesis_book_reports_total()
    {
        $sum = AquisitionThesis::where('aquisition_type', 1)->sum('quantity');
        
        $minus = AquisitionThesis::whereIn('aquisition_type', [2, 3])->sum('quantity');

        $total = $sum - $minus;

        $weeded_minus = AquisitionThesis::where('aquisition_type', 2)->sum('quantity');

        $lost_minus = AquisitionThesis::where('aquisition_type', 3)->sum('quantity');

        $all = [
            'total' => $total, 
            'weeded' => $weeded_minus,
            'lost' => $lost_minus 
        ];

        return $all;
    }

    public function check_session_queries()
    {
        if(session()->has('thesis_book_reports_toOrder') != true){

            session(['thesis_book_reports_toOrder' => 'created_at' ]);
            
        }

        if(session()->has('thesis_book_reports_orderBy') != true){

            session(['thesis_book_reports_orderBy' => 'desc' ]);
            
        }

        if (session()->has('thesis_book_reports_per_page') != true) {

            session(['thesis_book_reports_per_page' => 5 ]);
            
        }
    }


    public function thesis_book_reports_per_page($per_page = 10) 
    {
        $per_page_array = [5,10,20,50,100,200,500];
        
        if(in_array($per_page, $per_page_array)){
            
            session(['thesis_book_reports_per_page' => $per_page ]);

        }else{
            
            session(['thesis_book_reports_per_page' => 5 ]);
            
        }

        return redirect()->route('admin.reports.thesis_book_reports_view');

    }

    public function thesis_book_reports_toOrder($ToOrder = 'created_at') 
    {

        if($ToOrder == 'acc_no' || $ToOrder ==  'call_no' || $ToOrder ==  'title' || $ToOrder ==  'subject_name' || $ToOrder ==  'copyright' || $ToOrder ==  'code' || $ToOrder ==  'created_at'){
            
            session(['thesis_book_reports_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['thesis_book_reports_toOrder' => 'created_at' ]);

        }

        return redirect()->route('admin.reports.thesis_book_reports_view');

    }

    public function thesis_book_reports_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || $orderBy == 'desc'){
            
            session(['thesis_book_reports_orderBy' => $orderBy ]);
            
        }else{
            
            session(['thesis_book_reports_orderBy' => 'desc' ]);

        }

        return redirect()->route('admin.reports.thesis_book_reports_view');

    }

    public function filter_thesis_book_reports($filter = 'all') 
    {
        
        if($filter == 'all' || $filter ==  'addition' || $filter ==  'weeded' || $filter == 'lost'){
            
            session(['thesis_book_reports_getAll' => $filter ]);
            
        }else{
            
            session(['thesis_book_reports_getAll' => 'all' ]);

        }

        return redirect()->route('admin.reports.thesis_book_reports_view');

    }

    public function search_thesis_book_reports($search = '')
    {
        $this->check_session_queries();
        
        $thesis_book_report_query =  $thesis_book_report_query = $this->get_thesis_book_reports_query();

        $thesis_authors = ThesisAuthor::select('thesis_id')->where('name', 'like', '%'.$search.'%')->distinct()->get();
        
        $thesis_id_authors = [];
        
        foreach ($thesis_authors as $author) {
            
            array_push($thesis_id_authors, $author->thesis_id);

        }

        $thesis_book_report_query = $thesis_book_report_query->Where('thesis_books.acc_no', 'like', '%'.$search.'%')
            ->orWhereIn('thesis_books.id', $thesis_id_authors)
            ->orWhere('thesis_books.call_no', 'like', '%'.$search.'%')
            ->orWhere('thesis_books.title', 'like', '%'.$search.'%')
            ->orWhere('thesis_subjects.name', 'like', '%'.$search.'%')
            ->orWhere('thesis_books.copyright', 'like', '%'.$search.'%')
            ->orWhere('programs.code', 'like', '%'.$search.'%');
            
        session()->flash('admin_search', $search);
        session(['thesis_book_reports_getAll' => 'all' ]);
        
        $thesis_book_reports = $this->add_order_queries($thesis_book_report_query, true);

        $count = $thesis_book_reports->count();
        
        $total = $this->get_thesis_book_reports_total();
        
        $thesis_authors = $this->get_thesis_authors();

        if($count > 0){
                
            return view('admin.reports.thesis_book_reports')->with('thesis_book_reports', $thesis_book_reports)
                ->with('total', $total)
                ->with('thesis_authors', $thesis_authors);

        }else{
            
            session()->flash('error_status', 'No data found!');

            return view('admin.reports.thesis_book_reports')->with('thesis_book_reports', $thesis_book_reports)
                ->with('total', $total)
                ->with('thesis_authors', $thesis_authors);
            
        }
    }


    public function thesis_book_reports_filter_by_date($calendar_type, $search_date = null)
    {
        if($calendar_type == 'date' || $calendar_type == 'week' || $calendar_type == 'month' || $calendar_type == 'year'){
            
            if($search_date != null){
                
                session(['thesis_book_reports_calendar_type' => $calendar_type ]);
                session(['thesis_book_reports_search_date' => $search_date ]);

                $this->check_session_queries();
            
                $thesis_book_report_query =  $thesis_book_report_query = $this->get_thesis_book_reports_query();

                if($calendar_type == 'date'){
                    
                    $thesis_book_report_query->whereDate('aquisition_theses.created_at', $search_date)->get();

                }else if($calendar_type == 'week' ){
                    
                    $year = substr($search_date,0, -4);
                    $week_number = substr($search_date,6);

                    $first_day = "";
                    $last_day = "";

                    for($day=1; $day<=7; $day++){
                        $days = date('Y-m-d', strtotime($year."W".$week_number.$day));
                        if($day == 1){
                            $first_day = $days;
                        }else if($day == 7){
                            $last_day = $days . " 23:59:59.999";
                        }
                    }
                    
                    $thesis_book_report_query->whereBetween('aquisition_theses.created_at', [$first_day, $last_day])->get();

                }else if($calendar_type == 'month'){

                    $month = substr($search_date,5);

                    $year = substr($search_date,0, -3);
                    
                    $thesis_book_report_query->whereMonth('aquisition_theses.created_at', $month)
                        ->whereYear('aquisition_theses.created_at', $year)->get();

                }else if($calendar_type == 'year'){

                    $thesis_book_report_query->whereYear('aquisition_theses.created_at', $search_date)->get();

                }

                session()->flash('admin_search', $search_date);
                
                $thesis_book_reports = $this->add_order_queries($thesis_book_report_query, true);
                
                $count = $thesis_book_reports->count();

                $total = $this->get_thesis_book_reports_total();

                $thesis_authors = $this->get_thesis_authors();
                
                if($count > 0){
        
                    return view('admin.reports.thesis_book_reports')->with('thesis_book_reports', $thesis_book_reports)
                        ->with('total', $total)
                        ->with('thesis_authors', $thesis_authors);
        
                }else{
                    
                    session()->flash('error_status', 'No data found!');

                    return view('admin.reports.thesis_book_reports')->with('thesis_book_reports', $thesis_book_reports)
                        ->with('total', $total)
                        ->with('thesis_authors', $thesis_authors);
                    
                }
                
            }else{
                
                return redirect()->route('admin.reports.thesis_book_reports_view');

            }
            
        }else{
            
            return redirect()->route('admin.reports.thesis_book_reports_view');

        }
    }

    public function thesis_book_reports_filter_start_end_date($start_date = null, $end_date = null)
    {
        if($start_date != null && $end_date != null){

            session(['thesis_book_reports_calendar_type' => 'start_end' ]);
            session(['thesis_book_reports_search_date_start' => $start_date ]);
            session(['thesis_book_reports_search_date_end' => $end_date ]);

            $this->check_session_queries();
            
            $thesis_book_report_query =  $thesis_book_report_query = $this->get_thesis_book_reports_query();
            
            session()->flash('admin_search', 'Start: ' . $start_date . ' - ' . 'End : ' . $end_date);
            
            $end_date = date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes',strtotime($end_date)));
            
            $thesis_book_report_query->whereBetween('aquisition_theses.created_at', [$start_date, $end_date])->get();
            
            $thesis_book_reports = $this->add_order_queries($thesis_book_report_query, true);
            
            $count = $thesis_book_reports->count();
            
            $total = $this->get_thesis_book_reports_total();

            $thesis_authors = $this->get_thesis_authors();
            
            if($count > 0){

                return view('admin.reports.thesis_book_reports')->with('thesis_book_reports', $thesis_book_reports)
                    ->with('total', $total)
                    ->with('thesis_authors', $thesis_authors);
    
            }else{
                
                session()->flash('error_status', 'No data found!');

                return view('admin.reports.thesis_book_reports')->with('thesis_book_reports', $thesis_book_reports)
                    ->with('total', $total)
                    ->with('thesis_authors', $thesis_authors);
                
            }
            
        }else{
                
            return redirect()->route('admin.reports.thesis_book_reports_view');

        }
    }
}

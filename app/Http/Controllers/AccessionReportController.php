<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use App\AquisitionBook;

class AccessionReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function accession_reports_view()
    {
        $accession_reports_all = $this->get_all_accession_reports();

        $total = $this->get_accession_reports_total();

        return view('admin.reports.accession_reports')->with('accession_reports', $accession_reports_all)
            ->with('total', $total);
            
    }

    public function get_all_accession_reports()
    {
        $this->check_session_queries();
        
        $accession_report_query = $this->get_accession_reports_query();
            
        $accession_report_query = $this->add_order_queries($accession_report_query, false); 
        
        return $accession_report_query;
        
    }

    public function add_order_queries($accession_report_query, $search_on)
    {
        if(session()->has('accession_reports_getAll')){

            if(session()->get('accession_reports_getAll') != 'all'){

                if(session()->get('accession_reports_getAll') == 'addition'){

                    $accession_report_query = $accession_report_query->where('aquisition_books.aquisition_type', 1);

                }else if(session()->get('accession_reports_getAll') == 'weeded'){

                    $accession_report_query = $accession_report_query->where('aquisition_books.aquisition_type', 2);
                    
                }else if(session()->get('accession_reports_getAll') == 'lost'){

                    $accession_report_query = $accession_report_query->where('aquisition_books.aquisition_type', 3);
                    
                }
            }
            
        }else{
                
            session(['accession_reports_getAll' => 'all' ]);

        }

        if($search_on){

            $count = $accession_report_query->count();
            
            session()->flash('admin_search_count', $count);
            
        }

        $accession_reports = $accession_report_query->orderBy(session()->get('accession_reports_toOrder'), session()->get('accession_reports_orderBy'))
            ->paginate(session()->get('accession_reports_per_page'));
            
        
        if($accession_reports->count() > 0){
            
            return $accession_reports;
            
        }else{
            
            session()->flash('error_status', 'No Accessions Yet!');

            return $accession_reports;

        }

    }

    public function get_accession_reports_query()
    {
        $accession_report_query = AquisitionBook::join('accessions', 'aquisition_books.acc_id', '=', 'accessions.id')
            ->join('authors', 'accessions.author_id', '=', 'authors.id')
            ->join('publishers', 'accessions.publisher_id', '=', 'publishers.id')
            ->select(
                'aquisition_books.*',
                'authors.author_name',
                'publishers.name AS publisher_name',
                'accessions.pic_url',
                'accessions.book_title',
                'accessions.copyright'
            );

        return $accession_report_query;
    }

    public function get_accession_reports_total()
    {
        $sum = AquisitionBook::where('aquisition_type', 1)->sum('quantity');
        
        $minus = AquisitionBook::whereIn('aquisition_type', [2, 3])->sum('quantity');

        $total = $sum - $minus;

        $weeded_minus = AquisitionBook::where('aquisition_type', 2)->sum('quantity');

        $lost_minus = AquisitionBook::where('aquisition_type', 3)->sum('quantity');

        $all = [
            'total' => $total, 
            'weeded' => $weeded_minus,
            'lost' => $lost_minus 
        ];

        return $all;
    }

    public function check_session_queries()
    {
        if(session()->has('accession_reports_toOrder') != true){

            session(['accession_reports_toOrder' => 'created_at' ]);
            
        }

        if(session()->has('accession_reports_orderBy') != true){

            session(['accession_reports_orderBy' => 'desc' ]);
            
        }

        if (session()->has('accession_reports_per_page') != true) {

            session(['accession_reports_per_page' => 5 ]);
            
        }
    }


    public function accession_reports_per_page($per_page = 10) 
    {
        $per_page_array = [5,10,20,50,100,200,500];
        
        if(in_array($per_page, $per_page_array)){
            
            session(['accession_reports_per_page' => $per_page ]);
            
        }else{
            
            session(['accession_reports_per_page' => 5 ]);

        }

        return redirect()->route('admin.reports.accession_reports_view');

    }

    public function accession_reports_toOrder($ToOrder = 'created_at') 
    {

        if($ToOrder == 'author_name' || $ToOrder ==  'book_title' || $ToOrder ==  'publisher_name' || $ToOrder ==  'copyright' || $ToOrder ==  'created_at'){
            
            session(['accession_reports_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['accession_reports_toOrder' => 'created_at' ]);

        }

        return redirect()->route('admin.reports.accession_reports_view');

    }

    public function accession_reports_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || $orderBy == 'desc' ){
            
            session(['accession_reports_orderBy' => $orderBy ]);
            
        }else{
            
            session(['accession_reports_orderBy' => 'desc' ]);

        }

        return redirect()->route('admin.reports.accession_reports_view');

    }

    public function filter_accession_reports($filter = 'all') 
    {
        
        if($filter == 'all' || $filter == 'addition' || $filter == 'weeded' || $filter == 'lost'){
            
            session(['accession_reports_getAll' => $filter ]);
            
        }else{
            
            session(['accession_reports_getAll' => 'all' ]);

        }

        return redirect()->route('admin.reports.accession_reports_view');

    }

    public function search_accession_reports($search = '')
    {
        $this->check_session_queries();
        
        $accession_report_query =  $accession_report_query = $this->get_accession_reports_query();

        $accession_report_query = $accession_report_query->Where('authors.author_name', 'like', '%'.$search.'%')
            ->orWhere('accessions.book_title', 'like', '%'.$search.'%')
            ->orWhere('accessions.copyright', 'like', '%'.$search.'%')
            ->orWhere('publishers.name', 'like', '%'.$search.'%')
            ->orWhere('aquisition_books.aquisition_type', 'like', '%'.$search.'%')
            ->orWhere('aquisition_books.quantity', 'like', '%'.$search.'%');
            
            
        session()->flash('admin_search', $search);
        session(['accession_reports_getAll' => 'all' ]);

        $accession_reports = $this->add_order_queries($accession_report_query, true);
        
        $count = $accession_reports->count();
        
        $total = $this->get_accession_reports_total();

        if($count > 0){

            return view('admin.reports.accession_reports')->with('accession_reports', $accession_reports)
                ->with('total', $total);

        }else{
            
            session()->flash('error_status', 'No data found!');
            
            return view('admin.reports.accession_reports')->with('accession_reports', $accession_reports)
                ->with('total', $total);
            
        }
    }


    public function accession_reports_filter_by_date($calendar_type, $search_date = null)
    {
        if($calendar_type == 'date' || $calendar_type == 'week' || $calendar_type == 'month' || $calendar_type == 'year'){
            
            if($search_date != null){

                session(['accession_reports_calendar_type' => $calendar_type ]);
                session(['accession_reports_search_date' => $search_date ]);
                
                $this->check_session_queries();
            
                $accession_report_query =  $accession_report_query = $this->get_accession_reports_query();

                if($calendar_type == 'date'){
                    
                    $accession_report_query->whereDate('aquisition_books.created_at', $search_date)->get();

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
                    
                    $accession_report_query->whereBetween('aquisition_books.created_at', [$first_day, $last_day])->get();

                }else if($calendar_type == 'month'){

                    $month = substr($search_date,5);

                    $year = substr($search_date,0, -3);
                    
                    $accession_report_query->whereMonth('aquisition_books.created_at', $month)
                        ->whereYear('aquisition_books.created_at', $year)->get();

                }else if($calendar_type == 'year'){

                    $accession_report_query->whereYear('aquisition_books.created_at', $search_date)->get();

                }

                session()->flash('admin_search', $search_date);
                
                $accession_reports = $this->add_order_queries($accession_report_query, true);
                
                $count = $accession_reports->count();
                
                $total = $this->get_accession_reports_total();

                if($count > 0){

                    return view('admin.reports.accession_reports')->with('accession_reports', $accession_reports)
                        ->with('total', $total);
        
                }else{
                    
                    session()->flash('error_status', 'No data found!');

                    return view('admin.reports.accession_reports')->with('accession_reports', $accession_reports)
                        ->with('total', $total);
                    
                }
                
            }else{
                
                return redirect()->route('admin.reports.accession_reports_view');

            }
            
        }else{
            
            return redirect()->route('admin.reports.accession_reports_view');

        }
    }

    public function accession_reports_filter_start_end_date($start_date = null, $end_date = null)
    {
        if($start_date != null && $end_date != null){

            session(['accession_reports_calendar_type' => 'start_end' ]);
            session(['accession_reports_search_date_start' => $start_date ]);
            session(['accession_reports_search_date_end' => $end_date ]);
            
            $this->check_session_queries();
            
            $accession_report_query =  $accession_report_query = $this->get_accession_reports_query();
            
            session()->flash('admin_search', 'Start: ' . $start_date . ' - ' . 'End : ' . $end_date);
            
            $end_date = date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes',strtotime($end_date)));

            $accession_report_query->whereBetween('aquisition_books.created_at', [$start_date, $end_date])->get();
            
            $accession_reports = $this->add_order_queries($accession_report_query, true);
        
            $count = $accession_reports->count();
            
            $total = $this->get_accession_reports_total();

            if($count > 0){

                return view('admin.reports.accession_reports')->with('accession_reports', $accession_reports)
                    ->with('total', $total);
    
            }else{
                
                session()->flash('error_status', 'No data found!');
    
                return view('admin.reports.accession_reports')->with('accession_reports', $accession_reports)
                    ->with('total', $total);
                
            }
            
        }else{
                
            return redirect()->route('admin.reports.accession_reports_view');

        }
    }
}

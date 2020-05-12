@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'reports']);     
    session(['sidebar_nav_lev_2' => 'book_reports_ul']); 
    session(['point_arrow' => 'accession_reports']);

    $admin_search = session()->get('admin_search');
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Book Aquisitions Reports</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.reports.search_accession_reports')}}" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-2 my-1">
          <label class="sr-only" for="search">Search Accession</label>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search">
        </div>

        <div class="col-auto my-1">
          <button type="submit" onclick="search_form()" class="btn btn-sm btn-primary m-1 font-weight-bold">
            Search&nbsp;<i class="fas fa-search"></i>
          </button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.reports.accession_reports_view')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('accession_reports_per_page')) {{session()->get('accession_reports_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_per_page') . '/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_per_page') . '/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_per_page') . '/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_per_page') . '/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_per_page') . '/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_per_page') . '/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_per_page') . '/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has('accession_reports_toOrder')) 
                @if (session()->get('accession_reports_toOrder') == 'acc_no')
                  Acc No.
                @elseif (session()->get('accession_reports_toOrder') == 'book_title')
                  Title
                @elseif (session()->get('accession_reports_toOrder') == 'author_name')
                  Author
                @elseif (session()->get('accession_reports_toOrder') == 'publisher_name')
                  Publisher
                @elseif (session()->get('accession_reports_toOrder') == 'copyright')
                  Copyright
                @elseif (session()->get('accession_reports_toOrder') == 'created_at')
                  Date Added
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_toOrder') . '/' . 'book_title'}}">Title</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_toOrder') . '/' . 'author_name'}}">Author</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_toOrder') . '/' . 'publisher_name'}}">Publisher</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_toOrder') . '/' . 'copyright'}}">Copyright</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_toOrder') . '/' . 'created_at'}}">Date Added</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has('accession_reports_orderBy')) 
                @if (session()->get('accession_reports_orderBy') == 'asc')
                  Asc
                @elseif (session()->get('accession_reports_orderBy') == 'desc')
                  Desc 
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_orderBy') . '/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.reports.accession_reports_orderBy') . '/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetAll: 
              @if (session()->has('accession_reports_getAll')) 
                @if (session()->get('accession_reports_getAll') == 'all')
                  All
                @elseif (session()->get('accession_reports_getAll') == 'addition')
                  Addition
                @elseif (session()->get('accession_reports_getAll') == 'weeded')
                  Weeded
                @elseif (session()->get('accession_reports_getAll') == 'lost')
                  Damage/Lost
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.reports.filter_accession_reports') . '/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.reports.filter_accession_reports') . '/' . 'addition'}}">Addition</a>
              <a class="dropdown-item" href="{{route('admin.reports.filter_accession_reports')  . '/' . 'weeded'}}">Weeded</a>
              <a class="dropdown-item" href="{{route('admin.reports.filter_accession_reports')  . '/' . 'lost'}}">Damage/Lost</a>
            </div>
          </div>
        </div>
        
        <div class="col-auto my-1">
          <button id="print" type="button" onclick="print_report()" class="btn btn-sm btn-secondary font-weight-bold">Print <i class="fas fa-print"></i></button>
        </div>
      </div>
    </form>

    @php
      $search_date_on = false;

      if(session()->has('accession_reports_calendar_type')){
        
        $search_date_on = true;
        
        $calendar_type = session()->pull('accession_reports_calendar_type');
        $search_date = session()->pull('accession_reports_search_date');
        
        if($calendar_type == 'start_end'){
          
          $start = session()->pull('accession_reports_search_date_start');
          $end = session()->pull('accession_reports_search_date_end');

        }
      }
    @endphp

    <form id="calendar_form" action="{{route('admin.reports.accession_reports_filter_url')}}" method ="get">
      <div class="form-row m-2">

        <span class="text-primary col-auto mx-1"><b>Filter By Specific:&nbsp;</b></span>
      
        <b class="text-info p-1 mx-1">Date: </b>
        <input type="date" id="date" class="border border-info rounded text-secondary pl-1" @if($search_date_on && $calendar_type == 'date') value="{{$search_date}}" @else value="{{date("Y-m-d")}}" @endif>
        <input onclick="calendar_form('date')" class="btn btn-sm btn-primary mx-2" type="submit" value="Go"/>

        <b class="text-info p-1 mx-1">Week: </b>
        <input type="week" id="week" class="border border-info rounded text-secondary pl-1"  @if($search_date_on && $calendar_type == 'week') value="{{$search_date}}" @endif>

        <input onclick="calendar_form('week')" class="btn btn-sm btn-primary mx-2" type="submit" value="Go"/>

        <b class="text-info p-1 mx-1">Month: </b>
        <input type="month" id="month" class="border border-info rounded text-secondary pl-1"  @if($search_date_on && $calendar_type == 'month') value="{{$search_date}}" @else value="{{date("Y-m")}}" @endif> 

        <input onclick="calendar_form('month')" class="btn btn-sm btn-primary mx-2" type="submit"  value="Go"/>
    
        <b class="text-info p-1 mx-1">Year: </b>
        <input type="number" id="year" class="border border-info rounded text-secondary pl-1" min="1900" max="2099" step="1"  @if($search_date_on && $calendar_type == 'year') value="{{$search_date}}" @else value="{{date("Y")}}" @endif/>

        <input onclick="calendar_form('year')" class="btn btn-sm btn-primary mx-2" type="submit" value="Go"/>
      </div>
    </form>

    <form id="calendar_form_start_end" action="{{route('admin.reports.accession_reports_filter_start_end_date_url')}}" method ="get">  
      <div class="form-row m-3">
        <b class="text-info p-1 mx-1">Start Date: </b>
        <input type="date" id="start" class="border border-info rounded text-secondary pl-1"  @if($search_date_on && $calendar_type == 'start_end') value="{{$start}}" @else value="{{date("Y-m-d")}}" @endif>

        <b class="text-info p-1 mx-1">End Date: </b>
        <input type="date" id="end" class="border border-info rounded text-secondary pl-1"  @if($search_date_on && $calendar_type == 'start_end') value="{{$end}}" @else value="{{date("Y-m-d")}}" @endif>
        
        <input onclick="calendar_form_start_end()" class="btn btn-sm btn-primary mx-2 " type="submit" value="Go"/>
      </div>
    </form>

    <div id="reports_print">
      <div class="form-row m-2">
        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b>Total No. <span class="text-info">Available</span> Books:&nbsp;</b></span>
          <span class="text-secondary"><b>{{$total['total']}}</b></span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b>Total No. <span class="text-danger">Weeded</span> Books:&nbsp;</b></span>
          <span class="text-secondary"><b>{{$total['weeded']}}</b></span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b>Total No. <span class="text-danger">Damage/Lost</span> Books:&nbsp;</b></span>
          <span class="text-secondary"><b>{{$total['lost']}}</b></span>
        </div>
      </div>

      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">Image</th>
            <th scope="col">Author</th>
            <th scope="col">Title</th>
            <th scope="col">Publisher</th>
            <th scope="col">Copyright</th>
            <th scope="col">Aquisition Type</th>
            <th scope="col">Quantity</th>
            <th scope="col">Date</th>
          </tr>
        </thead>
        <tbody>
          @if( session('error_status') )
            <tr>
              <td colspan="3">{{session()->pull('error_status')}}</td>
            </tr>
          @else
            @foreach ($accession_reports as $report)
              <tr>
                <td>
                  @if ($report->pic_url == null)
                    <img src="{{asset('storage/images/accession_images/noimage.png')}}" width="80" height="80" alt="{{$report->book_title}}" class="img-thumbnail">
                  @else
                    <img src="{{asset('storage/images/accession_images/' . $report->pic_url)}}" width="80" height="80" alt="{{$report->book_title}}" class="img-thumbnail img-fluid">
                  @endif
                </td>
                <td>{{$report->author_name}}</td>
                <td>{{$report->book_title}}</td>
                <td>{{$report->publisher_name}}</td>
                <td>{{$report->copyright}}</td>
                @if ($report->aquisition_type == 1)
                  <td class="text-success">
                    Addition
                  </td>
                  <td class="text-success">
                    {{$report->quantity}}
                  </td>
                @elseif ($report->aquisition_type == 2)  
                  <td class="text-danger">
                    Weeded
                  </td>
                  <td class="text-danger">
                    {{$report->quantity}}
                  </td>
                @elseif ($report->aquisition_type == 3)
                  <td class="text-danger">
                    Damage/Lost
                  </td>
                  <td class="text-danger">
                    {{$report->quantity}}
                  </td>
                @endif
                <td>{{date('F jS, Y h:i:s', strtotime($report->created_at))}}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
     {{$accession_reports->links()}}
  </div>

  <!-- For vue Date Time Picker
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">
    <h2 class="text-primary"><b>Get Aquisition By:</b></h2>
    <br />
    
    <div class="form-row m-2">
      <div class="col">
        <form action="{{route('admin.reports.accession_reports_filter_by_date')}}" method="POST">
          @csrf
          <span class="text-primary"><b>Date:</b></span>
          <input type="submit" class="btn btn-sm btn-primary my-1 p-auto" value="Go" />
          <input name="date_type" value="by_date" style="display: none;"/>
          <calendar name="calendar"></calendar>
        </form> 
      </div>
    </div>

  </div>
  -->
  <script type="application/javascript">
  
    function search_form(){

      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/'+ input_search );
      
    }

    function calendar_form(field){

      var date_search = $('#' + field).val();
      var form_action_value = $('#calendar_form').attr('action'); 
      
      $('#calendar_form').attr('action', form_action_value + '/calendar_type/'+ field + '/search_date/' + date_search);

    }

    function calendar_form_start_end(){

      var date_search_start = $('#start').val();
      var date_search_end = $('#end').val();

      var form_action_value = $('#calendar_form_start_end').attr('action'); 

      $('#calendar_form_start_end').attr('action', form_action_value + '/start_date/'+ date_search_start +'/end_date/' + date_search_end);

    }

    var title = "Book Aquisitions Reports";
    
    var libra_e_logo = "{{asset('storage/images/bg/libra_e_icon.png')}}";
    var sti_munoz_logo = "{{asset('storage/images/bg/sti_munoz_logo.png')}}";

    var admin_search = "{{$admin_search}}";
        
  </script>

  <script src="{{ asset('js/printThis.js') }}" type="application/javascript" defer></script>
      
  <script src="{{ asset('js/prints/print_reports.js') }}" type="application/javascript" defer></script>

@endsection 
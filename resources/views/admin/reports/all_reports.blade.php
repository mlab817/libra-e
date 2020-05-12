@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'reports']);     
    session(['sidebar_nav_lev_2' => $reports_info_data['sidebar_nav_lev_2']]); 
    session(['point_arrow' => $report_type . '_reports']);
    
    $admin_search = session()->get('admin_search');
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>{{$reports_info_data['title']}} Reports</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.reports.search_reports_url')}}" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-2 my-1">
          <label class="sr-only" for="search">Search</label>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search">
        </div>

        <div class="col-auto my-1">
          <button type="submit" onclick="search_form()" class="btn btn-sm btn-primary m-1 font-weight-bold">
            Search&nbsp;<i class="fas fa-search"></i>
          </button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.reports.all_reports') . '/' . $report_type}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has($report_type . '_reports_per_page')) {{session()->get($report_type . '_reports_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.reports.reports_per_page_url') . '/report_type/' . $report_type . '/per_page/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.reports.reports_per_page_url') . '/report_type/' . $report_type . '/per_page/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.reports.reports_per_page_url') . '/report_type/' . $report_type . '/per_page/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.reports.reports_per_page_url') . '/report_type/' . $report_type . '/per_page/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.reports.reports_per_page_url') . '/report_type/' . $report_type . '/per_page/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.reports.reports_per_page_url') . '/report_type/' . $report_type . '/per_page/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.reports.reports_per_page_url') . '/report_type/' . $report_type . '/per_page/' . 500}}">500</a>
            </div>
          </div>
        </div>
        
        @if ($report_type != 'attendance')
          <div class="col-auto my-1">
            <div class="dropdown">
              <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ToOrder:
                @if (session()->has($report_type . '_reports_toOrder')) 
                  @php $array_counter = 0; @endphp
                  @foreach ($reports_info_data['all_toOrder'] as $toOrder)
                    @if (session()->get($report_type . '_reports_toOrder') == $toOrder)
                      {{$reports_info_data['all_toOrder_name'][$array_counter]}}
                    @endif
                    @php $array_counter++; @endphp
                  @endforeach
                @endif
              </button>
              <div class="dropdown-menu" aria-labelledby="per_page_btn">
                @php $array_counter = 0; @endphp
                  @foreach ($reports_info_data['all_toOrder'] as $toOrder)
                    <a class="dropdown-item" href="{{route('admin.reports.reports_toOrder_url') . '/report_type/' . $report_type . '/toOrder/' . $toOrder}}">{{$reports_info_data['all_toOrder_name'][$array_counter]}}</a>
                  @php $array_counter++; @endphp
                @endforeach
              </div>
            </div>
          </div>

          <div class="col-auto my-1">
            <div class="dropdown">
              <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                OrderBy: 
                @if (session()->has($report_type . '_reports_orderBy')) 
                  @if (session()->get($report_type . '_reports_orderBy') == 'asc')
                    Asc
                  @elseif (session()->get($report_type . '_reports_orderBy') == 'desc')
                    Desc 
                  @endif
                @endif
              </button>
              <div class="dropdown-menu" aria-labelledby="per_page_btn">
                <a class="dropdown-item" href="{{route('admin.reports.reports_orderBy_url') . '/report_type/' . $report_type . '/orderBy/' . 'asc'}}">Ascending</a>
                <a class="dropdown-item" href="{{route('admin.reports.reports_orderBy_url') . '/report_type/' . $report_type . '/orderBy/' . 'desc'}}">Descending</a>
              </div>
            </div>
          </div>
        @endif

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetAll: 
              @if (session()->has($report_type . '_reports_getAll')) 
                @php $array_counter = 0; @endphp
                @foreach ($reports_info_data['all_filter'] as $filter)
                  @if (session()->get($report_type . '_reports_getAll') == $filter)
                    {{$reports_info_data['all_filter_name'][$array_counter]}}
                  @endif
                  @php $array_counter++; @endphp
                @endforeach
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              @php $array_counter = 0; @endphp
                @foreach ($reports_info_data['all_filter'] as $filter)
                  <a class="dropdown-item" href="{{route('admin.reports.filter_reports_url') . '/report_type/' . $report_type . '/filter/' . $filter}}">{{$reports_info_data['all_filter_name'][$array_counter]}}</a>
                @php $array_counter++; @endphp
              @endforeach
            </div>
          </div>
        </div>
        
        @if ($report_type == 'attendance' || $report_type == 'all_borrowed')
          <div class="col-auto my-1">
            <div class="dropdown">
              <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                GetUser: 
                @if (session()->has($report_type . '_reports_get_user_type')) 
                  @php $array_counter = 0; @endphp
                  @foreach ($reports_info_data['all_user_types'] as $user_type)
                    @if (session()->get($report_type . '_reports_get_user_type') == $user_type)
                      {{$reports_info_data['all_user_type_name'][$array_counter]}}
                      @break
                    @endif
                    @php $array_counter++; @endphp
                  @endforeach
                @endif
              </button>
              <div class="dropdown-menu" aria-labelledby="per_page_btn">
                @php $array_counter = 0; @endphp
                  @foreach ($reports_info_data['all_user_types'] as $user_type)
                    <a class="dropdown-item" href="{{route('admin.reports.filter_user_type_reports_url') . '/report_type/' . $report_type . '/user_type/' . $user_type}}">{{$reports_info_data['all_user_type_name'][$array_counter]}}</a>
                  @php $array_counter++; @endphp
                @endforeach
              </div>
            </div>
          </div>
        @endif

        @if ($report_type == 'egames')
          <div class="col-auto my-1">
            <div class="dropdown">
              <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                GetType: 
                @if (session()->has($report_type . '_reports_getAll_pc_type')) 
                  @if (session()->get($report_type . '_reports_getAll_pc_type') == 'all')
                    All
                  @elseif (session()->get($report_type . '_reports_getAll_pc_type') == 1)
                    E-games
                  @elseif (session()->get($report_type . '_reports_getAll_pc_type') == 2)
                    Research
                  @endif
                @endif
              </button>
              <div class="dropdown-menu" aria-labelledby="per_page_btn">
                <a class="dropdown-item" href="{{route('admin.reports.filter_pc_type_reports_url') . '/report_type/'.$report_type.'/filter/' . 'all'}}">Get All</a>
                <a class="dropdown-item" href="{{route('admin.reports.filter_pc_type_reports_url') . '/report_type/'.$report_type.'/filter/' . 1}}">E-games</a>
                <a class="dropdown-item" href="{{route('admin.reports.filter_pc_type_reports_url')  . '/report_type/'.$report_type.'/filter/' . 2}}">Research</a>
              </div>
            </div>
          </div>
        @endif
        
        <div class="col-auto my-1">
          <button id="print" type="button" onclick="print_report()" class="btn btn-sm btn-secondary font-weight-bold">Print <i class="fas fa-print"></i></button>
        </div>
      </div>
    </form>

    @php
      $search_date_on = false;

      if(session()->has($report_type . '_reports_calendar_type')){
        
        $search_date_on = true;
        
        $calendar_type = session()->pull($report_type . '_reports_calendar_type');
        $search_date = session()->pull($report_type . '_reports_search_date');
        
        if($calendar_type == 'start_end'){
          
          $start = session()->pull($report_type . '_reports_search_date_start');
          $end = session()->pull($report_type . '_reports_search_date_end');

        }
      }
    @endphp

    <form id="calendar_form" action="{{route('admin.reports.reports_filter_url')}}" method ="get">
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

    <form id="calendar_form_start_end" action="{{route('admin.reports.reports_filter_start_end_date_url')}}" method ="get">  
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
        @foreach ($all_count_data as $all_count)
          <div class="col-auto my-1 mx-2">
            <span class="text-primary"><b>@if ($all_count['name'] == 'All') Total No. @endif<span class="{{$all_count['color']}}">{{$all_count['name']}}:</span>&nbsp;</b></span>
            <span class="text-secondary"><b>{{$all_count['count']}}</b></span>
          </div>
        @endforeach
      </div>
  
      <table class="table table-hover">
        <thead>
          <tr>
            @foreach ($reports_info_data['all_table_columns'] as $th_name)
              <th scope="col">{{$th_name}}</th>
            @endforeach
          </tr>
        </thead>
        <tbody class="text-secondary">
          @if( session('error_status') )
            <tr>
              <td colspan="3">{{session()->pull('error_status')}}</td>
            </tr>
          @else
            @foreach ($reports as $report)
              @if ($report_type == 'attendance')
                <tr>
                  <td>
                    @foreach ($reports_info_data['all_users']['users'] as $user)
                      @if ($report->user_id == $user->id)
                        @if ($user->user_type == 1)
                          @foreach ($reports_info_data['all_users']['students'] as $student)
                            @php
                              $m_name = $student->m_name;
                              $m_student_initials = $m_name[0];  
                            @endphp
                            @if ($user->user_ref_id == $student->id)
                              <b>Student:</b><br>
                              {{$student->f_name}}&nbsp;{{$m_student_initials}}.&nbsp;{{$student->l_name}}
                              <br>
                              <b>Email:</b><br>
                              {{$student->email_add}}
                            @endif
                          @endforeach
                        @elseif ($user->user_type == 2)
                          @foreach ($reports_info_data['all_users']['staff_coach'] as $staff_coach)
                            @php
                              $m_name = $staff_coach->m_name;
                              $m_coach_initials = $m_name[0];  
                            @endphp
                            @if ($user->user_ref_id == $staff_coach->id)
                              <b>Staff/Coach:</b><br>
                              {{$staff_coach->f_name}}&nbsp;{{$m_coach_initials}}.&nbsp;{{$staff_coach->l_name}}
                              <br>
                              <b>Email:</b><br>
                              {{$staff_coach->email_add}}
                            @endif
                          @endforeach
                        @endif
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @if ($report->room_ref_no == 1)
                      Attendance Hall
                    @elseif ($report->room_ref_no == 2)
                      Cozy Room
                    @elseif ($report->room_ref_no == 3)
                      Reading Area
                    @elseif ($report->room_ref_no == 4)
                      E-Games/Research Room
                    @endif
                  </td>
                  <td>
                    @if ($report->user_type == 1)
                      <span class="text-info font-weight-bold">SeniorHigh</span>
                    @elseif ($report->user_type == 2)
                      <span class="text-primary font-weight-bold">Tertiary</span>
                    @elseif ($report->user_type == 3)
                      <span class="text-secondary font-weight-bold">Staff/Coach</span>
                    @endif
                  </td>
                  <td>
                    {{date('F jS, Y h:i:s a', strtotime($report->created_at))}}
                  </td>
                </tr>
              @elseif ($report_type == 'borrowed_books')
                <tr>
                  <td><b>{{$report->transaction_no}}</b></td>
                  <td>
                    @if ($report->pic_url == null)
                      <img src="{{asset('storage/images/accession_images/noimage.png')}}" width="150" height="150" alt="{{$report->book_title}}" class="img-thumbnail">
                    @else
                      <img src="{{asset('storage/images/accession_images/' . $report->pic_url)}}" width="150" height="150" alt="{{$report->book_title}}" class="img-thumbnail img-fluid">
                    @endif
                  </td>
                  <td>
                    {{$report->book_title}}
                  </td>
                  <td>
                    {{$report->author_name}}
                  </td>
                  <td>
                    {{$report->accession_no}}
                  </td>
                  <td>
                    @foreach ($reports_info_data['all_users']['users'] as $user)
                      @if ($report->user_id == $user->id)
                        @if ($user->user_type == 1)
                          @foreach ($reports_info_data['all_users']['students'] as $student)
                            @php
                              $m_name = $student->m_name;
                              $m_student_initials = $m_name[0];  
                            @endphp
                            @if ($user->user_ref_id == $student->id)
                              <b>Student:</b><br>
                              {{$student->f_name}}&nbsp;{{$m_student_initials}}.&nbsp;{{$student->l_name}}
                              <br>
                              <b>Email:</b><br>
                              {{$student->email_add}}
                            @endif
                          @endforeach
                        @elseif ($user->user_type == 2)
                          @foreach ($reports_info_data['all_users']['staff_coach'] as $staff_coach)
                            @php
                              $m_name = $staff_coach->m_name;
                              $m_coach_initials = $m_name[0];  
                            @endphp
                            @if ($user->user_ref_id == $staff_coach->id)
                              <b>Staff/Coach:</b><br>
                              {{$staff_coach->f_name}}&nbsp;{{$m_coach_initials}}.&nbsp;{{$staff_coach->l_name}}
                            @endif
                          @endforeach
                        @endif
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @if ($report->status == 1)
                      <b>Date to be Reserved:</b><br>
                      {{date('F jS, Y', strtotime($report->due_date))}}
                    @elseif ($report->status == 2)
                      <b>Date Reserved:</b><br>
                      {{date('F jS, Y', strtotime($report->start_date))}}
                      <br>
                      <b>Date to be Returned:</b><br>
                      {{date('F jS, Y', strtotime($report->due_date))}}
                    @elseif ($report->status == 3)
                      <b>Date Borrowed:</b><br>
                      {{date('F jS, Y', strtotime($report->start_date))}}
                      <br>
                      <b>Date to be Returned:</b><br>
                      {{date('F jS, Y', strtotime($report->due_date))}}
                    @elseif ($report->status == 4)
                      <b>Date Borrowed:</b><br>
                      {{date('F jS, Y', strtotime($report->start_date))}}
                      <br>
                      <b>Date to be Returned:</b><br>
                      {{date('F jS, Y', strtotime($report->due_date))}}
                      <br>
                      <b>Date user Returned:</b><br>
                      {{date('F jS, Y', strtotime($report->return_date))}}
                    @elseif ($report->status == 5)
                      <b>Date user Returned:</b><br>
                      {{date('F jS, Y', strtotime($report->start_date))}}
                      <br>
                      <b>Date to be Returned:</b><br>
                      {{date('F jS, Y', strtotime($report->due_date))}}
                    @elseif ($report->status == 8)
                      <b>Date Denied:</b><br>
                      {{date('F jS, Y', strtotime($report->start_date))}}
                      <br>
                      <b>Date to be Reserved:</b><br>
                      {{date('F jS, Y', strtotime($report->due_date))}}
                    @elseif ($report->status == 9)
                      <b>Date Cancelled:</b><br>
                      {{date('F jS, Y', strtotime($report->start_date))}}
                      <br>
                      <b>Date to be Reserved:</b><br>
                      {{date('F jS, Y', strtotime($report->due_date))}}
                    @elseif ($report->status == 10)
                      <b>Date Borrowed:</b><br>
                      {{date('F jS, Y', strtotime($report->start_date))}}
                      <br>
                      <b>Date to be Returned:</b><br>
                      {{date('F jS, Y', strtotime($report->due_date))}}
                    @endif
                  </td>
                  <td>
                    @if ($report->status == 1)
                      <span class="text-warning"><b>Pending request</b></span>
                    @elseif ($report->status == 2)
                      <span class="text-info"><b>Approved</b></span>
                      @if ($report->start_date <= date('Y-m-d H:i:s') && $report->due_date >= date('Y-m-d H:i:s'))
                        @if ($report->availability == 2)
                          <br>
                          <span class="text-secondary mt-2">
                            <b>Notes:</b><br>
                            <span class="text-danger">
                              <b>
                                The book is not yet returned from the last user who borrowed it!
                                <br>
                                Cannot proceed on claiming the book!
                              </b>
                            </span>
                          </span>
                        @endif
                      @endif
                    @elseif ($report->status == 3)
                      <span class="text-primary"><b>Borrowed</b></span>
                    @elseif ($report->status == 4)
                      <span class="text-success"><b>Returned</b></span>
                    @elseif ($report->status == 5)
                      <span class="text-danger"><b>Damage/Lost</b></span>
                    @elseif ($report->status == 8)
                      <span class="text-danger"><b>Denied</b></span><br>
                      <span class="text-secondary"><b>Notes:</b>&nbsp;
                        {{$report->notes}}
                      </span>
                    @elseif ($report->status == 9)
                      <span class="text-danger"><b>Cancelled</b></span>
                    @elseif ($report->status == 10)
                      <span class="text-danger"><b>Overdue</b></span><br/>
                      <span class="text-secondary"><b>Notes:</b>&nbsp;
                        {{$report->notes}}
                      </span>
                    @elseif ($report->status == 11)
                      <span class="text-danger"><b>Returned & Overdue</b></span><br/>
                      <span class="text-secondary"><b>Notes:</b>&nbsp;
                        {{$report->notes}}
                      </span>
                    @endif
                  </td>
                  <td>
                    {{date('F jS, Y', strtotime($report->created_at))}}
                  </td>
                </tr>
              @elseif ($report_type == 'all_borrowed')
                <tr>
                  <td>
                    @if ($report->classification_name == 'Unassigned')
                      <span class="text-warning font-weight-bold">{{$report->classification_name}}</span>    
                    @else
                      {{$report->classification_name}}
                    @endif
                  </td>
                  <td>
                    @if ($report->book_type == 1)
                      <span class="text-primary font-weight-bold">Book</span>
                    @elseif ($report->book_type == 2)
                      <span class="text-info font-weight-bold">Thesis</span>
                    @endif
                  </td>
                  <td>{{$report->book_count}}</td>
                  <td>{{$report->book_title}}</td>
                  <td>
                    @if ($report->user_type == 1)
                      <span class="text-primary font-weight-bold">Student</span>
                    @elseif ($report->user_type == 2)
                      <span class="text-info font-weight-bold">Staff/Coach</span>
                    @endif
                  </td>
                </tr>
              @elseif ($report_type == 'egames')
                <tr>
                  <td><b>{{$report->pc_name}}</b></td>
                  <td>
                    @if ($report->pc_type == 1)
                      <span class="text-primary font-weight-bold">E-games</span>
                    @elseif ($report->pc_type == 2)
                      <span class="text-info font-weight-bold">Research</span>
                    @endif
                  </td>
                  <td>
                    <span class="h5">{{$report->f_name}}</span>&nbsp;
                    @php
                      $m_name = $report->m_name;
                      $m_intitals = $m_name[0];  
                    @endphp
                    <span class="h5">
                      {{$m_intitals}}.&nbsp;
                      {{$report->l_name}}
                    </span>
                    <br>
                    <span class="font-weight-bold"><b>Email:</b></span>&nbsp;
                    {{$report->email_add}}
                  </td>
                  <td>{{date('F jS, Y', strtotime($report->reserve_date))}}</td>
                  <td>
                    @php
                      $start_time = strtotime($report->reserve_time_slot);
                      $start_time = date('h:i:s a', $start_time);
                      $end_time = strtotime("+".$reports_info_data['egames_per_session']." minutes", strtotime($report->reserve_time_slot));
                      $end_time = date('h:i:s a', $end_time);
                    @endphp
                    {{$start_time . ' - ' . $end_time}}
                  </td>
                  <td>
                    @if ($report->status == 1)
                      <span class="text-warning font-weight-bold">Pending</span>
                    @elseif ($report->status == 2)
                      <span class="text-primary font-weight-bold">Reserved</span>
                    @elseif ($report->status == 3)
                      <span class="text-success font-weight-bold">Playing</span>
                    @elseif ($report->status == 4)
                      <span class="text-info font-weight-bold">Finished</span>
                    @elseif ($report->status == 8)
                      <span class="text-danger font-weight-bold">Denied</span><br>
                      <span class="text-secondary font-weight-bold">Notes: </span><br>
                      {{$report->notes}}
                    @elseif ($report->status == 9)
                      <span class="text-danger font-weight-bold">Cancelled</span>
                    @endif
                  </td>
                  <td>
                    {{date('F jS, Y', strtotime($report->created_at))}}
                  </td>
                </tr>    
              @elseif ($report_type == 'rooms')
                <tr>
                  <td><b>{{$report->topic_description}}</b></td>
                  <td>
                    @php $all_porposes = json_decode($report->purpose); @endphp
                    @foreach ($all_porposes as $porpose)
                      {{$porpose}},&nbsp;
                    @endforeach
                  </td>
                  <td>
                    @foreach ($reports_info_data['all_users']['users'] as $user)
                      @if ($report->user_id == $user->id)
                        @if ($user->user_type == 1)
                          @foreach ($reports_info_data['all_users']['students'] as $student)
                            @php
                              $m_name = $student->m_name;
                              $m_student_initials = $m_name[0];  
                            @endphp
                            @if ($user->user_ref_id == $student->id)
                              <b>Student:</b><br>
                              {{$student->f_name}}&nbsp;{{$m_student_initials}}.&nbsp;{{$student->l_name}}
                              <br>
                              <b>Email:</b><br>
                              {{$student->email_add}}
                            @endif
                          @endforeach
                        @elseif ($user->user_type == 2)
                          @foreach ($reports_info_data['all_users']['staff_coach'] as $staff_coach)
                            @php
                              $m_name = $staff_coach->m_name;
                              $m_coach_initials = $m_name[0];  
                            @endphp
                            @if ($user->user_ref_id == $staff_coach->id)
                              <b>Staff/Coach:</b><br>
                              {{$staff_coach->f_name}}&nbsp;{{$m_coach_initials}}.&nbsp;{{$staff_coach->l_name}}
                            @endif
                          @endforeach
                        @endif
                      @endif
                    @endforeach
                  </td>
                  <td>{{date('F jS, Y', strtotime($report->reserve_date))}}</td>
                  <td>
                    @php
                      $start_time = date('h:i:s a', strtotime($report->reserve_time_start));
                      $end_time = date('h:i:s a', strtotime($report->reserve_time_end));
                    @endphp
                    {{$start_time . ' - ' . $end_time}}
                  </td>
                  <td>
                    @if ($report->status == 1)
                      <span class="text-warning font-weight-bold">Pending</span>
                    @elseif ($report->status == 2)
                      <span class="text-primary font-weight-bold">Reserved</span>
                    @elseif ($report->status == 3)
                      <span class="text-success font-weight-bold">On Use</span>
                    @elseif ($report->status == 4)
                      <span class="text-info font-weight-bold">Finished</span>
                    @elseif ($report->status == 8)
                      <span class="text-danger font-weight-bold">Denied</span><br>
                      <span class="text-secondary font-weight-bold">Notes: </span><br>
                      {{$report->notes}}
                    @elseif ($report->status == 9)
                      <span class="text-danger font-weight-bold">Cancelled</span>
                    @endif
                  </td>
                  <td>
                    {{date('F jS, Y', strtotime($report->created_at))}}
                  </td>
                </tr>
              @elseif ($report_type == 'accountabilities')
                
              @endif
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
    
     {{$reports->links()}}
     
  </div>

  <input type="text" name="report_type" id="report_type" value="{{$report_type}}" style="display:none;" />

  <script type="application/javascript">
  
    function search_form(){

      var report_type = $('#report_type').val();
      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/report_type/' + report_type + '/search/' + input_search );
      
    }

    function calendar_form(field){

      var report_type = $('#report_type').val();
      var date_search = $('#' + field).val();
      var form_action_value = $('#calendar_form').attr('action'); 
      
      $('#calendar_form').attr('action', form_action_value + '/report_type/' + report_type + '/calendar_type/'+ field + '/search_date/' + date_search);

    }

    function calendar_form_start_end(){

      var report_type = $('#report_type').val();
      var date_search_start = $('#start').val();
      var date_search_end = $('#end').val();

      var form_action_value = $('#calendar_form_start_end').attr('action'); 

      $('#calendar_form_start_end').attr('action', form_action_value + '/report_type/' + report_type + '/start_date/'+ date_search_start +'/end_date/' + date_search_end);

    }

    var title = "{{$reports_info_data['title']}} Reports";
    
    var libra_e_logo = "{{asset('storage/images/bg/libra_e_icon.png')}}";
    var sti_munoz_logo = "{{asset('storage/images/bg/sti_munoz_logo.png')}}";

    var admin_search = "{{$admin_search}}";
    
  </script>

  <script src="{{ asset('js/printThis.js') }}" type="application/javascript" defer></script>
    
  <script src="{{ asset('js/prints/print_reports.js') }}" type="application/javascript" defer></script>

@endsection 
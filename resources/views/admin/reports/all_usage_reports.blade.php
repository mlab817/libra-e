@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'reports']);     
    session(['sidebar_nav_lev_2' => $usage_reports_info_data['sidebar_nav_lev_2']]); 
    session(['point_arrow' => $report_type . '_reports']);

    $admin_search = session()->get('admin_search');
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>{{$usage_reports_info_data['title']}} Reports</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <!--
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
      </div>
    </form>
    -->

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

    <div class="form-row mx-3 mb-3 mt-2">
      <div class="col-auto">
        <a href="{{route('admin.reports.all_usage_reports') . '/' . $report_type}}">
          <button type="button" class="btn btn-sm btn-primary font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
        </a>
      </div>

      <div class="col-auto">
        <button id="print" onclick="print_report()" class="btn btn-sm btn-secondary font-weight-bold">Print <i class="fas fa-print"></i></button>
      </div>
    </div>

    <div id="reports_print">
      <table class="table table-hover table-bordered">
        <thead>
          @if ($report_type == 'attendance_usage')
            <tr class="text-center text-secondary">
              <th class="align-middle h2 font-weight-bold" rowspan="2">Date</th>    
                @foreach ($usage_reports_info_data['all_table_columns'] as $th_name)
                  @if ($th_name == 'E-games/Research Room')
                    <th colspan="2" scope="col">{{$th_name}}</th>
                  @else
                    <th colspan="3" scope="col">{{$th_name}}</th>
                  @endif
                @endforeach
              <th class="align-middle h2 font-weight-bold" rowspan="2">Total</th>    
            </tr>
            <tr class="text-center">
              @for ($i = 1; $i <= 4; $i++)
                <th scope="col">SHS</th>
                <th scope="col">Tertiary</th>
                @if ($i != 4)
                  <th scope="col">Staff/Coach</th>
                @endif
              @endfor
            </tr>
          @else
            <tr>
            @foreach ($usage_reports_info_data['all_table_columns'] as $th_name)
              <th scope="col">{{$th_name}}</th>
            @endforeach
          </tr>
          @endif
        </thead>
        <tbody class="text-secondary text-center">
          @if( session('error_status') )
            <tr>
              <td colspan="12">{{session()->pull('error_status')}}</td>
            </tr>
          @else
            @foreach ($usage_reports as $usage)
              @if ($report_type == 'attendance_usage')
                <tr>
                  <td>{{date('F jS, Y', strtotime($usage['date']))}}</td>
                  <td>{{$usage['count_shs']}}</td>
                  <td>{{$usage['count_tertiary']}}</td>
                  <td>{{$usage['count_coaches']}}</td>
                  <td>{{$usage['count_shs_room_2']}}</td>
                  <td>{{$usage['count_tertiary_room_2']}}</td>
                  <td>{{$usage['count_coaches_room_2']}}</td>
                  <td>{{$usage['count_shs_room_3']}}</td>
                  <td>{{$usage['count_tertiary_room_3']}}</td>
                  <td>{{$usage['count_coaches_room_3']}}</td>
                  <td>{{$usage['count_shs_room_4']}}</td>
                  <td>{{$usage['count_tertiary_room_4']}}</td>
                  <td>{{$usage['count_total']}}</td>
                </tr>
              @elseif ($report_type == 'egames_usage')
                <tr>
                  <td>{{date('F jS, Y', strtotime($usage['date']))}}</td>
                  <td>{{$usage['count_shs']}}</td>
                  <td>{{$usage['count_tertiary']}}</td>
                  <td>{{$usage['count_total']}}</td>
                </tr>
                @elseif ($report_type == 'rooms_usage')
                <tr>
                  <td>{{date('F jS, Y', strtotime($usage['date']))}}</td>
                  <td>{{$usage['count_shs']}}</td>
                  <td>{{$usage['count_tertiary']}}</td>
                  <td>{{$usage['count_coach']}}</td>
                  <td>{{$usage['count_total']}}</td>
                </tr>     
              @endif
            @endforeach

            @if ($report_type == 'attendance_usage')
              <tr>
                <td class="font-weight-bold">Total</td>
                <td>{{$all_usage_count_data['shs']['count']}}</td>
                <td>{{$all_usage_count_data['tertiary']['count']}}</td>
                <td>{{$all_usage_count_data['coaches']['count']}}</td>
                <td>{{$all_usage_count_data['count_shs_room_2']['count']}}</td>
                <td>{{$all_usage_count_data['count_tertiary_room_2']['count']}}</td>
                <td>{{$all_usage_count_data['count_coaches_room_2']['count']}}</td>
                <td>{{$all_usage_count_data['count_shs_room_3']['count']}}</td>
                <td>{{$all_usage_count_data['count_tertiary_room_3']['count']}}</td>
                <td>{{$all_usage_count_data['count_coaches_room_3']['count']}}</td>
                <td>{{$all_usage_count_data['count_shs_room_4']['count']}}</td>
                <td>{{$all_usage_count_data['count_tertiary_room_4']['count']}}</td>
                <td></td>
              </tr>
              <tr>
                <td class="font-weight-bold">Overall</td>
                <td colspan="3">{{$all_usage_count_data['count_total_room_1']['count']}}</td>
                <td colspan="3">{{$all_usage_count_data['count_total_room_2']['count']}}</td>
                <td colspan="3">{{$all_usage_count_data['count_total_room_3']['count']}}</td>
                <td colspan="2">{{$all_usage_count_data['count_total_room_4']['count']}}</td>
                <td>{{$all_usage_count_data['all']['count']}}</td>
              </tr>
            @endif
          @endif
        </tbody>
      </table>

      <div class="row m2 p-2">
        <div class="col-12 p-2 text-center">
          <div class="row">
            @php
              $all_count_view = ['All', 'Average Users', 'SHS', 'Average SHS', 'Tertiary', 'Average Tertiary', 'Staff/Coach', 'Average Staff/Coach',]
            @endphp
            
            @foreach ($all_usage_count_data as $all_count)
              @if (in_array($all_count['name'], $all_count_view))
                <div class="col-auto my-1 mx-2">
                  <span class="text-primary"><b>@if ($all_count['name'] == 'All') Total No. @endif<span class="{{$all_count['color']}}">{{$all_count['name']}}:</span>&nbsp;</b></span>
                  <span class="text-secondary"><b>{{$all_count['count']}}</b></span>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      </div>
      
      @if ($report_type == 'attendance_usage')
      <div id="reports_usage_charts">
        <div class="row m2 p-2">
          <div class="col-lg-6 p-2">
            <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="usage_bar_chart"></canvas>
          </div>
          
          <div class="col-lg-6 p-2">
            <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="all_users"></canvas>
          </div>
          
          <!--
          <div class="col-sm-6 col-lg-4 p-2">
            <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="myChart3"></canvas>
          </div>
          
          <div class="col-sm-6 col-lg-4 p-2">
            <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="myChart4"></canvas>
          </div>
    
          <div class="col-sm-6 col-lg-4 p-2">
            <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="myChart5"></canvas>
          </div>
          -->
        </div>
      </div>
    @endif
      
    </div>
  </div>

  <input type="text" id="api_url" value="{{route('libra_e.fetch_month_egames_usage')}}" style="display:none;" />
  
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

    /// Print Data 
    var title = "{{$usage_reports_info_data['title']}} Reports";
    
    var libra_e_logo = "{{asset('storage/images/bg/libra_e_icon.png')}}";
    var sti_munoz_logo = "{{asset('storage/images/bg/sti_munoz_logo.png')}}";

    var admin_search = "{{$admin_search}}";
        
  </script>
  
  <!-- charts js -->

  @if ($report_type == 'attendance_usage')
    <script type="application/javascript">

      var json_usage_reports = @php echo json_encode($usage_reports) @endphp;

      var json_all_usage_count_data = @php echo json_encode($all_usage_count_data) @endphp;
      
    </script>

    <script src="{{ asset('js/charts/usage_charts.js') }}" type="application/javascript" defer></script>
  @endif

  <script src="{{ asset('js/printThis.js') }}" type="application/javascript" defer></script>
  
  <script src="{{ asset('js/prints/print_reports.js') }}" type="application/javascript" defer></script>

@endsection 
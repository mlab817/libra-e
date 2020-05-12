@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'rfid']);     
    session(['sidebar_nav_lev_2' => $info_data['sidebar_nav_lev_2']]); 
    session(['point_arrow' => $type . '_rfid']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>{{$info_data['title']}} Rfid's</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.rfid.search_rfid_url')}}" id="search_form">
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
          <a href="{{route('admin.rfid.all_users') . '/' . $type}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has($type . '_rfid_per_page')) {{session()->get($type . '_rfid_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_per_page_url') . '/type/' . $type . '/per_page/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_per_page_url') . '/type/' . $type . '/per_page/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_per_page_url') . '/type/' . $type . '/per_page/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_per_page_url') . '/type/' . $type . '/per_page/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_per_page_url') . '/type/' . $type . '/per_page/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_per_page_url') . '/type/' . $type . '/per_page/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_per_page_url') . '/type/' . $type . '/per_page/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder:
              @if (session()->has($type . '_rfid_toOrder')) 
                @php $array_counter = 0; @endphp
                @foreach ($info_data['all_toOrder'] as $toOrder)
                  @if (session()->get($type . '_rfid_toOrder') == $toOrder)
                    {{$info_data['all_toOrder_name'][$array_counter]}}
                  @endif
                  @php $array_counter++; @endphp
                @endforeach
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              @php $array_counter = 0; @endphp
                @foreach ($info_data['all_toOrder'] as $toOrder)
                  <a class="dropdown-item" href="{{route('admin.rfid.rfid_toOrder_url') . '/type/' . $type . '/toOrder/' . $toOrder}}">{{$info_data['all_toOrder_name'][$array_counter]}}</a>
                @php $array_counter++; @endphp
              @endforeach
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has($type . '_rfid_orderBy')) 
                @if (session()->get($type . '_rfid_orderBy') == 'asc')
                  Asc
                @elseif (session()->get($type . '_rfid_orderBy') == 'desc')
                  Desc 
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_orderBy_url') . '/type/' . $type . '/orderBy/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.rfid.rfid_orderBy_url') . '/type/' . $type . '/orderBy/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>
        
        @if ($type == 'all_students')
          <div class="col-auto my-1">
            <div class="dropdown">
              <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                GetAll: 
                @if (session()->has($type . '_rfid_getAll')) 
                  @php $array_counter = 0; @endphp
                  @foreach ($info_data['all_filter'] as $filter)
                    @if (session()->get($type . '_rfid_getAll') == 'all')
                      All
                      @break
                    @elseif (session()->get($type . '_rfid_getAll') == $filter)
                      {{$info_data['all_filter_name'][$array_counter]}}
                    @endif
                    @php $array_counter++; @endphp
                  @endforeach
                @endif
              </button>
              <div class="dropdown-menu" aria-labelledby="per_page_btn">
                @php $array_counter = 0; @endphp
                  @foreach ($info_data['all_filter'] as $filter)
                    <a class="dropdown-item" href="{{route('admin.rfid.filter_rfid_url') . '/type/' . $type . '/filter/' . $filter}}">{{$info_data['all_filter_name'][$array_counter]}}</a>
                  @php $array_counter++; @endphp
                @endforeach
              </div>
            </div>
          </div>
        @endif
      
        <div class="col-auto my-1">
          <a href="{{route('admin.rfid.scan_rfid') . '/' . $type}}" class="btn btn-sm btn-success font-weight-bold">Add Rfid to User <i class="fas fa-plus-circle"></i></a>
        </div>    
      </div>
    </form>

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
          @foreach ($info_data['all_table_columns'] as $th_name)
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
          @foreach ($rfids as $rfid)
            @if ($type == 'all_students')
              <tr>
                <td>
                  <span class="h5"><b>Student:</b></span>&nbsp;
                  <span class="h5">{{$rfid->f_name}}</span>&nbsp;
                  @php
                    $m_name = $rfid->m_name;
                    $m_intitals = $m_name[0];  
                  @endphp
                  <span class="h5">
                    {{$m_intitals}}.&nbsp;
                    {{$rfid->l_name}}
                  </span>
                  <br>
                  <span class="h5"><b>Email:</b></span>&nbsp;
                  {{$rfid->email_add}}
                </td>
                <td>
                  @if ($rfid->type == 0)
                    <span class="text-info font-weight-bold">SeniorHigh</span>
                  @elseif ($rfid->type == 1)
                    <span class="text-primary font-weight-bold">Tertiary</span>
                  @endif
                </td>
                <td>
                  <button class="btn btn-danger font-weight-bold" onclick="delete_modal_click({{$rfid->user_id}}, '{{$type}}', '{{$rfid->f_name}}', '{{$m_intitals}}.', '{{$rfid->l_name}}')" data-toggle="modal" data-target="#change_rfid_modal">
                    Change Rfid&nbsp;<i class="fas fa-eraser"></i>
                  </button>
                </td>
              </tr>
            @elseif ($type == 'all_coaches')
              <tr>
                <td>
                  <span class="h5"><b>Staff/Coach:</b></span>&nbsp;
                  <span class="h5">{{$rfid->f_name}}</span>&nbsp;
                  @php
                    $m_name = $rfid->m_name;
                    $m_intitals = $m_name[0];  
                  @endphp
                  <span class="h5">
                    {{$m_intitals}}.&nbsp;
                    {{$rfid->l_name}}
                  </span>
                  <br>
                  <span class="h5"><b>Email:</b></span>&nbsp;
                  {{$rfid->email_add}}
                </td>
                <td>
                  <button class="btn btn-danger font-weight-bold" onclick="delete_modal_click({{$rfid->user_id}}, '{{$type}}', '{{$rfid->f_name}}', '{{$m_intitals}}.', '{{$rfid->l_name}}')" data-toggle="modal" data-target="#change_rfid_modal">
                    Change Rfid&nbsp;<i class="fas fa-eraser"></i>
                  </button>
                </td>
              </tr>
            @elseif ($type == 'all_books')
              
            @endif
          @endforeach
        @endif
      </tbody>
    </table>
     {{$rfids->links()}}
  </div>

  <!-- Modal -->
  <div class="modal fade" id="change_rfid_modal" tabindex="-1" role="dialog" aria-labelledby="change_rfid_modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="change_rfid_modalLabel">Are you sure to change user rfid?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="modal_body" class="modal-body">
        
        </div>
        <div class="modal-footer">
          <form id="change_form"  action="{{route('admin.rfid.scan_change_rfid_url')}}" method="get">
            <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary font-weight-bold">Change Rfid</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="application/javascript">
  
    function search_form(){

      var type = $('#type').val();
      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/type/' + type + '/search/' + input_search );
      
    }

    function delete_modal_click(id, type, f_name, m_name, l_name) {

      $("#modal_body").empty();
      var b_name = '<b>' + f_name + ' ' + m_name + ' ' + l_name + '</b>';
      $("#modal_body").append(b_name);

      var form_action_value = $('#change_form').attr('action'); 
      
      $('#change_form').attr('action', form_action_value + '/type/' + type + '/user_id/' + id );

    }

  </script>

@endsection 
@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'rooms']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'rooms_' . $status_type]);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>{{$title}} Rooms Reservations</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.rooms.reservation.search_all_rooms_reservations_url')}}" id="search_form">
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
          <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/' . $status_type}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has($status_type . '_rooms_per_page')) {{session()->get($status_type . '_rooms_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_per_page_url') . '/type/'.$status_type.'/per_page/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_per_page_url') . '/type/'.$status_type.'/per_page/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_per_page_url') . '/type/'.$status_type.'/per_page/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_per_page_url') . '/type/'.$status_type.'/per_page/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_per_page_url') . '/type/'.$status_type.'/per_page/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_per_page_url') . '/type/'.$status_type.'/per_page/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_per_page_url') . '/type/'.$status_type.'/per_page/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has($status_type . '_rooms_toOrder')) 
                @if (session()->get($status_type . '_rooms_toOrder') == 'topic_description')
                  Topic/Description
                @elseif (session()->get($status_type . '_rooms_toOrder') == 'purpose')
                  Purpose
                @elseif (session()->get($status_type . '_rooms_toOrder') == 'reserve_date')
                  Date
                @elseif (session()->get($status_type . '_rooms_toOrder') == 'reserve_time_start')
                  Time Slot
                @elseif (session()->get($status_type . '_rooms_toOrder') == 'updated_at')
                  Latest
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_toOrder_url') . '/type/'.$status_type.'/toOrder/' . 'topic_description'}}">Topic/Description</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_toOrder_url') . '/type/'.$status_type.'/toOrder/' . 'purpose'}}">Purpose</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_toOrder_url') . '/type/'.$status_type.'/toOrder/' . 'reserve_date'}}">Date</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_toOrder_url') . '/type/'.$status_type.'/toOrder/' . 'reserve_time_start'}}">Time Slot</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_toOrder_url') . '/type/'.$status_type.'/toOrder/' . 'updated_at'}}">Latest</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has($status_type . '_rooms_orderBy')) 
                @if (session()->get($status_type . '_rooms_orderBy') == 'asc')
                  Asc
                @elseif (session()->get($status_type . '_rooms_orderBy') == 'desc')
                  Desc 
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_orderBy_url') . '/type/'.$status_type.'/orderBy/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.rooms.reservation.rooms_orderBy_url') . '/type/'.$status_type.'/orderBy/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        @if ($status_type == 'all_transactions' || $status_type == 'all_events')
          <div class="col-auto my-1">
            <div class="dropdown">
              <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                GetAll: 
                @if (session()->has($status_type . '_rooms_getAll_status')) 
                  @if (session()->get($status_type . '_rooms_getAll_status') == 'all')
                    Get All
                  @elseif (session()->get($status_type . '_rooms_getAll_status') == 1)
                    Pending
                  @elseif (session()->get($status_type . '_rooms_getAll_status') == 2)
                    Reserved
                  @elseif (session()->get($status_type . '_rooms_getAll_status') == 3)
                    On Use
                  @elseif (session()->get($status_type . '_rooms_getAll_status') == 4)
                    Finished
                  @elseif (session()->get($status_type . '_rooms_getAll_status') == 8)
                    Denied
                  @elseif (session()->get($status_type . '_rooms_getAll_status') == 9)
                    Cancelled
                  @endif
                @endif
              </button>
              <div class="dropdown-menu" aria-labelledby="per_page_btn">
                <a class="dropdown-item" href="{{route('admin.rooms.reservation.filter_status_rooms_url') . '/type/'.$status_type.'/filter/' . 'all'}}">Get All</a>
                <a class="dropdown-item" href="{{route('admin.rooms.reservation.filter_status_rooms_url') . '/type/'.$status_type.'/filter/' . 1}}">Pending</a>
                <a class="dropdown-item" href="{{route('admin.rooms.reservation.filter_status_rooms_url')  . '/type/'.$status_type.'/filter/' . 2}}">Reserved</a>
                <a class="dropdown-item" href="{{route('admin.rooms.reservation.filter_status_rooms_url')  . '/type/'.$status_type.'/filter/' . 3}}">On Use</a>
                <a class="dropdown-item" href="{{route('admin.rooms.reservation.filter_status_rooms_url')  . '/type/'.$status_type.'/filter/' . 4}}">Finished</a>
                <a class="dropdown-item" href="{{route('admin.rooms.reservation.filter_status_rooms_url')  . '/type/'.$status_type.'/filter/' . 8}}">Denied</a>
                <a class="dropdown-item" href="{{route('admin.rooms.reservation.filter_status_rooms_url')  . '/type/'.$status_type.'/filter/' . 9}}">Cancelled</a>
              </div>
            </div>
          </div>
        @endif
      </div>
    </form>

    @if ($status_type == 'all_transactions' || $status_type == 'all_events')
      <div class="form-row m-2">
        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b>Total No. of Request: &nbsp;</b></span>
          <span class="badge badge-light p-2">{{$count['all']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-warning">Pending:</span></b></span>
          <span class="badge badge-light p-2">{{$count['pending']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-primary">Reserved:</span></b></span>
          <span class="badge badge-light p-2">{{$count['reserved']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-success">On Use:</span></b></span>
          <span class="badge badge-light p-2">{{$count['on_use']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-info">Finished:</span></b></span>
          <span class="badge badge-light p-2">{{$count['finished']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-danger">Denied:</span></b></span>
          <span class="badge badge-light p-2">{{$count['denied']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-danger">Cancelled:</span></b></span>
          <span class="badge badge-light p-2">{{$count['cancelled']}}</span>
        </div>
      </div>
    @else
      <div class="form-row m-2">
        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b>Total No. of Request: &nbsp;</b></span>
          <span class="text-secondary"><b>{{$count}}</b></span>
        </div>
      </div>
    @endif

    <table class="table table-hover text-secondary">
      <thead>
        <tr>
          <th scope="col">Topic/Description</th>
          <th scope="col">Purpose</th>
          <th scope="col">User</th>
          <th scope="col">Reserve Date</th>
          <th scope="col">Time-slot</th>
          <th scope="col">Status</th>
          @if ($status_type == 'all_events')
            <th scope="col">Created</th>   
          @else
            <th scope="col">Actions</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @if( session('error_status') )
          <tr>
            <td colspan="3">{{session()->pull('error_status')}}</td>
          </tr>
        @else
          @foreach ($reservations as $reservation)
          <tr>
            <td><b>{{$reservation->topic_description}}</b></td>
            <td>
              @php $all_porposes = json_decode($reservation->purpose); @endphp
              @foreach ($all_porposes as $porpose)
                {{$porpose}},&nbsp;
              @endforeach
            </td>
            <td>
              @foreach ($all_users['users'] as $user)
                @if ($reservation->user_id == $user->id)
                  @if ($user->user_type == 1)
                    @foreach ($all_users['students'] as $student)
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
                    @foreach ($all_users['staff_coach'] as $staff_coach)
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
            <td>{{date('F jS, Y', strtotime($reservation->reserve_date))}}</td>
            <td>
              @php
                $start_time = date('h:i:s a', strtotime($reservation->reserve_time_start));
                $end_time = date('h:i:s a', strtotime($reservation->reserve_time_end));
              @endphp
              {{$start_time . ' - ' . $end_time}}
            </td>
            <td>
              @if ($reservation->status == 1)
                <span class="text-warning font-weight-bold">Pending</span>
              @elseif ($reservation->status == 2)
                <span class="text-primary font-weight-bold">Reserved</span>
              @elseif ($reservation->status == 3)
                <span class="text-success font-weight-bold">On Use</span>
              @elseif ($reservation->status == 4)
                <span class="text-info font-weight-bold">Finished</span>
              @elseif ($reservation->status == 8)
                <span class="text-danger font-weight-bold">Denied</span><br>
                <span class="text-secondary font-weight-bold">Notes: </span><br>
                {{$reservation->notes}}
              @elseif ($reservation->status == 9)
                <span class="text-danger font-weight-bold">Cancelled</span>
              @endif
            </td>
            @if ($status_type == 'all_events')
              <td>
                {{date('F jS, Y', strtotime($reservation->created_at))}}
              </td>
            @else
              <td>
                <a href="{{route('admin.rooms.reservation.view_reservation') . '/' . $reservation->id}}" class="btn btn-success btn-sm m-1">
                  <b>View&nbsp;<i class="far fa-eye"></i></b>
                </a>
              </td>
            @endif
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$reservations->links()}}
  </div>

  <script type="application/javascript">
  
    function search_form(){
      
      var status_type = '{{$status_type}}';
      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/status_type/' + status_type + '/search/' + input_search );
      
    }

  </script>

@endsection 
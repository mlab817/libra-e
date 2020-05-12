@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => '']);     
  @endphp

  <div class="container-fluid">
    <div class="container">
      <div class="m-3 pt-4 animated fadeInDown">
        <h1 class="text-info display-5 main_h1"><b>My Reservation</b></h1>
      </div>
    </div>

    <div class="container">
      <div class="row box_form my-3">
        <div class="col-12 p-4">
          <ul class="nav justify-content-center">
            <li class="nav-item">
              <a class="nav-link text-info h4" href="{{ route('libraE.my_reservations') }}">
                <b>Books&nbsp;<i class="fas fa-book"></i></b>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-info h4" href="{{ route('libraE.my_reservations.egames') }}">
                <b>Gaming Room&nbsp;<i class="fas fa-gamepad"></i></b>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-info h4 border-bottom border-info" href="{{ route('libraE.my_reservations.rooms') }}">
                <b>Rooms/Areas&nbsp;<i class="fas fa-door-open"></i></b>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-12 my-3">
        @include('inc.status')
        @include('inc.libraE_search')
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row box_form mx-4">
      <div class="col-12 p-5">
        
        <h4 class="text-primary mb-2"><b>Gaming Room Reservations</b></h4>

        <form action="{{route('libraE.my_reservations.rooms.search_my_rooms_reservations')}}" id="search_form">
          <div class="row mt-2 mb-4 align-items-center">
            <div class="col-sm-3 my-1">
              <label class="sr-only" for="search">Search</label>
              <input type="text" name="search" id="search" class="form-control" placeholder="Search">
            </div>
    
            <div class="col-auto">
              <button onclick="search_form()" type="submit" class="btn btn-sm btn-primary m-1 font-weight-bold">
                Search&nbsp;<i class="fas fa-search"></i>
              </button>
            </div>
          

            <div class="col-auto my-1">
              <a href="{{route('libraE.my_reservations.rooms')}}">
                <button type="button" class="btn btn-sm btn-primary font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
              </a>
            </div>
    
            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Per page: @if (session()->has('my_rooms_per_page')) {{session()->get('my_rooms_per_page', 'default')}} @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_per_page') . '/' . 5}}">5</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_per_page') . '/' . 10}}">10</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_per_page') . '/' . 20}}">20</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_per_page') . '/' . 50}}">50</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_per_page') . '/' . 100}}">100</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_per_page') . '/' . 200}}">200</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_per_page') . '/' . 500}}">500</a>
                </div>
              </div>
            </div>

            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  ToOrder: 
                  @if (session()->has('my_rooms_toOrder')) 
                    @if (session()->get('my_rooms_toOrder') == 'topic_description')
                      Topic/Description
                    @elseif (session()->get('my_rooms_toOrder') == 'purpose')
                      Purpose
                    @elseif (session()->get('my_rooms_toOrder') == 'reserve_date')
                      Date
                    @elseif (session()->get('my_rooms_toOrder') == 'reserve_time_start')
                      Time Slot
                    @elseif (session()->get('my_rooms_toOrder') == 'updated_at')
                      Latest
                    @endif
                  @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_toOrder') . '/' . 'topic_description'}}">Topic/Description</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_toOrder') . '/' . 'purpose'}}">Purpose</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_toOrder') . '/' . 'reserve_date'}}">Date</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_toOrder') . '/' . 'reserve_time_start'}}">Time Slot</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_toOrder') . '/' . 'updated_at'}}">Latest</a>
                </div>
              </div>
            </div>
            
            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  OrderBy: 
                  @if (session()->has('my_rooms_orderBy')) 
                    @if (session()->get('my_rooms_orderBy') == 'asc')
                      Asc
                    @elseif (session()->get('my_rooms_orderBy') == 'desc')
                      Desc
                    @endif
                  @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_orderBy') . '/' . 'asc'}}">Ascending</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.my_rooms_orderBy') . '/' . 'desc'}}">Descending</a>
                </div>
              </div>
            </div>
            
            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  GetAll: 
                  @if (session()->has('my_rooms_getAll_status')) 
                    @if (session()->get('my_rooms_getAll_status') == 'all')
                      All
                    @elseif (session()->get('my_rooms_getAll_status') == 1)
                      Pending
                    @elseif (session()->get('my_rooms_getAll_status') == 2)
                      Reserved
                    @elseif (session()->get('my_rooms_getAll_status') == 3)
                      On Use
                    @elseif (session()->get('my_rooms_getAll_status') == 4)
                      Finished
                    @elseif (session()->get('my_rooms_getAll_status') == 8)
                      Denied
                    @elseif (session()->get('my_rooms_getAll_status') == 9)
                      Cancelled
                    @endif
                  @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.filter_my_rooms_reservations') . '/' . 'all'}}">Get All</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.filter_my_rooms_reservations') . '/' . 1}}">Pending</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.filter_my_rooms_reservations')  . '/' . 2}}">Reserved</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.filter_my_rooms_reservations')  . '/' . 3}}">On Use</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.filter_my_rooms_reservations')  . '/' . 4}}">Finished</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.filter_my_rooms_reservations')  . '/' . 8}}">Denied</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.rooms.filter_my_rooms_reservations')  . '/' . 9}}">Cancelled</a>
                </div>
              </div>
            </div>
          </div>
        </form>
        
        <table class="table table-hover text-secondary">
          <thead>
            <tr>
              <th scope="col">Topic/Description</th>
              <th scope="col">Purpose</th>
              <th scope="col">Reserve Date</th>
              <th scope="col">Time-slot</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            @if( session('error_status') )
              <tr>
                <td colspan="3">{{session()->pull('error_status')}}</td>
              </tr>
            @else
              @foreach ($my_rooms_reservations as $reservation)
                <tr>
                  <td class="font-weight-bold">{{$reservation->topic_description}}</td>
                  <td>
                    @php $all_porposes = json_decode($reservation->purpose); @endphp
                    @foreach ($all_porposes as $porpose)
                      {{$porpose}},&nbsp;
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
                      <span class="text-danger font-weight-bold">Denied</span>
                    @elseif ($reservation->status == 9)
                      <span class="text-danger font-weight-bold">Cancelled</span>
                    @endif
                  </td>
                  <td>
                    @if ($reservation->status == 1)
                      <button onclick="delete_modal_click({{$reservation->id}} , '{{$reservation->pc_name}}', '{{date('F jS, Y', strtotime($reservation->reserve_date))}}' ,'{{$start_time . ' - ' . $end_time}}')" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                        <b>Delete&nbsp;<i class="fas fa-trash-alt"></i></b>
                      </button>
                    @elseif ($reservation->status == 2)
                      <button onclick="delete_modal_click({{$reservation->id}} , '{{$reservation->pc_name}}', '{{date('F jS, Y', strtotime($reservation->reserve_date))}}' ,'{{$start_time . ' - ' . $end_time}}')" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                        <b>Cancel&nbsp;<i class="fas fa-trash-alt"></i></b>
                      </button>
                    @elseif ($reservation->status == 8)
                      <span class="text-secondary font-weight-bold">Notes: </span><br>
                      {{$reservation->notes}}
                    @elseif ($reservation->status == 9)
                      <span class="text-danger font-weight-bold">Cancelled</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
        {{$my_rooms_reservations->links()}}
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Are you sure to Cancel/Delete?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="modal_body">
            
          </div>
          <div class="modal-footer">
            <form method="POST" id="delete_form">
              @method('DELETE')
              @csrf
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  <input type="text" id="form_delete_url" value="{{route('libraE.my_reservations.rooms.delete_my_rooms_reservations')}}" style="display: none" />

  <script type="application/javascript">
  
    function search_form(){

      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 

      $('#search_form').attr('action', form_action_value + '/'+ input_search );

    }
  
    function delete_modal_click(id, pc_name, reserve_time, time_slot) {

      $("#modal_body").empty();
      var info = '<b>Reserve time:</b> '+ pc_name + ' : ' + reserve_time + ' : ' + time_slot;
      $("#modal_body").append(info);
      
      var form_action_value = $('#form_delete_url').val(); 

      $('#delete_form').attr('action', form_action_value + '/' + id );

    }

  </script>
@endsection
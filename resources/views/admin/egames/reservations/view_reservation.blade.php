@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'egames']);     
    session(['sidebar_nav_lev_2' => 'egames_reservations_ul']); 
    session(['point_arrow' => 'egames_' . $reservation_data['point_arrow']]);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>View E-games Reservation</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">
    <div class="col-12">
      <div class="row">
        <div class="col-lg-4">

          <div class="form-row">
            <div class="form-group col-12 p-1">
              <h3 class="text-primary"><b>Egames Reservation</b></h3>
            </div>
          </div>

          <div class="row">
            <div class="col-12 text-secondary p-2">
              @php
                $m_name = $reservation->m_name;
                $m_initials = $m_name[0];  
              @endphp
              <h5><b>Reserved By:&nbsp;</b>
                {{$reservation->f_name}}&nbsp;{{$m_initials}}.&nbsp;{{$reservation->l_name}}&nbsp;
                (Student)
              </h5>
              
              <h5><b>Email:&nbsp;</b>
                {{$reservation->email_add}}
              </h5>
              
              <h5><b>Phone No:&nbsp;</b>
                +63-{{$reservation->contact_no}}
              </h5>
            </div>

            <div class="col-12 text-secondary p-2">
              <h5><b>
                Status:&nbsp;
                <span class="{{$reservation_data['color_class']}}">
                  @if ($reservation->status == 5)
                    Damage/Lost  
                  @elseif ($reservation->status == 11)
                    Returned & Overdue  
                  @else
                    {{ucfirst($reservation_data['point_arrow'])}}
                  @endif
                </span>
              </b></h5>
              
              @if ($reservation->status == 8 || $reservation->status == 10 || $reservation->status == 11)
                <h5><b>Notes:&nbsp;</b>
                  <span class="text-secondary">{{$reservation->notes}}</span>
                </h5>
              @endif
            </div>

            @php
              $start_time = strtotime($reservation->reserve_time_slot);
              $start_time_H = date('H:i:s', $start_time);
              $start_time = date('h:i:s a', $start_time);
              $end_time = strtotime("+".$egames_settings['per_session']." minutes", strtotime($reservation->reserve_time_slot));
              $end_time_H = date('H:i:s', $end_time);
              $end_time = date('h:i:s a', $end_time);

              $reserve_date = date('Y-m-d', strtotime($reservation->reserve_date));
              $reserve_date_time_start = $reserve_date . ' ' . $start_time_H;
              $reserve_date_time_end = $reserve_date . ' ' . $end_time_H;

              $reserve_date_time_start = date('Y-m-d H:i:s', strtotime($reserve_date_time_start));
              $reserve_date_time_end = date('Y-m-d H:i:s', strtotime($reserve_date_time_end));

            @endphp

            <div class="col-12 text-secondary p-2">
              <b>PC Name: </b>{{$reservation->pc_name}}<br>
              @if ($reservation->status == 1 || $reservation->status == 8 || $reservation->status == 9)
                <b>Date to be Reserved:</b><br>
                {{date('F jS, Y', strtotime($reservation->reserve_date))}}
                <br>
                <b>Time Slot:</b><br>
                {{$start_time . ' - ' . $end_time}}
                @if ($reservation->status == 8 || $reservation->status == 9)
                  <br><b>Date Denied:</b><br>
                  {{date('F jS, Y', strtotime($reservation->updated_at))}}
                @endif
              @else
                <b>Date Reserved:</b><br>
                {{date('F jS, Y', strtotime($reservation->reserve_date))}}
                <br>
                <b>Time Slot:</b><br>
                {{$start_time . ' - ' . $end_time}}
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @endif
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="form-group col offset-md-4">
              <a href="{{$reservation_data['url_back']}}" class="btn btn-primary">
                <i class="fas fa-arrow-circle-left"></i>&nbsp;Back
              </a>
            </div>    
          </div>
        </div>

        <div class="col-lg-8">
          <div class="row">
            <div class="col-12 p-1">
              <span class="text-info h4">
                <b>Availability</b>
              </span>
              <br>
            </div>
            
            <div class="col-12 p-1">
              <span class="text-info h5 my-2 p-3">
                <b>Date: </b><span class="text-secondary h6 font-weight-bold">{{date('F jS, Y', strtotime($reservation->reserve_date))}}</span>
              </span>
            </div>
            
            @if ($reservations == "No Reservation yet!")
              <div class="col-12">
                <div class="alert alert-info alert-dismissible animated pulse row m-1 mt-2 p-1" role="alert">
                  <b><i class="fas fa-info-circle"></i>&nbsp;{{$reservations}}</b>
                  <button type="button" class="close p-1 pr-2" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
            @endif

            <div class="col-12 mt-3">
              <div class="timetable"></div>
            </div>

            <div class="col-12 mt-4">
              <form action="{{$reservation_data['form_url']}}" method="POST">
                @csrf
                
                <input type="number" name="reservation_id" value="{{$reservation->id}}" style="display: none;" />

                @if ($reservation->status == 1)
                  <button type="submit" class="btn btn-primary mx-2">
                    <b>Approve&nbsp;<i class="fas fa-thumbs-up"></i></b>
                  </button>

                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denyModal">
                    <b>Deny&nbsp;<i class="fas fa-ban"></i></b>
                  </button>
                @elseif ($reservation->status == 2)
                  @php
                    if($reserve_date_time_start <= date('Y-m-d H:i:s') && $reserve_date_time_end >= date('Y-m-d H:i:s')){
                      $disabled = false;
                    }else{
                      $disabled = true;
                    }
                  @endphp
                
                  <button type="submit" class="btn btn-success mx-2" @if($disabled) disabled @endif>
                    <b>Start Playing&nbsp;<i class="fas fa-play-circle"></i></b>
                  </button>

                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denyModal">
                    <b>Deny&nbsp;<i class="fas fa-ban"></i></b>
                  </button>
                @elseif ($reservation->status == 3)
                  <button type="submit" class="btn btn-info mx-2">
                    <b>End Session&nbsp;<i class="fas fa-check-circle"></i></b>
                  </button>
                @endif
              </form>
              @if ($reservation->status == 2)
                <br>
                <span class="text-secondary mt-2">
                  <b>Note:</b> 
                  Can only be started if within the reserved date and time slot.
                </span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Deny Modal -->
  <div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form method="POST" action="{{route('admin.egames.reservation.deny_reservation') . '/' . $reservation->id }}" id="delete_form">
        @method('DELETE')
        @csrf

        <input type="text" name="type" value="@if ($reservation->status == 1) deny_request @elseif ($reservation->status == 2) deny_approved @endif" style="display:none;" />
        
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="denyModalLabel">Are you sure to Deny Reservation?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          
          <div class="modal-body" id="modal_body">
            <span class="text-secondary">
              @if ($reservation->status == 1 || $reservation->status == 8 || $reservation->status == 9)
                <b>Date to be Reserved:</b><br>
                {{date('F jS, Y', strtotime($reservation->reserve_date))}}
                <br>
                <b>Time Slot:</b><br>
                {{$start_time . ' - ' . $end_time}}
                @if ($reservation->status == 8 || $reservation->status == 9)
                  <br><b>Date Denied:</b><br>
                  {{date('F jS, Y', strtotime($reservation->updated_at))}}
                @endif
              @else
                <b>Date Reserved:</b><br>
                {{date('F jS, Y', strtotime($reservation->reserve_date))}}
                <br>
                <b>Time Slot:</b><br>
                {{$start_time . ' - ' . $end_time}}
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @endif
            </span>

            <div class="form-group">
              <label for="notes" class="text-secondary font-weight-bold my-2">Please Leave a Note:</label>
              <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3" required></textarea>
              
              @error('notes')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Deny</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  

  <script src="{{ asset('timetable/scripts/timetable.js') }}" type="application/javascript"></script>

  <script type="application/javascript">
    
    var timetable = new Timetable();
    timetable.setScope(8,20)
    timetable.useTwelveHour()
    
    var pc_slots = [];
    
    @foreach ($pc_slots as $pc_slot)
      @if($pc_slot->status == 1)
        pc_slots.push('{{$pc_slot->pc_name}}');
      @endif
    @endforeach
    
    timetable.addLocations(pc_slots);

    @if ($reservations != "No Reservation yet!")
      @foreach ($reservations as $reservation_on_date)
        @if($reservation_on_date->status == 2 || $reservation_on_date->status == 3 || $reservation_on_date->status == 4)
          @php
            $year = substr($reservation_on_date->reserve_date,0,4);
            $month = substr($reservation_on_date->reserve_date,5,2);
            $day = substr($reservation_on_date->reserve_date,8,2);
            $hour = substr($reservation_on_date->reserve_time_slot,0,2);
            $minutes = substr($reservation_on_date->reserve_time_slot,3,2);
            
            $end_time = strtotime("+".$egames_settings['per_session']." minutes", strtotime($reservation_on_date->reserve_time_slot));
            $end_time = date('H:i:s', $end_time);
            $end_hour = substr($end_time,0,2);
            $end_minutes = substr($end_time,3,2);
          @endphp
          
          var class_obj = {};
          
          @if ($reservation_on_date->status == 2)
            class_obj = {class: 'bg-primary border-primary'};
          @elseif ($reservation_on_date->status == 3)
            class_obj = { class: 'bg-success border-success'};
          @elseif ($reservation_on_date->status == 4)
            class_obj = {class: 'bg-info border-info'};
          @else
            class_obj = {class: 'bg-info border-info'};
          @endif
          
          timetable.addEvent('Reserved', '{{$reservation_on_date->pc_name}}', new Date({{$year}},{{$month}},{{$day}},{{$hour}},{{$minutes}}), new Date({{$year}},{{$month}},{{$day}},{{$end_hour}},{{$end_minutes}}), class_obj);
          
        @endif
      @endforeach
    @endif
    
    
    /* try data
    timetable.addEvent('Sightseeing', 'Pc-1', new Date(2015,7,17,9,00), new Date(2015,7,17,11,30), { class: 'bg-success border-success'});
    timetable.addEvent('try', 'Pc-2', new Date(2020,01,16,14,30), new Date(2020,01,16,16,00),  {class: 'bg-info border-info'});
    timetable.addEvent('Frankadelic', 'Pc-2', new Date(2015,7,17,10,45), new Date(2015,7,17,12,30), {class: 'bg-primary border-primary'});
    */
    var renderer = new Timetable.Renderer(timetable);
    renderer.draw('.timetable');

  </script>
  
@endsection 
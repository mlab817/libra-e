@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => 'reservations']);     
  @endphp

  <div class="container-fluid">
    <div class="container">
      <div class="m-3 pt-4 animated fadeInDown">
        <h1 class="text-info display-5 main_h1"><b>Reserve E-games/Research Slot</b></h1>
      </div>

      <div class="row m-3 p-3">
        <div class="col-12">
          <span class="text-info h3"><b>Available PC's & Schedules</b></span>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row animated fadeIn">
      <div class="col-12">
        <form id="search_date" action="{{route('libraE.reservations.egames.search_date')}}" method ="get">
          <div class="form-row animated fadeIn">
            <div class="form-group col-md-2 text-md-right p-1">
              <span class="text-info"><b>Date: </b></span>
            </div>

            <div class="form-group col-md-6">
              <input type="date" id="date" value="{{$search_date}}" class="border border-info rounded text-secondary pl-1">
              <input class="btn btn-sm btn-primary font-weight-bold mx-2" onclick="search_date()" type="submit" value="Select"/>
            </div>
          </div>
        </form>
      </div>

      <div class="col-12">
        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-md-right p-1">
            <span class="text-info"><b>Select PC:</b></span>
          </div>

          <div class="form-group col-md-6">
            <select id="pc_slot" name="pc_slot" class="form-control text-secondary">
              @foreach ($pc_slots as $pc_slot)
                @if ($pc_slot->status == 1)
                  <option value="{{$pc_slot->id}}">{{$pc_slot->pc_name}}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-md-right p-1">
            <span class="text-info"><b>Select Time-Slot:</b></span>
          </div>

          <div class="form-group col-md-6">
            <select id="time" name="time" class="form-control text-secondary">
              @foreach ($time_schedules as $sched)
                <option value="{{$sched['start_time']}}">{{$sched['start_time']}} - {{$sched['end_time']}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="col-12 text-center my-2">
        <form id="reserve" action="{{route('libraE.reservations.egames.reserve')}}" method ="post">
          @csrf
          <input type="date" id="reserve_date" value="{{$search_date}}" name="reserve_date" style="display:none;">
          <input type="number" id="select_pc" name="select_pc" style="display:none;">
          <input type="text" id="select_time" name="select_time" style="display:none;">
          <input class="btn btn-primary font-weight-bold" onclick="reserve()" type="submit" value="Reserve"/>
        </form>
      </div>

      <div class="col-12">
        @include('inc.status')
        @php session()->pull('error_status') @endphp
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
        <span class="text-info h6 my-2 p-3">
          <b>Date:  </b><span class="text-secondary h6 font-weight-bold">{{date('F jS, Y', strtotime($search_date))}}</span>
        </span>
        <div class="timetable"></div>
      </div>
    </div>

    <div class="row mt-5">
      @foreach ($pc_slots as $pc_slot)
        <div class="col-md-6 col-lg-4 mb-5">
          <div class="card shadow_card_book">
            <img class="card-img-top" src="{{asset('storage/images/bg/pc_logo.jpg')}}" width="300" height="300" alt="" class="img-thumbnail book_card_image img-fluid">
            <div class="card-body">
              <h5 class="card-title simple_black_color font-weight-bold">PC No.&nbsp;{{$pc_slot->pc_no}}</h5>
              <p class="card-text text-secondary">{{$pc_slot->pc_name}}</p>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item text-secondary">{{$pc_slot->notes}}</li>
              <li class="list-group-item text-secondary">
                <span class="font-weight-bold">Description:</span><br>
                {{$pc_slot->description}}
              </li>
              <li class="list-group-item">
                @if ($pc_slot->status == 1)
                  <span class="text-success"><b>Active</b></span> 
                @elseif ($pc_slot->status == 0)
                  <span class="text-danger"><b>Inactive</b></span> 
                @endif
              </li>
            </ul>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <script src="{{ asset('timetable/scripts/timetable.js') }}" type="application/javascript"></script>
  
  <script type="application/javascript">
  
    var timetable = new Timetable();
    // /timetable.setScope({{substr($egames_settings['start_time'],0,2)}},{{substr($egames_settings['end_time'],0,2) + 1}})
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
      @foreach ($reservations as $reservation)
        @if($reservation->status == 2 || $reservation->status == 3 || $reservation->status == 4)
          @php
            $year = substr($reservation->reserve_date,0,4);
            $month = substr($reservation->reserve_date,5,2);
            $day = substr($reservation->reserve_date,8,2);
            $hour = substr($reservation->reserve_time_slot,0,2);
            $minutes = substr($reservation->reserve_time_slot,3,2);
            
            $end_time = strtotime("+".$egames_settings['per_session']." minutes", strtotime($reservation->reserve_time_slot));
            $end_time = date('H:i:s', $end_time);
            $end_hour = substr($end_time,0,2);
            $end_minutes = substr($end_time,3,2);
          @endphp
          
          var class_obj = {};
          
          @if ($reservation->status == 2)
            class_obj = {class: 'bg-primary border-primary'};
          @elseif ($reservation->status == 3)
            class_obj = { class: 'bg-success border-success'};
          @elseif ($reservation->status == 4)
            class_obj = {class: 'bg-info border-info'};
          @else
            class_obj = {class: 'bg-info border-info'};
          @endif
          
          timetable.addEvent('Reserved', '{{$reservation->pc_name}}', new Date({{$year}},{{$month}},{{$day}},{{$hour}},{{$minutes}}), new Date({{$year}},{{$month}},{{$day}},{{$end_hour}},{{$end_minutes}}), class_obj);
          
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

    // Search Date
    function search_date(){

      var date = $('#date').val();
      var form_action_value = $('#search_date').attr('action'); 
      
      $('#search_date').attr('action', form_action_value + '/'+ date );
      
    }
    
    // Reserve
    function reserve(){
      
      //var date = $('#date').val();
      var pc_slot = $('#pc_slot').val();
      var time = $('#time').val();

      //$('#reserve_date').val(date);
      $('#select_pc').val(pc_slot);
      $('#select_time').val(time);
      
    }
  
  </script>
  
@endsection
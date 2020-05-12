@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => 'reservations']);     
  @endphp

  <div class="container-fluid">
    <div class="container">
      <div class="m-3 pt-4 animated fadeInDown">
        <h1 class="text-info display-5 main_h1"><b>Reserve Room</b></h1>
      </div>

      <div class="row m-3 p-3">
        <div class="col-12">
          <span class="text-info h3"><b>Room Schedules</b></span>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row animated fadeIn">
      <div class="col-12">
        <form id="search_date" action="{{route('libraE.reservations.rooms.search_date')}}" method ="get">
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

      <div class="col-12 my-3">
        <span class="text-info h6 my-2 p-3">
          <b>Date:  </b><span class="text-secondary h6 font-weight-bold">{{date('F jS, Y', strtotime($search_date))}}</span>
        </span>
        <div class="timetable"></div>
      </div>

      <div class="col-12">
        <hr>
      </div>

      <form id="reserve" class="row" action="{{route('libraE.reservations.rooms.reserve')}}" method ="post">
        <div class="col-12 mt-3">
          <div class="form-row animated fadeIn">
            <div class="form-group col-md-2 text-md-right p-1">
              <span class="text-info"><b>Select Start Time:</b></span>
            </div>

            <div class="form-group col-md-6">
              <select id="start_time" name="start_time" class="form-control text-secondary">
                @foreach ($time_schedules as $sched)
                  <option value="{{$sched['start_time']}}">{{$sched['start_time']}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="col-12 mb-4">
          <div class="form-row animated fadeIn">
            <div class="form-group col-md-2 text-md-right p-1">
              <span class="text-info"><b>Select End Time:</b></span>
            </div>

            <div class="form-group col-md-6">
              <select id="end_time" name="end_time" class="form-control text-secondary">
                @foreach ($time_schedules as $sched)
                  <option value="{{$sched['end_time']}}">{{$sched['end_time']}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="col-12 mb-1">
          <h5 class="text-info font-weight-bold">Purpose:</h5>
        </div>

        @foreach ($all_purpose as $key => $value)
          <div class="col-lg-4 col-md-6 text-secondary">
            <div class="form-row animated fadeIn">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="{{$key}}" value="{{$value}}" id="{{$key}}">
                <label class="custom-control-label" for="{{$key}}">{{$value}}</label>
              </div>
            </div>
          </div>
        @endforeach

        <div class="col-12 my-2 text-secondary">
          <div class="form-row animated fadeIn">
            <div class="custom-control custom-checkbox my-2">
              <input type="checkbox" class="custom-control-input" name="others" value="Others"  id="others">
              <label class="custom-control-label" for="others">Others</label>
            </div>
            <input type="text" name="others_text" value="{{ old('others_text') }}" class="form-control @error('others_text') is-invalid @enderror" id="others_text" placeholder="Others please specify...">

            @error('others_text')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="col-12 mt-2 text-secondary">
          <div id="topic_description" class="form-row animated fadeIn">
            <div class="form-group col-md-2 text-right p-1">
              <span class="text-info"><b>Topic/Description*</b></span>
            </div>
    
            <div class="form-group col-md-6">
              <input type="text" name="topic_description" value="{{ old('topic_description') }}" class="form-control text-secondary @error('topic_description') is-invalid @enderror" placeholder="Topic Description" required> 
    
              @error('topic_description')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
        </div>

        <div class="col-12 text-secondary">
          <div id="no_users" class="form-row animated fadeIn">
            <div class="form-group col-md-2 text-right p-1">
              <span class="text-info"><b>No. of Student/Faculty*</b></span>
            </div>
    
            <div class="form-group col-md-6">
              <input type="number" min="0" name="no_users" value="{{ old('no_users') }}" class="form-control text-secondary @error('no_users') is-invalid @enderror" placeholder="No. of Student/Faculty" required> 
    
              @error('no_users')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
        </div>


        <div class="col-12 text-center my-5">
          @csrf
          <input type="date" id="reserve_date" value="{{$search_date}}" name="reserve_date" style="display:none;">
          <input class="btn btn-primary font-weight-bold" type="submit" value="Reserve"/>
        </div>
      </form>
    </div>
  </div>


  <script src="{{ asset('timetable/scripts/timetable.js') }}" type="application/javascript"></script>
  
  <script type="application/javascript">
  
    var timetable = new Timetable();
    timetable.setScope(8,20)
    timetable.useTwelveHour()
    
    timetable.addLocations(['Cozy Room']);

    @if ($reservations != "No Reservation yet!")
      @foreach ($reservations as $reservation)
        @if($reservation->status == 2 || $reservation->status == 3 || $reservation->status == 4)
          @php
            $year = substr($reservation->reserve_date,0,4);
            $month = substr($reservation->reserve_date,5,2);
            $day = substr($reservation->reserve_date,8,2);

            $hour = substr($reservation->reserve_time_start,0,2);
            $minutes = substr($reservation->reserve_time_start,3,2);

            $end_hour = substr($reservation->reserve_time_end,0,2);
            $end_minutes = substr($reservation->reserve_time_end,3,2);
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
          
          timetable.addEvent('Reserved', 'Cozy Room', new Date({{$year}},{{$month}},{{$day}},{{$hour}},{{$minutes}}), new Date({{$year}},{{$month}},{{$day}},{{$end_hour}},{{$end_minutes}}), class_obj);
          
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
  </script>
@endsection
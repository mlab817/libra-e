@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'reports']);     
    session(['sidebar_nav_lev_2' => 'attendance_reports_ul']); 
    session(['point_arrow' => 'attendance_scanner']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Attendance Scanner</b></h3>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="container">
        <div class="mx-1 mt-5 p-5 card bg-white box_form text-center">
          
          <form action="{{route('attendance_api')}}" class="row" method="post">
            <div class="col-12 my-5">
              <h1 class="display-1 font-weight-bold text-info">Attendance RFID <i class="far fa-address-card"></i></h1>
              <p class="lead text-secondary">
                Connect the RFID Scanner in your computer then scan the rfid card and select desired room.
              </p>
            </div>
            
            <div class="col-12 my-1">
              <div class="container">
                @include('inc.status')
              </div>
            </div>
              
            <div class="col-12 mb-4 px-5 text-center">
              <input class="form-control form-control-lg @error('rfid_id') is-invalid @enderror" name="rfid_id" type="text" placeholder="RFID ID." autofocus="autofocus" onfocus="this.select()" required>
              @error('rfid_id')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <input type="submit"  style="display:none;">
            </div>

            <div class="col-12 px-5">
              <div class="form-row animated fadeIn">
                <div class="form-group col-md-3 text-md-right p-1">
                  <span class="text-info">
                    <b>Select Room:</b>
                  </span>
                </div>
      
                <div class="form-group col-md-9">
                  <select id="room_ref_no" name="room_ref_no" class="form-control text-secondary" required>
                    <option value=1>Main Attendance</option>
                    <option value=2>Cozy Room</option>
                    <option value=3>Reading Area</option>
                    <option value=4>E-games/Research Room</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="col-12 mb-5 px-5 text-center">
              <a class="btn btn-lg btn-primary font-weight-bold mx-1" href="{{route('admin.reports.attendance_scanner')}}">Refresh <i class="fas fa-sync-alt"></i></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection 
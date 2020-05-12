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

    <h3 class="ml-2 p-1 text-primary"><b>{{$info_data['title']}} Add Rfid</b></h3>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="container">
        <div class="mx-1 mt-5 p-5 card bg-white box_form text-center">
          
          <div class="row">
            <div class="col-12 my-5">
              <h1 class="display-1 font-weight-bold text-info">Scan & Change RFID <i class="far fa-address-card"></i></h1>
              <p class="lead text-secondary">
                Connect the RFID Scanner in your computer then scan the rfid card you wish add.
              </p>
            </div>
            
            <div class="col-12 my-1">
              <div class="container">
                @include('inc.status')
              </div>
            </div>
              
            <div class="col-12 mb-5 px-5 text-center">
              <form action="{{route('admin.rfid.change_rfid')}}" method="post">
                @csrf
                @method('DELETE')
                <input name="type" type="text" value="{{$type}}" style="display:none;" required>
                <input type="text" name="user_id" value="{{$user_id}}" style="display:none;" />
                <input class="form-control form-control-lg @error('rfid_id') is-invalid @enderror" name="rfid_id" type="text" placeholder="RFID ID." autofocus="autofocus" onfocus="this.select()" required>
                @error('rfid_id')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <input type="submit"  style="display:none;">
              </form>
            </div>

            <div class="col-12 mb-5 px-5 text-center">
              <a class="btn btn-lg btn-secondary font-weight-bold mx-1" href="{{route('admin.rfid.all_users') . '/' . $type}}"><i class="fas fa-arrow-circle-left"></i> Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection 
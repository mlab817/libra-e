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

    <h3 class="ml-2 p-1 text-primary"><b>{{$info_data['title']}} Bind Rfid</b></h3>
  </div>

  

  <div class="row">
    <div class="col-12">
      <div class="container">
        <div class="mx-1 mt-5 p-5 card bg-white box_form text-center">
          <div class="row">
            <div class="col-12 my-5">
              <h1 class="display-1 font-weight-bold text-info">Bind RFID <i class="far fa-address-card"></i></h1>
              <p class="lead text-secondary">
                Bind the scanned RFID to a user.
              </p>
            </div>

            <div class="col-12">
              @include('inc.status')
            </div>
            
            <div class="col-12 mb-5 px-5 text-center">
              <form action="{{route('admin.rfid.add_rfid')}}" class="row" method="post">
                @csrf
                <input name="type" type="text" value="{{$type}}" style="display:none;" required>
                
                <input class="form-control form-control-lg" value="{{$rfid_id}}" name="rfid_id" type="text" placeholder="RFID ID." readonly required>
                
                <div class="col-12 mt-3">
                  <div class="form-row animated fadeIn">
                    <div class="form-group col-md-3 text-md-right p-1">
                      <span class="text-info">
                        @if ($type == 'all_students')
                          <b>Select Student:</b>
                        @elseif ($type == 'all_coaches')
                          <b>Select Coach:</b>
                        @endif
                      </span>
                    </div>
          
                    <div class="form-group col-md-9">
                      <select id="user_id" name="user_id" class="form-control text-secondary" required>
                        <option value="">
                          @if ($type == 'all_students')
                            ---Select Student---
                          @elseif ($type == 'all_coaches')
                            ---Select Coach---
                          @endif
                        </option>
                        @foreach ($all_users as $user)
                          @php
                            $m_name = $user->m_name;
                            $m_intitals = $m_name[0];  
                          @endphp
                          <option value="{{$user->user_id}}">{{$user->l_name}} {{$user->f_name}}, {{$m_intitals}}. | @if ($type == 'all_students') {{$user->code}} @elseif ($type == 'all_coaches') {{$user->name}} @endif</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="col-12 mt-4">
                  <button type="submit" class="btn btn-lg btn-primary font-weight-bold"/>
                    Submit <i class="fas fa-check-circle"></i>
                  </button>
                </div>
              </form>
            </div>

            <div class="col-12 mb-5 px-5 text-center">
              <a class="btn btn-lg btn-secondary font-weight-bold mx-1" href="{{route('admin.rfid.scan_rfid') . '/' . $type}}"><i class="fas fa-arrow-circle-left"></i> Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection 
@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accounts']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'staff_coaches']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>View Staff/Coach</b></h3>
  </div>

  @include('inc.errors_all')

  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">
    <div class="col-12">
      <div class="row">
        <div class="col-6">

          <div class="form-row">
            <div class="form-group col-12 p-1">
              <h3 class="text-primary"><b>View Staff/Coach</b></h3>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Library Card No.:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary"><b>FC{{$staff_coach->lib_card_no}}</b></span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Emplyee ID No.:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary"><b>{{$staff_coach->emp_id_no}}</b></span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>First Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$staff_coach->f_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Middle Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$staff_coach->m_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Last Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$staff_coach->l_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Gender:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($staff_coach->gender == 1)
                  Male
                @elseif ($staff_coach->gender == 2)
                  Female
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Address:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$staff_coach->address}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Email Address:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$staff_coach->email_add}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Contact No:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">+63-{{$staff_coach->contact_no}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Department:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$staff_coach->department_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>School Year:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$staff_coach->school_year}}-{{$staff_coach->school_year + 1 }}</span>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Status:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($staff_coach->status == 1)
                  <span class="text-success">Employed</span>
                @elseif ($staff_coach->status == 2)
                  <span class="text-danger">Un-Employed</span>
                @endif
              </span>
            </div>
          </div>

          <div class="form-row mt-2">
            <div class="form-group col offset-md-1">
              <a href="{{route('admin.accounts.staff_coaches')}}" class="btn btn-primary">
                <i class="fas fa-arrow-circle-left"></i>&nbsp;Back
              </a>
            </div>    
          </div>
        </div>

        <div class="col-6">
          <div class="row">
            <div class="col-12 p-1 text-center">
              <span class="text-secondary float-left">
                <span class="text-info"><b>Picture</b></span>
                @if ($staff_coach->pic_url == null)
                  <img src="{{asset('storage/images/staff_coach_images/noimage.png')}}" alt="..." class="img-thumbnail">
                @else
                  <img src="{{asset('storage/images/staff_coach_images/' . $staff_coach->pic_url)}}" alt="..." class="img-thumbnail">
                @endif
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection




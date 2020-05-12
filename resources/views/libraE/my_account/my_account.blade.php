@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => '']);     
  @endphp

  <div class="container">
    <div class="row"> 
      <div class="col-12 my-3">
        @include('inc.status')
      </div>
    </div>
  </div>
  
  <div class="container">
    <div class="row box_form mb-5 mt-4">
      <div class="col-12 m-3 p-4 animated fadeInDown">
        <h1 class="text-info display-5 main_h1"><b>My Account <i class="fas fa-user-circle"></i></b></h1>
      </div>

      <div class="col-md-4 mt-2">
        <div class="row">
          <div class="col-12">
            <h4 class="text-info font-weight-bold px-3 m-1">Picture</h4>
            <hr>
          </div>

          <div class="col-12 px-5 pb-5 pt-3">
            @if (session()->get('loggedIn_user_type') == 1)
              @if ($all_user_data->pic_url == null)
                <img src="{{asset('storage/images/student_images/noimage.png')}}" alt="..." class="img-thumbnail">
              @else
                <img src="{{asset('storage/images/student_images/' . $all_user_data->pic_url)}}" alt="..." class="img-thumbnail">
              @endif
            @elseif (session()->get('loggedIn_user_type') == 2)
              @if ($all_user_data->pic_url == null)
                <img src="{{asset('storage/images/staff_coach_images/noimage.png')}}" alt="..." class="img-thumbnail">
              @else
                <img src="{{asset('storage/images/staff_coach_images/' . $all_user_data->pic_url)}}" alt="..." class="img-thumbnail">
              @endif
            @endif
          </div>
        </div>
      </div>
      
      <div class="col-md-8 mt-2">
        <div class="row">
          <div class="col-12">
            <h4 class="text-info font-weight-bold px-3">Account Info
            <a href="{{route('libraE.edit_my_account')}}" class="btn btn-warning btn-sm ">
              <b>Edit&nbsp;<i class="far fa-edit"></i></b>
            </a>
          </h4> 
            
            <hr>
          </div>

          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">First Name: </span>
            <span class="text-secondary h5">{{$all_user_data->f_name}}</span>
          </div>

          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">Middle Name: </span>
            <span class="text-secondary h5">{{$all_user_data->m_name}}</span>
          </div>

          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">Last Name: </span>
            <span class="text-secondary h5">{{$all_user_data->l_name}}</span>
          </div>

          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">Contact No: </span>
            <span class="text-secondary h5">+63-{{$all_user_data->contact_no}}</span>
          </div>

          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">Email: </span>
            <span class="text-secondary h5">{{$all_user_data->email_add}}</span>
          </div>

          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">Address: </span>
            <span class="text-secondary h5">{{$all_user_data->address}}</span>
          </div>

          <div class="col-12"><hr></div>

          <div class="col-12 p-2 mb-2">
            <span class="text-secondary">
              <b>Note: </b>The data below are based on the registration form upon creating an account in the library. If you wish to change some information below, please ask the librarian.
            </span>
          </div>
          
          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">Library Card No: </span>
            <span class="text-secondary h5">
              @if (session()->get('loggedIn_user_type') == 1)
               ST{{$all_user_data->lib_card_no}}
              @elseif(session()->get('loggedIn_user_type') == 2)
               FC{{$all_user_data->lib_card_no}}
              @endif
            </span>
          </div>

          
          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">
              @if (session()->get('loggedIn_user_type') == 1) 
                Student ID No: 
              @elseif(session()->get('loggedIn_user_type') == 2) 
                Employee ID No: 
              @endif
            </span>
            <span class="text-secondary h5">
              @if (session()->get('loggedIn_user_type') == 1) 
                {{$all_user_data->stud_id_no}} 
              @elseif(session()->get('loggedIn_user_type') == 2) 
                {{$all_user_data->emp_id_no}} 
              @endif
            </span>
          </div>
          
          <div class="col-12 p-2">
            <span class="h5 text-info font-weight-bold">Gender: </span>
            <span class="text-secondary h5">
              @if ($all_user_data->gender == 1)
                Male
              @elseif ($all_user_data->gender == 2)
                Female
              @endif
            </span>
          </div>

          @if (session()->get('loggedIn_user_type') == 1) 
          
            <div class="col-12 p-2">
              <span class="h5 text-info font-weight-bold">Program: </span>
              <span class="text-secondary h5">{{$all_user_data->program_code}}&nbsp;|&nbsp;{{$all_user_data->program_name}}</span>
            </div>

            <div class="col-12 p-2">
              <span class="h5 text-info font-weight-bold">Grade/Year: </span>
              <span class="text-secondary h5">
                @if ($all_user_data->grade_year == 1)
                  {{$all_user_data->grade_year}}st Year    
                @elseif ($all_user_data->grade_year == 2)
                  {{$all_user_data->grade_year}}nd Year    
                @elseif ($all_user_data->grade_year == 3)
                  {{$all_user_data->grade_year}}rd Year    
                @elseif ($all_user_data->grade_year == 4)
                  {{$all_user_data->grade_year}}th Year    
                @elseif ($all_user_data->grade_year == 11 || 12)
                  Grade{{$all_user_data->grade_year}}    
                @endif
              </span>
            </div>

            <div class="col-12 p-2">
              <span class="h5 text-info font-weight-bold">Section: </span>
              <span class="text-secondary h5">{{$all_user_data->program_section_code}}{{$all_user_data->section_code}}</span>
            </div>

            <div class="col-12 p-2">
              <span class="h5 text-info font-weight-bold">School Year: </span>
              <span class="text-secondary h5">{{$all_user_data->school_year}}-{{$all_user_data->school_year + 1 }}</span>
            </div>

            <div class="col-12 p-2">
              <span class="h5 text-info font-weight-bold">Sem: </span>
              <span class="text-secondary h5">{{$all_user_data->sem}}@if ($all_user_data->sem == 1)st @elseif($all_user_data->sem == 2)nd @endif&nbsp;Semester</span>
            </div>

            <div class="col-12 p-2 mb-5">
              <span class="h5 text-info font-weight-bold">Status: </span>
              <span class="text-secondary h5">
                @if ($all_user_data->status == 1)
                  <span class="text-success">Enrolled</span>
                @elseif ($all_user_data->status == 2)
                  <span class="text-danger">Un-Enrolled</span>
                @elseif ($all_user_data->status == 3)
                  <span class="text-info">Alumni</span>
                @endif
              </span>
            </div>

          @elseif (session()->get('loggedIn_user_type') == 2)  
          
            <div class="col-12 p-2">
              <span class="h5 text-info font-weight-bold">Department: </span>
              <span class="text-secondary h5">{{$all_user_data->department_name}}</span>
            </div>
          
            <div class="col-12 p-2">
              <span class="h5 text-info font-weight-bold">School Year: </span>
              <span class="text-secondary h5">{{$all_user_data->school_year}}-{{$all_user_data->school_year + 1 }}</span>
            </div>

            <div class="col-12 p-2 mb-5">
              <span class="h5 text-info font-weight-bold">School Year: </span>
              <span class="text-secondary h5">
                @if ($all_user_data->status == 1)
                  <span class="text-success">Employed</span>
                @elseif ($all_user_data->status == 2)
                  <span class="text-danger">Un-Employed</span>
                @endif
              </span>
            </div>

          @endif
        </div>
      </div>
    </div>
  </div>
  </div>


@endsection
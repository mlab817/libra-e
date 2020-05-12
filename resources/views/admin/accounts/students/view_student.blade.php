@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accounts']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'students']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>View Student</b></h3>
  </div>

  @include('inc.errors_all')

  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">
    <div class="col-12">
      <div class="row">
        <div class="col-6">

          <div class="form-row">
            <div class="form-group col-12 p-1">
              <h3 class="text-primary"><b>View Student</b></h3>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Library Card No.:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary"><b>ST{{$student->lib_card_no}}</b></span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Student ID No.:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary"><b>{{$student->stud_id_no}}</b></span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>First Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$student->f_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Middle Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$student->m_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Last Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$student->l_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Gender:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($student->gender == 1)
                  Male
                @elseif ($student->gender == 2)
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
              <span class="text-secondary">{{$student->address}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Email Address:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$student->email_add}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Contact No:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">+63-{{$student->contact_no}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Program:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$student->program_code}}&nbsp;|&nbsp;{{$student->program_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Grade/Year:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($student->grade_year == 1)
                  {{$student->grade_year}}st Year    
                @elseif ($student->grade_year == 2)
                  {{$student->grade_year}}nd Year    
                @elseif ($student->grade_year == 3)
                  {{$student->grade_year}}rd Year    
                @elseif ($student->grade_year == 4)
                  {{$student->grade_year}}th Year    
                @elseif ($student->grade_year == 11 || 12)
                  Grade{{$student->grade_year}}    
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Section:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$student->program_section_code}}{{$student->section_code}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>School Year:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$student->school_year}}-{{$student->school_year + 1 }}</span>
            </div>
          </div>
          
          @if ($student->program_type == 1)
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <span class="text-info"><b>Sem:</b></span>
              </div>

              <div class="col-md-6 p-1">
                <span class="text-secondary">{{$student->sem}}@if ($student->sem == 1)st @elseif($student->sem == 2)nd @endif&nbsp;Semester</span>
              </div>
            </div>
          @endif
          
          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Status:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($student->status == 1)
                  <span class="text-success">Enrolled</span>
                @elseif ($student->status == 2)
                  <span class="text-danger">Un-Enrolled</span>
                @elseif ($student->status == 3)
                  <span class="text-info">Alumni</span>
                @endif
              </span>
            </div>
          </div>

          <div class="form-row mt-2">
            <div class="form-group col offset-md-1">
              <a href="{{route('admin.accounts.students')}}" class="btn btn-primary">
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
                @if ($student->pic_url == null)
                  <img src="{{asset('storage/images/student_images/noimage.png')}}" alt="..." class="img-thumbnail">
                @else
                  <img src="{{asset('storage/images/student_images/' . $student->pic_url)}}" alt="..." class="img-thumbnail">
                @endif
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection




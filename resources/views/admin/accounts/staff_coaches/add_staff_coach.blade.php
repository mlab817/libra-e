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

    <h3 class="ml-2 p-1 text-primary"><b>Add Staff/Coach</b></h3>
  </div>

  @include('inc.errors_all')
  
  @include('inc.status')
  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <div class="col-12">
      	
      <div class="form-row">
        <div class="form-group col-12 p-1">
          <h3 class="text-primary"><b>New Staff/Coach</b></h3>
        </div>
      </div>

      <form action="{{route('admin.accounts.store_staff_coach')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>New Library Card No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><b>FC-</b></span>
              </div>

              <input type="text" name="lib_card_no" value="{{$file_maintenance['new_lib_card_no']}}" class="form-control text-secondary" readonly> 
            </div>
          </div>
        </div>

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Employee ID. No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="emp_id_no" value="{{ old('emp_id_no') }}" class="form-control text-secondary @error('emp_id_no') is-invalid @enderror" placeholder="Employee ID. No." required> 

            @error('emp_id_no')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>First Name*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="f_name" value="{{ old('f_name') }}" class="form-control text-secondary @error('f_name') is-invalid @enderror" placeholder="First name" required> 

            @error('f_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Middle Name*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="m_name" value="{{ old('m_name') }}" class="form-control text-secondary @error('m_name') is-invalid @enderror" placeholder="Middle name" required> 

            @error('m_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Last Name*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="l_name" value="{{ old('l_name') }}" class="form-control text-secondary @error('l_name') is-invalid @enderror" placeholder="Last name" required> 

            @error('l_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Gender*</b></span>
          </div>
          
          <div class="form-group text-secondary col p-1">
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="gender" value="1" name="gender" @if(old('gender') == 1) checked @endif class="custom-control-input" checked>
              <label class="custom-control-label" for="gender"><b>Male</b></label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="gender2" value="2" name="gender" @if(old('gender') == 2) checked @endif class="custom-control-input">
              <label class="custom-control-label" for="gender2"><b>Female</b></label>
            </div>
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Address*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="address" value="{{ old('address') }}" class="form-control text-secondary @error('address') is-invalid @enderror" placeholder="Address" required> 

            @error('address')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Email Address*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="email" name="email_add" value="{{ old('email_add') }}" class="form-control text-secondary @error('email_add') is-invalid @enderror" placeholder="Email Address" required> 

            @error('email_add')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Contact No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><b>+63-</b></span>
              </div>
          
              <input type="number" name="contact_no" value="{{ old('contact_no') }}" class="form-control text-secondary @error('contact_no') is-invalid @enderror" placeholder="Contact no" required> 

              @error('contact_no')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Main Picture</b></span>
          </div>
        
          <div class="form-group col-md-6">
            <input type="file" name="pic_file" accept="image/x-png,image/jpeg" value="{{ old('pic_file') }}" class="form-control-file @error('pic_file') is-invalid @enderror" id="pic_cover" required>

            @error('pic_file')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        
        <!--
        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>staff_coach Picture*</b></span>
          </div>
      
          <div class="form-group col-md-6">
            
            <div id="my_camera_2" class="border border-info rounded p-2 m-1" style="height:320px; max-width:320px"></div>

            <button type="button" class="btn btn-primary" >Take Snapshot</button>
            
          </div>
        </div>
        -->

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Department*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="department"  onchange="getId(this.value);" class="form-control text-secondary @error('department') is-invalid @enderror" required>
              <option value="">---Select department---</option>
                @foreach ( $file_maintenance['departments'] as $department)
                  <option value="{{$department->id}}" {{old('department') == $department->id  ? "selected":""}}>
                    {{$department->name}}
                  </option>
                @endforeach
            </select>

            @error('department')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Status*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="status" value="{{ old('status') }}" class="form-control text-secondary @error('status') is-invalid @enderror" required>
              <option value="">---Select Status---</option>
                <option value="1" {{old('status') == 1  ? "selected":""}}>
                  Employed
                </option>
                <option value="2" {{old('status') == 2  ? "selected":""}}>
                  Un-Employed
                </option>
            </select>

            @error('status')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>School Year Start*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="number" min="1800" max="3000" name="school_year" value="{{ old('school_year') }}" class="form-control text-secondary @error('school_year') is-invalid @enderror" placeholder="School Year Start" required> 

            @error('school_year')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col offset-md-1">
            <a href="{{route('admin.accounts.staff_coaches')}}" class="btn btn-secondary">Cancel</a>
            <input type="submit" class="btn btn-primary" />
          </div>    
        </div>
      </form>
    </div>  
  </div>
@endsection




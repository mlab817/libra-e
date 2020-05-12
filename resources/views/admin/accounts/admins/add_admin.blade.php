@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accounts']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'admins']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Add Admin</b></h3>
  </div>

  @include('inc.errors_all')
  
  @include('inc.status')
  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <div class="col-12">
      	
      <div class="form-row">
        <div class="form-group col-12 p-1">
          <h3 class="text-primary"><b>New Admin</b></h3>
        </div>
      </div>

      <form action="{{route('admin.accounts.admins.store_admin')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Username*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="username" value="{{ old('username') }}" class="form-control text-secondary @error('username') is-invalid @enderror" placeholder="Username" required> 

            @error('username')
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
            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control text-secondary @error('first_name') is-invalid @enderror" placeholder="First name" required> 

            @error('first_name')
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
            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control text-secondary @error('middle_name') is-invalid @enderror" placeholder="Middle name" required> 

            @error('middle_name')
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
            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control text-secondary @error('last_name') is-invalid @enderror" placeholder="Last name" required> 

            @error('last_name')
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

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Admin Role*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="admin_role_id" class="form-control text-secondary @error('admin_role_id') is-invalid @enderror" required>
              <option value="">---Select Admin Role---</option>
                @foreach ( $admin_roles as $admin_role)
                  <option value="{{$admin_role->id}}">
                    {{$admin_role->description}}
                  </option>
                @endforeach
            </select>

            @error('admin_role_id')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <input type="number" name="status" value="1" style="display: none;" />

        <div class="form-row">
          <div class="form-group col offset-md-1">
            <a href="{{route('admin.accounts.admins')}}" class="btn btn-secondary">Cancel</a>
            <input type="submit" class="btn btn-primary" />
          </div>    
        </div>
        
        <div class="form-row">
          <div class="form-group m-0 col offset-md-1">
            <span class="text-secondary"><b>Note: </b>
              Default Password is pass1234
            </span>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group m-0 col offset-md-1">
            <span class="text-secondary"><b>Note: </b>
              Default Pin Code is 1234
            </span>
          </div>
        </div>
        
      </form>
    </div>  
  </div>
@endsection




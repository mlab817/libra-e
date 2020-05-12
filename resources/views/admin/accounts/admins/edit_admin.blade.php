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

    <h3 class="ml-2 p-1 text-primary"><b>Edit Admin</b></h3>
  </div>

  @include('inc.errors_all')
  
  @include('inc.status')
  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <div class="col-12">
      	
      <div class="form-row">
        <div class="form-group col-12 p-1">
          <h3 class="text-primary"><b>Edit Admin</b></h3>
        </div>
      </div>

      <form action="{{route('admin.accounts.admins.store_admin')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="number" name="id" value="{{$admin->id}}" style="display: none;" />
        
        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Username*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="username" value="{{ $admin->username }}" class="form-control text-secondary @error('username') is-invalid @enderror" placeholder="Username" required> 

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
            <input type="text" name="first_name" value="{{ $admin->first_name }}" class="form-control text-secondary @error('first_name') is-invalid @enderror" placeholder="First name" required> 

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
            <input type="text" name="middle_name" value="{{ $admin->middle_name }}" class="form-control text-secondary @error('middle_name') is-invalid @enderror" placeholder="Middle name" required> 

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
            <input type="text" name="last_name" value="{{ $admin->last_name }}" class="form-control text-secondary @error('last_name') is-invalid @enderror" placeholder="Last name" required> 

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
            <input type="email" name="email_add" value="{{ $admin->email_add }}" class="form-control text-secondary @error('email_add') is-invalid @enderror" placeholder="Email Address" required> 

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
            <select name="admin_role_id" class="form-control text-secondary @error('admin_role_id') is-invalid @enderror"  value="{{$admin->admin_role_id}}" required>
              <option value="">---Select Admin Role---</option>
                @foreach ( $admin_roles as $admin_role)
                  <option value="{{$admin_role->id}}" {{ $admin_role->id == $admin->admin_role_id  ? "selected":""}}>
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

        
        <div class="form-row">
          <div class="form-group col-md-6 offset-md-2">
            <div class="custom-control custom-checkbox mt-2">
              <input type="checkbox" value=1 name="status" class="custom-control-input" id="status" @if ($admin->status == 1) checked @endif />
              <label class="custom-control-label" for="status">Active</label>
            </div>
          </div>    
        </div>

        <div class="form-row">
          <div class="form-group col-md-6 offset-md-2">
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




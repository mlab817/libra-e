@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'file_maintenance']);     
    session(['sidebar_nav_lev_2' => 'file_maintenance_accounts_ul']); 
    session(['point_arrow' => 'programs']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Edit section</b></h3>
  </div>

  @include('inc.status')

  <div class="row mx-1 mt-4">
    <form class="col-7 p-4 card bg-white box_form" action="{{route('admin.file_maintenance.store_section')}}" method="post">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" value="{{$section->id}}">

      <input type="number" style="display:none" name="program_id" value={{$section->program_id}} />

      <div class="form-group">
        <label for="code_section" class="text-secondary"><b>Section Code</b></label>
        <input type="text" name="code_section" value="{{$section->code}}" class="form-control @error('code_section') is-invalid @enderror" id="name" placeholder="Section Name" required>

        @error('code_section')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
        
        @if(session('error_status')) 
          @php session()->pull('error_status') @endphp 
        @endif
 
        <div class="custom-control custom-checkbox mt-2">
          <input type="checkbox" value=1 name="status" class="custom-control-input" id="status" @if ($section->status == 1) checked @endif />
          <label class="custom-control-label" for="status">Active</label>
        </div>
      </div>

      <div class="form-group">
        <a href="{{route('admin.file_maintenance.edit_program_view') . '/' . $section->program_id}}" class="btn btn-secondary btn-sm"><b>Cancel</b></a>
        <button type="submit" class="btn btn-primary btn-sm"><b>Submit</b></button>
      </div>
    </form>
  </div>

@endsection
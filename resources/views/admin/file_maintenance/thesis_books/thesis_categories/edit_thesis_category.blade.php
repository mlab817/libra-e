@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'file_maintenance']);     
    session(['sidebar_nav_lev_2' => 'file_maintenance_thesis_ul']); 
    session(['point_arrow' => 'thesis_categories']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Edit Thesis Category</b></h3>
  </div>

  @include('inc.status')

  <div class="row mx-1 mt-4">
    <form class="col-7 p-4 card bg-white box_form" action="{{route('admin.file_maintenance.store_thesis_category')}}" method="post">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" value="{{$thesis_category->id}}">

      <div class="form-group">
        <label for="name" class="text-secondary"><b>Thesis Category Name</b></label>
        <input type="text" name="name" value="{{$thesis_category->name}}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Thesis Category Name" required>

        @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror

        <div class="custom-control custom-checkbox mt-2">
          <input type="checkbox" value=1 name="status" class="custom-control-input" id="status" @if ($thesis_category->status == 1) checked @endif />
          <label class="custom-control-label" for="status">Active</label>
        </div>
      </div>

      <div class="form-group">
        <a href="{{route('admin.file_maintenance.thesis_categories')}}" class="btn btn-secondary btn-sm"><b>Cancel</b></a>
        <button type="submit" class="btn btn-primary btn-sm"><b>Submit</b></button>
      </div>
    </form>
  </div>

@endsection
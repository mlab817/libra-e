@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'file_maintenance']);     
    session(['sidebar_nav_lev_2' => 'file_maintenance_books_ul']); 
    session(['point_arrow' => 'categories']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Edit Category</b></h3>
  </div>

  @include('inc.status')

  <div class="row mx-1 mt-4">
    <form class="col-7 p-4 card bg-white box_form" action="{{route('admin.file_maintenance.store_category')}}" method="post">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" value="{{$category->id}}">

      <div class="form-group">
        <label for="code" class="text-secondary"><b>Code</b></label>
        <input type="text" name="code" value="{{$category->code}}" class="form-control @error('code') is-invalid @enderror" id="code" placeholder="Category Code" readonly>
      </div>
      
      <div class="form-group">
        <label for="name" class="text-secondary"><b>Category Name</b></label>
        <input type="text" name="name" value="{{$category->name}}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Category Name" required>

        @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror

      </div>

      <div class="form-group">
        <a href="{{route('admin.file_maintenance.categories')}}" class="btn btn-secondary btn-sm"><b>Cancel</b></a>
        <button type="submit" class="btn btn-primary btn-sm"><b>Submit</b></button>
      </div>
    </form>
  </div>

@endsection
@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'books']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'thesis_books']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>View Thesis Book</b></h3>
  </div>

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">
    <div class="col-12">
      <div class="row">
        <div class="col-6">

          <div class="form-row">
            <div class="form-group col-12 p-1">
              <h3 class="text-primary"><b>View Thesis Book</b></h3>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Acc No:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary"><b>T-</b>{{$thesis_book->acc_no}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Call No:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary"><b>T'</b>{{$thesis_book->call_no}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Title:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$thesis_book->title}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Authors:</b></span>
            </div>

            <div class="col-md-6 p-1">
              @foreach ($thesis_authors as $author)
                <span class="text-secondary">{{$author->name}}</span><br />
              @endforeach
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Month:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$month_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Copyright:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$thesis_book->copyright}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>System Type:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$thesis_book->type_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Category:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$thesis_book->category_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Subject:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$thesis_book->subject_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Program:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$thesis_book->program_name}}</span>
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="form-group col offset-md-3">
              <a href="{{route('admin.thesis.thesis_books')}}" class="btn btn-primary">
                <i class="fas fa-arrow-circle-left"></i>&nbsp;Back
              </a>
            </div>    
          </div>
        </div>

        <div class="col-6">
          <div class="row">
            <div class="col-12 p-1 text-center">
              <span class="text-info h3 float-left">
                <b>Handle</b>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection




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

    <h3 class="ml-2 p-1 text-primary"><b>Thesis Books</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.thesis.search_thesis_book')}}" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-3 my-1">
          <label class="sr-only" for="search">Search Thesis</label>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search Thesis book">
        </div>

        <div class="col-auto my-1">
          <button type="submit" onclick="search_form()" class="btn btn-sm btn-primary m-1 font-weight-bold">
            Search&nbsp;<i class="fas fa-search"></i>
          </button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.thesis.thesis_books')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('thesis_books_per_page')) {{session()->get('thesis_books_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_per_page') . '/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_per_page') . '/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_per_page') . '/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_per_page') . '/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_per_page') . '/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_per_page') . '/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_per_page') . '/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has('thesis_books_toOrder')) 
                @if (session()->get('thesis_books_toOrder') == 'acc_no')
                  Acc No.
                @elseif (session()->get('thesis_books_toOrder') == 'call_no')
                  Call No.
                @elseif (session()->get('thesis_books_toOrder') == 'title')
                  Title
                @elseif (session()->get('thesis_books_toOrder') == 'subject_name')
                  Subject
                @elseif (session()->get('thesis_books_toOrder') == 'copyright')
                  Copyright
                @elseif (session()->get('thesis_books_toOrder') == 'code')
                  Code
                @elseif (session()->get('thesis_books_toOrder') == 'created_at')
                  Date Added
                @elseif (session()->get('thesis_books_toOrder') == 'updated_at')
                  Date Updated
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_toOrder') . '/' . 'acc_no'}}">Acc No.</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_toOrder') . '/' . 'call_no'}}">Call No.</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_toOrder') . '/' . 'title'}}">Title</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_toOrder') . '/' . 'subject_name'}}">Subject</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_toOrder') . '/' . 'copyright'}}">Copyright</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_toOrder') . '/' . 'code'}}">Program</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_toOrder') . '/' . 'created_at'}}">Date Added</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_toOrder') . '/' . 'updated_at'}}">Date Updated</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has('thesis_books_orderBy')) 
                @if (session()->get('thesis_books_orderBy') == 'asc')
                  Asc
                @elseif (session()->get('thesis_books_orderBy') == 'desc')
                  Desc
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_orderBy') . '/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.thesis.thesis_books_orderBy') . '/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetAll: 
              @if (session()->has('thesis_books_getAll')) 
                @if (session()->get('thesis_books_getAll') == 'all')
                  All
                @elseif (session()->get('thesis_books_getAll') == 'active')
                  Active
                @elseif (session()->get('thesis_books_getAll') == 'inactive')
                  Inactive
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.thesis.filter_thesis_books') . '/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.thesis.filter_thesis_books') . '/' . 'active'}}">Active</a>
              <a class="dropdown-item" href="{{route('admin.thesis.filter_thesis_books')  . '/' . 'inactive'}}">Inactive</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.thesis.add_thesis_book_view') . '/' . 4}}" class="btn btn-sm btn-success font-weight-bold">Add Thesis Book <i class="fas fa-plus-circle"></i></a>
        </div>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Acc No.</th>
          <th scope="col">Call No.</th>
          <th scope="col">Title</th>
          <th scope="col">Authors</th>
          <th scope="col">Subject</th>
          <th scope="col">Copyright</th>
          <th scope="col">Program</th>
          <th scope="col">Status</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        @if( session('error_status') )
          <tr>
            <td colspan="3">{{session()->pull('error_status')}}</td>
          </tr>
        @else
          @foreach ($thesis_books as $thesis)
            <tr>
              <td><b><span class="text-secondary">T-</span>{{$thesis->acc_no}}</b></td>
              <td><b class="text-secondary">T'</b>{{$thesis->call_no}}</td>
              <td>{{$thesis->title}}</td>
              <td>
                @foreach ($thesis_authors as $author)
                  @if ($author->thesis_id == $thesis->id)
                    {{$author->name}}<br>
                  @endif
                @endforeach
              </td>
              <td>{{$thesis->subject_name}}</td>
              <td>{{$thesis->copyright}}</td>
              <td>{{$thesis->code}}</td>
              @if ( $thesis->thesis_book_status == 1)
                <td class="text-success">Active</td>
              @elseif ($thesis->thesis_book_status == 0)    
                <td class="text-danger">Inactive</td>
              @endif
              <td>
                <a href="{{route('admin.thesis.view_thesis_book') . '/' . $thesis->id}}" class="btn btn-success btn-sm m-1">
                  <b>View&nbsp;<i class="far fa-eye"></i></b>
                </a>

                <a href="{{route('admin.thesis.edit_thesis_book_view') . '/' . $thesis->id}}" class="btn btn-warning btn-sm m-1">
                  <b>Edit&nbsp;<i class="far fa-edit"></i></b>
                </a>

                <button onclick="delete_modal_click({{$thesis->id}} , '{{$thesis->acc_no}}', '{{$thesis->call_no}}', '{{$thesis->call_no}}')" type="button" class="btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#deleteModal">
                  <b>Delete&nbsp;<i class="fas fa-trash-alt"></i></b>
                </button>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$thesis_books->links()}}
  </div>

  <!--
  <div class="row mx-1 mt-4">
    <div class="col-5 p-4 card bg-white box_form">
      <div class="row">
        <div class="col">
          <h5><b class="text-primary">Add Thesis Book</b>
            <a href="{{route('admin.thesis.add_thesis_book_view') . '/' . 4}}" class="btn btn-sm btn-success">Add&nbsp;<i class="fas fa-plus-circle"></i></a>
          </h5>
        </div>
      </div>
      
      
      <hr style="margin-top: 0px;">

      <div class="row">
        <div class="col"><h5><b class="text-primary">Import Thesis Books from Excell</b></h5></div>
      </div>

      @include('inc.errors_invalid_input')

      <form class="pt-1" action="{{route('admin.thesis.import_excell_thesis_books')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <div class="custom-file">
            <label for="customFile">Choose file</label>
            <input type="file" class="form-control-file @error('excell_thesis_books') is-invalid @enderror" name="excell_thesis_books" id="customFile" required/>
          </div>

          @error('excell_thesis_books')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-sm">Submit&nbsp;<i class="fas fa-plus-circle"></i></button>
        </div>
      </form>
    </div>
  </div>
  -->

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Are you sure to delete Thesis Book?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal_body">
          
        </div>
        <div class="modal-footer">
          <form method="POST" id="delete_form">
            @method('DELETE')
            @csrf
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  
  <script type="application/javascript">
  
    function search_form(){

      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/'+ input_search );
      
    }
  
    function delete_modal_click(id, code, call_no, name) {

      $("#modal_body").empty();
      var b_name = '<span><b class="text-secondary">' + code + ' : ' + call_no + ' : ' + name +'</b></span>';
      $("#modal_body").append(b_name);

       $('#delete_form').attr('action', 'delete_thesis_book/'+ id );
    }
  </script>

@endsection 
@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'books']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'accessioning']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Accessions</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.books.search_accession')}}" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-3 my-1">
          <label class="sr-only" for="search">Search Accessions</label>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search Accession">
        </div>

        <div class="col-auto my-1">
          <button onclick="search_form()" type="submit" class="btn btn-sm btn-primary m-1 font-weight-bold">
            Search&nbsp;<i class="fas fa-search"></i>
          </button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.books.accessioning')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('accessions_per_page')) {{session()->get('accessions_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.books.accessions_per_page') . '/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_per_page') . '/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_per_page') . '/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_per_page') . '/' . 80}}">80</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_per_page') . '/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_per_page') . '/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_per_page') . '/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has('accessions_toOrder')) 
                @if (session()->get('accessions_toOrder') == 'accession_no')
                  Accession No.
                @elseif (session()->get('accessions_toOrder') == 'author_name')
                  Author
                @elseif (session()->get('accessions_toOrder') == 'book_title')
                  Book Title
                @elseif (session()->get('accessions_toOrder') == 'publisher_name')
                  Publisher
                @elseif (session()->get('accessions_toOrder') == 'copyright')
                  Copyright
                @elseif (session()->get('accessions_toOrder') == 'created_at')
                  Date Added
                @elseif (session()->get('accessions_toOrder') == 'updated_at')
                  Date Updated
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.books.accessions_toOrder') . '/' . 'accession_no'}}">Acc No.</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_toOrder') . '/' . 'author_name'}}">Author</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_toOrder') . '/' . 'book_title'}}">Title</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_toOrder') . '/' . 'publisher_name'}}">Publisher</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_toOrder') . '/' . 'copyright'}}">Copyright</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_toOrder') . '/' . 'created_at'}}">Date Added</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_toOrder') . '/' . 'updated_at'}}">Date Updated</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has('accessions_orderBy')) 
                @if (session()->get('accessions_orderBy') == 'asc')
                  Asc
                @elseif (session()->get('accessions_orderBy') == 'desc')
                  Desc
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.books.accessions_orderBy') . '/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.books.accessions_orderBy') . '/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetAll: 
              @if (session()->has('accessions_getAll')) 
                @if (session()->get('accessions_getAll') == 'all')
                  All
                @elseif (session()->get('accessions_getAll') == 1)
                  Active
                @elseif (session()->get('accessions_getAll') == 0)
                  Inactive
                @elseif (session()->get('accessions_getAll') == 2)
                  Weeded
                @elseif (session()->get('accessions_getAll') == 3)
                  Lost
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.books.filter_accessions') . '/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.books.filter_accessions') . '/' . 1}}">Active</a>
              <a class="dropdown-item" href="{{route('admin.books.filter_accessions')  . '/' . 0}}">Inactive</a>
              <a class="dropdown-item" href="{{route('admin.books.filter_accessions')  . '/' . 2}}">Weeded</a>
              <a class="dropdown-item" href="{{route('admin.books.filter_accessions')  . '/' . 3}}">Lost</a>
            </div>
          </div>
        </div>

      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Acc No.</th>
          <th scope="col">Image</th>
          <th scope="col">Author</th>
          <th scope="col">Title</th>
          <th scope="col">Publisher</th>
          <th scope="col">Copyright</th>
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
          @foreach ($accessions as $accession)
            <tr>
              <td><b>{{$accession->accession_no}}</b></td>
              <td>
                @if ($accession->pic_url == null)
                  <img src="{{asset('storage/images/accession_images/noimage.png')}}" width="80" height="80" alt="{{$accession->book_title}}" class="img-thumbnail">
                @else
                  <img src="{{asset('storage/images/accession_images/' . $accession->pic_url)}}" width="80" height="80" alt="{{$accession->book_title}}" class="img-thumbnail img-fluid">
                @endif
              </td>
              <td>{{$accession->author_name}}</td>
              <td>{{$accession->book_title}}</td>
              <td>{{$accession->publisher_name}}</td>
              <td>{{$accession->copyright}}</td>
              @if ( $accession->accession_status == 1)
                <td class="text-success">Active</td>
              @elseif ($accession->status == 0)    
                <td class="text-danger">Inactive</td>
              @elseif ($accession->status == 2)    
                <td class="text-danger">Weeded</td>
              @elseif ($accession->status == 3)    
                <td class="text-danger">Lost</td>
              @endif
              <td>
                <a href="{{route('admin.books.view_accession') . '/' . $accession->accession_no_id}}" class="btn btn-success btn-sm m-1">
                  <b>View&nbsp;<i class="far fa-eye"></i></b>
                </a>

                <a href="{{route('admin.books.edit_accession_view') . '/' . $accession->accession_no_id}}" class="btn btn-warning btn-sm m-1">
                  <b>Edit&nbsp;<i class="far fa-edit"></i></b>
                </a>

                <a href="{{route('admin.books.handle_accession_view') . '/' . $accession->accession_no_id}}" class="btn btn-secondary btn-sm m-1">
                  <b>Handle&nbsp;<i class="fas fa-folder-open"></i></b>
                </a>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$accessions->links()}}
  </div>

  <div class="row mx-1 mt-4">
    <div class="col-5 p-4 card bg-white box_form">
      <div class="row">
        <div class="col">
          <h5><b class="text-primary">Add Accession</b>
            <a href="{{route('admin.books.add_accession_view')}}" class="btn btn-sm btn-success">Add&nbsp;<i class="fas fa-plus-circle"></i></a>
          </h5>
        </div>
      </div>

      <hr style="margin-top: 0px;">

      <div class="row">
        <div class="col"><h5><b class="text-primary">Import Accession from Excell</b></h5></div>
      </div>

      @include('inc.errors_invalid_input')

      <form class="pt-1" action="{{route('admin.books.import_excell_accessions')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <div class="custom-file">
            <label for="customFile">Choose file</label>
            <input type="file" class="form-control-file @error('excell_accessions') is-invalid @enderror" name="excell_accessions" id="customFile" required/>
          </div>

          @error('excell_accessions')
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

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Are you sure to delete Accession?</h5>
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
  
    function delete_modal_click(id, code, name) {

      $("#modal_body").empty();
      var b_name = '<span><b class="text-secondary">' + code + ': '+ name +'</b></span>';
      $("#modal_body").append(b_name);

       $('#delete_form').attr('action', 'delete_accession/'+ id );
    }
    
  </script>

@endsection 
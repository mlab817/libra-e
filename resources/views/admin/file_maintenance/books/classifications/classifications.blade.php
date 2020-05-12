@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'file_maintenance']);    
    session(['sidebar_nav_lev_2' => 'file_maintenance_books_ul']); 
    session(['point_arrow' => 'classifications']); 
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Classifications</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.file_maintenance.search_classification')}}" method="get" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-3 my-1">
          <label class="sr-only" for="search_classification">Search Classification</label>
          <input type="text" name="search_classification" id="search_classification" class="form-control" placeholder="Search classification">
        </div>

        <div class="col-auto my-1">
          <button type="submit" onclick="search_form()" class="btn btn-sm btn-primary m-1 font-weight-bold">Search&nbsp;<i class="fas fa-search"></i></button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.file_maintenance.classifications')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('classifications_per_page')) {{session()->get('classifications_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.file_maintenance.classifications_per_page') . '/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.file_maintenance.classifications_per_page') . '/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.file_maintenance.classifications_per_page') . '/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.file_maintenance.classifications_per_page') . '/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.file_maintenance.classifications_per_page') . '/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.file_maintenance.classifications_per_page') . '/' . 200}}">200</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.file_maintenance.filter_classifications') . '/' . 1}}">
            <button type="button" class="btn btn-sm btn-success m-1 font-weight-bold">Get all Active</button>
          </a>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.file_maintenance.filter_classifications')  . '/' . 0}}">
            <button type="button" class="btn btn-sm btn-danger m-1 font-weight-bold">Get all Inactive</button>
          </a>
        </div>

      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Name</th>
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
          @foreach ($classifications as $classification)
            <tr>
              <td>{{$classification->name}}</td>
              @if ( $classification->status == 1)
                <td class="text-success">Active</td>
              @elseif ($classification->status == 0)    
                <td class="text-danger">Inactive</td>
              @endif
              <td>
                <a href="{{route('admin.file_maintenance.edit_classification_view') . '/' . $classification->id}}" class="btn btn-warning btn-sm"><b>Edit&nbsp;<i class="far fa-edit"></i></b></a>
                <button onclick="delete_modal_click({{$classification->id}} , '{{$classification->name}}')" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                  <b>Delete&nbsp;<i class="fas fa-trash-alt"></i></b>
                </button>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$classifications->links()}}
  </div>

  <div class="row mx-1 mt-4">
    <div class="col-5 p-4 card bg-white box_form">
      <div class="row">
        <div class="col"><h5><b class="text-primary">Add classifications</b></h5></div>
      </div>

      @include('inc.errors_invalid_input')

      <form class="pt-1" action="{{route('admin.file_maintenance.store_classification')}}" method="POST">
        @csrf
        <div class="form-group">
          <label for="name" class="text-secondary">Name</label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Classification Name" required>

          @error('name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror

           <input type="number" style="display:none" name="status" value=1 />
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-sm">Add&nbsp;<i class="fas fa-plus-circle"></i></button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Are you sure to delete classification?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal_body">
          
        </div>
        <div class="modal-footer">
          <form method="POST" id="classification_delete_form">
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

      var input_search = $('#search_classification').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/'+ input_search );
      
    }
  
    function delete_modal_click(id, name) {

      $("#modal_body").empty();
      var name = '<b>' + name + '</b>';
      $("#modal_body").append(name);

       $('#classification_delete_form').attr('action', 'delete_classification/'+ id );
    }
  </script>

@endsection 
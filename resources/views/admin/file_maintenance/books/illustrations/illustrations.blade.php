@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'file_maintenance']);     
    session(['sidebar_nav_lev_2' => 'file_maintenance_books_ul']); 
    session(['point_arrow' => 'illustrations']);     
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Illustrations</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4">

    <div class="col-5">

      <div class="row">
        <div class="col p-4 card bg-white box_form">
          <div class="row">
            <div class="col"><h5><b class="text-primary">Add Illustrations </b></h5></div>
          </div>

          <form class="pt-1" action="{{route('admin.file_maintenance.store_illustration')}}" method="post">
            @csrf
            <div class="form-group">
              <label for="name" class="text-secondary"><b>Name</b></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Illustration Name" required>

              @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror

              <input type="number" style="display:none" name="status" value=1 />
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-sm"><b>Add&nbsp;<i class="fas fa-plus-circle"></i></b></button>
            </div>
          </form>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col p-4 card bg-white box_form">
          <div class="row">
            <div class="col"><h5><b class="text-primary">Search Illustrations</b></h5></div>
          </div>

          <form class="pt-1" action="{{route('admin.file_maintenance.search_illustration')}}" method="GET">
            <div class="form-group">
              <label for="search" class="text-secondary"><b>Name</b></label>
              <input type="text" name="search" class="form-control" id="search" placeholder="Search Illutration" required>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success btn-sm"><b>Search&nbsp;<i class="fas fa-search"></i></b></button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-6 ml-2">
      <div class="row">
      
        <div class="col p-4 card bg-white box_form" style="max-height:500px">
        
          <div class="form-row mb-2 align-items-center">

            <div class="col-auto my-1">
              <a href="{{route('admin.file_maintenance.illustrations')}}" class="btn btn-sm btn-primary m-1 font-weight-bold">
                Refresh&nbsp;<i class="fas fa-sync-alt"></i>
              </a>
            </div>

            <div class="col-auto my-1">
              <a href="{{route('admin.file_maintenance.filter_illustrations') . '/' . 1}}" class="btn btn-sm btn-success m-1 font-weight-bold">
                Get all Active
              </a>
            </div>

            <div class="col-auto my-1">
              <a href="{{route('admin.file_maintenance.filter_illustrations')  . '/' . 0}}" class="btn btn-sm btn-danger m-1 font-weight-bold">
                Get all Inactive
              </a>
            </div>

          </div>
        
          <table class="table table-hover table-responsive">
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @if( session('error_status') )
                <tr>
                  <td colspan="3">{{session()->pull('error_status')}}</td>
                </tr>
              @else
                @foreach ($illustrations as $illustration)
                  <tr>
                    <td>{{$illustration->name}}</td>
                    @if ( $illustration->status == 1)
                      <td class="text-success">Active</td>
                    @elseif ($illustration->status == 0)    
                      <td class="text-danger">Inactive</td>
                    @endif
                    <td>
                      <a href="{{route('admin.file_maintenance.edit_illustration_view') . '/' . $illustration->id}}" 
                        class="btn btn-warning btn-sm">
                          <b>Edit&nbsp;<i class="far fa-edit"></i>
                        </b>
                      </a>

                      <button onclick="delete_modal_click({{$illustration->id}} , '{{$illustration->name}}')" 
                        type="button" class="btn btn-danger btn-sm"
                        data-toggle="modal" data-target="#deleteModal"
                      >
                        <b>Delete&nbsp;<i class="fas fa-trash-alt"></i></b>
                      </button>
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Are you sure to delete Illustration?</h5>
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
  
    function delete_modal_click(id, name) {

      $("#modal_body").empty();
      var b_name = '<b>' + name + '</b>';
      $("#modal_body").append(b_name);

       $('#delete_form').attr('action', 'delete_illustration/'+ id );
    }

  </script>

@endsection
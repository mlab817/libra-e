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

    <h3 class="ml-2 p-1 text-primary"><b>Admins</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.accounts.admins.search_admin')}}" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-3 my-1">
          <label class="sr-only" for="search">Search admin</label>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search admin">
        </div>

        <div class="col-auto my-1">
          <button type="submit" onclick="search_form()" class="btn btn-sm btn-primary m-1 font-weight-bold">
            Search&nbsp;<i class="fas fa-search"></i>
          </button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.accounts.admins')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('admins_per_page')) {{session()->get('admins_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_per_page') . '/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_per_page') . '/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_per_page') . '/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_per_page') . '/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_per_page') . '/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_per_page') . '/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_per_page') . '/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has('admins_toOrder')) 
                @if (session()->get('admins_toOrder') == 'username')
                  Username
                @elseif (session()->get('admins_toOrder') == 'last_name')
                  Name
                @elseif (session()->get('admins_toOrder') == 'email_add')
                  Email Add.
                @elseif (session()->get('admins_toOrder') == 'role_description')
                  Role
                @elseif (session()->get('admins_toOrder') == 'updated_at')
                  Date Updated
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_toOrder') . '/' . 'username'}}">Username</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_toOrder') . '/' . 'last_name'}}">Name</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_toOrder') . '/' . 'email_add'}}">Email Add</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_toOrder') . '/' . 'role_description'}}">Role</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_toOrder') . '/' . 'updated_at'}}">Date Updated</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has('admins_orderBy')) 
                @if (session()->get('admins_orderBy') == 'asc')
                  Asc
                @elseif (session()->get('admins_orderBy') == 'desc')
                  Desc
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_orderBy') . '/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.admins_orderBy') . '/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetAll: 
              @if (session()->has('admins_getAll')) 
                @if (session()->get('admins_getAll') == 'all')
                  All
                @elseif (session()->get('admins_getAll') == 1)
                  Active
                @elseif (session()->get('admins_getAll') == 0)
                  Inactive
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.admins.filter_admins') . '/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.filter_admins') . '/' . 1}}">Active</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.filter_admins')  . '/' . 0}}">Inactive</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetRoles: 
              @if (session()->has('admins_roles_getAll')) 
                @if (session()->get('admins_roles_getAll') == 'all')
                  All
                @elseif (session()->get('admins_roles_getAll') == 1)
                  Head Librarian
                @elseif (session()->get('admins_roles_getAll') == 2)
                  Assistant Librarian
                @elseif (session()->get('admins_roles_getAll') == 3)
                  Student Assistant
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.admins.roles_admins') . '/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.roles_admins') . '/' . 1}}">Head Librarian</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.roles_admins')  . '/' . 2}}">Assistant Librarian</a>
              <a class="dropdown-item" href="{{route('admin.accounts.admins.roles_admins')  . '/' . 3}}">Student Assistant</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.accounts.admins.add_admin_view')}}" class="btn btn-sm btn-success font-weight-bold">
            Add Admin <i class="fas fa-plus-circle"></i>
          </a>
        </div>
        
        <div class="col-12">
          <div class="form-row m-3">
            <div class="col-auto my-1 mx-2">
              <span class="text-primary"><b>Total No. Admins: &nbsp;</b></span>
              <span class="badge badge-light p-2">{{$all_count['all']}}</span>
            </div>
            
            <div class="col-auto my-1 mx-2">
              <b><span class="text-primary">Head Librarian:</span></b>
              <span class="badge badge-light p-2">{{$all_count['head_librarian']}}</span>
            </div>
            
            <div class="col-auto my-1 mx-2">
              <b><span class="text-primary">Assist. Librarian:</span></b>
              <span class="badge badge-light p-2">{{$all_count['assist_librarian']}}</span>
            </div>
      
            <div class="col-auto my-1 mx-2 mr-3">
              <b><span class="text-info">Student Assistant</span></b>
              <span class="badge badge-light p-2">{{$all_count['student_assistant']}}</span>
            </div>      

            <div class="col-auto my-1 mx-2">
              <b><span class="text-success">Active:</span></b>
              <span class="badge badge-light p-2">{{$all_count['active']}}</span>
            </div>
      
            <div class="col-auto my-1 mx-2 mr-3">
              <b><span class="text-danger">Inactive</span></b>
              <span class="badge badge-light p-2">{{$all_count['in_active']}}</span>
            </div>      
          </div>
        </div>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Username</th>
          <th scope="col">Name</th>
          <th scope="col">Email Add.</th>
          <th scope="col">Role</th>
          <th scope="col">Status</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody class="text-secondary">
        @if( session('error_status') )
          <tr>
            <td colspan="3">{{session()->pull('error_status')}}</td>
          </tr>
        @else
          @foreach ($admins as $admin)
            <tr>
              <td class="font-weight-bold">{{$admin->username}}</td>
              <td>
                {{$admin->first_name}}&nbsp;
                @php
                  $m_name = $admin->middle_name;
                  $m_intitals = $m_name[0];  
                @endphp
                {{$m_intitals}}.&nbsp;
                {{$admin->last_name}}&nbsp;
              </td>
              <td>{{$admin->email_add}}</td>
              <td>{{$admin->role_description}}</td>
              @if ( $admin->status == 1)
                <td class="text-success">Active</td>
              @elseif ($admin->status == 0)    
                <td class="text-danger">Inactive</td>
              @endif
              <td>
                <a href="{{route('admin.accounts.admins.edit_admin_view') . '/' . $admin->id}}" class="btn btn-warning btn-sm m-1">
                  <b>Edit&nbsp;<i class="far fa-edit"></i></b>
                </a>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$admins->links()}}
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Are you sure to delete admin?</h5>
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

       $('#delete_form').attr('action', 'delete_admin/'+ id );
    }
  </script>

@endsection 
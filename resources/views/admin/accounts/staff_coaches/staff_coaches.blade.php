@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accounts']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'staff_coaches']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Staff/Coaches</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.accounts.search_staff_coach')}}" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-3 my-1">
          <label class="sr-only" for="search">Search Staff/Coaches</label>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search">
        </div>

        <div class="col-auto my-1">
          <button type="submit" onclick="search_form()" class="btn btn-sm btn-primary m-1 font-weight-bold">
            Search&nbsp;<i class="fas fa-search"></i>
          </button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.accounts.staff_coaches')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('staff_coaches_per_page')) {{session()->get('staff_coaches_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_per_page') . '/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_per_page') . '/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_per_page') . '/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_per_page') . '/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_per_page') . '/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_per_page') . '/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_per_page') . '/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has('staff_coaches_toOrder')) 
                @if (session()->get('staff_coaches_toOrder') == 'lib_card_no')
                  Lib Card No.
                @elseif (session()->get('staff_coaches_toOrder') == 'emp_id_no')
                  Emp. ID. No.
                @elseif (session()->get('staff_coaches_toOrder') == 'full_name')
                  Name
                @elseif (session()->get('staff_coaches_toOrder') == 'department_name')
                  Dept.
                @elseif (session()->get('staff_coaches_toOrder') == 'email_add')
                  Email Add.
                @elseif (session()->get('staff_coaches_toOrder') == 'school_year')
                  School Year
                @elseif (session()->get('staff_coaches_toOrder') == 'created_at')
                  Date Added
                @elseif (session()->get('staff_coaches_toOrder') == 'updated_at')
                  Date Updated
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_toOrder') . '/' . 'lib_card_no'}}">Lib Card No.</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_toOrder') . '/' . 'emp_id_no'}}">Emp. ID. No.</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_toOrder') . '/' . 'full_name'}}">Name</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_toOrder') . '/' . 'department_name'}}">Dept.</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_toOrder') . '/' . 'email_add'}}">Email Add.</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_toOrder') . '/' . 'school_year'}}">School Year</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_toOrder') . '/' . 'created_at'}}">Date Added</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_toOrder') . '/' . 'updated_at'}}">Date Updated</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has('staff_coaches_orderBy')) 
                @if (session()->get('staff_coaches_orderBy') == 'asc')
                  Asc
                @elseif (session()->get('staff_coaches_orderBy') == 'desc')
                  Desc
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_orderBy') . '/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.accounts.staff_coaches_orderBy') . '/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetAll: 
              @if (session()->has('staff_coaches_getAll')) 
                @if (session()->get('staff_coaches_getAll') == 'all')
                  All
                @elseif (session()->get('staff_coaches_getAll') == 1)
                  Employed
                @elseif (session()->get('staff_coaches_getAll') == 2)
                  Un-Employed
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.filter_staff_coaches') . '/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.accounts.filter_staff_coaches') . '/' . 1}}">Employed</a>
              <a class="dropdown-item" href="{{route('admin.accounts.filter_staff_coaches')  . '/' . 2}}">Un-Employed</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.accounts.add_staff_coach_view')}}" class="btn btn-sm btn-success font-weight-bold">
            Add Staff/Coach <i class="fas fa-plus-circle"></i>
          </a>
        </div>

        <div class="col-12">
          <div class="form-row m-3">
            <div class="col-auto my-1 mx-2">
              <span class="text-primary"><b>Total No. Staff/Coaches: &nbsp;</b></span>
              <span class="badge badge-light p-2">{{$all_count['all']}}</span>
            </div>
            
            <div class="col-auto my-1 mx-2">
              <b><span class="text-success">Employed:</span></b>
              <span class="badge badge-light p-2">{{$all_count['employed']}}</span>
            </div>
            
            <div class="col-auto my-1 mx-2">
              <b><span class="text-danger">Un-employed:</span></b>
              <span class="badge badge-light p-2">{{$all_count['un_employed']}}</span>
            </div>
          </div>
        </div>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Lib Card No.</th>
          <th scope="col">Emp. ID. No.</th>
          <th scope="col">Name</th>
          <th scope="col">Dept.</th>
          <th scope="col">Email Add.</th>
          <th scope="col">School Year</th>
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
          @foreach ($staff_coaches as $staff_coach)
            <tr>
              <td><b>FC{{$staff_coach->lib_card_no}}</b></td>
              <td>{{$staff_coach->emp_id_no}}</td>
              <td>
                {{$staff_coach->f_name}}&nbsp;
                @php
                  $m_name = $staff_coach->m_name;
                  $m_intitals = $m_name[0];  
                @endphp
                {{$m_intitals}}.&nbsp;
                {{$staff_coach->l_name}}&nbsp;
              </td>
              <td>{{$staff_coach->department_name}}</td>
              <td>{{$staff_coach->email_add}}</td>
              <td>{{$staff_coach->school_year}}-{{$staff_coach->school_year + 1}}</td>
              @if ( $staff_coach->staff_coach_status == 1)
                <td class="text-success">Employed</td>
              @elseif ($staff_coach->status == 2)    
                <td class="text-danger">Un-Employed</td>
              @endif
              <td>
                <a href="{{route('admin.accounts.view_staff_coach') . '/' . $staff_coach->staff_coach_id}}" class="btn btn-success btn-sm m-1">
                  <b>View&nbsp;<i class="far fa-eye"></i></b>
                </a>

                <a href="{{route('admin.accounts.edit_staff_coach_view') . '/' . $staff_coach->staff_coach_id}}" class="btn btn-warning btn-sm m-1">
                  <b>Edit&nbsp;<i class="far fa-edit"></i></b>
                </a>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$staff_coaches->links()}}
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Are you sure to delete Staff/Coach?</h5>
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

       $('#delete_form').attr('action', 'delete_staff_coach/'+ id );
    }
  </script>

@endsection 
@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accounts']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'students']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Students</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.accounts.search_student')}}" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-3 my-1">
          <label class="sr-only" for="search">Search students</label>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search student">
        </div>

        <div class="col-auto my-1">
          <button type="submit" onclick="search_form()" class="btn btn-sm btn-primary m-1 font-weight-bold">
            Search&nbsp;<i class="fas fa-search"></i>
          </button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.accounts.students')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('students_per_page')) {{session()->get('students_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.students_per_page') . '/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_per_page') . '/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_per_page') . '/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_per_page') . '/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_per_page') . '/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_per_page') . '/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_per_page') . '/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has('students_toOrder')) 
                @if (session()->get('students_toOrder') == 'lib_card_no')
                  Lib Card No.
                @elseif (session()->get('students_toOrder') == 'stud_id_no')
                  Stud. ID. No.
                @elseif (session()->get('students_toOrder') == 'full_name')
                  Name
                @elseif (session()->get('students_toOrder') == 'program_code')
                  Program
                @elseif (session()->get('students_toOrder') == 'section_code')
                  Section
                @elseif (session()->get('students_toOrder') == 'grade_year')
                  Grade/Year
                @elseif (session()->get('students_toOrder') == 'created_at')
                  Date Added
                @elseif (session()->get('students_toOrder') == 'updated_at')
                  Date Updated
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.students_toOrder') . '/' . 'lib_card_no'}}">Lib Card No.</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_toOrder') . '/' . 'stud_id_no'}}">Stud. ID. No.</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_toOrder') . '/' . 'full_name'}}">Name</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_toOrder') . '/' . 'program_code'}}">Program</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_toOrder') . '/' . 'section_code'}}">Section</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_toOrder') . '/' . 'grade_year'}}">Grade/Year</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_toOrder') . '/' . 'created_at'}}">Date Added</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_toOrder') . '/' . 'updated_at'}}">Date Updated</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has('students_orderBy')) 
                @if (session()->get('students_orderBy') == 'asc')
                  Asc
                @elseif (session()->get('students_orderBy') == 'desc')
                  Desc
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.students_orderBy') . '/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.accounts.students_orderBy') . '/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetAll: 
              @if (session()->has('students_getAll')) 
                @if (session()->get('students_getAll') == 'all')
                  All
                @elseif (session()->get('students_getAll') == 1)
                  Enrolled
                @elseif (session()->get('students_getAll') == 2)
                  Un-Enrolled
                @elseif (session()->get('students_getAll') == 3)
                  Alumni
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accounts.filter_students') . '/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.accounts.filter_students') . '/' . 1}}">Enrolled</a>
              <a class="dropdown-item" href="{{route('admin.accounts.filter_students')  . '/' . 2}}">Un-Enrolled</a>
              <a class="dropdown-item" href="{{route('admin.accounts.filter_students')  . '/' . 3}}">Alumni</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.accounts.add_student_view')}}" class="btn btn-sm btn-success font-weight-bold">Add Student&nbsp;<i class="fas fa-plus-circle"></i></a>
        </div>
        
        <div class="col-12">
          <div class="form-row m-3">
            <div class="col-auto my-1 mx-2">
              <span class="text-primary"><b>Total No. Student: &nbsp;</b></span>
              <span class="badge badge-light p-2">{{$all_count['all']}}</span>
            </div>
            
            <div class="col-auto my-1 mx-2">
              <b><span class="text-success">Enrolled:</span></b>
              <span class="badge badge-light p-2">{{$all_count['enrolled']}}</span>
            </div>
            
            <div class="col-auto my-1 mx-2">
              <b><span class="text-danger">Un-Enrolled:</span></b>
              <span class="badge badge-light p-2">{{$all_count['un_enrolled']}}</span>
            </div>

            <div class="col-auto my-1 mx-2">
              <b><span class="text-info">Alumni:</span></b>
              <span class="badge badge-light p-2">{{$all_count['alumni']}}</span>
            </div>
          </div>
        </div>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Lib Card No.</th>
          <th scope="col">Stud. ID. No.</th>
          <th scope="col">Name</th>
          <th scope="col">Program</th>
          <th scope="col">Section</th>
          <th scope="col">Grade/Year</th>
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
          @foreach ($students as $student)
            <tr>
              <td><b>ST{{$student->lib_card_no}}</b></td>
              <td>{{$student->stud_id_no}}</td>
              <td>
                {{$student->f_name}}&nbsp;
                @php
                  $m_name = $student->m_name;
                  $m_intitals = $m_name[0];  
                @endphp
                {{$m_intitals}}.&nbsp;
                {{$student->l_name}}&nbsp;
              </td>
              <td>{{$student->program_code}}</td>
              <td>{{$student->program_section_code}}{{$student->section_code}}</td>
              <td>
                @if ($student->grade_year == 1)
                  {{$student->grade_year}}st      
                @elseif ($student->grade_year == 2)
                  {{$student->grade_year}}nd
                @elseif ($student->grade_year == 3)
                  {{$student->grade_year}}rd
                @elseif ($student->grade_year == 4)
                  {{$student->grade_year}}th
                @elseif ($student->grade_year == 11 || 12)
                  Grade{{$student->grade_year}}
                @endif
              </td>
              @if ( $student->student_status == 1)
                <td class="text-success">Enrolled</td>
              @elseif ($student->status == 2)    
                <td class="text-danger">Un-Enrolled</td>
              @elseif ($student->status == 3)    
                <td class="text-info">Alumni</td>
              @endif
              <td>
                <a href="{{route('admin.accounts.view_student') . '/' . $student->student_id}}" class="btn btn-success btn-sm m-1">
                  <b>View&nbsp;<i class="far fa-eye"></i></b>
                </a>

                <a href="{{route('admin.accounts.edit_student_view') . '/' . $student->student_id}}" class="btn btn-warning btn-sm m-1">
                  <b>Edit&nbsp;<i class="far fa-edit"></i></b>
                </a>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$students->links()}}
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Are you sure to delete student?</h5>
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

       $('#delete_form').attr('action', 'delete_student/'+ id );
    }
  </script>

@endsection 
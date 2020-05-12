@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'books']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'students']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Edit Student</b></h3>
  </div>

  @include('inc.errors_all')
  
  @include('inc.status')
  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <div class="col-12">
      	
      <div class="form-row">
        <div class="form-group col-12 p-1">
          <h3 class="text-primary"><b>Edit Student</b></h3>
        </div>
      </div>

      <form action="{{route('admin.accounts.store_student')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input name="id" value="{{$student->student_id}}" style="display:none;" />

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Library Card No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><b>ST-</b></span>
              </div>

              <input type="text" name="lib_card_no" value="{{$student->lib_card_no}}" class="form-control text-secondary" readonly> 
            </div>
          </div>
        </div>

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Student ID. No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="stud_id_no" value="{{ $student->stud_id_no }}" class="form-control text-secondary @error('stud_id_no') is-invalid @enderror" placeholder="Student ID. No." required> 

            @error('stud_id_no')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>First Name*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="f_name" value="{{ $student->f_name }}" class="form-control text-secondary @error('f_name') is-invalid @enderror" placeholder="First name" required> 

            @error('f_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Middle Name*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="m_name" value="{{ $student->m_name }}" class="form-control text-secondary @error('m_name') is-invalid @enderror" placeholder="Middle name" required> 

            @error('m_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Last Name*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="l_name" value="{{ $student->l_name }}" class="form-control text-secondary @error('l_name') is-invalid @enderror" placeholder="Last name" required> 

            @error('l_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Gender*</b></span>
          </div>
          
          <div class="form-group text-secondary col p-1">
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="gender" value="1" name="gender" @if($student->gender == 1) checked @endif class="custom-control-input" checked>
              <label class="custom-control-label" for="gender"><b>Male</b></label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="gender2" value="2" name="gender" @if($student->gender == 2) checked @endif class="custom-control-input">
              <label class="custom-control-label" for="gender2"><b>Female</b></label>
            </div>
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Address*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="address" value="{{ $student->address }}" class="form-control text-secondary @error('address') is-invalid @enderror" placeholder="Address" required> 

            @error('address')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Email Address*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="email" name="email_add" value="{{ $student->email_add }}" class="form-control text-secondary @error('email_add') is-invalid @enderror" placeholder="Email Address" required> 

            @error('email_add')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Contact No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><b>+63-</b></span>
              </div>
          
              <input type="number" name="contact_no" value="{{ $student->contact_no }}" class="form-control text-secondary @error('contact_no') is-invalid @enderror" placeholder="Contact no" required> 

              @error('contact_no')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Main Picture</b></span>
          </div>
        
          <div class="form-group col-md-6">
            <input name="pic_name" value="{{$student->pic_url}}" style="display:none;" />
            @if ($student->pic_url == null)
              <img src="{{asset('storage/images/student_images/noimage.png')}}" alt="..." class="img-thumbnail">
            @else
              <img src="{{asset('storage/images/student_images/' . $student->pic_url)}}" alt="..." class="img-thumbnail">
            @endif
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Change Picture</b></span>
          </div>
        
          <div class="form-group col-md-6">
            <input type="file" name="pic_file" accept="image/x-png,image/jpeg" class="form-control-file @error('pic_file') is-invalid @enderror" id="pic_cover">

            @error('pic_file')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
  
        <!--
        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Student Picture*</b></span>
          </div>
      
          <div class="form-group col-md-6">
            
            <div id="my_camera_2" class="border border-info rounded p-2 m-1" style="height:320px; max-width:320px"></div>

            <button type="button" class="btn btn-primary" >Take Snapshot</button>
            
          </div>
        </div>
        -->

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Program*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="program"  onchange="getId(this.value);" value="{{$student->program_id}}" class="form-control text-secondary @error('program') is-invalid @enderror" required>
              <option value="">---Select Program---</option>
                @foreach ( $file_maintenance['programs'] as $program)
                  <option value="{{$program->id}}" {{ $student->program_id == $program->id  ? "selected":""}}>
                    {{$program->code}} &nbsp;| &nbsp; {{$program->name}}
                  </option>
                @endforeach
            </select>

            @error('program')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Section:</b></span>
          </div>
          
          <div class="form-group col-md-6 p-1">
            <span class="text-secondary">{{$student->program_section_code}}{{$student->section_code}}</span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Change Section?</b></span>
          </div>

          <div class="form-group col-md-6">
            <select id="section" name="section" class="form-control text-secondary @error('section') is-invalid @enderror">
              <option value="">---Select Program First---</option>
            </select>

            @error('section')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Grade/Year:</b></span>
          </div>
          
          <div class="form-group col-md-6 p-1">
            <span class="text-secondary">
              @if ($student->grade_year == 1)
                {{$student->grade_year}}st Year    
              @elseif ($student->grade_year == 2)
                {{$student->grade_year}}nd Year    
              @elseif ($student->grade_year == 3)
                {{$student->grade_year}}rd Year    
              @elseif ($student->grade_year == 4)
                {{$student->grade_year}}th Year    
              @elseif ($student->grade_year == 11 || 12)
                Grade{{$student->grade_year}}    
              @endif
            </span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Change Grade/Year?</b></span>
          </div>

          <div class="form-group col-md-6">
            <select id="grade_year" name="grade_year" class="form-control text-secondary @error('grade_year') is-invalid @enderror">
              <option value="">---Select Program First---</option>
            </select>

            @error('grade_year')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Status*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="status" value="{{ $student->status }}" class="form-control text-secondary @error('status') is-invalid @enderror" required>
              <option value="">---Select Status---</option>
                <option value="1" {{$student->status == 1  ? "selected":""}}>
                  Enrolled
                </option>
                <option value="2" {{$student->status == 2  ? "selected":""}}>
                  Un-Enrolled
                </option>
                <option value="3" {{$student->status == 3  ? "selected":""}}>
                  Alumni
                </option>
            </select>

            @error('status')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>School Year Start*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="number" min="1800" max="3000" name="school_year" value="{{ $student->school_year }}" class="form-control text-secondary @error('school_year') is-invalid @enderror" placeholder="School Year Start" required> 

            @error('school_year')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div id="sem" class="form-row animated fadeIn " style="display:none;">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Sem*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="sem" value="{{ $student->sem }}" class="form-control text-secondary @error('sem') is-invalid @enderror">
              <option value="">---Select Semester---</option>
                <option value="1" {{$student->sem == 1  ? "selected":""}}>
                  1st Semester
                </option>
                <option value="2" {{$student->sem == 2  ? "selected":""}}>
                  2nd Semester
                </option>
            </select>

            @error('sem')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col offset-md-1">
            <a href="{{route('admin.accounts.students')}}" class="btn btn-secondary">Cancel</a>
            <input type="submit" class="btn btn-primary" />
          </div>    
        </div>
      </form>
    </div>  
  </div>

  <script type="application/javascript">
    setTimeout( function(){
      $(document).ready(function() {
        getId({{$student->program_id}});
      });
    }, 900 );
  
  
    function getId(val){
      $.ajax({
        type: "GET",
        url: '{{route('admin.accounts.get_data_section')}}' + '/' + val,
        success: function(data){
            $("#section").html(data);
        }
      })

      $.ajax({
        type: "GET",
        url: '{{route('admin.accounts.get_data_grade_year')}}' + '/' + val,
        success: function(data){
            $("#grade_year").html(data);
        }
      })

      $.ajax({
        type: "GET",
        url: '{{route('admin.accounts.get_data_sem')}}' + '/' + val,
        success: function(data){
            if(data == 1){
              $("#sem").show();
            }else if(data == 0){
              $("#sem").hide();
            }
          
        }
      })
    }
  </script>


  
@endsection




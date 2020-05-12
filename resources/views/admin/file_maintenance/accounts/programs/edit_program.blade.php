@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'file_maintenance']);     
    session(['sidebar_nav_lev_2' => 'file_maintenance_accounts_ul']); 
    session(['point_arrow' => 'programs']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Edit Course/Program</b></h3>
  </div>

  @include('inc.status')

  <div class="row mx-1 mt-4">

    <div class="col-6">
      <div class="row">
        <form class="col p-4 card bg-white box_form" action="{{route('admin.file_maintenance.store_program')}}" method="post">
          @csrf
          @method('PUT')
          <input type="hidden" name="id" value="{{$program->id}}">

          <div class="form-group">
          
            <label for="code" class="text-secondary"><b>Course/Program Code</b></label>
            
            <input type="text" name="code" value="{{$program->code}}" 
              class="form-control @error('code') is-invalid @enderror" 
              id="code" placeholder="Course/Progran Code" required
            >

            @error('code')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

          </div>

          <div class="form-group">
          
            <label for="section_code" class="text-secondary"><b>Section Code</b></label>
            
            <input type="text" name="section_code" value="{{$program->section_code}}" 
              class="form-control @error('section_code') is-invalid @enderror" 
              id="section_code" placeholder="Section Code" required
            >

            @error('section_code')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

          </div>

          <div class="form-group">
            <label for="name" class="text-secondary"><b>Course/Program</b></label>
            
            <input type="text" name="name" value="{{$program->name}}" 
              class="form-control @error('name') is-invalid @enderror" 
              id="name" placeholder="Course/Program" required
            >

            @error('name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <label for="name" class="text-secondary"><b>Type Course/Program</b></label>
            <select name="type" value="{{ $program->type }}" class="form-control text-secondary @error('type') is-invalid @enderror" required>
              <option value="">---Select Program---</option>
              <option value="1" {{$program->type == 1  ? "selected":""}}>Tertiary</option>
              <option value="0" {{$program->type == 0  ? "selected":""}}>Senior High</option>
            </select>

            @error('type')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

            <div class="custom-control custom-checkbox mt-2">
              <input type="checkbox" value=1 name="status" class="custom-control-input" id="status" @if ($program->status == 1) checked @endif />
              <label class="custom-control-label" for="status">Active</label>
            </div>
          </div>

          <div class="form-group">
            <a href="{{route('admin.file_maintenance.programs')}}" class="btn btn-secondary btn-sm"><b>Cancel</b></a>
            <button type="submit" class="btn btn-primary btn-sm"><b>Submit</b></button>
          </div>
        </form>
      </div>

      <div class="row mt-2">
        <div class="col p-4 card bg-white box_form">
          <div class="row">
            <div class="col"><h5><b class="text-primary">Add Section</b></h5></div>
          </div>

          <form class="pt-1" action="{{route('admin.file_maintenance.store_section')}}" method="post">
            @csrf
            
            <input type="number" style="display:none" name="program_id" value={{$program->id}} />

            <div class="form-group">
              <label for="code_section" class="text-secondary"><b>Code</b></label>
              <div class="input-group ">
                <div class="input-group-prepend">
                  <div class="input-group-text">{{$program->section_code}}</div>
                </div>
                <input type="text" name="code_section" class="form-control @error('code_section') is-invalid @enderror" id="code_section" placeholder="Code" required>
              </div>

              @error('code_section')
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
    </div>

    <div class="col-5 ml-2">

      <div class="row">
        <div class="col p-4 card bg-white box_form" style="max-height:500px">

          <div class="row">
            <div class="col"><h5><b class="text-primary">Sections</b></h5></div>
          </div>
            
          <table class="table table-hover table-responsive">
            <thead>
              <tr>
                <th scope="col">Code#</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @if( session('error_status') == 'No Section Yet!' )
                <tr>
                  <td colspan="3">{{session()->pull('error_status')}}</td>
                </tr>
              @else
                  @foreach ($sections as $section)
                  <tr>
                    <th scope="row">{{$program->section_code}}{{$section->code}}</th>
                    @if ( $section->status == 1)
                      <td class="text-success">Active</td>
                    @elseif ($section->status == 0)    
                      <td class="text-danger">Inactive</td>
                    @endif
                    <td>
                      <a href="{{route('admin.file_maintenance.edit_section_view') . '/' . $section->id}}" 
                        class="btn btn-warning btn-sm m-1">
                          <b>Edit&nbsp;<i class="far fa-edit"></i>
                        </b>
                      </a>

                      <button onclick="delete_modal_click({{$section->id}} , '{{$program->section_code}}{{$section->code}}')" 
                        type="button" class="btn btn-danger btn-sm m-1"
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
          <h5 class="modal-title" id="deleteModalLabel">Are you sure to Delete Section?</h5>
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
      var section = '<b>' + name + '</b>';
      $("#modal_body").append(section);

       $('#delete_form').attr('action', 'delete_section/'+ id );
    }
  </script>

@endsection
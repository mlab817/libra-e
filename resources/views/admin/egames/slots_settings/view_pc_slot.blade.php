@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'egames']);     
    session(['sidebar_nav_lev_2' => 'egames_settings_ul']); 
    session(['point_arrow' => 'pc_slots']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>View/Edit PC Slot</b></h3>
  </div>

  @include('inc.status')

  <div class="row mx-1 mt-4">

    <div class="col-6">
      <div class="row">
        <div class="col p-4 card bg-white box_form" style="max-height:500px">

          <div class="row">
            <div class="col"><h3><b class="text-primary">PC Slot</b></h3></div>
          </div>
          
          <div class="row">
            <div class="col-md-3 text-right p-1">
              <span class="text-info h4"><b>PC No:</b></span>
            </div>

            <div class="col-md-7 p-2">
              <span class="text-secondary h5"><b>{{$pc_slot->pc_no}}</b></span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 text-right p-1">
              <span class="text-info h4"><b>PC Name:</b></span>
            </div>

            <div class="col-md-7 p-2">
              <span class="text-secondary h5"><b>{{$pc_slot->pc_name}}</b></span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 text-right p-1">
              <span class="text-info h4"><b>PC Type:</b></span>
            </div>

            <div class="col-md-7 p-2">
              <span class="text-secondary h5"><b>
                @if ($pc_slot->pc_type == 1)
                  E-games
                @elseif ($pc_slot->pc_type == 2)
                  Research
                @endif
              </b></span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 text-right p-1">
              <span class="text-info h4"><b>Status:</b></span>
            </div>

            <div class="col-md-7 p-2">
              @if ($pc_slot->status == 1)
                <span class="text-success h5"><b>Active</b></span>
              @elseif ($pc_slot->status == 0)
                <span class="text-danger h5"><b>Inactive</b></span>
              @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 text-right p-1">
              <span class="text-info h4"><b>Notes:</b></span>
            </div>

            <div class="col-md-7 p-2">
              <span class="text-secondary h5">{{$pc_slot->notes}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 text-right p-1">
              <span class="text-info h4"><b>Description:</b></span>
            </div>

            <div class="col-md-7 p-2">
              <span class="text-secondary h5">{{$pc_slot->description}}</span>
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="form-group col offset-md-4">
              <a href="{{route('admin.egames.slots_settings.pc_slots')}}" class="btn btn-primary">
                <i class="fas fa-arrow-circle-left"></i>&nbsp;Back
              </a>
            </div>   
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-5 ml-2">
      <div class="row">
        <form class="col p-4 card bg-white box_form" action="{{route('admin.egames.slots_settings.add_pc_slot')}}" method="post">
          @csrf
          @method('PUT')

          <input type="hidden" name="id" value="{{$pc_slot->id}}">

          <div class="form-group mb-1">
            <h5><b class="text-primary">Edit PC Slot</b></h5>
          </div>

          <div class="form-group">
          
            <label for="pc_no" class="text-secondary"><b>PC No.</b></label>
            
            <input type="text" name="pc_no" value="{{$pc_slot->pc_no}}" 
              class="form-control @error('pc_no') is-invalid @enderror" 
              id="pc_no" placeholder="PC No." required
            >

            @error('pc_no')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

          </div>

          <div class="form-group">
          
            <label for="pc_name" class="text-secondary"><b>PC Name</b></label>
            
            <input type="text" name="pc_name" value="{{$pc_slot->pc_name}}" 
              class="form-control @error('pc_name') is-invalid @enderror" 
              id="pc_name" placeholder="PC Name" required
            >

            @error('pc_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

          </div>

          <div class="form-group">
          
            <label for="pc_type" class="text-secondary"><b>PC Type</b></label>
            
            <select id="pc_type" name="pc_type" value="{{ old('pc_type') }}" class="form-control text-secondary" required>
              <option value="">---Select PC Type---</option>
              <option value="1" {{ 1 == $pc_slot->pc_type ? "selected":""}}>E-games</option>
              <option value="2" {{ 2 == $pc_slot->pc_type ? "selected":""}}>Research</option>
            </select>
          
            @error('pc_type')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

          </div>

          <div class="form-group">
            <label for="notes" class="text-secondary"><b>Notes:</b></label>
            
            <input type="text" name="notes" value="{{$pc_slot->notes}}" 
              class="form-control @error('notes') is-invalid @enderror" 
              id="notes" placeholder="Notes"
            >

            @error('notes')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <label for="description" class="text-secondary"><b>Description:</b></label>
            
            <input type="text" name="description" value="{{$pc_slot->description}}" 
              class="form-control @error('description') is-invalid @enderror" 
              id="description" placeholder="Description"
            >

            @error('description')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

            <div class="custom-control custom-checkbox mt-2">
              <input type="checkbox" value=1 name="status" class="custom-control-input" id="status" @if ($pc_slot->status == 1) checked @endif />
              <label class="custom-control-label" for="status">Active</label>
            </div>
          </div>

          <div class="form-group">
            <a href="{{route('admin.egames.slots_settings.pc_slots')}}" class="btn btn-secondary btn-sm"><b>Cancel</b></a>
            <button type="submit" class="btn btn-primary btn-sm"><b>Submit</b></button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
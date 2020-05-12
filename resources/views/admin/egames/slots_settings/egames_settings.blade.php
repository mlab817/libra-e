@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'egames']);     
    session(['sidebar_nav_lev_2' => 'egames_settings_ul']); 
    session(['point_arrow' => $type]);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Egames Settings</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row">
    <div class="col-md-6">
      <div class="mx-1 mt-4 p-5 card bg-white box_form">
        @if( session('error_no_settings_yet') )
        
          <div class="alert alert-danger animated shake row m-1 mt-2 p-1" role="alert">
            <b><i class="fas fa-exclamation-circle"></i>&nbsp;{{session('error_no_settings_yet')}}</b>
          </div>
  
          <form action="{{route('admin.egames.slots_settings.store_settings')}}" method="post">
            @csrf
  
            <div class="row mt-3 mb-2">
              <div class="col"><h3><b class="text-primary">Settings</b></h3></div>
            </div>
            
            <div class="row">
              <div class="col-md-3 text-right p-1">
                <span class="text-info h4"><b>Per Session:</b></span>
              </div>
      
              <div class="col-md-3">
                <div class="form-group">
                  <input type="number" min="60" name="per_session" value="{{ old('per_session') }}" class="form-control @error('per_session') is-invalid @enderror" id="per_session" placeholder="Minutes" required>
      
                  @error('per_session')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
  
            <div class="row">
              <div class="col-md-3 text-right p-1">
                <span class="text-info h4"><b>Start Time:</b></span>
              </div>
      
              <div class="col-md-3">
                <div class="form-group">
                  <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" class="form-control @error('start_time') is-invalid @enderror" id="start_time" placeholder="Start Time" required>
      
                  @error('start_time')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-3 text-right p-1">
                <span class="text-info h4"><b>End Time:</b></span>
              </div>
      
              <div class="col-md-3">
                <div class="form-group">
                  <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" class="form-control @error('end_time') is-invalid @enderror" id="end_time" placeholder="End Time" required>
      
                  @error('end_time')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
  
            <div class="form-row mt-1">
              <div class="form-group col offset-md-4">
                <button type="submit" class="btn btn-primary"><b>Submit&nbsp;<i class="fas fa-cog"></i></b></button>
              </div>   
            </div>
         </form>
  
        @else
  
          <form action="{{route('admin.egames.slots_settings.store_settings')}}" method="post">
            @csrf
            @method('PUT')
  
            <div class="row mb-2">
              <div class="col"><h3><b class="text-primary">Settings</b></h3></div>
            </div>
            
            <div class="row">
              <div class="col-md-3 text-right p-1">
                <span class="text-info h4"><b>Per Session:</b></span>
              </div>
      
              <div class="col-md-3">
                <div class="form-group">
                  <input type="number" min="60" name="per_session" value="{{$egames_settings->per_session}}" class="form-control @error('per_session') is-invalid @enderror" id="per_session" placeholder="Minutes" required>
      
                  @error('per_session')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
  
            <div class="row">
              <div class="col-md-3 text-right p-1">
                <span class="text-info h4"><b>Start Time:</b></span>
              </div>
      
              <div class="col-md-3">
                <div class="form-group">
                  <input type="time" id="start_time" name="start_time" value="{{$egames_settings->start_time}}" class="form-control @error('start_time') is-invalid @enderror" id="start_time" placeholder="Start Time" required>
      
                  @error('start_time')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-3 text-right p-1">
                <span class="text-info h4"><b>End Time:</b></span>
              </div>
      
              <div class="col-md-3">
                <div class="form-group">
                  <input type="time" id="end_time" name="end_time" value="{{$egames_settings->end_time}}" class="form-control @error('end_time') is-invalid @enderror" id="end_time" placeholder="End Time" required>
      
                  @error('end_time')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
  
            <div class="form-row mt-1">
              <div class="form-group col offset-md-4">
                <button type="submit" class="btn btn-primary"><b>Submit&nbsp;<i class="fas fa-cog"></i></b></button>
              </div>   
            </div>
          </form>
        @endif
  
        <div class="row">
          <div class="col-12 p-1">
            <span class="text-secondary"><b>Note:</b> Start/End time are 24 Hour format.</span><br>
            <span class="text-secondary"><b>Note:</b> Per Session is per usage of a user in minutes time.</span>
          </div>
        </div>
      </div>
    </div>

    @if($time_schedules != null)
      <div class="col-md-6">
        <div class="mx-1 mt-4 p-5 card bg-white box_form">
          <div class="row mb-2">
            <div class="col"><h3><b class="text-primary">Time Schedule</b></h3></div>
          </div>

          <div class="row">
            <table class="table">
              <thead class="text-info">
                <tr>
                  <th scope="col">Start Time</th>
                  <th scope="col">End Time</th>
                </tr>
              </thead>
              <tbody class="text-secondary">
                @foreach ($time_schedules as $sched)
                  <tr>
                    <td>{{$sched['start_time']}}</td>
                    <td>{{$sched['end_time']}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection 
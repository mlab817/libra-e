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

    <h3 class="ml-2 p-1 text-primary"><b>PC Slots</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.egames.slots_settings.search_pc_slots')}}" id="search_form">
      <div class="form-row mb-2 align-items-center">
        <div class="col-sm-2 my-1">
          <label class="sr-only" for="search">Search</label>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search">
        </div>

        <div class="col-auto my-1">
          <button type="submit" onclick="search_form()" class="btn btn-sm btn-primary m-1 font-weight-bold">
            Search&nbsp;<i class="fas fa-search"></i>
          </button>
        </div>

        <div class="col-auto my-1">
          <a href="{{route('admin.egames.slots_settings.pc_slots')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has($type . '_egames_per_page')) {{session()->get($type . '_egames_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.egames.egames_per_page_url') . '/type/'.$type.'/per_page/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.egames.egames_per_page_url') . '/type/'.$type.'/per_page/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.egames.egames_per_page_url') . '/type/'.$type.'/per_page/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.egames.egames_per_page_url') . '/type/'.$type.'/per_page/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.egames.egames_per_page_url') . '/type/'.$type.'/per_page/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.egames.egames_per_page_url') . '/type/'.$type.'/per_page/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.egames.egames_per_page_url') . '/type/'.$type.'/per_page/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has($type . '_egames_toOrder')) 
                @if (session()->get($type . '_egames_toOrder') == 'pc_no')
                  PC No.
                @elseif (session()->get($type . '_egames_toOrder') == 'pc_name')
                  PC Name
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.egames.egames_toOrder_url') . '/type/'.$type.'/toOrder/' . 'pc_no'}}">PC No.</a>
              <a class="dropdown-item" href="{{route('admin.egames.egames_toOrder_url') . '/type/'.$type.'/toOrder/' . 'pc_name'}}">PC Name</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has($type . '_egames_orderBy')) 
                @if (session()->get($type . '_egames_orderBy') == 'asc')
                  Asc
                @elseif (session()->get($type . '_egames_orderBy') == 'desc')
                  Desc 
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.egames.egames_orderBy_url') . '/type/'.$type.'/orderBy/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.egames.egames_orderBy_url') . '/type/'.$type.'/orderBy/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>
        
        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetStatus: 
              @if (session()->has($type . '_egames_getAll_status')) 
                @if (session()->get($type . '_egames_getAll_status') == 'all')
                  All
                @elseif (session()->get($type . '_egames_getAll_status') == 1)
                  Active
                @elseif (session()->get($type . '_egames_getAll_status') == 0)
                  Inactive
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.egames.filter_status_egames_url') . '/type/'.$type.'/filter/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.egames.filter_status_egames_url') . '/type/'.$type.'/filter/' . 1}}">Active</a>
              <a class="dropdown-item" href="{{route('admin.egames.filter_status_egames_url')  . '/type/'.$type.'/filter/' . 0}}">Inactive</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetType: 
              @if (session()->has($type . '_egames_getAll_pc_type')) 
                @if (session()->get($type . '_egames_getAll_pc_type') == 'all')
                  All
                @elseif (session()->get($type . '_egames_getAll_pc_type') == 1)
                  E-games
                @elseif (session()->get($type . '_egames_getAll_pc_type') == 2)
                  Research
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.egames.filter_pc_type_egames_url') . '/type/'.$type.'/filter/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.egames.filter_pc_type_egames_url') . '/type/'.$type.'/filter/' . 1}}">E-games</a>
              <a class="dropdown-item" href="{{route('admin.egames.filter_pc_type_egames_url')  . '/type/'.$type.'/filter/' . 2}}">Research</a>
            </div>
          </div>
        </div>
      </div>
    </form>

    <div class="form-row m-2">
      <div class="col-auto my-1 mx-2">
        <span class="text-primary"><b>Total No. PC Slots: &nbsp;</b></span>
        <span class="badge badge-light p-2">{{$count['all']}}</span>
      </div>

      <div class="col-auto my-1 mx-2">
        <span class="text-success"><b>Active: &nbsp;</b></span>
        <span class="badge badge-light p-2">{{$count['active']}}</span>
      </div>

      <div class="col-auto my-1 mx-2">
        <span class="text-danger"><b>Inactive: &nbsp;</b></span>
        <span class="badge badge-light p-2">{{$count['inactive']}}</span>
      </div>

      <div class="col-auto my-1 mx-2">
        <span class="text-primary"><b>E-games: &nbsp;</b></span>
        <span class="badge badge-light p-2">{{$count['egames']}}</span>
      </div>

      <div class="col-auto my-1 mx-2">
        <span class="text-info"><b>Research: &nbsp;</b></span>
        <span class="badge badge-light p-2">{{$count['research']}}</span>
      </div>
    </div>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">PC No.</th>
          <th scope="col">Pc Name</th>
          <th scope="col">Pc Type</th>
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
          @foreach ($pc_slots as $pc_slot)
          <tr>
            <td class="text-secondary">
              <h4><b>#&nbsp;{{$pc_slot->pc_no}}</b></h4>
            </td>
            
            <td class="text-secondary">
              <b>{{$pc_slot->pc_name}}</b>
            </td>

            <td>
              @if ($pc_slot->pc_type == 1)
                <b class="text-primary">E-games</b>
              @elseif($pc_slot->pc_type == 2)
                <b class="text-info">Research</b>
              @endif  
            </td>

            <td>
              @if ($pc_slot->status == 1)
                <b class="text-success">Active</b>
              @elseif($pc_slot->status == 0)
                <b class="text-danger">Inactive</b>
              @endif  
            </td>

            <td>
              <a href="{{route('admin.egames.slots_settings.view_pc_slot') . '/' . $pc_slot->id}}" class="btn btn-success btn-sm m-1">
                <b>View&nbsp;<i class="far fa-eye"></i></b>
              </a>
            </td>
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$pc_slots->links()}}
  </div>

  <div class="row mx-1 mt-4">
    <div class="col-5 p-4 card bg-white box_form">
      <div class="row">
        <div class="col"><h5><b class="text-primary">Add PC</b></h5></div>
      </div>

      @include('inc.errors_invalid_input')

      <form class="pt-1" action="{{route('admin.egames.slots_settings.add_pc_slot')}}" method="POST">
        @csrf
        <div class="form-group">
          <label for="pc_no" class="text-secondary font-weight-bold">PC No.*</label>
          <input type="number" name="pc_no" value="{{$last_pc_no}}" class="form-control @error('pc_no') is-invalid @enderror" id="pc_no" placeholder="PC No" required>

          @error('pc_no')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="form-group">
          <label for="pc_name" class="text-secondary font-weight-bold">PC Name*</label>
          <input type="text" name="pc_name" value="{{ old('pc_name') }}" class="form-control @error('pc_name') is-invalid @enderror" id="pc_name" placeholder="PC Name" required>

          @error('pc_name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="form-group">
          <label for="pc_type" class="text-secondary font-weight-bold">PC Type*</label>
          <select id="pc_type" name="pc_type" value="{{ old('pc_name') }}" class="form-control text-secondary" required>
            <option value="">---Select PC Type---</option>
            <option value="1">E-games</option>
            <option value="2">Research</option>
          </select>
        </div>

        <div class="form-group">
          <label for="notes" class="text-secondary font-weight-bold">Note:</label>
          <textarea name="notes" value="{{ old('notes') }}" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="2"></textarea>
          
          @error('notes')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="text-secondary font-weight-bold">Description:</label>
          <textarea name="description" value="{{ old('description') }}" class="form-control @error('description') is-invalid @enderror" id="description" rows="3"></textarea>
          
          @error('description')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <input type="number" style="display:none" name="status" value=1 />

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-sm">Add&nbsp;<i class="fas fa-plus-circle"></i></button>
        </div>
      </form>
    </div>
  </div>

  <script type="application/javascript">
  
    function search_form(){

      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/'+ input_search );
      
    }

  </script>

@endsection 
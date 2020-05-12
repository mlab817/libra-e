@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => 'reservations']);     
  @endphp

  <div class="container-fluid">
    <div class="container">
      <div class="m-3 pt-4 animated fadeInDown">
        <h1 class="text-info display-5 main_h1"><b>{{$pc_slot->pc_name}}</b></h1>
      </div>

      <div class="row m-3 p-3">
        <div class="col-12">
          <span class="text-info h3"><b>Schedules:</b></span>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">

    </div>
  </div>
  
  
@endsection
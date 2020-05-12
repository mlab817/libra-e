@extends('layouts.appAdmin')

@section('content')
  
  @php
    session(['sidebar_nav' => 'dashboard']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'main_dashboard']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Main Dashboard</b></h3>
  </div>
  
@endsection
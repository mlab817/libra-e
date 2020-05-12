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
  
  
  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-2 p-2">
    <div class="col-sm-6 col-lg-4 p-2">
      <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="myChart"></canvas>
    </div>
    
    <div class="col-sm-6 col-lg-4 p-2">
      <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="myChart2"></canvas>
    </div>
    
    <div class="col-sm-6 col-lg-4 p-2">
      <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="myChart3"></canvas>
    </div>
    
    <div class="col-sm-6 col-lg-4 p-2">
      <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="myChart4"></canvas>
    </div>

    <div class="col-sm-6 col-lg-4 p-2">
      <canvas class="dashboard_chart p-3 m-1 card bg-white box_form" id="myChart5"></canvas>
    </div>
  </div>

  <input type="text" id="api_url" value="{{route('libra_e.fetch_month_egames_usage')}}" style="display:none;" />

  <script src="{{ asset('js/charts/admin_charts.js') }}" type="application/javascript"></script>

  
@endsection
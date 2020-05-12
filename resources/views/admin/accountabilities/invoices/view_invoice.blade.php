@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accountabilities']);     
    session(['sidebar_nav_lev_2' => 'accountabilities_invoices_ul']); 
    session(['point_arrow' => '']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>View Invoice</b></h3>
  </div>

  @include('inc.status')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <div class="col-12">
      <div class="row">
  
        <div class="col-12 mb-3 text-center">
          <a href="{{route('admin.accountabilities.invoices'). '/students'}}">
            <button type="button" class="btn btn-primary m-1 font-weight-bold"><i class="fas fa-arrow-circle-left"></i>&nbsp;Back to Students</button>
          </a>
  
          <a href="{{route('admin.accountabilities.invoices'). '/coaches'}}">
            <button type="button" class="btn btn-primary m-1 font-weight-bold"><i class="fas fa-arrow-circle-left"></i>&nbsp;Back to Coaches</button>
          </a>
  
          <a href="{{route('admin.accountabilities.view_invoice') . '/' . $invoice_id}}">
            <button type="button" class="btn btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>
        
        <div class="col-12 text-center">
          <iframe id="receipt_iframe" height="800" src="{{route('admin.accountabilities.print_invoice') . '/' . $invoice_id}}" class="border border-primary"></iframe>
        </div>
      </div>
    </div>
  </div>

@endsection 
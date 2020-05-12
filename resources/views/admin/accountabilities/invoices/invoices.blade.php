@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accountabilities']);     
    session(['sidebar_nav_lev_2' => 'accountabilities_invoices_ul']); 
    session(['point_arrow' => 'invoices_'.$type]);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>{{ucfirst($type)}} Invoices</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.accountabilities.search_invoices_url')}}" id="search_form">
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
          <a href="{{route('admin.accountabilities.invoices'). '/' . $type}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has($type.'_invoices_per_page')) {{session()->get($type.'_invoices_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_per_page_url') . '/type/'.$type.'/per_page/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_per_page_url') . '/type/'.$type.'/per_page/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_per_page_url') . '/type/'.$type.'/per_page/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_per_page_url') . '/type/'.$type.'/per_page/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_per_page_url') . '/type/'.$type.'/per_page/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_per_page_url') . '/type/'.$type.'/per_page/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_per_page_url') . '/type/'.$type.'/per_page/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has($type.'_invoices_toOrder')) 
                @if (session()->get($type.'_invoices_toOrder') == 'invoice_no')
                  Invoice No.
                @elseif (session()->get($type.'_invoices_toOrder') == 'l_name')
                  User
                @elseif (session()->get($type.'_invoices_toOrder') == 'date_of_payment')
                  Payment Date
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_toOrder_url') . '/type/'.$type.'/toOrder/' . 'invoice_no'}}">Invoice No.</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_toOrder_url') . '/type/'.$type.'/toOrder/' . 'l_name'}}">User</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_toOrder_url') . '/type/'.$type.'/toOrder/' . 'date_of_payment'}}">Payment Date</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has($type.'_invoices_orderBy')) 
                @if (session()->get($type.'_invoices_orderBy') == 'asc')
                  Asc
                @elseif (session()->get($type.'_invoices_orderBy') == 'desc')
                  Desc 
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_orderBy_url') . '/type/'.$type.'/orderBy/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.invoices_orderBy_url') . '/type/'.$type.'/orderBy/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>
      </div>
    </form>

    <div class="form-row m-2">
      <div class="col-auto my-1 mx-2">
        <span class="text-primary"><b>Total No. of Invoices: &nbsp;</b></span>
        <span class="badge badge-light p-2">{{$count}}</span>
      </div>
    </div>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Invoice No.</th>
          <th scope="col">User</th>
          <th scope="col">Payment Date</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @if( session('error_status') )
          <tr>
            <td colspan="3">{{session()->pull('error_status')}}</td>
          </tr>
        @else
          @foreach ($invoices as $invoice)
          <tr>
            <td>
              <span class="text-secondary h6"><b>{{$invoice->invoice_no}}</b></span>
            </td>
            <td class="text-secondary">
              <span class="h5">{{$invoice->f_name}}</span>&nbsp;
              @php
                $m_name = $invoice->m_name;
                $m_intitals = $m_name[0];  
              @endphp
              <span class="h5">
                {{$m_intitals}}.&nbsp;
                {{$invoice->l_name}}
              </span>
              <br>
              <span class="h5"><b>Email:</b></span>&nbsp;
              {{$invoice->email_add}}
            </td>
            <td>{{date('F jS, Y h:i:s', strtotime($invoice->date_of_payment))}}</td>
            <td>
              <a href="{{route('admin.accountabilities.view_invoice') . '/' . $invoice->id}}" class="btn btn-success btn-sm m-1">
                <b>View&nbsp;<i class="far fa-eye"></i></b>
              </a>
            </td>
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$invoices->links()}}
  </div>

  <script type="application/javascript">
  
    function search_form(){

      var type = '{{$type}}';

      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/type/'+ type +'/search/'+ input_search );
      
    }

  </script>

@endsection 
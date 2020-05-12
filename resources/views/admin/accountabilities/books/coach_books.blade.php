@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accountabilities']);     
    session(['sidebar_nav_lev_2' => 'accountabilities_books_ul']); 
    session(['point_arrow' => 'coach_books']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Staff/Coaches Book Accountabilites</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.accountabilities.search_book_accountabilities_url')}}" id="search_form">
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
          <a href="{{route('admin.accountabilities.student_books')}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('coach_books_accountabilities_per_page')) {{session()->get('coach_books_accountabilities_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_per_page_url') . '/type/coach_books/per_page/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_per_page_url') . '/type/coach_books/per_page/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_per_page_url') . '/type/coach_books/per_page/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_per_page_url') . '/type/coach_books/per_page/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_per_page_url') . '/type/coach_books/per_page/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_per_page_url') . '/type/coach_books/per_page/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_per_page_url') . '/type/coach_books/per_page/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has('coach_books_accountabilities_toOrder')) 
                @if (session()->get('coach_books_accountabilities_toOrder') == 'transaction_no')
                  Trans. No.
                @elseif (session()->get('coach_books_accountabilities_toOrder') == 'book_title')
                  Title
                @elseif (session()->get('coach_books_accountabilities_toOrder') == 'author_name')
                  Author
                @elseif (session()->get('coach_books_accountabilities_toOrder') == 'accession_no')
                  Acc. No.
                @elseif (session()->get('coach_books_accountabilities_toOrder') == 'l_name')
                  User
                @elseif (session()->get('coach_books_accountabilities_toOrder') == 'updated_at')
                  Latest
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_toOrder_url') . '/type/coach_books/toOrder/' . 'transaction_no'}}">Trans. No.</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_toOrder_url') . '/type/coach_books/toOrder/' . 'book_title'}}">Title</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_toOrder_url') . '/type/coach_books/toOrder/' . 'author_name'}}">Author</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_toOrder_url') . '/type/coach_books/toOrder/' . 'accession_no'}}">Acc. No.</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_toOrder_url') . '/type/coach_books/toOrder/' . 'l_name'}}">User</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_toOrder_url') . '/type/coach_books/toOrder/' . 'updated_at'}}">Latest</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has('coach_books_accountabilities_orderBy')) 
                @if (session()->get('coach_books_accountabilities_orderBy') == 'asc')
                  Asc
                @elseif (session()->get('coach_books_accountabilities_orderBy') == 'desc')
                  Desc 
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_orderBy_url') . '/type/coach_books/orderBy/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.accountabilities_orderBy_url') . '/type/coach_books/orderBy/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetType: 
              @if (session()->has('coach_books_accountabilities_getAll_type')) 
                @if (session()->get('coach_books_accountabilities_getAll_type') == 'all')
                  Get All
                @elseif (session()->get('coach_books_accountabilities_getAll_type') == 2)
                  Overdue
                @elseif (session()->get('coach_books_accountabilities_getAll_type') == 3)
                  Damage/Lost
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accountabilities.filter_type_accountabilities_url') . '/type/coach_books/filter/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.filter_type_accountabilities_url')  . '/type/coach_books/filter/' . 2}}">Overdue</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.filter_type_accountabilities_url')  . '/type/coach_books/filter/' . 3}}">Damage/Lost</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              GetStatus: 
              @if (session()->has('coach_books_accountabilities_getAll_status')) 
                @if (session()->get('coach_books_accountabilities_getAll_status') == 'all')
                  Get All
                @elseif (session()->get('coach_books_accountabilities_getAll_status') == 1)
                  Unpaid
                @elseif (session()->get('coach_books_accountabilities_getAll_status') == 2)
                  Cleared
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.accountabilities.filter_status_accountabilities_url') . '/type/coach_books/filter/' . 'all'}}">Get All</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.filter_status_accountabilities_url') . '/type/coach_books/filter/' . 1}}">Unpaid</a>
              <a class="dropdown-item" href="{{route('admin.accountabilities.filter_status_accountabilities_url')  . '/type/coach_books/filter/' . 2}}">Cleared</a>
            </div>
          </div>
        </div>
      </div>
    </form>

    <div class="form-row m-2">
      <div class="col-auto my-1 mx-2">
        <span class="text-primary"><b>Total No. of Accountables: &nbsp;</b></span>
        <span class="badge badge-light p-2">{{$count['all']}}</span>
      </div>
      
      <div class="col-auto my-1 mx-2">
        <span class="text-primary"><b><span class="text-danger">Unpaid:</span></b></span>
        <span class="badge badge-light p-2">{{$count['unpaid']}}</span>
      </div>

      <div class="col-auto my-1 mx-2">
        <span class="text-primary"><b><span class="text-danger">Unsettled:</span></b></span>
        <span class="badge badge-light p-2">{{$count['unsettled']}}</span>
      </div>

      <div class="col-auto my-1 mx-2 mr-3">
        <span class="text-primary"><b><span class="text-success">Cleared</span></b></span>
        <span class="badge badge-light p-2">{{$count['cleared']}}</span>
      </div>      

      <div class="col-auto my-1 mx-2">
        <span class="text-primary"><b><span class="text-danger">Overdue:</span></b></span>
        <span class="badge badge-light p-2">{{$count['overdue']}}</span>
      </div>

      <div class="col-auto my-1 mx-2">
        <span class="text-primary"><b><span class="text-danger">Damage/Lost:</span></b></span>
        <span class="badge badge-light p-2">{{$count['damaged_lost']}}</span>
      </div>
    </div>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Trans. No.</th>
          <th scope="col">Image</th>
          <th scope="col">Title</th>
          <th scope="col">Author</th>
          <th scope="col">Acc. No.</th>
          <th scope="col">User</th>
          <th scope="col">Type</th>
          <th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
        @if( session('error_status') )
          <tr>
            <td colspan="3">{{session()->pull('error_status')}}</td>
          </tr>
        @else
          @foreach ($accountabilities as $accountability)
          <tr>
            <td><b>{{$accountability->transaction_no}}</b></td>
            <td>
              @if ($accountability->pic_url == null)
                <img src="{{asset('storage/images/accession_images/noimage.png')}}" width="80" height="80" alt="{{$accountability->book_title}}" class="img-thumbnail">
              @else
                <img src="{{asset('storage/images/accession_images/' . $accountability->pic_url)}}" width="80" height="80" alt="{{$accountability->book_title}}" class="img-thumbnail img-fluid">
              @endif
            </td>
            <td>
              {{$accountability->book_title}}
            </td>
            <td>
              {{$accountability->author_name}}
            </td>
            <td>
              {{$accountability->accession_no}}
            </td>
            <td>
              <b>Student:</b><br>
              {{$accountability->student_f_name}}&nbsp;
              @php
                $m_name = $accountability->student_m_name;
                $m_intitals = $m_name[0];  
              @endphp
              {{$m_intitals}}.&nbsp;
              {{$accountability->student_l_name}}
              <br>
              <b>Email:</b><br>
              {{$accountability->student_email_add}}
            </td>
            <td>
              @if ($accountability->accountability_type == 2)
                <span class="text-danger"><b>Overdue</b></span>
              @elseif ($accountability->accountability_type == 3)
                <span class="text-danger"><b>Damage/Lost</b></span><br>
                <span class="text-secondary"><b>Notes:</b></span><br>
                <span class="text-secondary">{{$accountability->borrowed_book_notes}}</span>
              @endif
            </td>
            <td>
              @if ($accountability->status == 1)
                @if ($accountability->accountability_type == 2)
                  <span class="text-danger"><b>Unpaid</b></span>
                @elseif ($accountability->accountability_type == 3)
                  <span class="text-danger"><b>Unsettled</b></span>
                @endif
              @elseif ($accountability->status == 2)
                <span class="text-success"><b>Cleared</b></span>
              @endif
            </td>
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$accountabilities->links()}}
  </div>

  <script type="application/javascript">
  
    function search_form(){

      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 

      $('#search_form').attr('action', form_action_value + '/type/coach_books/search/'+ input_search );

    }

  </script>

@endsection 
@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'borrowing']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => $status_type]);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>{{$title}} Book Reservations</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <form action="{{route('admin.borrowing.search_all_book_reservations_url')}}" id="search_form">
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
          <a href="{{route('admin.borrowing.all_book_reservations') . '/' . $status_type}}">
            <button type="button" class="btn btn-sm btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Per page: @if (session()->has('admin_book_reservations_per_page')) {{session()->get('admin_book_reservations_per_page', 'default')}} @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_per_page_url') . '/status/'.$status_type.'/per_page/' . 5}}">5</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_per_page_url') . '/status/'.$status_type.'/per_page/' . 10}}">10</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_per_page_url') . '/status/'.$status_type.'/per_page/' . 20}}">20</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_per_page_url') . '/status/'.$status_type.'/per_page/' . 50}}">50</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_per_page_url') . '/status/'.$status_type.'/per_page/' . 100}}">100</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_per_page_url') . '/status/'.$status_type.'/per_page/' . 200}}">200</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_per_page_url') . '/status/'.$status_type.'/per_page/' . 500}}">500</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ToOrder: 
              @if (session()->has('admin_book_reservations_toOrder')) 
                @if (session()->get('admin_book_reservations_toOrder') == 'transaction_no')
                  Trans. No.
                @elseif (session()->get('admin_book_reservations_toOrder') == 'book_title')
                  Title
                @elseif (session()->get('admin_book_reservations_toOrder') == 'author_name')
                  Author
                @elseif (session()->get('admin_book_reservations_toOrder') == 'accession_no')
                  Acc. No.
                @elseif (session()->get('admin_book_reservations_toOrder') == 'due_date')
                  Date
                @elseif (session()->get('admin_book_reservations_toOrder') == 'updated_at')
                  Latest
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_toOrder_url') . '/status/'.$status_type.'/ToOrder/' . 'transaction_no'}}">Trans. No.</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_toOrder_url') . '/status/'.$status_type.'/ToOrder/' . 'book_title'}}">Title</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_toOrder_url') . '/status/'.$status_type.'/ToOrder/' . 'author_name'}}">Author</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_toOrder_url') . '/status/'.$status_type.'/ToOrder/' . 'accession_no'}}">Acc. No.</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_toOrder_url') . '/status/'.$status_type.'/ToOrder/' . 'due_date'}}">Date</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_toOrder_url') . '/status/'.$status_type.'/ToOrder/' . 'updated_at'}}">Latest</a>
            </div>
          </div>
        </div>

        <div class="col-auto my-1">
          <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              OrderBy: 
              @if (session()->has('admin_book_reservations_orderBy')) 
                @if (session()->get('admin_book_reservations_orderBy') == 'asc')
                  Asc
                @elseif (session()->get('admin_book_reservations_orderBy') == 'desc')
                  Desc 
                @endif
              @endif
            </button>
            <div class="dropdown-menu" aria-labelledby="per_page_btn">
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_orderBy_url') . '/status/'.$status_type.'/orderBy/' . 'asc'}}">Ascending</a>
              <a class="dropdown-item" href="{{route('admin.borrowing.admin_book_reservations_orderBy_url') . '/status/'.$status_type.'/orderBy/' . 'desc'}}">Descending</a>
            </div>
          </div>
        </div>

        @if ($status_type == 'all_transactions' || $status_type == 'all_events')
          <div class="col-auto my-1">
            <div class="dropdown">
              <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                GetAll: 
                @if (session()->has('admin_book_reservations_getAll')) 
                  @if (session()->get('admin_book_reservations_getAll') == 'all')
                    Get All
                  @elseif (session()->get('admin_book_reservations_getAll') == 1)
                    Pending
                  @elseif (session()->get('admin_book_reservations_getAll') == 2)
                    Approved
                  @elseif (session()->get('admin_book_reservations_getAll') == 3)
                    Borrowed
                  @elseif (session()->get('admin_book_reservations_getAll') == 4)
                    Returned
                  @elseif (session()->get('admin_book_reservations_getAll') == 5)
                    Damage/Lost
                  @elseif (session()->get('admin_book_reservations_getAll') == 8)
                    Denied
                  @elseif (session()->get('admin_book_reservations_getAll') == 9)
                    Cancelled
                  @elseif (session()->get('admin_book_reservations_getAll') == 10)
                    Overdue
                  @elseif (session()->get('admin_book_reservations_getAll') == 11)
                    Returned & Overdue
                  @endif
                @endif
              </button>
              <div class="dropdown-menu" aria-labelledby="per_page_btn">
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url') . '/status/'.$status_type.'/filter/' . 'all'}}">Get All</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url') . '/status/'.$status_type.'/filter/' . 1}}">Pending</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url')  . '/status/'.$status_type.'/filter/' . 2}}">Approved</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url')  . '/status/'.$status_type.'/filter/' . 3}}">Borrowed</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url')  . '/status/'.$status_type.'/filter/' . 4}}">Returned</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url')  . '/status/'.$status_type.'/filter/' . 5}}">Damage/Lost</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url')  . '/status/'.$status_type.'/filter/' . 8}}">Denied</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url')  . '/status/'.$status_type.'/filter/' . 9}}">Cancelled</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url')  . '/status/'.$status_type.'/filter/' . 10}}">Overdue</a>
                <a class="dropdown-item" href="{{route('admin.borrowing.filter_admin_book_reservations_url')  . '/status/'.$status_type.'/filter/' . 11}}">Returned & Overdue</a>
              </div>
            </div>
          </div>
        @endif
      </div>
    </form>

    @if ($status_type == 'all_transactions' || $status_type == 'all_events')
      <div class="form-row m-2">
        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b>Total No. of Request: &nbsp;</b></span>
          <span class="badge badge-light p-2">{{$count['all']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-warning">Pending:</span></b></span>
          <span class="badge badge-light p-2">{{$count['pending']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-info">Approved:</span></b></span>
          <span class="badge badge-light p-2">{{$count['approved']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-primary">Borrowed:</span></b></span>
          <span class="badge badge-light p-2">{{$count['borrowed']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-success">Returned:</span></b></span>
          <span class="badge badge-light p-2">{{$count['returned']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-danger">Damage/lost:</span></b></span>
          <span class="badge badge-light p-2">{{$count['damage_lost']}}</span>
        </div>
        
        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-danger">Denied:</span></b></span>
          <span class="badge badge-light p-2">{{$count['denied']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-danger">Cancelled:</span></b></span>
          <span class="badge badge-light p-2">{{$count['cancelled']}}</span>
        </div>

        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b><span class="text-danger">Overdue:</span></b></span>
          <span class="badge badge-light p-2">{{$count['overdue']}}</span>
        </div>
      </div>
    @else
      <div class="form-row m-2">
        <div class="col-auto my-1 mx-2">
          <span class="text-primary"><b>Total No. of Request: &nbsp;</b></span>
          <span class="text-secondary"><b>{{$count}}</b></span>
        </div>
      </div>
    @endif

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Trans. No.</th>
          <th scope="col">Image</th>
          <th scope="col">Title</th>
          <th scope="col">Author</th>
          <th scope="col">Acc. No.</th>
          <th scope="col">User</th>
          <th scope="col">Date</th>
          <th scope="col">Status</th>
          @if ($status_type == 'all_events')
            <th scope="col">Created</th>   
          @else
            <th scope="col">Actions</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @if( session('error_status') )
          <tr>
            <td colspan="3">{{session()->pull('error_status')}}</td>
          </tr>
        @else
          @foreach ($books as $book)
          <tr>
            <td><b>{{$book->transaction_no}}</b></td>
            <td>
              @if ($book->pic_url == null)
                <img src="{{asset('storage/images/accession_images/noimage.png')}}" width="150" height="150" alt="{{$book->book_title}}" class="img-thumbnail">
              @else
                <img src="{{asset('storage/images/accession_images/' . $book->pic_url)}}" width="150" height="150" alt="{{$book->book_title}}" class="img-thumbnail img-fluid">
              @endif
            </td>
            <td>
              {{$book->book_title}}
            </td>
            <td>
              {{$book->author_name}}
            </td>
            <td>
              {{$book->accession_no}}
            </td>
            <td>
              @foreach ($all_users['users'] as $user)
                @if ($book->user_id == $user->id)
                  @if ($user->user_type == 1)
                    @foreach ($all_users['students'] as $student)
                      @php
                        $m_name = $student->m_name;
                        $m_student_initials = $m_name[0];  
                      @endphp
                      @if ($user->user_ref_id == $student->id)
                        <b>Student:</b><br>
                        {{$student->f_name}}&nbsp;{{$m_student_initials}}.&nbsp;{{$student->l_name}}
                        <br>
                        <b>Email:</b><br>
                        {{$student->email_add}}
                      @endif
                    @endforeach
                  @elseif ($user->user_type == 2)
                    @foreach ($all_users['staff_coach'] as $staff_coach)
                      @php
                        $m_name = $staff_coach->m_name;
                        $m_coach_initials = $m_name[0];  
                      @endphp
                      @if ($user->user_ref_id == $staff_coach->id)
                        <b>Staff/Coach:</b><br>
                        {{$staff_coach->f_name}}&nbsp;{{$m_coach_initials}}.&nbsp;{{$staff_coach->l_name}}
                      @endif
                    @endforeach
                  @endif
                @endif
              @endforeach
            </td>
            <td>
              @if ($book->status == 1)
                <b>Date to be Reserved:</b><br>
                {{date('F jS, Y', strtotime($book->due_date))}}
              @elseif ($book->status == 2)
                <b>Date Reserved:</b><br>
                {{date('F jS, Y', strtotime($book->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($book->due_date))}}
              @elseif ($book->status == 3)
                <b>Date Borrowed:</b><br>
                {{date('F jS, Y', strtotime($book->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($book->due_date))}}
              @elseif ($book->status == 4)
                <b>Date Borrowed:</b><br>
                {{date('F jS, Y', strtotime($book->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($book->due_date))}}
                <br>
                <b>Date user Returned:</b><br>
                {{date('F jS, Y', strtotime($book->return_date))}}
              @elseif ($book->status == 5)
                <b>Date user Returned:</b><br>
                {{date('F jS, Y', strtotime($book->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($book->due_date))}}
              @elseif ($book->status == 8)
                <b>Date Denied:</b><br>
                {{date('F jS, Y', strtotime($book->start_date))}}
                <br>
                <b>Date to be Reserved:</b><br>
                {{date('F jS, Y', strtotime($book->due_date))}}
              @elseif ($book->status == 9)
                <b>Date Cancelled:</b><br>
                {{date('F jS, Y', strtotime($book->start_date))}}
                <br>
                <b>Date to be Reserved:</b><br>
                {{date('F jS, Y', strtotime($book->due_date))}}
              @elseif ($book->status == 10)
                <b>Date Borrowed:</b><br>
                {{date('F jS, Y', strtotime($book->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($book->due_date))}}
              @endif
            </td>
            <td>
              @if ($book->status == 1)
                <span class="text-warning"><b>Pending request</b></span>
              @elseif ($book->status == 2)
                <span class="text-info"><b>Approved</b></span>
                @if ($book->start_date <= date('Y-m-d H:i:s') && $book->due_date >= date('Y-m-d H:i:s'))
                  @if ($book->availability == 2)
                    <br>
                    <span class="text-secondary mt-2">
                      <b>Notes:</b><br>
                      <span class="text-danger">
                        <b>
                          The book is not yet returned from the last user who borrowed it!
                          <br>
                          Cannot proceed on claiming the book!
                        </b>
                      </span>
                    </span>
                  @endif
                @endif
              @elseif ($book->status == 3)
                <span class="text-primary"><b>Borrowed</b></span>
              @elseif ($book->status == 4)
                <span class="text-success"><b>Returned</b></span>
              @elseif ($book->status == 5)
                <span class="text-danger"><b>Damage/Lost</b></span>
              @elseif ($book->status == 8)
                <span class="text-danger"><b>Denied</b></span><br>
                <span class="text-secondary"><b>Notes:</b>&nbsp;
                  {{$book->notes}}
                </span>
              @elseif ($book->status == 9)
                <span class="text-danger"><b>Cancelled</b></span>
              @elseif ($book->status == 10)
                <span class="text-danger"><b>Overdue</b></span><br/>
                <span class="text-secondary"><b>Notes:</b>&nbsp;
                  {{$book->notes}}
                </span>
              @elseif ($book->status == 11)
                <span class="text-danger"><b>Returned & Overdue</b></span><br/>
                <span class="text-secondary"><b>Notes:</b>&nbsp;
                  {{$book->notes}}
                </span>
              @endif
            </td>
            @if ($status_type == 'all_events')
              <td>
                {{date('F jS, Y', strtotime($book->created_at))}}
              </td>
            @else
              <td>
                <a href="{{route('admin.borrowing.view_reservation') . '/' . $book->id}}" class="btn btn-success btn-sm m-1">
                  <b>View&nbsp;<i class="far fa-eye"></i></b>
                </a>
              </td>
            @endif
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>
     {{$books->links()}}
  </div>

  <script type="application/javascript">
  
    function search_form(){
      
      var status_type = '{{$status_type}}';
      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 
      
      $('#search_form').attr('action', form_action_value + '/status_type/' + status_type + '/search/' + input_search );
      
    }

  </script>

@endsection 
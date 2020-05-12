@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => '']);     
  @endphp

  <div class="container-fluid">
    <div class="container">
      <div class="m-3 pt-4 animated fadeInDown">
        <h1 class="text-info display-5 main_h1"><b>My Reservation</b></h1>
      </div>
    </div>

    <div class="container">
      <div class="row box_form my-3">
        <div class="col-12 p-4">
          <ul class="nav justify-content-center">
            <li class="nav-item">
              <a class="nav-link text-info h4 border-bottom border-info" href="{{ route('libraE.my_reservations') }}">
                <b>Books&nbsp;<i class="fas fa-book"></i></b>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-info h4" href="{{ route('libraE.my_reservations.egames') }}">
                <b>Gaming Room&nbsp;<i class="fas fa-gamepad"></i></b>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-info h4" href="{{ route('libraE.my_reservations.rooms') }}">
                <b>Rooms/Areas&nbsp;<i class="fas fa-door-open"></i></b>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-12 my-3">
        @include('inc.status')
        @include('inc.libraE_search')
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row box_form mx-4">
      <div class="col-12 p-5">
        
        <h4 class="text-primary mb-2"><b>Books Reservations</b></h4>

        <form action="{{route('libraE.my_reservations.books.search_book_reservations')}}" id="search_form">
          <div class="row mt-2 mb-4 align-items-center">
            <div class="col-sm-3 my-1">
              <label class="sr-only" for="search">Search</label>
              <input type="text" name="search" id="search" class="form-control" placeholder="Search">
            </div>
    
            <div class="col-auto">
              <button onclick="search_form()" type="submit" class="btn btn-sm btn-primary m-1 font-weight-bold">
                Search&nbsp;<i class="fas fa-search"></i>
              </button>
            </div>
          

            <div class="col-auto my-1">
              <a href="{{route('libraE.my_reservations')}}">
                <button type="button" class="btn btn-sm btn-primary font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
              </a>
            </div>
    
            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Per page: @if (session()->has('book_reservations_per_page')) {{session()->get('book_reservations_per_page', 'default')}} @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_per_page') . '/' . 5}}">5</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_per_page') . '/' . 10}}">10</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_per_page') . '/' . 20}}">20</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_per_page') . '/' . 50}}">50</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_per_page') . '/' . 100}}">100</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_per_page') . '/' . 200}}">200</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_per_page') . '/' . 500}}">500</a>
                </div>
              </div>
            </div>

            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  ToOrder: 
                  @if (session()->has('book_reservations_toOrder')) 
                    @if (session()->get('book_reservations_toOrder') == 'transaction_no')
                      Trans. No.
                    @elseif (session()->get('book_reservations_toOrder') == 'book_title')
                      Title
                    @elseif (session()->get('book_reservations_toOrder') == 'author_name')
                      Author
                    @elseif (session()->get('book_reservations_toOrder') == 'accession_no')
                      Acc. No.
                    @elseif (session()->get('book_reservations_toOrder') == 'due_date')
                      Date
                    @elseif (session()->get('book_reservations_toOrder') == 'updated_at')
                      Latest
                    @endif
                  @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_toOrder') . '/' . 'transaction_no'}}">Trans. No.</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_toOrder') . '/' . 'book_title'}}">Title</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_toOrder') . '/' . 'author_name'}}">Author</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_toOrder') . '/' . 'accession_no'}}">Acc. No.</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_toOrder') . '/' . 'accession_no'}}">Date</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_toOrder') . '/' . 'updated_at'}}">Latest</a>
                </div>
              </div>
            </div>
            
            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  OrderBy: 
                  @if (session()->has('book_reservations_orderBy')) 
                    @if (session()->get('book_reservations_orderBy') == 'asc')
                      Asc
                    @elseif (session()->get('book_reservations_orderBy') == 'desc')
                      Desc
                    @endif
                  @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_orderBy') . '/' . 'asc'}}">Ascending</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.book_reservations_orderBy') . '/' . 'desc'}}">Descending</a>
                </div>
              </div>
            </div>
            
            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-info text-white dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  GetAll: 
                  @if (session()->has('book_reservations_getAll')) 
                    @if (session()->get('book_reservations_getAll') == 'all')
                      All
                    @elseif (session()->get('book_reservations_getAll') == 1)
                      Pending
                    @elseif (session()->get('book_reservations_getAll') == 2)
                      Approved
                    @elseif (session()->get('book_reservations_getAll') == 3)
                      Borrowed
                    @elseif (session()->get('book_reservations_getAll') == 4)
                      Returned
                    @elseif (session()->get('book_reservations_getAll') == 5)
                      Damage/Lost
                    @elseif (session()->get('book_reservations_getAll') == 8)
                      Denied
                    @elseif (session()->get('book_reservations_getAll') == 9)
                      Cancelled
                    @elseif (session()->get('book_reservations_getAll') == 10)
                      Overdue
                    @elseif (session()->get('book_reservations_getAll') == 11)
                      Returned & Overdue
                    @endif
                  @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations') . '/' . 'all'}}">Get All</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations') . '/' . 1}}">Pending</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations')  . '/' . 2}}">Approved</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations')  . '/' . 3}}">Borrowed</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations')  . '/' . 4}}">Returned</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations')  . '/' . 5}}">Damage/Lost</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations')  . '/' . 8}}">Denied</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations')  . '/' . 9}}">Cancelled</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations')  . '/' . 10}}">Overdue</a>
                  <a class="dropdown-item" href="{{route('libraE.my_reservations.books.filter_book_reservations')  . '/' . 11}}">Returned & Overdue</a>
                </div>
              </div>
            </div>
          </div>
        </form>
        
        <table class="table table-hover text-secondary">
          <thead>
            <tr>
              <th scope="col">Trans. No.</th>
              <th scope="col">Image</th>
              <th scope="col">Title</th>
              <th scope="col">Author</th>
              <th scope="col">Acc No.</th>
              <th scope="col">Date</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
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
                    <img src="{{asset('storage/images/accession_images/noimage.png')}}" alt="..." class="img-thumbnail">
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
                  @elseif ($book->status == 11)
                    <b>Date Borrowed:</b><br>
                    {{date('F jS, Y', strtotime($book->start_date))}}
                    <br>
                    <b>Date to be Returned:</b><br>
                    {{date('F jS, Y', strtotime($book->due_date))}}
                    <b>Date user Returned:</b><br>
                    {{date('F jS, Y', strtotime($book->return_date))}}
                    <br>
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
                <td>
                  @if ($book->status == 1)
                    <button onclick="delete_modal_click({{$book->id}} , '{{$book->book_title}}')" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                      <b>Delete&nbsp;<i class="fas fa-trash-alt"></i></b>
                    </button>
                  @elseif ($book->status == 2)
                    <button onclick="delete_modal_click({{$book->id}} , '{{$book->book_title}}')" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                      <b>Cancel&nbsp;<i class="fas fa-trash-alt"></i></b>
                    </button>
                  @endif
                </td>
              </tr>
              @endforeach
            @endif
          </tbody>
        </table>
        {{$books->links()}}
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Are you sure to Cancel/Delete?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="modal_body">
            
          </div>
          <div class="modal-footer">
            <form method="POST" id="delete_form">
              @method('DELETE')
              @csrf
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <input type="text" id="form_delete_url" value="{{route('libraE.my_reservations.books.delete_book_reservations')}}" style="display: none" />

  <script type="application/javascript">
  
    function search_form(){

      var input_search = $('#search').val();
      var form_action_value = $('#search_form').attr('action'); 

      $('#search_form').attr('action', form_action_value + '/'+ input_search );

    }
  
    function delete_modal_click(id, name) {

      $("#modal_body").empty();
      var book_title = '<b>' + name + '</b>';
      $("#modal_body").append(book_title);

      var form_action_value = $('#form_delete_url').val(); 

       $('#delete_form').attr('action', form_action_value + '/'+ id );
    }

  </script>
@endsection
@extends('layouts.appAdmin')

@section('content')

@php
  session(['sidebar_nav' => 'borrowing']);     
  session(['sidebar_nav_lev_2' => '']); 
  session(['point_arrow' => 'borrow_book']);
@endphp

<div class="row">
  <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
    <i class="fas fa-bars"></i>
  </button>

  <h3 class="ml-2 p-1 text-primary"><b>Borrow Book</b></h3>
</div>

@include('inc.status')
@php session()->pull('error_status') @endphp

@include('inc.search')

<div class="row mx-1 mt-4 p-5 card bg-white box_form">
  <div class="col-12">
    <div class="container-fluid">
      <div class="container">
        <div class="m-3 pt-4 animated fadeInDown">
          <h1 class="text-info display-5 main_h1"><b>Reserve Book/s</b></h1>
        </div>
  
        <form action="{{route('admin.borrowing.search_book')}}" id="search_form">
          <div class="row m-3 p-3">
            <div class="col-12">
              <span class="text-secondary h4"><b>Search Books</b></span>
            </div>
            
            <div class="col-md-8 offset-md-1 col-sm-12 my-1">
              <input class="form-control form-control-lg" type="text" name="search" id="search" placeholder="Search Book">
            </div>
            <div class="col-md-3 col-sm-12 my-1">
              <button class="btn btn-lg btn-success" onclick="search_form()" type="submit"><b>Search&nbsp;<i class="fas fa-search"></i></b></button>
            </div>
          </div>
        </form>
  
        <div class="row">
          <div class="col-12 mb-5">
            @include('inc.status')
            @include('inc.libraE_search')
          </div>
        </div>
      </div>
    </div>
  
  
    <div class="container">
      <div class="row box_form mx-4">
        <div class="col-12 p-5">
  
          <div class="row">
            <span class="text-secondary">
              <b>Note:</b> 
              Only those books that has 2 or more available copies can be borrowed.
              The library requires to have atleast one copy of book to be available for the library.
            </span>
          </div>
  
          <div class="row mt-3">
            <h3 class="text-info font-weight-bold">All Available Books</h3>
          </div>
  
          <div class="row mt-2 mb-4">
            <div class="col-auto offset-md-1 my-1">
              <a href="{{route('admin.borrowing.borrow_book')}}">
                <button type="button" class="btn btn-sm btn-primary font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
              </a>
            </div>
    
            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Per page: @if (session()->has('available_books_per_page')) {{session()->get('available_books_per_page', 'default')}} @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_per_page') . '/' . 5}}">5</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_per_page') . '/' . 10}}">10</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_per_page') . '/' . 20}}">20</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_per_page') . '/' . 50}}">50</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_per_page') . '/' . 100}}">100</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_per_page') . '/' . 200}}">200</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_per_page') . '/' . 500}}">500</a>
                </div>
              </div>
            </div>
  
            <div class="col-auto my-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  ToOrder: 
                  @if (session()->has('available_books_toOrder')) 
                    @if (session()->get('available_books_toOrder') == 'book_title')
                      Book Title
                    @elseif (session()->get('available_books_toOrder') == 'author_name')
                      Author
                    @elseif (session()->get('available_books_toOrder') == 'publisher_name')
                      Publisher
                    @elseif (session()->get('available_books_toOrder') == 'copyright')
                      Copyright
                    @elseif (session()->get('available_books_toOrder') == 'created_at')
                      Date Added
                    @endif
                  @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="per_page_btn">
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_toOrder') . '/' . 'book_title'}}">Title</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_toOrder') . '/' . 'author_name'}}">Author</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_toOrder') . '/' . 'publisher_name'}}">Publisher</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_toOrder') . '/' . 'copyright'}}">Copyright</a>
                  <a class="dropdown-item" href="{{route('admin.borrowing.available_books_toOrder') . '/' . 'created_at'}}">Date Added</a>
                </div>
              </div>
            </div>
            
            <div class="col-auto my-1">
                <div class="dropdown">
                  <button class="btn btn-sm btn-secondary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    OrderBy: 
                    @if (session()->has('available_books_orderBy')) 
                      @if (session()->get('available_books_orderBy') == 'asc')
                        Asc
                      @elseif (session()->get('available_books_orderBy') == 'desc')
                        Desc
                      @endif
                    @endif
                  </button>
                  <div class="dropdown-menu" aria-labelledby="per_page_btn">
                    <a class="dropdown-item" href="{{route('admin.borrowing.available_books_orderBy') . '/' . 'asc'}}">Ascending</a>
                    <a class="dropdown-item" href="{{route('admin.borrowing.available_books_orderBy') . '/' . 'desc'}}">Descending</a>
                  </div>
                </div>
              </div>
          </div>
          
  
          <div class="row mt-3">
            @if( session('error_status') )
              <div class="col-12 p-5">
                <h1 class="display-3">{{session()->pull('error_status')}}</h1>
                <img src="{{asset('storage/images/bg/nothing-found.png')}}" width="300" height="300">
              </div>
              <tr>
                <td colspan="3">{{session()->pull('error_status')}}</td>
              </tr>
            @else
              @foreach ($all_books as $book)
                <div class="col-md-4 mb-5">
                  <div class="card shadow_card_book">
                    @if ($book->pic_url == null)
                      <img src="{{asset('storage/images/accession_images/noimage.png')}}" width="300" height="300" alt="{{$book->book_title}}" class="img-thumbnail book_card_image img-fluid">
                    @else
                      <img src="{{asset('storage/images/accession_images/' . $book->pic_url)}}" width="300" height="300" alt="{{$book->book_title}}" class="img-thumbnail book_card_image img-fluid">
                    @endif
                    <div class="card-body">
                      <h5 class="card-title font-weight-bold text-secondary">{{$book->book_title}}</h5>
                      <p class="card-text text-secondary">{{$book->author_name}}</p>
                    </div>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item">{{$book->publisher_name}}</li>
                      <li class="list-group-item">{{$book->copyright}}</li>
                      <li class="list-group-item">
                        <b>No. Copies:</b>
  
                        @php 
                          $no_available = true;
                          $availabe_to_borrow = 0;
                        @endphp
  
                        @foreach ($available_book as $book_ref)
                          @if ($book->accession_id == $book_ref->accession_id)
                            @if ($book_ref->count > 0)
                              {{$book_ref->count}}
                              @php 
                                $no_available = false; 
                                $availabe_to_borrow = $book_ref->count - 1;
                              @endphp
                            @endif
                          @endif
                        @endforeach
                        
                        @if ($no_available)
                          <span class="text-danger">0</span>
                        @endif
                      </li>
  
                      <li class="list-group-item">
                        <b>Available No. Copies for borrowing:</b>
                        @if ($availabe_to_borrow == 0)
                          <span class="text-danger">0</span>
                        @else
                          <span class="text-success">{{$availabe_to_borrow}}</span>
                        @endif
                      </li>
                    </ul>
                    <div class="card-body">
                      <a href="{{route('admin.borrowing.view_book') . '/' . $book->accession_id}}" class="card-link btn btn-success btn-sm m-1">
                        <b>View&nbsp;<i class="far fa-eye"></i></b>
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
  
          <div class="row">
            <div class="col-12">
              <div class="float-right">{{$all_books->links()}}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
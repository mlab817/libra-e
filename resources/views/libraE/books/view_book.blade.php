@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => 'reservations']);     
  @endphp

  <div class="container-fluid">
    <div class="container">
      <div class="m-3 pt-4 animated fadeInDown">
        <h1 class="text-info display-5 main_h1"><b>Reserve Book/s</b></h1>
      </div>

      @include('inc.status')
      
    </div>
  </div>


  <div class="container-fluid">
    <div class="row box_form m-5 p-5">
      <div class="col-md-4">
        <div class="row">
          <div class="col-12 p-3 text-center">
            <h4 class="text-secondary"><b>Image</b></h3>
            <hr>
          </div>

          <div class="col-12 text-center">
            @if ($book->pic_url == null || '')
              <img src="{{asset('storage/images/accession_images/noimage.png')}}" alt="..." class="img-thumbnail">
            @else
              <img src="{{asset('storage/images/accession_images/' . $book->pic_url)}}" width="300" height="300" alt="{{$book->book_title}}" class="img-thumbnail img-fluid">
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-8"> 
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-12 p-3">
                <h4 class="text-secondary"><b>Book Info:</b></h4>
                <hr>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Book Title:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  {{$book->book_title}}
                </h5>
              </div>
            </div>
    
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Author:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  {{$book->author_name}}
                </h5>
              </div>
            </div>
    
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Publisher:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  {{$book->publisher_name}}
                </h5>
              </div>
            </div>
    
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Copyright:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  @if ($book->copyright == null)
                    Unassigned
                  @else
                    {{$book->copyright}}
                  @endif
                </h5>
              </div>
            </div>
    
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Category 1:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  {{$book->category_name}}
                </h5>
              </div>
            </div>
    
            @if ($category_2 != null)
              <div class="row">
                <div class="col-md-4 text-right p-1">
                  <h5 class="text-info"><b>Category 2:</b></h5>
                </div>
      
                <div class="col-md-6 p-1">
                  <h5 class="text-secondary">
                    {{$category_2->name}}
                  </h5>
                </div>
              </div>
            @endif
            
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Illustration:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  @if ($book->illustration_name == null)
                    Unassigned
                  @else
                    {{$book->illustration_name}}
                  @endif
                </h5>
              </div>
            </div>
    
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Edition:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  @if ($book->edition == null)
                    Unassigned
                  @else
                    {{$book->edition}}
                  @endif
                </h5>
              </div>
            </div>
    
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Volume:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  @if ($book->volume == null)
                    Unassigned
                  @else
                    {{$book->volume}}
                  @endif
                </h5>
              </div>
            </div>
    
            <div class="row">
              <div class="col-md-4 text-right p-1">
                <h5 class="text-info"><b>Pages:</b></h5>
              </div>
    
              <div class="col-md-6 p-1">
                <h5 class="text-secondary">
                  @if ($book->pages == null)
                    Unassigned
                  @else
                    {{$book->pages}}
                  @endif
                </h5>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="row">
              <div class="col-12 p-3">
                <h4 class="text-secondary"><b>Availability:</b></h4>
                <hr>
              </div>
            </div>
            
            <div class="row">
              <div class="col-12 p-1">
                <h6 class="text-info"><b>Available No Copies:&nbsp;
                @if ($count > 0)
                  <span class="text-success">{{$count}}</span>
                @else
                  <span class="text-danger">0</span>
                @endif
                </b></h6>
              </div>
              
              <div class="col-12 p-1">
                <h6 class="text-info"><b>Available No Copies for Borrowing:&nbsp;
                @if ($count > 1)
                  <span class="text-success">{{$count - 1}}</span>
                @else
                  <span class="text-danger">0</span>
                  <span class="text-danger m-3"><b>No Available Books!</b></span>
                @endif
                </b></h6>
              </div>

              <calendar-availability 
                url_form="{{route('libraE.books.reserve_book')}}" 
                csrf="{{ csrf_token() }}" 
                user_type="{{ session('loggedIn_user_type') }}" 
                count_accessions='{{$count}}' 
                accession_id='{{$book->accession_id}}' 
                :no_accessions='@json($no_accessions)' 
              />
            </div>
          </div>
        </div>
      </div>

      <hr>

      <div class="col-md-4">
        
        <hr>
        
        <div class="form-row mt-2">
          <div class="form-group col offset-md-1">
            <a href="{{route('libraE.reservations.books')}}" class="btn btn-primary">
              <i class="fas fa-arrow-circle-left"></i>&nbsp;Back
            </a>
          </div>    
        </div>
      </div>
      
      <div class="col-md-8">

        <hr>

        <div class="form-row mt-2">
          @if ($count <= 1)
            <div class="form-group col offset-md-1">
              <span class="text-secondary">
                <b>Note:</b> 
                Only those books that has 2 or more available copies can be borrowed.
                The library requires to have atleast one copy of book to be available for the library.
              </span>
            </div>    
          @endif
          
          <div class="form-group col offset-md-1">
            <span class="text-secondary">
              <b>Note:</b> 
              Upon Approving the request in reserving the book/s to be borrowed, Please claim the book on the reserve date.
              If the book/s is unclaimed in the said date, the request will be invalid thus returned to its availability state.
            </span>
          </div>    
        </div>
      </div>
    </div>
  </div>
@endsection
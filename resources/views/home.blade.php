@extends('layouts.app')

@section('content')

@php
  session(['active_page' => 'home']);     
@endphp

<div class="jumbotron jumbotron-fluid" id="jumbletron_header" >
  <div class="container">
    <div class="bg-white front_box mx-5 p-3">
      <div class="row">
        <div class="col-12 p-5">
          <h1 class="display-1 main_blue_color animated fadeIn">Libra.<span style="color: #ff8c1a;">E</span></h1>
          <p class="lead animated fadeIn">
            Study, Relax, Learn and Play.
          </p>
        </div>
      </div>
    </div>

    <div class="second_box">
      <div class="info_box">
        <div class="row px-5">
          <div class="col-md-6 p-5">
            <div>
              <h1 class="display-1 text-center main_blue_color pt-5">Study</h1>
              <p class="lead text-center text-secondary">
                abooomm yeah nkfkdk aaallss jdsalala.
              </p>
            </div>
          </div>
          
          <div class="col-md-6 p-5">
            <img src="{{asset('storage/images/library_pics/pic_1.jpg')}}" alt="..." class="rounded float-right img-fluid ">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container" style="margin-top:500px;">
  <div class="row px-5">
    <div class="col-md-6 p-5">
      <img src="{{asset('storage/images/library_pics/pic_2.jpg')}}" alt="..." class="rounded float-left img-fluid">
    </div>
    
    <div class="col-md-6 p-5">
      <h1 class="display-1 text-center pt-5" style="color: #ff8c1a;">Relax</h1>
      <p class="lead text-center text-secondary">
        Chill kunyare wala ka thesis.
      </p>
    </div>
    
    <div class="col-md-6 p-5">
      <h1 class="display-1 text-center main_blue_color pt-5">Learn</h1>
      <p class="lead text-center text-secondary">
        Kunyare pra stig.
      </p>
    </div>
    
    <div class="col-md-6 p-5">
      <img src="{{asset('storage/images/library_pics/pic_3.jpg')}}" alt="..." class="rounded float-right img-fluid ">
    </div>

    <div class="col-md-6 p-5">
      <img src="{{asset('storage/images/library_pics/pic_4.jpg')}}" alt="..." class="rounded float-left img-fluid">
    </div>

    <div class="col-md-6 p-5">
      <h1 class="display-1 text-center pt-5" style="color: #ff8c1a;">Play</h1>
      <p class="lead text-center text-secondary">
        rararara rararara.
      </p>
    </div>
  </div>
</div>


<div class="container-fluid bg-white p-5">
  <div class="container pb-5">
    <div class="mx-3 mt-5 p-3 animated fadeInDown text-center">
      <h1 class="main_blue_color display-3 main_h1"><b>Reservations</b></h1>
      
      <p class="lead text-secondary">
        Reserve your wanted book/s, amenities in Libra-E.
      </p>
    </div>
  
    <div class="row">
      <div class="col">
        <div class="card-deck">
          <div class="card shadow_card animated flipInY mb-3">
            <a href="{{ route('libraE.reservations.books') }}" style="text-decoration:none">
              <img class="card-img-top reserv_images" src="{{asset('storage/images/bg/borrow_book.jpg')}}" alt="Reserve Books">
              <div class="card-body">
                <h5 class="card-title simple_black_color"><b>Reserve Books</b></h5>
                <p class="card-text text-secondary">Reserve the book/s you wish to borrow</p>
              </div>
            </a>
          </div>
  
          <div class="card shadow_card animated flipInY mb-3">
            <a href="{{ route('libraE.reservations.egames') }}" style="text-decoration:none">
              <img class="card-img-top reserv_images" src="{{asset('storage/images/bg/gaming_room.jpg')}}" alt="Reserve Books">
              <div class="card-body">
                <h5 class="card-title simple_black_color"><b>Reserve Gaming/Research Room</b></h5>
                <p class="card-text text-secondary">Reserve your time slot for the Gaming Room.</p>
              </div>
            </a>
          </div>  
  
          <div class="card shadow_card animated flipInY mb-3">
            <a href="{{ route('libraE.reservations.rooms') }}" style="text-decoration:none">
              <img class="card-img-top reserv_images" src="{{asset('storage/images/bg/areas_library.jpg')}}" alt="Reserve Books">
              <div class="card-body">
                <h5 class="card-title simple_black_color"><b>Reserve Rooms/Areas of the Library</b></h5>
                <p class="card-text text-secondary">Reserve the Rooms/Areas of the library for your friends or group usage.</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
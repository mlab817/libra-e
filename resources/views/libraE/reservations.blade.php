@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => 'reservations']);     
  @endphp

  <div class="container">
    <div class="m-3 p-3 animated fadeInDown text-center">
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

    <div class="row m-5"></div>
    
  </div>
  

@endsection

@php
 if (session()->has('loggedIn_user_data')){
    $user = session('loggedIn_user_data');
    $user_full_name = $user->f_name . ' ' . $user->l_name;
    $user_pic_url = $user->pic_url;
 } 
@endphp

<nav class="navbar navbar-expand-lg navbar-light text-secondary p-4 bg-white nav_border_bot">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav" aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="container">
    <div class="collapse navbar-collapse" id="main_nav">
      
      <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{asset('storage/images/bg/libra_e_icon.png')}}" width="150" height="150" alt="Libra.E" class="img-fluid">
      </a>
      
      <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        <li class="nav-item @if ( session('active_page') == 'home' ) active @endif">
          <a class="nav-link h5" href="{{ route('home') }}"><b>Home</b></a>
        </li>
        
        @auth
          <li class="nav-item @if ( session('active_page') == 'reservations' ) active @endif">
            <a class="nav-link h5 " href="{{ route('libraE.reservations_view') }}"><b>Reservations</b></a>
          </li>
        @endauth
        
        <li class="nav-item">
          <a class="nav-link h5 " href="#"><b>About Us</b></a>
        </li>

        <li class="nav-item">
          <a class="nav-link h5 " href="#"><b>Contact Us</b></a>
        </li>
        
      </ul>
      
      <ul class="navbar-nav justify-content-end mx-2">
        @auth
          <li class="nav-item text-center">
            <p class="text-secondary m-3 ">Welcome,&nbsp;<b>{{$user_full_name}}</b></p>
          </li>
          
          <li class="nav-item py-3">
            <button type="button" class="btn btn-light btn-sm">
              <i class="fas fa-bell"></i><span class="badge badge-danger">1</span>
            </button>
          </li>

          <li class="nav-item">
            <div class="dropdown">
              <button class="btn btn-link text-secondary dropdown-toggle" type="button" id="accountButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @if (session('loggedIn_user_type') == 1)
                  <img src="{{asset('storage/images/student_images/' . $user_pic_url)}}" alt="..." width="45" height="45" class="rounded-circle">
                @elseif (session('loggedIn_user_type') == 2)    
                  <img src="{{asset('storage/images/staff_coach_images/' . $user_pic_url)}}" alt="..." width="45" height="45" class="rounded-circle">
                @endif
              </button>
              <div class="dropdown-menu dropdown-menu-right animated fadeIn" aria-labelledby="accountButton">
                <a class="dropdown-item text-secondary" href="{{ route('libraE.my_account') }}"><b>My Account&nbsp;<i class="fas fa-user-circle"></i></b></a>
                <a class="dropdown-item text-secondary" href="{{ route('libraE.my_reservations') }}"><b>My Reservations&nbsp;<i class="fas fa-map-marked-alt"></i></b></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-secondary" href="{{ route('logout') }}"><b>Log-Out&nbsp;<i class="fas fa-sign-out-alt"></i></b></a>
              </div>
            </div>
          </li>
        @endauth

        @guest
          <li class="nav-item">
            <button class="btn btn-success main_green_yellow_bg text-white my-2 my-sm-0" type="button" data-toggle="modal" data-target="#loginModal">
              <b>Login&nbsp;<i class="fas fa-sign-in-alt"></i></b>
            </button>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav> 

<div class="m-0">
  @yield('content')
</div>
  

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border: none;">
      <div class="center_form">
        <div class="row">
          
          <div class="col-12 ">
            <h1 style="color: #1165F6"><b>Libra-E</b></h1>
            <hr>
          </div>
  
          @if ($errors->any() || session('invalid_login'))
            <div class="alert alert-danger alert-dismissible animated shake col-12" role="alert">
              Invalid email or Password!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
  
          <div class="col-12">
            <form action="{{ route('login') }}" method="post" class="dashboard_form">
              @csrf
              <div class="form-group">
                <label class="text-secondary" for="email">Email Address</label>
                <input 
                  type="email" 
                  class="form-control bg_inputs @error('email') is-invalid @enderror" 
                  id="email" 
                  name="email" 
                  placeholder="Enter email"
                  required
                >
  
                @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <div class="form-group">
                <label class="text-secondary" for="password">Password</label>
                <input 
                  type="password" 
                  class="form-control bg_inputs @error('password') is-invalid @enderror" 
                  id="password" 
                  name="password" 
                  placeholder="Password"
                  required
                >
  
                @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <button type="submit" class="btn btn-primary btn-block">Submit</button>
              
            </form>
          </div>

          <div class="col-12 p-3 mt-2 text-secondary">
            <p>No account yet?&nbsp;<span class="font-weight-normal">Contact the Library Office</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  @php $login_error = false @endphp

  @foreach ($errors->all() as $error)
    @if ($error == 'These credentials do not match our records.')
      @php $login_error = true @endphp
    @endif
  @endforeach

<script type="application/javascript">
  var error_login = @if ($login_error) 1 @else 0 @endif ;

  if(error_login){
    setTimeout( function(){
      $(document).ready(function() {
        $('#loginModal').modal('show')
      });
    }, 900 );
  }
</script>
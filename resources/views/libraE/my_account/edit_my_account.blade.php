@extends('layouts.app')

@section('content')
  @php
    session(['active_page' => '']);     
  @endphp
  
  <div class="container">
    <div class="row">
      <div class="col-12 my-3">
        @include('inc.status')
      </div>
    </div>
  </div>
  
  <div class="container">
    <div class="row box_form mb-5 mt-4">
      <div class="col-12 m-3 p-4 animated fadeInDown">
        <h1 class="text-info display-5 main_h1"><b>Edit Account <i class="far fa-edit"></i></b></h1>
      </div>

      <div class="col-md-4 mt-2">
        <div class="row">
          <div class="col-12">
            <h4 class="text-info font-weight-bold px-3 m-1">Picture</h4>
            <hr>
          </div>

          <div class="col-12 px-5 pb-5 pt-3">
            @if (session()->get('loggedIn_user_type') == 1)
              @if ($all_user_data->pic_url == null)
                <img src="{{asset('storage/images/student_images/noimage.png')}}" alt="..." class="img-thumbnail">
              @else
                <img src="{{asset('storage/images/student_images/' . $all_user_data->pic_url)}}" alt="..." class="img-thumbnail">
              @endif
            @elseif (session()->get('loggedIn_user_type') == 2)
              @if ($all_user_data->pic_url == null)
                <img src="{{asset('storage/images/staff_coach_images/noimage.png')}}" alt="..." class="img-thumbnail">
              @else
                <img src="{{asset('storage/images/staff_coach_images/' . $all_user_data->pic_url)}}" alt="..." class="img-thumbnail">
              @endif
            @endif
          </div>
        </div>
      </div>
      
      <div class="col-md-8 mt-2">
        <div class="row">
          <div class="col-12">
            <h4 class="text-info font-weight-bold px-3">Account Info
              <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#change_password">
                <b>Change Password&nbsp;<i class="fas fa-user-lock"></i></b>
              </button>
            </h4> 
            <hr>
          </div>

          <form action="{{route('libraE.update_my_account')}}" class="col-12" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-row animated fadeIn">
              <div class="form-group col-md-2 text-right p-1">
                <span class="text-info"><b>First Name*</b></span>
              </div>
    
              <div class="form-group col-md-6">
                <input type="text" name="f_name" value="{{ $all_user_data->f_name }}" class="form-control text-secondary @error('f_name') is-invalid @enderror" placeholder="First name" required> 
    
                @error('f_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
    
            <div class="form-row animated fadeIn">
              <div class="form-group col-md-2 text-right p-1">
                <span class="text-info"><b>Middle Name*</b></span>
              </div>
    
              <div class="form-group col-md-6">
                <input type="text" name="m_name" value="{{ $all_user_data->m_name }}" class="form-control text-secondary @error('m_name') is-invalid @enderror" placeholder="Middle name" required> 
    
                @error('m_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
    
            <div class="form-row animated fadeIn">
              <div class="form-group col-md-2 text-right p-1">
                <span class="text-info"><b>Last Name*</b></span>
              </div>
    
              <div class="form-group col-md-6">
                <input type="text" name="l_name" value="{{ $all_user_data->l_name }}" class="form-control text-secondary @error('l_name') is-invalid @enderror" placeholder="Last name" required> 
    
                @error('l_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            
            <div class="form-row animated fadeIn">
              <div class="form-group col-md-2 text-right p-1">
                <span class="text-info"><b>Contact No.*</b></span>
              </div>
    
              <div class="form-group col-md-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><b>+63-</b></span>
                  </div>
              
                  <input type="number" name="contact_no" value="{{ $all_user_data->contact_no }}" class="form-control text-secondary @error('contact_no') is-invalid @enderror" placeholder="Contact no" required> 
    
                  @error('contact_no')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="form-row animated fadeIn">
              <div class="form-group col-md-2 text-right p-1">
                <span class="text-info"><b>Email Address*</b></span>
              </div>
    
              <div class="form-group col-md-6">
                <input type="email" name="email_add" value="{{ $all_user_data->email_add }}" class="form-control text-secondary @error('email_add') is-invalid @enderror" placeholder="Email Address" required> 
    
                @error('email_add')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-row animated fadeIn">
              <div class="form-group col-md-2 text-right p-1">
                <span class="text-info"><b>Address*</b></span>
              </div>
    
              <div class="form-group col-md-6">
                <input type="text" name="address" value="{{ $all_user_data->address }}" class="form-control text-secondary @error('address') is-invalid @enderror" placeholder="Address" required> 
    
                @error('address')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-2 text-right p-1">
                <span class="text-info"><b>Change Picture</b></span>
              </div>
            
              <div class="form-group col-md-6">
                <input type="file" name="pic_file" accept="image/x-png,image/jpeg" class="form-control-file @error('pic_file') is-invalid @enderror" id="pic_cover">

                <input name="pic_name" value="{{$all_user_data->pic_url}}" style="display:none;" />
                @error('pic_file')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-row mb-5 text-center">
              <a href="{{route('libraE.my_account')}}" class="btn btn-secondary m-2">
                <b><i class="fas fa-arrow-circle-left"></i>&nbsp;Back</b>
              </a>
              <button type="submit" class="btn btn-primary m-2"><b>Submit&nbsp;</b></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<form action="{{route('libraE.update_my_account_password')}}" method="POST" enctype="multipart/form-data">

  <div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="change_passwordLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="change_passwordLabel">Change Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="row">
            <div class="col-12">
              @csrf
              @method('PUT')
              
              <div class="form-row animated fadeIn">
                <div class="form-group col-md-4 text-right p-1">
                  <span class="text-info"><b>Current Password*</b></span>
                </div>
      
                <div class="form-group col-md-8">
                  <input type="password" name="current_password" value="{{ old('current_password') }}" class="form-control text-secondary @error('current_password') is-invalid @enderror" placeholder="Current Password" required> 
      
                  @error('current_password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-row animated fadeIn">
                <div class="form-group col-md-4 text-right p-1">
                  <span class="text-info"><b>New Password*</b></span>
                </div>
      
                <div class="form-group col-md-8">
                  <input type="password" name="new_password" value="{{ old('new_password') }}" class="form-control text-secondary @error('new_password') is-invalid @enderror" placeholder="New Password" required> 
      
                  @error('new_password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-row animated fadeIn">
                <div class="form-group col-md-4 text-right p-1">
                  <span class="text-info"><b>Repeat new Password*</b></span>
                </div>
      
                <div class="form-group col-md-8">
                  <input type="password" name="rep_new_password" value="{{ old('rep_new_password') }}" class="form-control text-secondary @error('rep_new_password') is-invalid @enderror" placeholder="Repeat New Password" required> 
      
                  @error('rep_new_password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

</form>


@endsection
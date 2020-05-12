@extends('layouts.app_login')

@section('content')
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form style="margin: auto; margin-top: 50px" method="POST" action="{{ route('admin.register') }}">
    @csrf
    <div class="form-group row">
      <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

      <div class="col-md-6">
        <input 
          id="username" 
          type="text" 
          class="form-control 
          @error('username') is-invalid @enderror" 
          name="username" 
          value="{{ old('username') }}" 
          required
        >

        @error('username')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

      <div class="col-md-6">
        <input 
          id="first_name"
          type="text"
          class="form-control 
          @error('first_name') is-invalid @enderror" 
          name="first_name" 
          value="{{ old('first_name') }}" 
          required
        >

        @error('first_name')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="middle_name" class="col-md-4 col-form-label text-md-right">{{ __('Middle Name') }}</label>

      <div class="col-md-6">
        <input 
          id="middle_name"
          type="text"
          class="form-control 
          @error('middle_name') is-invalid @enderror" 
          name="middle_name" 
          value="{{ old('middle_name') }}" 
          required
        >

        @error('middle_name')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

      <div class="col-md-6">
        <input 
          id="last_name"
          type="text"
          class="form-control 
          @error('last_name') is-invalid @enderror" 
          name="last_name" 
          value="{{ old('last_name') }}" 
          required
        >

        @error('last_name')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="admin_role" class="col-md-4 col-form-label text-md-right">{{ __('Admin Role') }}</label>

      <div class="col-md-6">
        <select class="form-control @error('admin_role_id') is-invalid @enderror" id="admin_role" name="admin_role_id" >
        
          <option value="">---Select Role---</option>
          
            @foreach ($admin_roles as $admin_role)
              <option value="{{ $admin_role->id }}">{{ $admin_role->description }}</option>
            @endforeach

        </select>

        @error('admin_role_id')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

      <div class="col-md-6">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

        @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
        </div>
    </div>

    <div class="form-group row">
      <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

      <div class="col-md-6">
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
      </div>
    </div>

    <div class="form-group row mb-0">
      <div class="col-md-6 offset-md-4">
        <button type="submit" class="btn btn-primary">
          {{ __('Register') }}
        </button>
      </div>
    </div>

  </form>
    
@endsection


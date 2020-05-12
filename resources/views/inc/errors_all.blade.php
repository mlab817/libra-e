

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible animated shake row m-1 mt-2 p-1" role="alert">
    <ul class="m-1">
      @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


@if ( session('book_exist') )
  <div class="alert alert-info alert-dismissible animated pulse row m-1 mt-2 p-1" role="alert">
    <b><i class="fas fa-exclamation-circle"></i>&nbsp;{{session()->pull('book_exist')}}</b>
    <button type="button" class="close p-1 pr-2" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif 
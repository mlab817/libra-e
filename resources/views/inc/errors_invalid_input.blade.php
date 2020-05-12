

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible animated shake row m-1 p-1" role="alert">
    <b><i class="fas fa-exclamation-circle"></i>&nbsp;Invalid Input!</b>
    <button type="button" class="close p-1 pr-2" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
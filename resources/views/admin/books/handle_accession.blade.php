@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'books']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'accessioning']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Handle Accession</b></h3>
  </div>

  @include('inc.errors_all')

  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">
    <div class="col-12">
      <div class="row">
        <div class="col-5">

          <div class="form-row">
            <div class="form-group col-12 p-1">
              <h3 class="text-primary"><b>Handle Accession</b></h3>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 text-right p-1">
              <span class="text-info h4"><b>Accession No:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary h4">{{$accession->accession_no}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 text-right p-1">
              <span class="text-info h4"><b>Author Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary h4">{{$accession->author_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 text-right p-1">
              <span class="text-info h4"><b>Book Title:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary h4">{{$accession->book_title}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 text-right p-1">
              <span class="text-info h4"><b>Publisher Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary h4">{{$accession->publisher_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 text-right p-1">
              <span class="text-info h4"><b>Copyright:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary h4">
                @if ($accession->copyright == null)
                  <span class="text-warning h4">Unassigned</span>
                @else
                  {{$accession->copyright}}
                @endif
              </span>
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="form-group col offset-md-4">
              <a href="{{route('admin.books.accessioning')}}" class="btn btn-primary">
                <i class="fas fa-arrow-circle-left"></i>&nbsp;Back
              </a>
            </div>    
          </div>
        </div>

        <div class="col-6">
          <div class="row">
            <div class="col-12 p-1 text-center">
              <span class="text-info h4">
                <b>Actions</b>
              </span>
            </div>

            <div class="col-12 p-1 text-center">
              <button onclick="action_modal_click('{{route('admin.books.move_accession')}}' ,{{$accession->accession_no_id}} , '{{$accession->accession_no}}', '{{$accession->author_name}}', '{{$accession->book_title}}', 'Weeded')" type="button" class="btn btn-warning text-black" data-toggle="modal" data-target="#actionModal">
                <b>Add to Weeded&nbsp;<i class="fas fa-archive"></i></b>
              </button>
            </div>
            
            <div class="col-12 p-1 text-center">
              <button onclick="action_modal_click('{{route('admin.books.move_accession')}}' ,{{$accession->accession_no_id}} , '{{$accession->accession_no}}', '{{$accession->author_name}}', '{{$accession->book_title}}', 'Lost')" type="button" class="btn btn-danger" data-toggle="modal" data-target="#actionModal">
                  <b>Add to Lost&nbsp;<i class="fas fa-question-circle"></i></b>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--Action Modal -->
  <div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="actionModalLabel">Are you sure to move Accession?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="modal_body">
            
          </div>
          <div class="modal-footer">
            <form method="POST" id="action_form">
              @method('DELETE')
              @csrf
              <input type="text" id="type" name="type" style="display:none;" />
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  <script type="application/javascript">
  
    function action_modal_click(url, id, code, author_name, book_title, type) {
      
      $("#modal_body").empty();
      var b_name = '<span><b class="text-secondary">' + code + ' : '+ author_name + '&nbsp;|&nbsp;' + book_title +'&nbsp; : Add to </b></span><b class="text-danger">' + type + '</b>';
      $("#modal_body").append(b_name);

      $("#type").val(type);

      $('#action_form').attr('action', url + '/'+ id );

    }
  </script>

@endsection




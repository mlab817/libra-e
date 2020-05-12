@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'accountabilities']);     
    session(['sidebar_nav_lev_2' => 'accountabilities_all_users_ul']); 
    session(['point_arrow' => '']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Print User Accountabilites</b></h3>
  </div>

  @include('inc.status')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <div class="col-12">
      <div class="row">
  
        <div class="col-12 mb-3 text-center">
          <a href="{{route('admin.accountabilities.all_students')}}">
            <button type="button" class="btn btn-primary m-1 font-weight-bold"><i class="fas fa-arrow-circle-left"></i>&nbsp;Back to Students</button>
          </a>
  
          <a href="{{route('admin.accountabilities.all_coaches')}}">
            <button type="button" class="btn btn-primary m-1 font-weight-bold"><i class="fas fa-arrow-circle-left"></i>&nbsp;Back to Coaches</button>
          </a>
  
          <a href="{{route('admin.accountabilities.view_user_accountability') . '/' . $user_id}}">
            <button type="button" class="btn btn-primary m-1 font-weight-bold">Refresh&nbsp;<i class="fas fa-sync-alt"></i></button>
          </a>
        </div>
        
        @php $damage_lost_show = false; @endphp
        @php $receipt_show = false; @endphp

        <div id="damage_lost_div" class="col-12 my-2 animated fadeIn" style="display:none;">
          <div class="row">
            <div class="col-12 my-1 text-center">
              <h3 class="text-danger"><b>Damage/Lost Borrowed Books</b></h3>
            </div>

            <div class="col-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Trans No.</th>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Acc. No.</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($accountabilities as $accountability)
                    @if ($accountability->accountability_type == 3)
                      @php $damage_lost_show = true; @endphp
                      <tr>
                        <td><b>{{$accountability->transaction_no}}</b></td>
                        <td>
                          @if ($accountability->pic_url == null)
                            <img src="{{asset('storage/images/accession_images/noimage.png')}}" width="80" height="80" alt="{{$accountability->book_title}}" class="img-thumbnail">
                          @else
                            <img src="{{asset('storage/images/accession_images/' . $accountability->pic_url)}}" width="80" height="80" alt="{{$accountability->book_title}}" class="img-thumbnail img-fluid">
                          @endif
                        </td>
                        <td>{{$accountability->book_title}}</td>
                        <td>{{$accountability->accession_no}}</td>
                        <td>{{$accountability->borrowed_book_notes}}</td>
                        <td>
                          <button type="button" class="btn btn-success" onclick="mark_settled_modal_click({{$accountability->id}} , '{{$accountability->book_title}}', '{{$accountability->borrowed_book_notes}}')" data-toggle="modal" data-target="#mark_settledModal">
                            <b>Mark as Settled&nbsp;<i class="fas fa-thumbs-up"></i></b>
                          </button>
                        </td>
                      </tr>
                    @elseif ($accountability->accountability_type == 2)
                      @php $receipt_show = true; @endphp
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        
        @if ($receipt_show)
          <div class="col-12 mb-4 text-center">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pay_receipt">
              <b>Mark as Paid&nbsp;<i class="fas fa-thumbs-up"></i></b>
            </button>
          </div>

          <div class="col-12 text-center">
            <iframe id="receipt_iframe" height="800" src="{{route('admin.accountabilities.print_receipt') . '/' . $user_id}}" class="border border-primary"></iframe>
          </div>
        @endif
      </div>
    </div>
  </div>


  <!-- Mark Settled Modal -->
  <div class="modal fade" id="mark_settledModal" tabindex="-1" role="dialog" aria-labelledby="mark_settledModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary" id="mark_settledModalLabel"><b>Are you sure to mark lost/damage as settled?</b></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="p-1 text-secondary">
            <span id='mark_settled_info' class="h5"></span><br>
            <span><b>Notes:&nbsp;</b>
            <span id='mark_settled_notes'></span>
          </div>
        </div>
        <div class="modal-footer">
          <form method="POST" action="{{route('admin.accountabilities.mark_settled')}}" id="delete_form">
            @csrf
            <input id="accountability_id" type="number" name="accouuntability_id" style="display:none;">
            <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success font-weight-bold">Mark as Settled&nbsp;<i class="fas fa-thumbs-up"></i></button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="pay_receipt" tabindex="-1" role="dialog" aria-labelledby="pay_receiptLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{route('admin.accountabilities.mark_paid')}}" method="POST">
          @csrf
          
          <input type="number" name="user_id" value="{{$user_id}}" style="display:none;">
          
          <div class="modal-header">
            <h5 class="modal-title text-primary" id="pay_receiptLabel"><b>Mark as Paid</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <span class="text-primary">
                  <b>Fill up data fields:</b>
                </span>
              </div>

              <div class="col-12 mt-2">
                <span class="text-primary">
                  <b>Invoice No:*</b>
                </span>
                <br>
                <input type="number" min="201800000000000" max="232000000000000" name="invoice_no" value="{{ old('invoice_no') }}" class="form-control text-secondary @error('invoice_no') is-invalid @enderror" placeholder="Invoice No" required>

                @error('invoice_no')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="col-12 mt-2">
                <span class="text-primary">
                  <b>Admin user pin code:*</b>
                </span>
                <br>
                <input type="number" min="0000" max="9999" name="pin_code" value="{{ old('pin_code') }}" class="form-control text-secondary @error('pin_code') is-invalid @enderror" placeholder="Pin Code" required>

                @error('pin_code')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success font-weight-bold">Mark as Paid&nbsp;<i class="fas fa-thumbs-up"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script type="application/javascript">

    var damage_lost_show = '{{$damage_lost_show}}';
    
    if(damage_lost_show){
      setTimeout( function(){
        $(document).ready(function() {
          $("#damage_lost_div").show();
        });
      }, 10 );
    }

    function mark_settled_modal_click(id, book_title, notes) {

      $("#mark_settled_info").empty();
      var b_book_title = '<b>' + book_title + '</b>';
      $("#mark_settled_info").append(b_book_title);

      $("#mark_settled_notes").empty();
      $("#mark_settled_notes").append(notes);

      $("#accountability_id").val(id);

    }

  </script>
  
@endsection 
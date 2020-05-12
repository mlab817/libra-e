@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'borrowing']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => $reservation_data['point_arrow']]);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>View Book Reservation</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4 p-5 card bg-white box_form">
    <div class="col-12">
      <div class="row">
        <div class="col-5">

          <div class="form-row">
            <div class="form-group col-12 p-1">
              <h3 class="text-primary"><b>Book Reservation</b></h3>
            </div>
          </div>

          <div class="row">
            <div class="col-12 text-center">
              @if ($reservation->pic_url == null)
                <img src="{{asset('storage/images/accession_images/noimage.png')}}" alt="..." class="img-thumbnail">
              @else
                <img src="{{asset('storage/images/accession_images/' . $reservation->pic_url)}}" width="150" height="150" alt="{{$reservation->book_title}}" class="img-thumbnail img-fluid">
              @endif
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-md-6 text-right p-1">
              <span class="text-info h4"><b>Transaction No:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary h4">{{$reservation->transaction_no}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 text-right p-1">
              <span class="text-info h4"><b>Book Title:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary h4">{{$reservation->book_title}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 text-right p-1">
              <span class="text-info h4"><b>Author Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary h4">{{$reservation->author_name}}</span>
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="form-group col offset-md-4">
              <a href="{{$reservation_data['url_back']}}" class="btn btn-primary">
                <i class="fas fa-arrow-circle-left"></i>&nbsp;Back
              </a>
            </div>    
          </div>
        </div>

        <div class="col-6">
          <div class="row">
            <div class="col-12 p-1">
              <span class="text-info h4">
                <b>Availability</b>
              </span>
            </div>

            <div class="col-12 text-secondary p-2">
              @php
                $m_name = $user_data['user']->m_name;
                $m_initials = $m_name[0];  
              @endphp
              <h5><b>Reserved By:&nbsp;</b>
                {{$user_data['user']->f_name}}&nbsp;{{$m_initials}}.&nbsp;{{$user_data['user']->l_name}}&nbsp;
                @if ($user_data['user_type'] == 1)
                  (Student)
                @elseif ($user_data['user_type'] == 2)
                  (Staff/Coach)
                @endif
              </h5>
              
              <h5><b>Email:&nbsp;</b>
                {{$user_data['user']->email_add}}
              </h5>
              
              <h5><b>Phone No:&nbsp;</b>
                +63-{{$user_data['user']->contact_no}}
              </h5>
            </div>

            <div class="col-12 text-secondary p-2">
              <h5><b>
                Status:&nbsp;
                <span class="{{$reservation_data['color_class']}}">
                  @if ($reservation->status == 5)
                    Damage/Lost  
                  @elseif ($reservation->status == 11)
                    Returned & Overdue  
                  @else
                    {{ucfirst($reservation_data['point_arrow'])}}
                  @endif
                </span>
              </b></h5>
              
              @if ($reservation->status == 8 || $reservation->status == 10 || $reservation->status == 11)
                <h5><b>Notes:&nbsp;</b>
                  <span class="text-secondary">{{$reservation->notes}}</span>
                </h5>
              @endif
            </div>
            
            <div class="col-12 text-secondary p-2">
              @if ($reservation->status == 1)
                <b>Date to be Reserved:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @elseif ($reservation->status == 2)
                <b>Date Reserved:</b><br>
                {{date('F jS, Y', strtotime($reservation->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @elseif ($reservation->status == 3)
                <b>Date Borrowed:</b><br>
                {{date('F jS, Y', strtotime($reservation->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @elseif ($reservation->status == 4)
                <b>Date user Borrowed:</b><br>
                {{date('F jS, Y', strtotime($reservation->start_date))}}
                <br>
                <b>Date prescribed to be Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
                <br>
                <b>Date user Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->return_date))}}
              @elseif ($reservation->status == 5)
                <b>Date user Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @elseif ($reservation->status == 8)
                <b>Date Denied:</b><br>
                {{date('F jS, Y', strtotime($reservation->start_date))}}
                <br>
                <b>Date to be Reserved:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @elseif ($reservation->status == 9)
                <b>Date Cancelled:</b><br>
                {{date('F jS, Y', strtotime($reservation->start_date))}}
                <br>
                <b>Date to be Reserved:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @elseif ($reservation->status == 10)
                <b>Date Borrowed:</b><br>
                {{date('F jS, Y', strtotime($reservation->start_date))}}
                <br>
                <b>Date to be Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
              @elseif ($reservation->status == 11)
                <b>Date user Borrowed:</b><br>
                {{date('F jS, Y', strtotime($reservation->start_date))}}
                <br>
                <b>Date prescribed to be Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->due_date))}}
                <br>
                <b>Date user Returned:</b><br>
                {{date('F jS, Y', strtotime($reservation->return_date))}}
              @endif
            </div>

            <div class="col-12">
              <admin-calendar-availability 
                borrow_id="{{$reservation->id}}" 
                accession_no_id="{{$reservation->accession_no_id}}" 
                status="{{$reservation->status}}" 
                start_date="{{$reservation->start_date}}"
                due_date="{{$reservation->due_date}}"
              />
            </div>

            <form action="{{$reservation_data['form_url']}}" method="POST">
              @csrf
              
              <input name="borrow_id" value="{{$reservation->id}}" style="display:none;" /> 

              <input name="accession_id" value="{{$reservation->accession_id}}" style="display:none;" /> 

              <input name="accession_no_id" value="{{$reservation->accession_no_id}}" style="display:none;" /> 

              <input name="start_date" type="text" value="{{$reservation->start_date}}" style="display:none;" /> 

              <input name="due_date" type="text" value="{{$reservation->due_date}}" style="display:none;" /> 
              
              <div class="col-12 mt-3">
                @if ($reservation->status == 1 || ($reservation->status == 8 && $reservation->due_date >= date('Y-m-d H:i:s')))
                  <button type="submit" class="btn btn-primary mx-2">
                    <b>Approve&nbsp;<i class="fas fa-thumbs-up"></i></b>
                  </button>
                @endif

                @php
                  if ($reservation->start_date <= date('Y-m-d H:i:s') && $reservation->due_date >= date('Y-m-d H:i:s')){
                    
                    $disable = false;

                    if($reservation->availability == 2){
                      $unreturned_yet = true;
                    }else{
                      $unreturned_yet = false;
                    }

                  }else{
                    $disable = true;
                    $unreturned_yet = false;
                  }

                  
                @endphp

                @if ($reservation->status == 2)
                  <button type="submit" class="btn btn-primary mx-2" @if ($disable) disabled @endif @if ($unreturned_yet) disabled @endif>
                    <b><i class="fas fa-hand-holding"></i>&nbsp;Claim Reserve Book</b>
                  </button>
                @elseif ($reservation->status == 3 || $reservation->status == 10)
                  <button type="submit" class="btn btn-success mx-2">
                    <b>Return Book&nbsp;<i class="fas fa-undo"></i></b>
                  </button>

                  <button type="button" class="btn btn-danger mx-2" data-toggle="modal" data-target="#damage_lost_Modal">
                    <b>Add to Damage/Lost&nbsp;<i class="fas fa-heart-broken"></i></i></b>
                  </button>
                @elseif ($reservation->status == 4)
                <!--
                  <button type="submit" class="btn btn-danger mx-2">
                    <b>Unreturn Book&nbsp;<i class="fas fa-undo"></i></b>
                  </button>
                -->
                @endif

                @if ($reservation->status == 1 || $reservation->status == 2)
                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denyModal">
                    <b>Deny&nbsp;<i class="fas fa-ban"></i></b>
                  </button>
                @endif

                <br>
                
                @if ($unreturned_yet && $reservation->status == 2)
                  <br>
                  <span class="text-secondary mt-2">
                    <b>Note:</b> 
                    <span class="text-danger">
                      <b>
                        The book is not yet returned from the last user who borrowed it!
                        <br>
                        Cannot proceed on claiming the book!
                      </b>
                    </span>
                  </span>
                @endif

                @if ($reservation->status == 2)
                  <br>
                  <span class="text-secondary mt-2">
                    <b>Note:</b> 
                    Can only be claimed if today is within the reserved date!
                  </span>
                @elseif ($reservation->status == 10)
                  <br>
                  <span class="text-secondary mt-2">
                    <b>Note:</b> 
                    <span class="text-danger">The book is to be Returned long overdue!</span>
                  </span>
                @elseif ($reservation->status == 3)
                  <br>
                  <span class="text-secondary mt-2">
                    <b>Note:</b> 
                    Only the Head or Assist. Librarian can add the books to Damage/Lost!
                  </span>
                @endif
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Deny Modal -->
  <div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form method="POST" action="{{route('admin.borrowing.deny_reservation') . '/' . $reservation->id }}" id="delete_form">
        @method('DELETE')
        @csrf

        <input type="text" name="type" value="@if ($reservation->status == 1) deny_request @elseif ($reservation->status == 2) deny_approved @endif" style="display:none;" />
        
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="denyModalLabel">Are you sure to Deny Reservation?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          
          <div class="modal-body" id="modal_body">
            <span><b class="text-secondary">{{$reservation->book_title}}</b></span>

            <div class="form-group">
              <label for="notes">Please Leave a Note:</label>
              <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3" required></textarea>
              
              @error('notes')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Deny</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Damage/Lost Modal -->
  <div class="modal fade" id="damage_lost_Modal" tabindex="-1" role="dialog" aria-labelledby="damage_lostModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form method="POST" action="{{route('admin.borrowing.damage_lost_reservation') . '/' . $reservation->id }}" id="delete_form">
        @method('DELETE')
        @csrf
        
        <input name="acc_id" value="{{$reservation->accession_id}}" style="display:none;" />

        <input name="accession_no_id" value="{{$reservation->accession_no_id}}" style="display:none;" />

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="denyModalLabel">Are you sure to add to damage/lost the borrowed book?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          
          <div class="modal-body" id="modal_body">
            <span><b class="text-secondary">{{$reservation->book_title}}</b></span>

            <div class="form-group">
              <label for="notes">Please Leave a Note:</label>
              <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3" required></textarea>
              
              @error('notes')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Add to Damage/Lost</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <script type="application/javascript">
    var error_input = @if ($errors->all()) 1 @else 0 @endif ;
  
    if(error_input){
      setTimeout( function(){
        $(document).ready(function() {
          $('#denyModal').modal('show')
        });
      }, 900 );
    }
  </script>
  
@endsection 
@extends('layouts.for_print')

@section('content')

  @php
   $book_unreturned = false;   
  @endphp

  <div class="text-center mt-3">
    <button id="print_receipt_btn" class="btn btn-primary mb-2" onclick="myFunction()"><b>Print Invoice&nbsp;<i class="fas fa-print"></i></b></button>
    
    <br>

    <span id="unreturned_note" style="display:none" class="text-danger animated shake"><b>User Borrowed books is unreturned Yet!</b></span>
  </div>
  

  <div class="mx-5 mt-3 mb-5 pb-4 row receipt_accountaibility">
    <div class="col-3 offset-9 text-right pm-2 pt-2">
      @if ($user_data['user_type'] == 1)
        <span class="main_sky_blue_color">Student Copy</span>
      @elseif ($user_data['user_type'] == 2)
        <span class="main_sky_blue_color">Employee Copy</span>
      @endif
    </div>
    
    <div class="col-12 p-2">
      <div class="row">
        <div class="col-6 text-center">
          <h1 class="display-5 main_green_blue_color mt-5"><b>Invoice</b></h1>
        </div>
        <div class="col-6">
          <img src="{{asset('storage/images/bg/libra_e_icon.png')}}" width="150" height="150" alt="Libra.E" class="img-fluid ml-5">
        </div>
      </div>

      <div class="hr_border_color mx-3"></div>

    </div>

    @php
      $current_date = date('Y-m-dH');
      $no_date = str_replace("-", "", $current_date);

      $num = $user_data['user']->user_id;
      $num = (string)$num;
      $num_length = strlen($num);


      for($i = $num_length; $i < 5; $i++){
          $num = "0" . $num;
      }

      $invoice_no = $no_date . $num;

      $m_name = $user_data['user']->m_name;
      $m_intitals = $m_name[0];
    @endphp

    <div class="col-12 p-2 mx-3">
      <div class="row">
        <div class="col-5 py-1">
          <div class="row">
            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">To:&nbsp;</b>{{$user_data['user']->f_name . ' ' . $m_intitals . '. ' . $user_data['user']->l_name}}</span>
            </div>

            <div class="col-12 py-1">
              @if ($user_data['user_type'] == 1)
                <span class="main_green_blue_color h5"><b class="main_sky_blue_color">StudentID:&nbsp;</b>{{$user_data['user']->stud_id_no}}</span>
              @elseif ($user_data['user_type'] == 2)
                <span class="main_green_blue_color h5"><b class="main_sky_blue_color">EmployeeID:&nbsp;</b>{{$user_data['user']->emp_id_no}}</span>
              @endif
            </div>

            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">Invoice No:&nbsp;</b>{{$invoice_no}}</span>
            </div>
          </div>
        </div>
        
        <div class="col-7 py-1">
          <div class="row">
            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">From:&nbsp;</b>STI Munoz EDSA</span>
            </div>

            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">Issued & Valid only on:&nbsp;</b>{{date('F jS, Y')}}</span>
            </div>
    
            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">Due:&nbsp;</b>{{date('F jS, Y')}}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 mt-2 p-2">
      <div class="row">
        <div class="col-2">
          <span class="main_sky_blue_color h5 ml-4"><b>Trans No:</b></span>
        </div>
        
        <div class="col-7">
          <span class="main_sky_blue_color h5"><b>Description:</b></span>
        </div>

        <div class="col-3">
          <span class="main_sky_blue_color h5"><b>Price:</b></span>
        </div>

        <div class="col-12">
          <div class="hr_border_color mx-3 my-2"></div>
        </div>
      </div>

      <div class="row">
        @php
          $total_price = 0;
        @endphp

        @foreach ($receipts as $receipt)
          @if ($receipt->accountability_type == 2)
            <div class="col-2">
              <span class="main_green_blue_color ml-4">{{$receipt->transaction_no}}</span>
            </div>
            
            @php
              if($receipt->accountability_type == 2){
                if($receipt->borrowed_book_status == 10){

                  $book_unreturned = true;

                  $current_date = date('Y-m-d H:i:s'); 
                  $total_days = strtotime($current_date) - strtotime($receipt->due_date);
                  $days = round($total_days / (60 * 60 * 24));
    
                  $base_payment = 10;
    
                  $penalty_price = $base_payment + ($days * 5);
    
                  $total_price += $penalty_price;
                  
                }else if($receipt->borrowed_book_status == 11){
                  
                  $current_date = date('Y-m-d H:i:s'); 
                  $total_days = strtotime($receipt->return_date) - strtotime($receipt->due_date);
                  $days = round($total_days / (60 * 60 * 24));
    
                  $base_payment = 10;
    
                  $penalty_price = $base_payment + ($days * 5);
    
                  $total_price += $penalty_price;
                  
                }
                
              }else if($receipt->accountability_type == 1){

                $unclaimed_price = 10;
                
                $total_price += $unclaimed_price;
                
              }
            @endphp

            <div class="col-7">
              @if ($receipt->borrowed_book_id != null || '') 
              
                <span class="main_green_blue_color ">{{$receipt->book_title}}</span>
                
                @if ($receipt->accountability_type == 1)
                  <span class="text-danger">(Approved but did not claim.)</span>
                @elseif ($receipt->accountability_type == 2)
                  <span class="text-danger">
                    @if ($receipt->borrowed_book_status == 10)
                      (Overdue) Unreturned Yet!
                    @elseif ($receipt->borrowed_book_status == 11)
                      (Returned & Overdue)
                    @endif
                    &nbsp;{{$days}}&nbsp;Days</span>
                @elseif ($receipt->accountability_type == 2)
                  <span class="text-danger">(Damage/Lost)</span>
                @endif
              @endif
            </div>
            
            <div class="col-3">
              @if ($receipt->accountability_type == 2)
                <span class="main_green_blue_color">&#8369;{{$penalty_price}}</span>
              @elseif ($receipt->accountability_type == 1)
              <span class="main_green_blue_color">&#8369;{{$unclaimed_price}}</span>
              @endif
            </div>
          @endif
        @endforeach

        <div class="col-12">
          <div class="hr_border_color mx-3 my-2"></div>
        </div>

        <div class="col-3 offset-9">
          <span class="main_green_blue_color"><b class="main_sky_blue_color">Total Price:&nbsp;</b>&#8369;{{$total_price}}</span>
        </div>
      </div>

      <div class="row text-center mt-5">
        <div class="col-6">
          <div class="hr_border_color mx-5 my-2"></div>
          @if ($user_data['user_type'] == 1)
            <span class="main_sky_blue_color"><b>Student Signature</b></span>
          @elseif ($user_data['user_type'] == 2)
            <span class="main_sky_blue_color"><b>Employee Signature</b></span>
          @endif
        </div>
        
        <div class="col-6">
          <div class="hr_border_color mx-5 my-2"></div>
          <span class="main_sky_blue_color"><b>Librarian Signature</b></span>
        </div>
      </div>
    </div>
  </div>

  <div class="m-5 pb-4 row receipt_accountaibility">
    <div class="col-3 offset-9 text-right pm-2 pt-2">
      <span class="main_sky_blue_color">Library Copy</span>
    </div>
    
    <div class="col-12 p-2">
      <div class="row">
        <div class="col-6 text-center">
          <h1 class="display-5 main_green_blue_color mt-5"><b>Invoice</b></h1>
        </div>
        <div class="col-6">
          <img src="{{asset('storage/images/bg/libra_e_icon.png')}}" width="150" height="150" alt="Libra.E" class="img-fluid ml-5">
        </div>
      </div>

      <div class="hr_border_color mx-3"></div>

    </div>


    <!-- Library Copy -->

    
    <div class="col-12 p-2 mx-3">
      <div class="row">
        <div class="col-5 py-1">
          <div class="row">
            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">To:&nbsp;</b>{{$user_data['user']->f_name . ' ' . $m_intitals . '. ' . $user_data['user']->l_name}}</span>
            </div>

            <div class="col-12 py-1">
              @if ($user_data['user_type'] == 1)
                <span class="main_green_blue_color h5"><b class="main_sky_blue_color">StudentID:&nbsp;</b>{{$user_data['user']->stud_id_no}}</span>
              @elseif ($user_data['user_type'] == 2)
                <span class="main_green_blue_color h5"><b class="main_sky_blue_color">EmployeeID:&nbsp;</b>{{$user_data['user']->emp_id_no}}</span>
              @endif
            </div>

            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">Invoice No:&nbsp;</b>{{$invoice_no}}</span>
            </div>
          </div>
        </div>
        
        <div class="col-7 py-1">
          <div class="row">
            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">From:&nbsp;</b>STI Munoz EDSA</span>
            </div>

            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">Issued & Valid only on:&nbsp;</b>{{date('F jS, Y')}}</span>
            </div>
    
            <div class="col-12 py-1">
              <span class="main_green_blue_color h5"><b class="main_sky_blue_color">Due:&nbsp;</b>{{date('F jS, Y')}}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 mt-2 p-2">
      <div class="row">
        <div class="col-2">
          <span class="main_sky_blue_color h5 ml-4"><b>Trans No:</b></span>
        </div>
        
        <div class="col-7">
          <span class="main_sky_blue_color h5"><b>Description:</b></span>
        </div>

        <div class="col-3">
          <span class="main_sky_blue_color h5"><b>Price:</b></span>
        </div>

        <div class="col-12">
          <div class="hr_border_color mx-3 my-2"></div>
        </div>
      </div>

      <div class="row">
        @php
          $total_price = 0;
        @endphp

        @foreach ($receipts as $receipt)
          @if ($receipt->accountability_type == 2)
            <div class="col-2">
              <span class="main_green_blue_color ml-4">{{$receipt->transaction_no}}</span>
            </div>

            @php 
              if($receipt->accountability_type == 2){
                if($receipt->borrowed_book_status == 10){  

                  $current_date = date('Y-m-d H:i:s'); 
                  $total_days = strtotime($current_date) - strtotime($receipt->due_date);
                  $days = round($total_days / (60 * 60 * 24));

                  $base_payment = 10;

                  $penalty_price = $base_payment + ($days * 5);

                  $total_price += $penalty_price;

                }else if($receipt->borrowed_book_status == 11){

                  $current_date = date('Y-m-d H:i:s'); 
                  $total_days = strtotime($receipt->return_date) - strtotime($receipt->due_date);
                  $days = round($total_days / (60 * 60 * 24));

                  $base_payment = 10;

                  $penalty_price = $base_payment + ($days * 5);

                  $total_price += $penalty_price;

                }
                
              }else if($receipt->accountability_type == 1){

                $unclaimed_price = 10;

                $total_price += $unclaimed_price;
              }    
            @endphp

            <div class="col-7">
              @if ($receipt->borrowed_book_id != null || '') 
              
                <span class="main_green_blue_color ">{{$receipt->book_title}}</span>
                
                @if ($receipt->accountability_type == 1)
                  <span class="text-danger">(Approved but did not claim.)</span>
                @elseif ($receipt->accountability_type == 2)
                  <span class="text-danger">
                    @if ($receipt->borrowed_book_status == 10)
                      (Overdue) Unreturned Yet!
                    @elseif ($receipt->borrowed_book_status == 11)
                      (Returned & Overdue)
                    @endif
                    &nbsp;{{$days}}&nbsp;Days</span>
                @elseif ($receipt->accountability_type == 2)
                  <span class="text-danger">(Damage/Lost)</span>
                @endif
              @endif
            </div>
            
            <div class="col-3">
              @if ($receipt->accountability_type == 2)
                <span class="main_green_blue_color">&#8369;{{$penalty_price}}</span>
              @elseif ($receipt->accountability_type == 1)
                <span class="main_green_blue_color">&#8369;{{$unclaimed_price}}</span>
              @endif
            </div>
          @endif
        @endforeach

        <div class="col-12">
          <div class="hr_border_color mx-3 my-2"></div>
        </div>

        <div class="col-3 offset-9">
          <span class="main_green_blue_color"><b class="main_sky_blue_color">Total Price:&nbsp;</b>&#8369;{{$total_price}}</span>
        </div>
      </div>

      <div class="row text-center mt-5">
        <div class="col-6">
          <div class="hr_border_color mx-5 my-2"></div>
          @if ($user_data['user_type'] == 1)
            <span class="main_sky_blue_color"><b>Student Signature</b></span>
          @elseif ($user_data['user_type'] == 2)
            <span class="main_sky_blue_color"><b>Employee Signature</b></span>
          @endif
        </div>
        
        <div class="col-6">
          <div class="hr_border_color mx-5 my-2"></div>
          <span class="main_sky_blue_color"><b>Librarian Signature</b></span>
        </div>
      </div>
    </div>
  </div>

  <script type="application/javascript">

    var book_unreturned = '{{$book_unreturned}}';
    
    function myFunction() {
      if(book_unreturned == false || book_unreturned == 2){
        window.print(); 
      }
    }
    
    if(book_unreturned){
      setTimeout( function(){
        $(document).ready(function() {
          $("#print_receipt_btn").addClass("disabled");
          $("#unreturned_note").show();
        });
      }, 900 );
    }
  </script>
  
@endsection 
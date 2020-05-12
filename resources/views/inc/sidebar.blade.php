<div id="wrapper" class="toggled">

  <div id="sidebar-wrapper">
    <ul class="sidebar-nav">
      <li class="sidebar-brand">
        <a href="{{ route('admin.dashboard.main_dashboard') }}" class="h2 mt-2 p-2"><b>Libra-E</b></a>
      </li>

       <li class="li_divider"></li>

      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#dashboard_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="dashboard_ul"
        >

          <i class="fas fa-chart-line"></i>
            Dashboard
          <i class="fas fa-caret-down"></i>
          
        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'dashboard' ) show @endif" 
          id="dashboard_ul"
        >
          <li>
            <a href="{{ route('admin.dashboard.main_dashboard') }}">
              <i class="fas fa-chart-area"></i>
              Libra-E Dashboard 
              @if (( session('point_arrow') == 'main_dashboard' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>
        </ul>
      </li>

      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#reports_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="reports_ul"
        >
        
          <i class="fas fa-chart-bar"></i>
            Reports
          <i class="fas fa-caret-down"></i>

        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'reports' ) show @endif" 
          id="reports_ul"
        >
          <li>
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#attendance_reports_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="attendance_reports_ul"
            >
            
              <i class="fas fa-clipboard"></i>
                Attendance
              <i class="fas fa-caret-down"></i>
    
            </a>
    
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'attendance_reports_ul' ) show @endif" 
              id="attendance_reports_ul"
            >
              <li> 
                <a href="{{ route('admin.reports.attendance_scanner') }}">
                  <i class="fas fa-barcode"></i>
                  Attendance Scanner
                  @if (( session('point_arrow') == 'attendance_scanner' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li> 
                <a href="{{ route('admin.reports.all_reports') . '/' . 'attendance' }}">
                  <i class="fas fa-clipboard"></i>
                  Attendance
                  @if (( session('point_arrow') == 'attendance_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li> 
                <a href="{{ route('admin.reports.all_usage_reports') . '/' . 'attendance_usage' }}">
                  <i class="fas fa-clipboard-list"></i>
                  Usage
                  @if (( session('point_arrow') == 'attendance_usage_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>

          <li>
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#book_reports_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="book_reports_ul"
            >
            
              <i class="fas fa-book"></i>
                Books
              <i class="fas fa-caret-down"></i>
    
            </a>
    
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'book_reports_ul' ) show @endif" 
              id="book_reports_ul"
            >
              <li> 
                <a href="{{ route('admin.reports.accession_reports_view') }}">
                  <i class="fas fa-book-open"></i>
                  Book Aquisitions
                  @if (( session('point_arrow') == 'accession_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
    
              <li>  
                <a href="{{ route('admin.reports.thesis_book_reports_view') }}">
                  <i class="fas fa-swatchbook"></i>
                  Thesis Aquisitions
                  @if (( session('point_arrow') == 'thesis_book_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
              
              <li>  
                <i class="far fa-lightbulb"></i>
                Inspirational
              </li>
              
              <li>  
                <i class="fas fa-bookmark"></i>
                Journal
              </li>
            </ul>
          </li>

          <li>
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#borrowing_report_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="borrowing_report_ul"
            >
            
              <i class="fas fa-book-reader"></i>
                Borrowing
              <i class="fas fa-caret-down"></i>
            </a>
    
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'borrowed_books_reports_ul' ) show @endif" 
              id="borrowing_report_ul"
            >
              <li>  
                <a href="{{ route('admin.reports.all_reports') . '/' . 'borrowed_books' }}">
                  <i class="fas fa-book-reader"></i>
                    Borrowing
                  @if (( session('point_arrow') == 'borrowed_books_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li>  
                <a href="{{ route('admin.reports.all_reports') . '/' . 'all_borrowed' }}">
                  <i class="fas fa-hand-holding"></i>
                    All Borrowed
                  @if (( session('point_arrow') == 'all_borrowed_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>

          <li>
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#egames_report_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="egames_report_ul"
            >
            
              <i class="fas fa-desktop"></i>
                E-games/Research
              <i class="fas fa-caret-down"></i>
            </a>
    
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'egames_reports_ul' ) show @endif" 
              id="egames_report_ul"
            >
              <li>  
                <a href="{{ route('admin.reports.all_reports') . '/' . 'egames' }}">
                  <i class="fas fa-desktop"></i>
                  E-games/Research
                  @if (( session('point_arrow') == 'egames_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li>  
                <a href="{{ route('admin.reports.all_usage_reports') . '/' . 'egames_usage' }}">
                  <i class="fas fa-clipboard-list"></i>
                  Usage
                  @if (( session('point_arrow') == 'egames_usage_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>

          <li>
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#rooms_report_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="rooms_report_ul"
            >
            
              <i class="fas fa-door-open"></i>
                Rooms
              <i class="fas fa-caret-down"></i>
            </a>
    
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'rooms_reports_ul' ) show @endif" 
              id="rooms_report_ul"
            >
              <li>  
                <a href="{{ route('admin.reports.all_reports') . '/' . 'rooms' }}">
                  <i class="fas fa-door-open"></i>
                  Rooms
                  @if (( session('point_arrow') == 'rooms_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li>  
                <a href="{{ route('admin.reports.all_usage_reports') . '/' . 'rooms_usage' }}">
                  <i class="fas fa-clipboard-list"></i>
                  Usage
                  @if (( session('point_arrow') == 'rooms_usage_reports' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>

        </ul>
      </li>

      <li class="li_divider"></li>
      
      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#borrowing_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="borrowing_ul"
        >
        
          <i class="fas fa-book-reader"></i>
            Borrowing
          <i class="fas fa-caret-down"></i>

        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'borrowing' ) show @endif" 
          id="borrowing_ul"
        >
          <li>
            <a href="{{route('admin.borrowing.borrow_book')}}">
              <i class="fas fa-book-open"></i>
              Borrow Book
              @if (( session('point_arrow') == 'borrow_book' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>

          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/pending'}}">
              <i class="fas fa-exclamation"></i>
              Pending Request
              @if (( session('point_arrow') == 'pending' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>
          
          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/approved'}}">
              <i class="fas fa-thumbs-up"></i>
              Approved To be Borrowed
              @if (( session('point_arrow') == 'approved' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>
          
          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/borrowed'}}">
              <i class="fas fa-hand-holding"></i>
              Borrowed
              @if (( session('point_arrow') == 'borrowed' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>
            
          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/returned'}}">
              <i class="fas fa-undo-alt"></i>
              Returned
              @if (( session('point_arrow') == 'returned' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/damage_lost'}}">
              <i class="fas fa-heart-broken"></i>
              Damage/Lost
              @if (( session('point_arrow') == 'damage_lost' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/denied'}}">
              <i class="fas fa-ban"></i>
              Denied
              @if (( session('point_arrow') == 'denied' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>
          
          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/cancelled'}}">
              <i class="fas fa-window-close"></i>
              Cancelled
              @if (( session('point_arrow') == 'cancelled' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/overdue'}}">
              <i class="fas fa-clock"></i>
              Overdue
              @if (( session('point_arrow') == 'overdue' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/returned_overdue'}}">
              <i class="fas fa-undo-alt"></i><i class="fas fa-clock"></i>
              Returned & Overdue
              @if (( session('point_arrow') == 'returned_overdue' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/all_transactions'}}">
              <i class="fas fa-clipboard-list"></i>
              All Transactions
              @if (( session('point_arrow') == 'all_transactions' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.borrowing.all_book_reservations') . '/all_events'}}">
              <i class="far fa-calendar-alt"></i>
              All Events
              @if (( session('point_arrow') == 'all_events' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>
        </ul>
      </li>  

      <li class="li_divider"></li>

      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#egames_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="egames_ul"
        >
        
          <i class="fas fa-desktop"></i>
            E-Games/Research
          <i class="fas fa-caret-down"></i>

        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'egames' ) show @endif" 
          id="egames_ul"
        >
          <li>
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#egames_reservations_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="egames_reservations_ul"
            >
            
              <i class="far fa-calendar-alt"></i>
                Reservations
              <i class="fas fa-caret-down"></i>
    
            </a>
            
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'egames_reservations_ul' ) show @endif" 
              id="egames_reservations_ul"
            >
              <li>
                <a href="{{route('admin.egames.reservation.reserve_now')}}">
                  <i class="fas fa-play"></i>
                  Reserve Now
                  @if (( session('point_arrow') == 'egames_reserve_now' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
    
              <li>
                <a href="{{route('admin.egames.reservation.egames_reservation') . '/pending'}}">
                  <i class="fas fa-exclamation"></i>
                  Pending Request
                  @if (( session('point_arrow') == 'egames_pending' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
              
              <li>
                <a href="{{route('admin.egames.reservation.egames_reservation') . '/reserved'}}">
                  <i class="fas fa-thumbs-up"></i>
                  Reserved
                  @if (( session('point_arrow') == 'egames_reserved' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
              
              <li>
                <a href="{{route('admin.egames.reservation.egames_reservation') . '/playing'}}">
                  <i class="fas fa-play-circle"></i>
                  Active Playing
                  @if (( session('point_arrow') == 'egames_playing' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a>
              </li>
                
              <li>
                <a href="{{route('admin.egames.reservation.egames_reservation') . '/finished'}}">
                  <i class="fas fa-check-circle"></i>
                  Finished
                  @if (( session('point_arrow') == 'egames_finished' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a>
              </li>
    
              <li>
                <a href="{{route('admin.egames.reservation.egames_reservation') . '/denied'}}">
                  <i class="fas fa-ban"></i>
                  Denied
                  @if (( session('point_arrow') == 'egames_denied' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a>
              </li>
              
              <li>
                <a href="{{route('admin.egames.reservation.egames_reservation') . '/cancelled'}}">
                  <i class="fas fa-window-close"></i>
                  Cancelled
                  @if (( session('point_arrow') == 'egames_cancelled' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a>
              </li>
    
              <li>
                <a href="{{route('admin.egames.reservation.egames_reservation') . '/all_transactions'}}">
                  <i class="fas fa-clipboard-list"></i>
                  All Transactions
                  @if (( session('point_arrow') == 'egames_all_transactions' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a>
              </li>
    
              <li>
                <a href="{{route('admin.egames.reservation.egames_reservation') . '/all_events'}}">
                  <i class="far fa-calendar-alt"></i>
                  All Events
                  @if (( session('point_arrow') == 'egames_all_events' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a>
              </li>
            </ul>
          </li>
  
          <li>
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#egames_settings_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="egames_settings_ul"
            >
            
              <i class="fas fa-cog"></i>
                Slots & Settings
              <i class="fas fa-caret-down"></i>
    
            </a>
            
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'egames_settings_ul' ) show @endif" 
              id="egames_settings_ul"
            >
              <li>
                <a href="{{route('admin.egames.slots_settings.pc_slots')}}">
                  <i class="fas fa-desktop"></i>
                  PC Slots
                  @if (( session('point_arrow') == 'pc_slots' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li>
                <a href="{{route('admin.egames.slots_settings.egames_settings')}}">
                  <i class="fas fa-cog"></i>
                  Settings
                  @if (( session('point_arrow') == 'egames_settings' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>
        </ul>
      </li>
      
      <li class="li_divider"></li>

      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#rooms_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="rooms_ul"
        >
        
          <i class="fas fa-door-open"></i>
            Rooms
          <i class="fas fa-caret-down"></i>

        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'rooms' ) show @endif" 
          id="rooms_ul"
        >
          <li>
            <a href="{{route('admin.rooms.reservation.reserve_now')}}">
              <i class="fas fa-door-closed"></i>
              Reserve Now
              @if (( session('point_arrow') == 'rooms_reserve_now' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>

          <li>
            <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/pending'}}">
              <i class="fas fa-exclamation"></i>
              Pending Request
              @if (( session('point_arrow') == 'rooms_pending' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>
          
          <li>
            <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/reserved'}}">
              <i class="fas fa-thumbs-up"></i>
              Reserved
              @if (( session('point_arrow') == 'rooms_reserved' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>
          
          <li>
            <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/on_use'}}">
              <i class="fas fa-toggle-on"></i>
              On Use
              @if (( session('point_arrow') == 'rooms_on_use' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>
            
          <li>
            <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/finished'}}">
              <i class="fas fa-check-circle"></i>
              Finished
              @if (( session('point_arrow') == 'rooms_finished' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/denied'}}">
              <i class="fas fa-ban"></i>
              Denied
              @if (( session('point_arrow') == 'rooms_denied' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>
          
          <li>
            <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/cancelled'}}">
              <i class="fas fa-window-close"></i>
              Cancelled
              @if (( session('point_arrow') == 'rooms_cancelled' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/all_transactions'}}">
              <i class="fas fa-clipboard-list"></i>
              All Transactions
              @if (( session('point_arrow') == 'rooms_all_transactions' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>

          <li>
            <a href="{{route('admin.rooms.reservation.rooms_reservation') . '/all_events'}}">
              <i class="far fa-calendar-alt"></i>
              All Events
              @if (( session('point_arrow') == 'rooms_all_events' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>
        </ul>
      </li>
      
      <li class="li_divider"></li>

      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#accountabilities_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="accountabilities_ul"
        >
        
          <i class="fas fa-receipt"></i>
            Accountabilities
          <i class="fas fa-caret-down"></i>

        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'accountabilities' ) show @endif" 
          id="accountabilities_ul"
        >

          <li> 
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#accountabilities_all_users_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="accountabilities_all_users_ul"
            >
              <i class="fas fa-users"></i>
              All Users
              <i class="fas fa-caret-down"></i>
            </a> 
            
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'accountabilities_all_users_ul' ) show @endif" 
              id="accountabilities_all_users_ul"
            >
              <li>  
                <a href="{{ route('admin.accountabilities.all_students') }}">
                  <i class="fas fa-user"></i>
                  Students
                  @if ( session('point_arrow') == 'all_students' )
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
              <li>  
                <a href="{{ route('admin.accountabilities.all_coaches') }}">
                  <i class="fas fa-user-tie"></i>
                  Staff/Coaches
                  @if ( session('point_arrow') == 'all_coaches' )
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>

          <li> 
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#accountabilities_books_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="accountabilities_books_ul"
            >
              <i class="fas fa-book"></i>
              Books
              <i class="fas fa-caret-down"></i>
            </a> 
            
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'accountabilities_books_ul' ) show @endif" 
              id="accountabilities_books_ul"
            >
              <li>  
                <a href="{{ route('admin.accountabilities.student_books') }}">
                  <i class="fas fa-book-reader"></i>
                  Students
                  @if ( session('point_arrow') == 'student_books' )
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
              <li>  
                <a href="{{ route('admin.accountabilities.coach_books') }}">
                  <i class="fas fa-book-reader"></i>
                  Staff/Coaches
                  @if ( session('point_arrow') == 'coach_books' )
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>

          <li> 
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#accountabilities_invoices_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="accountabilities_invoices_ul"
            >
              <i class="fas fa-file-invoice"></i>
              Invoices
              <i class="fas fa-caret-down"></i>
            </a> 
            
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'accountabilities_invoices_ul' ) show @endif" 
              id="accountabilities_invoices_ul"
            >
              <li>  
                <a href="{{ route('admin.accountabilities.invoices') . '/students' }}">
                  <i class="fas fa-user"></i>
                  Students
                  @if ( session('point_arrow') == 'invoices_students' )
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
              <li>  
                <a href="{{ route('admin.accountabilities.invoices') . '/coaches' }}">
                  <i class="fas fa-user-tie"></i>
                  Staff/Coaches
                  @if ( session('point_arrow') == 'invoices_coaches' )
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>
        </ul>
      </li>

      <li class="li_divider"></li>
      
      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#books_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="books_ul"
        >
        
          <i class="fas fa-book"></i>
            Books
          <i class="fas fa-caret-down"></i>

        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'books' ) show @endif" 
          id="books_ul"
        >
          <li> 
            <a href="{{ route('admin.books.accessioning') }}">
              <i class="fas fa-book-open"></i>
              Accessions
              @if (( session('point_arrow') == 'accessioning' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>

          <li>  
            <a href="{{ route('admin.thesis.thesis_books') }}">
              <i class="fas fa-swatchbook"></i>
              Thesis Books
              @if (( session('point_arrow') == 'thesis_books' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>
          
          <li>  
            <i class="far fa-lightbulb"></i>
            Inspirational
          </li>
          
          <li>  
            <i class="fas fa-bookmark"></i>
            Journal
          </li>
        </ul>
      </li>

      <li class="li_divider"></li>
        
      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#file_maintenance_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="file_maintenance_ul"
        >
        
          <i class="far fa-file-code"></i>
            File Maintenance
          <i class="fas fa-caret-down"></i>

        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'file_maintenance' ) show @endif" 
          id="file_maintenance_ul"
        >
          <li> 
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#file_maintenance_books_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="file_maintenance_books_ul"
            >
              <i class="fas fa-book"></i>
              Books
              <i class="fas fa-caret-down"></i>
            </a> 
            
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'file_maintenance_books_ul' ) show @endif" 
              id="file_maintenance_books_ul"
            >

              <li> 
                <a href="{{ route('admin.file_maintenance.classifications') }}">
                  <i class="fas fa-shapes"></i>
                  Classifications
                  @if (( session('point_arrow') == 'classifications' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li> 
                <a href="{{ route('admin.file_maintenance.categories') }}">
                  <i class="fas fa-list-ul"></i>
                  Categories
                  @if (( session('point_arrow') == 'categories' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
    
              <li>  
                <a href="{{ route('admin.file_maintenance.authors') }}">
                  <i class="fas fa-pen-alt"></i>
                    Authors
                  @if (( session('point_arrow') == 'authors' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
              
              <li>  
                <a href="{{ route('admin.file_maintenance.publishers') }}">
                  <i class="fas fa-newspaper"></i>
                  Publishers
                  @if ( ( session('point_arrow') == 'publishers' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
    
              <li>  
                <a href="{{ route('admin.file_maintenance.illustrations') }}">
                  <i class="fas fa-pencil-ruler"></i>
                  Illustrations
                  @if (( session('point_arrow') == 'illustrations' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
    
              <li>  
                <a href="{{ route('admin.file_maintenance.tags') }}">
                  <i class="fas fa-tags"></i>
                  Tags
                  @if (( session('point_arrow') == 'tags' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

            </ul>
          </li>

          <li> 
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#file_maintenance_thesis_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="file_maintenance_thesis_ul"
            >
              <i class="fas fa-swatchbook"></i>
              Thesis Books
              <i class="fas fa-caret-down"></i>
            </a> 
            
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'file_maintenance_thesis_ul' ) show @endif" 
              id="file_maintenance_thesis_ul"
            >
              <li> 
                <a href="{{ route('admin.file_maintenance.system_types') }}">
                  <i class="fas fa-server"></i>
                  System Types
                  @if (( session('point_arrow') == 'system_types' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li> 
                <a href="{{ route('admin.file_maintenance.thesis_categories') }}">
                  <i class="fas fa-list-ul"></i>
                  Thesis Categories
                  @if (( session('point_arrow') == 'thesis_categories' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>

              <li> 
                <a href="{{ route('admin.file_maintenance.thesis_subjects') }}">
                  <i class="fas fa-laptop-code"></i>
                  Thesis Subjects
                  @if (( session('point_arrow') == 'thesis_subjects' ))
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>
          
          <li> 
            <a 
              class="h5" 
              data-toggle="collapse" 
              href="#file_maintenance_accounts_ul" 
              role="button" 
              aria-expanded="false" 
              aria-controls="file_maintenance_accounts_ul"
            >
              <i class="fas fa-users"></i>
              Accounts
              <i class="fas fa-caret-down"></i>
            </a> 
            
            <ul 
              class="collapse @if ( session('sidebar_nav_lev_2') == 'file_maintenance_accounts_ul' ) show @endif" 
              id="file_maintenance_accounts_ul"
            >
              <li>  
                <a href="{{ route('admin.file_maintenance.departments') }}">
                  <i class="fas fa-chalkboard-teacher"></i>
                  Departments
                  @if ( session('point_arrow') == 'departments' )
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
              <li>  
                <a href="{{ route('admin.file_maintenance.programs') }}">
                  <i class="fas fa-chalkboard"></i>
                  Course/Programs
                  @if ( session('point_arrow') == 'programs' )
                    &nbsp;<i class="fas fa-caret-right"></i>  
                  @endif
                </a> 
              </li>
            </ul>
          </li>
        </ul>
      </li>

      <li class="li_divider"></li>

      <li>
        <a 
        class="h5" 
        data-toggle="collapse" 
        href="#rfid_ul" 
        role="button" 
        aria-expanded="false" 
        aria-controls="rfid_ul"
        >
        
        <i class="fas fa-microchip"></i>
        RFID
        <i class="fas fa-caret-down"></i>
        
      </a>
        <ul 
          class="collapse @if ( session('sidebar_nav') == 'rfid' ) show @endif" 
          id="rfid_ul"
        >

          <li>
            <a href="{{route('admin.rfid.all_users') . '/' . 'all_students'}}">
              <i class="far fa-id-card"></i>
              All Students RFID 
              @if (( session('point_arrow') == 'all_students_rfid' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>

          <li>
            <a href="{{route('admin.rfid.all_users') . '/' . 'all_coaches'}}">
              <i class="fas fa-address-card"></i>
              All Staff/Coaches RFID 
              @if (( session('point_arrow') == 'all_coaches_rfid' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>
        
        </ul>
      </li>

      <li class="li_divider"></li>

      <li>
        <a 
          class="h5" 
          data-toggle="collapse" 
          href="#accounts_ul" 
          role="button" 
          aria-expanded="false" 
          aria-controls="accounts_ul"
        >
        
          <i class="fas fa-users"></i>
            Accounts
          <i class="fas fa-caret-down"></i>

        </a>

        <ul 
          class="collapse @if ( session('sidebar_nav') == 'accounts' ) show @endif" 
          id="accounts_ul"
        >
          <li>
            <a href="{{ route('admin.accounts.admins') }}">
              <i class="fas fa-users-cog"></i>
              Admins
              @if (( session('point_arrow') == 'admins' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a>
          </li>
          
          <li>
            <a href="{{ route('admin.accounts.staff_coaches') }}">
              <i class="fas fa-user-tie"></i>
              Staff/Coaches
              @if (( session('point_arrow') == 'staff_coaches' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>

          <li> 
            <a href="{{ route('admin.accounts.students') }}">
              <i class="fas fa-user"></i>
              Students
              @if (( session('point_arrow') == 'students' ))
                &nbsp;<i class="fas fa-caret-right"></i>  
              @endif
            </a> 
          </li>
        </ul>
      </li>

      <li class="li_divider"></li>

      <li>
        <a href="{{ route('admin.logout') }}" class="h5">
          <i class="fas fa-sign-out-alt"></i>
            Log-out
        </a>
      </li>
    </ul>
  </div>

  <div id="page-content-wrapper">
    <div class="container-fluid">
      @yield('content')
    </div>
  </div>
  
</div>
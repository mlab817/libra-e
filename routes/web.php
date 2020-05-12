<?php

// Auth::routes();

Route::get('/try', 'HomeController@try')->name('try');

// FrontEnd
Route::get('/', 'HomeController@index')->name('home');




/******************Users Login******************/

Route::post('login', 'LoginUsers\LoginUsersController@login')->name('login');
Route::get('logout',  'HomeController@logout')->name('logout');


/******************Libra-E Users******************/

// Reservations

Route::get('libraE/reservations', 'BookReservationController@reservation_view')->name('libraE.reservations_view');


// My Account

Route::get('libraE/my_account', 'MyAccountController@my_account')->name('libraE.my_account');

Route::get('libraE/my_account/edit_my_account', 'MyAccountController@edit_my_account')->name('libraE.edit_my_account');

Route::put('libraE/my_account/update_my_account', 'MyAccountController@update_my_account')->name('libraE.update_my_account');

Route::put('libraE/my_account/update_my_account_password', 'MyAccountController@update_my_account_password')->name('libraE.update_my_account_password');



// My Reservations

// My Book Reservations

Route::get('libraE/my_reservations/books', 'MyBookReservationController@my_reservations')
  ->name('libraE.my_reservations');

Route::get('libraE/reservations/books/search_book_reservations/{search?}', 'MyBookReservationController@search_book_reservations')
  ->name('libraE.my_reservations.books.search_book_reservations');

Route::get('libraE/reservations/books/book_reservations_per_page/{per_page?}', 'MyBookReservationController@book_reservations_per_page')
  ->name('libraE.my_reservations.books.book_reservations_per_page');

Route::get('libraE/reservations/books/book_reservations_toOrder/{ToOrder?}', 'MyBookReservationController@book_reservations_toOrder')
  ->name('libraE.my_reservations.books.book_reservations_toOrder');

Route::get('libraE/reservations/books/book_reservations_orderBy/{orderBy?}', 'MyBookReservationController@book_reservations_orderBy')
  ->name('libraE.my_reservations.books.book_reservations_orderBy');

Route::get('libraE/my_reservations/books/filter_book_reservations/{filter?}', 'MyBookReservationController@filter_book_reservations')
    ->name('libraE.my_reservations.books.filter_book_reservations');  

Route::delete('libraE/my_reservations/books/delete_book_reservations/{id?}', 'MyBookReservationController@delete_book_reservations')
  ->name('libraE.my_reservations.books.delete_book_reservations');


// My Gaming Room - Egames

Route::get('libraE/my_reservations/egames', 'EgamesReservationController@my_egames_reservation')
  ->name('libraE.my_reservations.egames');

Route::get('libraE/reservations/egames/search_my_egames_reservations/{search?}', 'EgamesReservationController@search_my_egames_reservations')
  ->name('libraE.my_reservations.egames.search_my_egames_reservations');
  
  
// Dynamic toOrder, orderBy

Route::get('libraE/reservations/egames/my_egames_per_page/{per_page?}', 'EgamesReservationController@my_egames_per_page')
  ->name('libraE.my_reservations.egames.my_egames_per_page');

Route::get('libraE/reservations/egames/my_egames_toOrder/{ToOrder?}', 'EgamesReservationController@my_egames_toOrder')
  ->name('libraE.my_reservations.egames.my_egames_toOrder');

Route::get('libraE/reservations/egames/my_egames_orderBy/{orderBy?}', 'EgamesReservationController@my_egames_orderBy')
  ->name('libraE.my_reservations.egames.my_egames_orderBy');

Route::get('libraE/my_reservations/egames/filter_my_egames_reservations/{filter?}', 'EgamesReservationController@filter_my_egames_reservations')
  ->name('libraE.my_reservations.egames.filter_my_egames_reservations');  

Route::delete('libraE/my_reservations/egames/delete_my_egames_reservations/{id?}', 'EgamesReservationController@delete_my_egames_reservations')
  ->name('libraE.my_reservations.egames.delete_my_egames_reservations');


// My Rooms Reservations

Route::get('libraE/my_reservations/rooms', 'RoomsReservationController@my_rooms_reservation')
  ->name('libraE.my_reservations.rooms');

Route::get('libraE/reservations/rooms/search_my_rooms_reservations/{search?}', 'RoomsReservationController@search_my_rooms_reservations')
->name('libraE.my_reservations.rooms.search_my_rooms_reservations');
  
  
// Dynamic toOrder, orderBy

Route::get('libraE/reservations/rooms/my_rooms_per_page/{per_page?}', 'RoomsReservationController@my_rooms_per_page')
  ->name('libraE.my_reservations.rooms.my_rooms_per_page');

Route::get('libraE/reservations/rooms/my_rooms_toOrder/{ToOrder?}', 'RoomsReservationController@my_rooms_toOrder')
  ->name('libraE.my_reservations.rooms.my_rooms_toOrder');

Route::get('libraE/reservations/rooms/my_rooms_orderBy/{orderBy?}', 'RoomsReservationController@my_rooms_orderBy')
  ->name('libraE.my_reservations.rooms.my_rooms_orderBy');

Route::get('libraE/my_reservations/rooms/filter_my_rooms_reservations/{filter?}', 'RoomsReservationController@filter_my_rooms_reservations')
  ->name('libraE.my_reservations.rooms.filter_my_rooms_reservations');  

Route::delete('libraE/my_reservations/rooms/delete_my_rooms_reservations/{id?}', 'RoomsReservationController@delete_my_rooms_reservations')
  ->name('libraE.my_reservations.rooms.delete_my_rooms_reservations');

  
  
// Book Reservations

Route::get('libraE/reservations/books', 'BookReservationController@reserve_books')
  ->name('libraE.reservations.books');

Route::get('libraE/reservations/books/search_book/{search?}', 'BookReservationController@search_book')
  ->name('libraE.books.search_book');
  
Route::get('libraE/reservations/books/available_books_per_page/{per_page?}', 'BookReservationController@available_books_per_page')
  ->name('libraE.books.available_books_per_page');

Route::get('libraE/reservations/books/available_books_toOrder/{ToOrder?}', 'BookReservationController@available_books_toOrder')
  ->name('libraE.books.available_books_toOrder');

Route::get('libraE/reservations/books/available_books_orderBy/{orderBy?}', 'BookReservationController@available_books_orderBy')
  ->name('libraE.books.available_books_orderBy');

Route::get('libraE/reservations/books/view_book/{id?}', 'BookReservationController@view_book')
  ->name('libraE.books.view_book');

Route::post('libraE/reservations/books/reserve_book', 'BookReservationController@reserve_book')
  ->name('libraE.books.reserve_book');


// Egames Reservations 

Route::get('libraE/reservations/egames', 'EgamesReservationController@egames')
  ->name('libraE.reservations.egames');
  
Route::get('libraE/reservations/egames/search_date/{search_date?}', 'EgamesReservationController@search_date')
  ->name('libraE.reservations.egames.search_date');

Route::post('libraE/reservations/egames/reserve', 'EgamesReservationController@reserve')
  ->name('libraE.reservations.egames.reserve');

  
// Rooms Reservations 

Route::get('libraE/reservations/rooms', 'RoomsReservationController@rooms_reservations')
  ->name('libraE.reservations.rooms');
  
Route::get('libraE/reservations/rooms/search_date/{search_date?}', 'RoomsReservationController@search_date')
  ->name('libraE.reservations.rooms.search_date');

Route::post('libraE/reservations/rooms/reserve', 'RoomsReservationController@reserve')
  ->name('libraE.reservations.rooms.reserve');

  
  
/******************Admin Login******************/

// Admin
Route::get('admin/login', 'Admin\LoginController@login_form')->name('admin.login_form');

Route::post('admin/login', 'Admin\LoginController@login')->name('admin.login');

Route::get('admin/logout', 'Admin\LoginController@logout')->name('admin.logout');

//Route::get('admin/register', 'Admin\RegisterController@register_form')->name('admin.register_form');

//Route::post('admin/register', 'Admin\RegisterController@register')->name('admin.register');


/******************Dashboard******************/


//Dashboard
Route::get('admin/dashboard/main_dashboard', 'AdminDashboardController@main_dashboard')
  ->name('admin.dashboard.main_dashboard');



/******************Reports******************/

  // Attendance Scanner
  Route::get('admin/reports/attendance_scanner', 'ReportsController@attendance_scanner')
    ->name('admin.reports.attendance_scanner');



  // Accession Reports

  Route::get('admin/reports/accession_reports', 'AccessionReportController@accession_reports_view')
    ->name('admin.reports.accession_reports_view');

  Route::get('admin/reports/search_accession_reports/{search?}', 'AccessionReportController@search_accession_reports')
    ->name('admin.reports.search_accession_reports');
    
  Route::get('admin/reports/accession_reports_per_page/{per_page?}', 'AccessionReportController@accession_reports_per_page')
    ->name('admin.reports.accession_reports_per_page');  

  Route::get('admin/reports/accession_reports_toOrder/{ToOrder?}', 'AccessionReportController@accession_reports_toOrder')
    ->name('admin.reports.accession_reports_toOrder');

  Route::get('admin/reports/accession_reports_orderBy/{orderBy?}', 'AccessionReportController@accession_reports_orderBy')
    ->name('admin.reports.accession_reports_orderBy');

  Route::get('admin/reports/filter_accession_reports/{filter?}', 'AccessionReportController@filter_accession_reports')
    ->name('admin.reports.filter_accession_reports');  
    
  Route::get('admin/reports/accession_reports_filter_by_date', 'AccessionReportController@accession_reports_filter_by_date')
    ->name('admin.reports.accession_reports_filter_url');
    
  Route::get('admin/reports/accession_reports_filter_by_date/calendar_type/{calendar_type?}/search_date/{search_date?}', 'AccessionReportController@accession_reports_filter_by_date')
    ->name('admin.reports.accession_reports_filter_by_date');

  Route::get('admin/reports/accession_reports_filter_start_end_date', 'AccessionReportController@accession_reports_filter_start_end_date')
    ->name('admin.reports.accession_reports_filter_start_end_date_url');
    
  Route::get('admin/reports/accession_reports_filter_start_end_date/start_date/{start_date?}/end_date/{end_date?}', 'AccessionReportController@accession_reports_filter_start_end_date')
    ->name('admin.reports.accession_reports_filter_start_end_date');


  // Thesis Book Reports

  Route::get('admin/reports/thesis_book_reports', 'ThesisBookReportController@thesis_book_reports_view')
    ->name('admin.reports.thesis_book_reports_view');

  Route::get('admin/reports/search_thesis_book_reports/{search?}', 'ThesisBookReportController@search_thesis_book_reports')
    ->name('admin.reports.search_thesis_book_reports');
    
  Route::get('admin/reports/thesis_book_reports_per_page/{per_page?}', 'ThesisBookReportController@thesis_book_reports_per_page')
    ->name('admin.reports.thesis_book_reports_per_page');  

  Route::get('admin/reports/thesis_book_reports_toOrder/{ToOrder?}', 'ThesisBookReportController@thesis_book_reports_toOrder')
    ->name('admin.reports.thesis_book_reports_toOrder');

  Route::get('admin/reports/thesis_book_reports_orderBy/{orderBy?}', 'ThesisBookReportController@thesis_book_reports_orderBy')
    ->name('admin.reports.thesis_book_reports_orderBy');

  Route::get('admin/reports/filter_thesis_book_reports/{filter?}', 'ThesisBookReportController@filter_thesis_book_reports')
    ->name('admin.reports.filter_thesis_book_reports');  
    
  Route::get('admin/reports/thesis_book_reports_filter_by_date', 'ThesisBookReportController@thesis_book_reports_filter_by_date')
    ->name('admin.reports.thesis_book_reports_filter_url');

  Route::get('admin/reports/thesis_book_reports_filter_by_date/calendar_type/{calendar_type?}/search_date/{search_date?}', 'ThesisBookReportController@thesis_book_reports_filter_by_date')
    ->name('admin.reports.thesis_book_reports_filter_by_date');

  Route::get('admin/reports/thesis_book_reports_filter_start_end_date', 'ThesisBookReportController@thesis_book_reports_filter_start_end_date')
    ->name('admin.reports.thesis_book_reports_filter_start_end_date_url');
    
  Route::get('admin/reports/thesis_book_reports_filter_start_end_date/start_date/{start_date?}/end_date/{end_date?}', 'ThesisBookReportController@thesis_book_reports_filter_start_end_date')
    ->name('admin.reports.thesis_book_reports_filter_start_end_date');


  // All Other Reports

  // Dynamic All Reports
  Route::get('admin/reports/all_reports/{report_type?}', 'ReportsController@all_reports')
    ->name('admin.reports.all_reports');

  Route::get('admin/reports/all_usage_reports/{report_type?}', 'ReportsController@all_usage_reports')
    ->name('admin.reports.all_usage_reports');

  // get url
  Route::get('admin/reports/search_reports', 'ReportsController@search_reports')
    ->name('admin.reports.search_reports_url');
    
  Route::get('admin/reports/search_reports/report_type/{report_type?}/search/{search?}', 'ReportsController@search_reports')
    ->name('admin.reports.search_reports');

  // get url
  Route::get('admin/reports/reports_per_page', 'ReportsController@reports_per_page')
    ->name('admin.reports.reports_per_page_url');  
    
  Route::get('admin/reports/reports_per_page/report_type/{report_type?}/per_page/{per_page?}', 'ReportsController@reports_per_page')
    ->name('admin.reports.reports_per_page');  

  // get url
  Route::get('admin/reports/reports_toOrder', 'ReportsController@reports_toOrder')
    ->name('admin.reports.reports_toOrder_url');
    
  Route::get('admin/reports/reports_toOrder/report_type/{report_type?}/toOrder/{toOrder?}', 'ReportsController@reports_toOrder')
    ->name('admin.reports.reports_toOrder');

  // get url
  Route::get('admin/reports/reports_orderBy', 'ReportsController@reports_orderBy')
    ->name('admin.reports.reports_orderBy_url');
    
  Route::get('admin/reports/reports_orderBy/report_type/{report_type?}/orderBy/{orderBy?}', 'ReportsController@reports_orderBy')
    ->name('admin.reports.reports_orderBy');

  // get url
  Route::get('admin/reports/filter_reports', 'ReportsController@filter_reports')
    ->name('admin.reports.filter_reports_url');  
    
  Route::get('admin/reports/filter_reports/report_type/{report_type?}/filter/{filter?}', 'ReportsController@filter_reports')
    ->name('admin.reports.filter_reports');  

  // get url
  Route::get('admin/reports/filter_user_type_reports', 'ReportsController@filter_user_type_reports')
    ->name('admin.reports.filter_user_type_reports_url');  
    
  Route::get('admin/reports/filter_user_type_reports/report_type/{report_type?}/user_type/{filter?}', 'ReportsController@filter_user_type_reports')
    ->name('admin.reports.filter_user_type_reports');  

  // get url
  Route::get('admin/reports/filter_pc_type_reports', 'ReportsController@filter_pc_type_reports')
    ->name('admin.reports.filter_pc_type_reports_url');  
    
  Route::get('admin/reports/filter_pc_type_reports/report_type/{report_type?}/filter/{filter?}', 'ReportsController@filter_pc_type_reports')
    ->name('admin.reports.filter_pc_type_reports');  


  // get url
  Route::get('admin/reports/reports_filter_by_date', 'ReportsController@reports_filter_by_date')
    ->name('admin.reports.reports_filter_url');

  Route::get('admin/reports/reports_filter_by_date/report_type/{report_type?}/calendar_type/{calendar_type?}/search_date/{search_date?}', 'ReportsController@reports_filter_by_date')
    ->name('admin.reports.reports_filter_by_date');

  // get url
  Route::get('admin/reports/reports_filter_start_end_date', 'ReportsController@reports_filter_start_end_date')
    ->name('admin.reports.reports_filter_start_end_date_url');

  Route::get('admin/reports/reports_filter_start_end_date/report_type/{report_type?}/start_date/{start_date?}/end_date/{end_date?}', 'ReportsController@reports_filter_start_end_date')
    ->name('admin.reports.reports_filter_start_end_date');

  

/******************Borrowing/******************/

/////// Books Borrowing

// Borrow Book
Route::get('admin/borrowing/books', 'AdminBorrowBookController@borrow_book')
  ->name('admin.borrowing.borrow_book');

Route::get('admin/borrowing/books/search_book/{search?}', 'AdminBorrowBookController@search_book')
  ->name('admin.borrowing.search_book');
  
Route::get('admin/borrowing/books/available_books_per_page/{per_page?}', 'AdminBorrowBookController@available_books_per_page')
  ->name('admin.borrowing.available_books_per_page');

Route::get('admin/borrowing/books/available_books_toOrder/{ToOrder?}', 'AdminBorrowBookController@available_books_toOrder')
  ->name('admin.borrowing.available_books_toOrder');

Route::get('admin/borrowing/books/available_books_orderBy/{orderBy?}', 'AdminBorrowBookController@available_books_orderBy')
  ->name('admin.borrowing.available_books_orderBy');

Route::get('admin/borrowing/books/view_book/{id?}', 'AdminBorrowBookController@view_book')
  ->name('admin.borrowing.view_book');

Route::post('admin/borrowing/books/reserve_book', 'AdminBorrowBookController@reserve_book')
  ->name('admin.borrowing.reserve_book');
  

// By Status

Route::get('admin/borrowing/all_book_reservations/{status_type?}', 'AdminBookReservationController@get_all_book_reservations')
  ->name('admin.borrowing.all_book_reservations');

// Orders & Filters
Route::get('admin/borrowing/admin_book_reservations_per_page', 'AdminBookReservationController@admin_book_reservations_per_page')
  ->name('admin.borrowing.admin_book_reservations_per_page_url');

Route::get('admin/borrowing/admin_book_reservations_per_page/status/{status?}/per_page/{per_page?}', 'AdminBookReservationController@admin_book_reservations_per_page')
  ->name('admin.borrowing.admin_book_reservations_per_page');

Route::get('admin/borrowing/admin_book_reservations_toOrder', 'AdminBookReservationController@admin_book_reservations_toOrder')
  ->name('admin.borrowing.admin_book_reservations_toOrder_url');

Route::get('admin/borrowing/admin_book_reservations_toOrder/status/{status?}/ToOrder/{ToOrder?}', 'AdminBookReservationController@admin_book_reservations_toOrder')
  ->name('admin.borrowing.admin_book_reservations_toOrder');

Route::get('admin/borrowing/admin_book_reservations_orderBy', 'AdminBookReservationController@admin_book_reservations_orderBy')
  ->name('admin.borrowing.admin_book_reservations_orderBy_url');

Route::get('admin/borrowing/admin_book_reservations_orderBy/status/{status?}/orderBy/{orderBy?}', 'AdminBookReservationController@admin_book_reservations_orderBy')
  ->name('admin.borrowing.admin_book_reservations_orderBy');

Route::get('admin/borrowing/filter_admin_book_reservations', 'AdminBookReservationController@filter_admin_book_reservations')
  ->name('admin.borrowing.filter_admin_book_reservations_url');

Route::get('admin/borrowing/filter_admin_book_reservations/status/{status?}/filter/{filter?}', 'AdminBookReservationController@filter_admin_book_reservations')
  ->name('admin.borrowing.filter_admin_book_reservations');

// Search
Route::get('admin/borrowing/search_all_book_reservations', 'AdminBookReservationController@search_all_book_reservations')
  ->name('admin.borrowing.search_all_book_reservations_url');

Route::get('admin/borrowing/search_all_book_reservations/status_type/{status_type?}/search/{search?}', 'AdminBookReservationController@search_all_book_reservations')
  ->name('admin.borrowing.search_all_book_reservations');

// View one Reservation
Route::get('admin/borrowing/view_reservation/{id?}', 'AdminBookReservationController@view_reservation')
  ->name('admin.borrowing.view_reservation');
  
// Handling Reservations

Route::post('admin/borrowing/approve_reservation', 'AdminBookReservationController@approve_reservation')
  ->name('admin.borrowing.approve_reservation');

Route::post('admin/borrowing/claim_reservation', 'AdminBookReservationController@claim_reservation')
  ->name('admin.borrowing.claim_reservation');

Route::post('admin/borrowing/return_reservation', 'AdminBookReservationController@return_reservation')
  ->name('admin.borrowing.return_reservation');

Route::delete('admin/borrowing/damage_lost_reservation/{id?}', 'AdminBookReservationController@damage_lost_reservation')
  ->name('admin.borrowing.damage_lost_reservation');

Route::post('admin/borrowing/unreturned_reservation', 'AdminBookReservationController@unreturned_reservation')
  ->name('admin.borrowing.unreturned_reservation');

Route::delete('admin/borrowing/deny_reservation/{id?}', 'AdminBookReservationController@deny_reservation')
  ->name('admin.borrowing.deny_reservation');

Route::post('admin/borrowing/return_overdue_reservation', 'AdminBookReservationController@return_overdue_reservation')
  ->name('admin.borrowing.return_overdue_reservation');



  

/******************Egames******************/

// Reservations

  // Reserve Now 
  Route::get('admin/egames/reservations/reserve_now', 'AdminEgamesReservationController@reserve_now')
    ->name('admin.egames.reservation.reserve_now');

  Route::get('admin/egames/reservations/reserve_now/search_date/{search_date?}', 'AdminEgamesReservationController@search_date')
    ->name('admin.egames.reservation.reserve_now.search_date');

  Route::post('admin/egames/reservations/reserve', 'AdminEgamesReservationController@reserve')
    ->name('admin.egames.reservation.reserve');


  // By Status 
  Route::get('admin/egames/reservations/egames_reservation/{status_type?}', 'AdminEgamesReservationController@egames_reservation')
    ->name('admin.egames.reservation.egames_reservation');

  // Search
  Route::get('admin/egames/reservations/search_all_egames_reservations', 'AdminEgamesReservationController@search_all_egames_reservations')
    ->name('admin.egames.reservation.search_all_egames_reservations_url');

  Route::get('admin/egames/reservations/search_all_egames_reservations/status_type/{status_type?}/search/{search?}', 'AdminEgamesReservationController@search_all_egames_reservations')
    ->name('admin.egames.reservation.search_all_egames_reservations');
  
  // View one Reservation
  Route::get('admin/egames/reservations/view_reservation/{id?}', 'AdminEgamesReservationController@view_reservation')
    ->name('admin.egames.reservation.view_reservation');
    
  // Handling Reservations

  Route::post('admin/egames/reservation/approve_reservation', 'AdminEgamesReservationController@approve_reservation')
    ->name('admin.egames.reservation.approve_reservation');

  Route::post('admin/egames/reservation/play_reservation', 'AdminEgamesReservationController@play_reservation')
    ->name('admin.egames.reservation.play_reservation');

  Route::post('admin/egames/reservation/finish_reservation', 'AdminEgamesReservationController@finish_reservation')
    ->name('admin.egames.reservation.finish_reservation');

  Route::delete('admin/egames/reservation/deny_reservation/{id?}', 'AdminEgamesReservationController@deny_reservation')
    ->name('admin.egames.reservation.deny_reservation');


// Slots & Settings

  // Slots

  Route::get('admin/egames/slots_settings/pc_slots', 'AdminEgamesReservationController@pc_slots')
    ->name('admin.egames.slots_settings.pc_slots');

  Route::post('admin/egames/slots_settings/add_pc_slot', 'AdminEgamesReservationController@add_pc_slot')
    ->name('admin.egames.slots_settings.add_pc_slot');

  Route::put('admin/egames/slots_settings/add_pc_slot', 'AdminEgamesReservationController@add_pc_slot')
    ->name('admin.egames.slots_settings.add_pc_slot');

  Route::get('admin/egames/slots_settings/view_pc_slot/{id?}', 'AdminEgamesReservationController@view_pc_slot')
    ->name('admin.egames.slots_settings.view_pc_slot');

  Route::get('admin/egames/slots_settings/search_pc_slots/{search?}', 'AdminEgamesReservationController@search_pc_slots')
    ->name('admin.egames.slots_settings.search_pc_slots');

  // Settings

  Route::get('admin/egames/slots_settings/egames_settings', 'AdminEgamesReservationController@egames_settings')
    ->name('admin.egames.slots_settings.egames_settings');

  Route::post('admin/egames/slots_settings/store_settings', 'AdminEgamesReservationController@store_settings')
    ->name('admin.egames.slots_settings.store_settings');

  Route::put('admin/egames/slots_settings/store_settings', 'AdminEgamesReservationController@store_settings')
    ->name('admin.egames.slots_settings.store_settings');
  


  // Egames Dynamic toOrder, orderBy, filter by status

  Route::get('admin/egames/egames_per_page/type/{type?}/per_page/{per_page?}', 'AdminEgamesReservationController@egames_per_page')
    ->name('admin.egames.egames_per_page');

  Route::get('admin/egames/egames_per_page', 'AdminEgamesReservationController@egames_per_page')
    ->name('admin.egames.egames_per_page_url');

  Route::get('admin/egames/egames_toOrder/type/{type?}/toOrder/{toOrder?}', 'AdminEgamesReservationController@egames_toOrder')
    ->name('admin.egames.egames_toOrder');

  Route::get('admin/egames/egames_toOrder', 'AdminEgamesReservationController@egames_toOrder')
    ->name('admin.egames.egames_toOrder_url');

  Route::get('admin/egames/egames_orderBy/type/{type?}/orderBy/{orderBy?}', 'AdminEgamesReservationController@egames_orderBy')
    ->name('admin.egames.egames_orderBy');

  Route::get('admin/egames/egames_orderBy', 'AdminEgamesReservationController@egames_orderBy')
    ->name('admin.egames.egames_orderBy_url');

  Route::get('admin/egames/filter_status_egames/type/{type?}/filter/{filter?}', 'AdminEgamesReservationController@filter_status_egames')
    ->name('admin.egames.filter_status_egames');  

  Route::get('admin/egames/filter_status_egames', 'AdminEgamesReservationController@filter_status_egames')
    ->name('admin.egames.filter_status_egames_url');  
    
  Route::get('admin/egames/filter_pc_type_egames/type/{type?}/filter/{filter?}', 'AdminEgamesReservationController@filter_pc_type_egames')
    ->name('admin.egames.filter_pc_type_egames');  

  Route::get('admin/egames/filter_pc_type_egames', 'AdminEgamesReservationController@filter_pc_type_egames')
    ->name('admin.egames.filter_pc_type_egames_url');  


/******************Rooms Reservations******************/

// Reserve Now 
Route::get('admin/rooms/reservations/reserve_now', 'AdminRoomsReservationController@reserve_now')
  ->name('admin.rooms.reservation.reserve_now');

Route::get('admin/rooms/reservations/reserve_now/search_date/{search_date?}', 'AdminRoomsReservationController@search_date')
  ->name('admin.rooms.reservation.reserve_now.search_date');

Route::post('admin/rooms/reservations/reserve', 'AdminRoomsReservationController@reserve')
  ->name('admin.rooms.reservation.reserve');


// By Status 
Route::get('admin/rooms/reservations/rooms_reservation/{status_type?}', 'AdminRoomsReservationController@rooms_reservation')
  ->name('admin.rooms.reservation.rooms_reservation');

// Search
Route::get('admin/rooms/reservations/search_all_rooms_reservations', 'AdminRoomsReservationController@search_all_rooms_reservations')
  ->name('admin.rooms.reservation.search_all_rooms_reservations_url');

Route::get('admin/rooms/reservations/search_all_rooms_reservations/status_type/{status_type?}/search/{search?}', 'AdminRoomsReservationController@search_all_rooms_reservations')
  ->name('admin.rooms.reservation.search_all_rooms_reservations');

// View one Reservation
Route::get('admin/rooms/reservations/view_reservation/{id?}', 'AdminRoomsReservationController@view_reservation')
  ->name('admin.rooms.reservation.view_reservation');

// Handling Reservations

Route::post('admin/rooms/reservation/approve_reservation', 'AdminRoomsReservationController@approve_reservation')
  ->name('admin.rooms.reservation.approve_reservation');

Route::post('admin/rooms/reservation/start_reservation', 'AdminRoomsReservationController@start_reservation')
  ->name('admin.rooms.reservation.start_reservation');

Route::post('admin/rooms/reservation/finish_reservation', 'AdminRoomsReservationController@finish_reservation')
  ->name('admin.rooms.reservation.finish_reservation');

Route::delete('admin/rooms/reservation/deny_reservation/{id?}', 'AdminRoomsReservationController@deny_reservation')
  ->name('admin.rooms.reservation.deny_reservation');


// Rooms Dynamic toOrder, orderBy, filter by status

Route::get('admin/rooms/reservations/rooms_per_page/type/{type?}/per_page/{per_page?}', 'AdminRoomsReservationController@rooms_per_page')
  ->name('admin.rooms.reservation.rooms_per_page');

Route::get('admin/rooms/reservations/rooms_per_page', 'AdminRoomsReservationController@rooms_per_page')
  ->name('admin.rooms.reservation.rooms_per_page_url');

Route::get('admin/rooms/reservations/rooms_toOrder/type/{type?}/toOrder/{toOrder?}', 'AdminRoomsReservationController@rooms_toOrder')
  ->name('admin.rooms.reservation.rooms_toOrder');

Route::get('admin/rooms/reservations/rooms_toOrder', 'AdminRoomsReservationController@rooms_toOrder')
  ->name('admin.rooms.reservation.rooms_toOrder_url');

Route::get('admin/rooms/reservations/rooms_orderBy/type/{type?}/orderBy/{orderBy?}', 'AdminRoomsReservationController@rooms_orderBy')
  ->name('admin.rooms.reservation.rooms_orderBy');

Route::get('admin/rooms/reservations/rooms_orderBy', 'AdminRoomsReservationController@rooms_orderBy')
  ->name('admin.rooms.reservation.rooms_orderBy_url');

Route::get('admin/rooms/reservations/filter_status_rooms/type/{type?}/filter/{filter?}', 'AdminRoomsReservationController@filter_status_rooms')
  ->name('admin.rooms.reservation.filter_status_rooms');  

Route::get('admin/rooms/reservations/filter_status_rooms', 'AdminRoomsReservationController@filter_status_rooms')
  ->name('admin.rooms.reservation.filter_status_rooms_url');  



/******************Accountabilities******************/

  // Users

  Route::get('admin/accountabilities/all_students', 'AccountabilityController@all_students')
    ->name('admin.accountabilities.all_students');

  Route::get('admin/accountabilities/all_coaches', 'AccountabilityController@all_coaches')
    ->name('admin.accountabilities.all_coaches');

  Route::get('admin/accountabilities/view_user_accountability/{id?}', 'AccountabilityController@view_user_accountability')
    ->name('admin.accountabilities.view_user_accountability');

  Route::get('admin/accountabilities/print_receipt/{id?}', 'AccountabilityController@print_receipt')
    ->name('admin.accountabilities.print_receipt');

  Route::post('admin/accountabilities/mark_paid', 'AccountabilityController@mark_paid')
    ->name('admin.accountabilities.mark_paid');

  Route::post('admin/accountabilities/mark_settled', 'AccountabilityController@mark_settled')
    ->name('admin.accountabilities.mark_settled');
  
  // Books

  Route::get('admin/accountabilities/student_books', 'AccountabilityController@student_books')
    ->name('admin.accountabilities.student_books');

  Route::get('admin/accountabilities/coach_books', 'AccountabilityController@coach_books')
    ->name('admin.accountabilities.coach_books');

  Route::get('admin/accountabilities/search_book_accountabilities', 'AccountabilityController@search_book_accountabilities')
    ->name('admin.accountabilities.search_book_accountabilities_url');

  Route::get('admin/accountabilities/search_book_accountabilities/type/{type?}/search/{search?}', 'AccountabilityController@search_book_accountabilities')
    ->name('admin.accountabilities.search_book_accountabilities');
    
  // Dynamic toOrder, orderBy, filters
    
  Route::get('admin/accountabilities/accountabilities_per_page/type/{type?}/per_page/{per_page?}', 'AccountabilityController@accountabilities_per_page')
    ->name('admin.accountabilities.accountabilities_per_page');

  Route::get('admin/accountabilities/accountabilities_per_page', 'AccountabilityController@accountabilities_per_page')
    ->name('admin.accountabilities.accountabilities_per_page_url');
    
  Route::get('admin/accountabilities/accountabilities_toOrder/type/{type?}/toOrder/{toOrder?}', 'AccountabilityController@accountabilities_toOrder')
    ->name('admin.accountabilities.accountabilities_toOrder');

  Route::get('admin/accountabilities/accountabilities_toOrder', 'AccountabilityController@accountabilities_toOrder')
    ->name('admin.accountabilities.accountabilities_toOrder_url');
    
  Route::get('admin/accountabilities/accountabilities_orderBy/type/{type?}/orderBy/{orderBy?}', 'AccountabilityController@accountabilities_orderBy')
    ->name('admin.accountabilities.accountabilities_orderBy');

  Route::get('admin/accountabilities/accountabilities_orderBy', 'AccountabilityController@accountabilities_orderBy')
    ->name('admin.accountabilities.accountabilities_orderBy_url');
  
  Route::get('admin/accountabilities/filter_type_accountabilities/type/{type?}/filter/{filter?}', 'AccountabilityController@filter_type_accountabilities')
    ->name('admin.accountabilities.filter_type_accountabilities');  

  Route::get('admin/accountabilities/filter_type_accountabilities', 'AccountabilityController@filter_type_accountabilities')
    ->name('admin.accountabilities.filter_type_accountabilities_url');  
  
  Route::get('admin/accountabilities/filter_status_accountabilities/type/{type?}/filter/{filter?}', 'AccountabilityController@filter_status_accountabilities')
    ->name('admin.accountabilities.filter_status_accountabilities');  

  Route::get('admin/accountabilities/filter_status_accountabilities', 'AccountabilityController@filter_status_accountabilities')
    ->name('admin.accountabilities.filter_status_accountabilities_url');  

  //Route::get('admin/accountabilities/view_book_accountability/{id?}', 'AccountabilityController@view_book_accountability')
    //->name('admin.accountabilities.view_book_accountability');

  
  // Invoices
  
  Route::get('admin/accountabilities/invoices/{type?}', 'AccountabilityController@invoices')
    ->name('admin.accountabilities.invoices');

  Route::get('admin/accountabilities/view_invoice/{id?}', 'AccountabilityController@view_invoice')
    ->name('admin.accountabilities.view_invoice');

  Route::get('admin/accountabilities/print_invoice/{id?}', 'AccountabilityController@print_invoice')
    ->name('admin.accountabilities.print_invoice');

  Route::get('admin/accountabilities/search_invoices', 'AccountabilityController@search_invoices')
    ->name('admin.accountabilities.search_invoices_url');

  Route::get('admin/accountabilities/search_invoices/type/{type?}/search/{search?}', 'AccountabilityController@search_invoices')
    ->name('admin.accountabilities.search_invoices');
    

  // Dynamic toOrder, orderBy

  Route::get('admin/accountabilities/invoices_per_page/type/{type?}/per_page/{per_page?}', 'AccountabilityController@invoices_per_page')
    ->name('admin.accountabilities.invoices_per_page');

  Route::get('admin/accountabilities/invoices_per_page', 'AccountabilityController@invoices_per_page')
    ->name('admin.accountabilities.invoices_per_page_url');

  Route::get('admin/accountabilities/invoices_toOrder/type/{type?}/toOrder/{toOrder?}', 'AccountabilityController@invoices_toOrder')
    ->name('admin.accountabilities.invoices_toOrder');

  Route::get('admin/accountabilities/invoices_toOrder', 'AccountabilityController@invoices_toOrder')
    ->name('admin.accountabilities.invoices_toOrder_url');

  Route::get('admin/accountabilities/invoices_orderBy/type/{type?}/orderBy/{orderBy?}', 'AccountabilityController@invoices_orderBy')
    ->name('admin.accountabilities.invoices_orderBy');

  Route::get('admin/accountabilities/invoices_orderBy', 'AccountabilityController@invoices_orderBy')
    ->name('admin.accountabilities.invoices_orderBy_url');


/******************Books******************/

  // Accessioning

  Route::post('admin/books/import_excell_accessions', 'BookController@import_excell_accessions')
    ->name('admin.books.import_excell_accessions');
    
  Route::get('admin/books/accessioning', 'BookController@accessioning_view')
    ->name('admin.books.accessioning');
    
  Route::get('admin/books/search_accession/{search?}', 'BookController@search_accession')
    ->name('admin.books.search_accession');
    
  Route::get('admin/books/accessions_per_page/{per_page?}', 'BookController@accessions_per_page')
    ->name('admin.books.accessions_per_page');
    
  Route::get('admin/books/accessions_toOrder/{ToOrder?}', 'BookController@accessions_toOrder')
    ->name('admin.books.accessions_toOrder');
    
  Route::get('admin/books/accessions_orderBy/{orderBy?}', 'BookController@accessions_orderBy')
    ->name('admin.books.accessions_orderBy');

  Route::get('admin/books/filter_accessions/{filter?}', 'BookController@filter_accessions')
    ->name('admin.books.filter_accessions');  
    
  Route::get('admin/books/add_accession', 'BookController@add_accession_view')
    ->name('admin.books.add_accession_view');
    
  Route::get('admin/books/view_accession/{id?}', 'BookController@view_accession')
    ->name('admin.books.view_accession');
    
  Route::post('admin/books/add_new_accession', 'BookController@add_new_accession')
    ->name('admin.books.add_new_accession');

  Route::put('admin/books/add_new_accession', 'BookController@add_new_accession')
    ->name('admin.books.add_new_accession');
    
  Route::post('admin/books/store_accession', 'BookController@store_accession')
    ->name('admin.books.store_accession');
    
  Route::get('admin/books/edit_accession/{id?}', 'BookController@edit_accession_view')
    ->name('admin.books.edit_accession_view');

  Route::put('admin/books/store_accession', 'BookController@store_accession')
    ->name('admin.books.store_accession');

  Route::get('admin/books/handle_accession/{id?}', 'BookController@handle_accession_view')
    ->name('admin.books.handle_accession_view');
    
  Route::delete('admin/books/move_accession/{id?}', 'BookController@move_accession')
    ->name('admin.books.move_accession');

  Route::delete('admin/books/delete_accession/{id?}', 'BookController@delete_accession')
    ->name('admin.books.delete_accession');

    
  // Thesis Books  

  Route::post('admin/thesis/import_excell_thesis_books', 'ThesisBookController@import_excell_thesis_books')
    ->name('admin.thesis.import_excell_thesis_books');

  Route::get('admin/thesis/thesis_books', 'ThesisBookController@thesis_books_view')
    ->name('admin.thesis.thesis_books');

  Route::get('admin/thesis/search_thesis_book/{search?}', 'ThesisBookController@search_thesis_book')
    ->name('admin.thesis.search_thesis_book');
    
  Route::get('admin/thesis/thesis_books_per_page/{per_page?}', 'ThesisBookController@thesis_books_per_page')
    ->name('admin.thesis.thesis_books_per_page');
    
  Route::get('admin/thesis/thesis_books_toOrder/{ToOrder?}', 'ThesisBookController@thesis_books_toOrder')
    ->name('admin.thesis.thesis_books_toOrder');
    
  Route::get('admin/thesis/thesis_books_orderBy/{orderBy?}', 'ThesisBookController@thesis_books_orderBy')
    ->name('admin.thesis.thesis_books_orderBy');

  Route::get('admin/thesis/filter_thesis_books/{filter?}', 'ThesisBookController@filter_thesis_books')
    ->name('admin.thesis.filter_thesis_books');  

  Route::get('admin/thesis/add_thesis_book/{authors?}', 'ThesisBookController@add_thesis_book_view')
    ->name('admin.thesis.add_thesis_book_view'); 
    
  Route::get('admin/thesis/view_thesis_book/{id?}', 'ThesisBookController@view_thesis_book')
    ->name('admin.thesis.view_thesis_book');

  Route::get('admin/thesis/edit_thesis_book/{id?}', 'ThesisBookController@edit_thesis_book_view')
    ->name('admin.thesis.edit_thesis_book_view');  

  Route::post('admin/thesis/store_thesis_book', 'ThesisBookController@store_thesis_book')
    ->name('admin.thesis.store_thesis_book');

  Route::get('admin/thesis/edit_thesis_book/{id?}', 'ThesisBookController@edit_thesis_book_view')
    ->name('admin.thesis.edit_thesis_book_view');

  Route::put('admin/thesis/store_thesis_book', 'ThesisBookController@store_thesis_book')
    ->name('admin.thesis.store_thesis_book');


/******************File Maintenance/******************/

/////// Books Filemaintenance


  // Classification

  Route::get('admin/file_maintenance/classifications', 'FileMaintenanceController@classifications_view')
    ->name('admin.file_maintenance.classifications');
    
  Route::get('admin/file_maintenance/classifications_per_page/{per_page?}', 'FileMaintenanceController@classifications_per_page')
    ->name('admin.file_maintenance.classifications_per_page');
    
  Route::get('admin/file_maintenance/search_classification/{search?}', 'FileMaintenanceController@search_classification')
    ->name('admin.file_maintenance.search_classification');
    
  Route::get('admin/file_maintenance/filter_classifications/{filter?}', 'FileMaintenanceController@filter_classifications')
    ->name('admin.file_maintenance.filter_classifications');  
    
  Route::post('admin/file_maintenance/store_classification', 'FileMaintenanceController@store_classification')
    ->name('admin.file_maintenance.store_classification');
    
  Route::get('admin/file_maintenance/edit_classification/{id?}', 'FileMaintenanceController@edit_classification_view')
    ->name('admin.file_maintenance.edit_classification_view');
    
  Route::put('admin/file_maintenance/store_classification', 'FileMaintenanceController@store_classification')
    ->name('admin.file_maintenance.store_classification');
    
  Route::delete('admin/file_maintenance/delete_classification/{id?}', 'FileMaintenanceController@delete_classification')
    ->name('admin.file_maintenance.delete_classification');
    

  // Categories Dewey Decimal

  Route::get('admin/file_maintenance/categories', 'FileMaintenanceController@categories_view')
  ->name('admin.file_maintenance.categories');

  Route::get('admin/file_maintenance/search_category', 'FileMaintenanceController@search_category')
    ->name('admin.file_maintenance.search_category');
    
  Route::get('admin/file_maintenance/edit_category/{id?}', 'FileMaintenanceController@edit_category_view')
    ->name('admin.file_maintenance.edit_category_view');
    
  Route::put('admin/file_maintenance/store_category', 'FileMaintenanceController@store_category')
    ->name('admin.file_maintenance.store_category');
    
    
  // Author

  Route::get('admin/file_maintenance/authors', 'FileMaintenanceController@authors_view')
    ->name('admin.file_maintenance.authors');
    
  Route::get('admin/file_maintenance/authors_per_page/{per_page?}', 'FileMaintenanceController@authors_per_page')
    ->name('admin.file_maintenance.authors_per_page');
    
  Route::get('admin/file_maintenance/search_author/{search?}', 'FileMaintenanceController@search_author')
    ->name('admin.file_maintenance.search_author');
    
  Route::get('admin/file_maintenance/filter_authors/{filter?}', 'FileMaintenanceController@filter_authors')
    ->name('admin.file_maintenance.filter_authors');  
    
  Route::post('admin/file_maintenance/store_author', 'FileMaintenanceController@store_author')
    ->name('admin.file_maintenance.store_author');
    
  Route::get('admin/file_maintenance/edit_author/{id?}', 'FileMaintenanceController@edit_author_view')
    ->name('admin.file_maintenance.edit_author_view');
    
  Route::put('admin/file_maintenance/store_author', 'FileMaintenanceController@store_author')
    ->name('admin.file_maintenance.store_author');
    
  Route::delete('admin/file_maintenance/delete_author/{id?}', 'FileMaintenanceController@delete_author')
    ->name('admin.file_maintenance.delete_author');

    
  // Publishers
  Route::get('admin/file_maintenance/publishers', 'FileMaintenanceController@publishers_view')
    ->name('admin.file_maintenance.publishers');
    
  Route::get('admin/file_maintenance/publishers_per_page/{per_page?}', 'FileMaintenanceController@publishers_per_page')
    ->name('admin.file_maintenance.publishers_per_page');
    
  Route::get('admin/file_maintenance/search_publisher/{search?}', 'FileMaintenanceController@search_publisher')
    ->name('admin.file_maintenance.search_publisher');
    
  Route::get('admin/file_maintenance/filter_publishers/{filter?}', 'FileMaintenanceController@filter_publishers')
    ->name('admin.file_maintenance.filter_publishers');  
    
  Route::post('admin/file_maintenance/store_publisher', 'FileMaintenanceController@store_publisher')
    ->name('admin.file_maintenance.store_publisher');
    
  Route::get('admin/file_maintenance/edit_publisher/{id?}', 'FileMaintenanceController@edit_publisher_view')
    ->name('admin.file_maintenance.edit_publisher_view');
    
  Route::put('admin/file_maintenance/store_publisher', 'FileMaintenanceController@store_publisher')
    ->name('admin.file_maintenance.store_publisher');
    
  Route::delete('admin/file_maintenance/delete_publisher/{id?}', 'FileMaintenanceController@delete_publisher')
    ->name('admin.file_maintenance.delete_publisher');
    

  // Illustrations
  Route::get('admin/file_maintenance/illustrations', 'FileMaintenanceController@illustrations_view')
    ->name('admin.file_maintenance.illustrations');
    
  Route::get('admin/file_maintenance/search_illustration', 'FileMaintenanceController@search_illustration')
    ->name('admin.file_maintenance.search_illustration');
    
  Route::get('admin/file_maintenance/filter_illustrations/{filter?}', 'FileMaintenanceController@filter_illustrations')
    ->name('admin.file_maintenance.filter_illustrations');  
    
  Route::post('admin/file_maintenance/store_illustration', 'FileMaintenanceController@store_illustration')
    ->name('admin.file_maintenance.store_illustration');
    
  Route::get('admin/file_maintenance/edit_illustration/{id?}', 'FileMaintenanceController@edit_illustration_view')
    ->name('admin.file_maintenance.edit_illustration_view');
    
  Route::put('admin/file_maintenance/store_illustration', 'FileMaintenanceController@store_illustration')
    ->name('admin.file_maintenance.store_illustration');
    
  Route::delete('admin/file_maintenance/delete_illustration/{id?}', 'FileMaintenanceController@delete_illustration')
    ->name('admin.file_maintenance.delete_illustration');

    
  // Tags
  Route::get('admin/file_maintenance/tags', 'FileMaintenanceController@tags_view')
    ->name('admin.file_maintenance.tags');
    
  Route::get('admin/file_maintenance/search_tag', 'FileMaintenanceController@search_tag')
    ->name('admin.file_maintenance.search_tag');
    
  Route::get('admin/file_maintenance/filter_tags/{filter?}', 'FileMaintenanceController@filter_tags')
    ->name('admin.file_maintenance.filter_tags');  
    
  Route::post('admin/file_maintenance/store_tag', 'FileMaintenanceController@store_tag')
    ->name('admin.file_maintenance.store_tag');
    
  Route::get('admin/file_maintenance/edit_tag/{id?}', 'FileMaintenanceController@edit_tag_view')
    ->name('admin.file_maintenance.edit_tag_view');
    
  Route::put('admin/file_maintenance/store_tag', 'FileMaintenanceController@store_tag')
    ->name('admin.file_maintenance.store_tag');
    
  Route::delete('admin/file_maintenance/delete_tag/{id?}', 'FileMaintenanceController@delete_tag')
    ->name('admin.file_maintenance.delete_tag');
    
    
    
  /////// Thesis Books Filemaintenance  


  // Thesis System Types

  Route::get('admin/file_maintenance/system_types', 'FileMaintenanceController@system_types_view')
    ->name('admin.file_maintenance.system_types');
    
  Route::get('admin/file_maintenance/system_types_per_page/{per_page?}', 'FileMaintenanceController@system_types_per_page')
    ->name('admin.file_maintenance.system_types_per_page');
    
  Route::get('admin/file_maintenance/search_system_type/{search?}', 'FileMaintenanceController@search_system_type')
    ->name('admin.file_maintenance.search_system_type');
    
  Route::get('admin/file_maintenance/filter_system_types/{filter?}', 'FileMaintenanceController@filter_system_types')
    ->name('admin.file_maintenance.filter_system_types');  
    
  Route::post('admin/file_maintenance/store_system_type', 'FileMaintenanceController@store_system_type')
    ->name('admin.file_maintenance.store_system_type');
    
  Route::get('admin/file_maintenance/edit_system_type/{id?}', 'FileMaintenanceController@edit_system_type_view')
    ->name('admin.file_maintenance.edit_system_type_view');
    
  Route::put('admin/file_maintenance/store_system_type', 'FileMaintenanceController@store_system_type')
    ->name('admin.file_maintenance.store_system_type');
    
  Route::delete('admin/file_maintenance/delete_system_type/{id?}', 'FileMaintenanceController@delete_system_type')
    ->name('admin.file_maintenance.delete_system_type');



  // Thesis Categories

  Route::get('admin/file_maintenance/thesis_categories', 'FileMaintenanceController@thesis_categories_view')
    ->name('admin.file_maintenance.thesis_categories');
    
  Route::get('admin/file_maintenance/thesis_categories_per_page/{per_page?}', 'FileMaintenanceController@thesis_categories_per_page')
    ->name('admin.file_maintenance.thesis_categories_per_page');
    
  Route::get('admin/file_maintenance/search_thesis_category/{search?}', 'FileMaintenanceController@search_thesis_category')
    ->name('admin.file_maintenance.search_thesis_category');
    
  Route::get('admin/file_maintenance/filter_thesis_categories/{filter?}', 'FileMaintenanceController@filter_thesis_categories')
    ->name('admin.file_maintenance.filter_thesis_categories');  
    
  Route::post('admin/file_maintenance/store_thesis_category', 'FileMaintenanceController@store_thesis_category')
    ->name('admin.file_maintenance.store_thesis_category');
    
  Route::get('admin/file_maintenance/edit_thesis_category/{id?}', 'FileMaintenanceController@edit_thesis_category_view')
    ->name('admin.file_maintenance.edit_thesis_category_view');
    
  Route::put('admin/file_maintenance/store_thesis_category', 'FileMaintenanceController@store_thesis_category')
    ->name('admin.file_maintenance.store_thesis_category');
    
  Route::delete('admin/file_maintenance/delete_thesis_category/{id?}', 'FileMaintenanceController@delete_thesis_category')
    ->name('admin.file_maintenance.delete_thesis_category');
    


  // Thesis Subjects

  Route::get('admin/file_maintenance/thesis_subjects', 'FileMaintenanceController@thesis_subjects_view')
    ->name('admin.file_maintenance.thesis_subjects');
    
  Route::get('admin/file_maintenance/thesis_subjects_per_page/{per_page?}', 'FileMaintenanceController@thesis_subjects_per_page')
    ->name('admin.file_maintenance.thesis_subjects_per_page');
    
  Route::get('admin/file_maintenance/search_thesis_subject/{search?}', 'FileMaintenanceController@search_thesis_subject')
    ->name('admin.file_maintenance.search_thesis_subject');
    
  Route::get('admin/file_maintenance/filter_thesis_subjects/{filter?}', 'FileMaintenanceController@filter_thesis_subjects')
    ->name('admin.file_maintenance.filter_thesis_subjects');  
    
  Route::post('admin/file_maintenance/store_thesis_subject', 'FileMaintenanceController@store_thesis_subject')
    ->name('admin.file_maintenance.store_thesis_subject');
    
  Route::get('admin/file_maintenance/edit_thesis_subject/{id?}', 'FileMaintenanceController@edit_thesis_subject_view')
    ->name('admin.file_maintenance.edit_thesis_subject_view');
    
  Route::put('admin/file_maintenance/store_thesis_subject', 'FileMaintenanceController@store_thesis_subject')
    ->name('admin.file_maintenance.store_thesis_subject');
    
  Route::delete('admin/file_maintenance/delete_thesis_subject/{id?}', 'FileMaintenanceController@delete_thesis_subject')
    ->name('admin.file_maintenance.delete_thesis_subject');

    

  /////// Accounts Filemaintenance

  
  // Departments

  Route::get('admin/file_maintenance/departments', 'FileMaintenanceController@departments_view')
    ->name('admin.file_maintenance.departments');
    
  Route::get('admin/file_maintenance/departments_per_page/{per_page?}', 'FileMaintenanceController@departments_per_page')
    ->name('admin.file_maintenance.departments_per_page');
    
  Route::get('admin/file_maintenance/search_department/{search?}', 'FileMaintenanceController@search_department')
    ->name('admin.file_maintenance.search_department');
    
  Route::get('admin/file_maintenance/filter_departments/{filter?}', 'FileMaintenanceController@filter_departments')
    ->name('admin.file_maintenance.filter_departments');  
    
  Route::post('admin/file_maintenance/store_department', 'FileMaintenanceController@store_department')
    ->name('admin.file_maintenance.store_department');
    
  Route::get('admin/file_maintenance/edit_department/{id?}', 'FileMaintenanceController@edit_department_view')
    ->name('admin.file_maintenance.edit_department_view');
    
  Route::put('admin/file_maintenance/store_department', 'FileMaintenanceController@store_department')
    ->name('admin.file_maintenance.store_department');
    
  Route::delete('admin/file_maintenance/delete_department/{id?}', 'FileMaintenanceController@delete_department')
    ->name('admin.file_maintenance.delete_department');  
    
    
  // Programs
  Route::get('admin/file_maintenance/programs', 'FileMaintenanceController@programs_view')
    ->name('admin.file_maintenance.programs');
    
  Route::get('admin/file_maintenance/filter_programs/{filter?}', 'FileMaintenanceController@filter_programs')
    ->name('admin.file_maintenance.filter_programs');
    
  Route::get('admin/file_maintenance/search_program/{search?}', 'FileMaintenanceController@search_program')
    ->name('admin.file_maintenance.search_program');  
    
  Route::post('admin/file_maintenance/store_program', 'FileMaintenanceController@store_program')
    ->name('admin.file_maintenance.store_program');
    
  Route::get('admin/file_maintenance/edit_program/{id?}', 'FileMaintenanceController@edit_program_view')
    ->name('admin.file_maintenance.edit_program_view');
    
  Route::put('admin/file_maintenance/store_program', 'FileMaintenanceController@store_program')
    ->name('admin.file_maintenance.store_program');

  Route::delete('admin/file_maintenance/delete_program/{id?}', 'FileMaintenanceController@delete_program')
    ->name('admin.file_maintenance.delete_program');
    
    
  // Sections
  Route::post('admin/file_maintenance/store_section', 'FileMaintenanceController@store_section')
    ->name('admin.file_maintenance.store_section');
    
  Route::get('admin/file_maintenance/edit_section/{id?}', 'FileMaintenanceController@edit_section_view')
    ->name('admin.file_maintenance.edit_section_view');
    
  Route::put('admin/file_maintenance/store_section', 'FileMaintenanceController@store_section')
    ->name('admin.file_maintenance.store_section');
    
  Route::delete('admin/file_maintenance/edit_program/delete_section/{id?}', 'FileMaintenanceController@delete_section')
    ->name('admin.file_maintenance.delete_section');


    
/******************RFID's/******************/

  
  // Dynamic All Rfid
  Route::get('admin/rfid/all_users/{type?}', 'RfidController@all_rfid')
    ->name('admin.rfid.all_users');

  // get url
  Route::get('admin/rfid/search_rfid', 'RfidController@search_rfid')
    ->name('admin.rfid.search_rfid_url');

  Route::get('admin/rfid/search_rfid/type/{type?}/search/{search?}', 'RfidController@search_rfid')
    ->name('admin.rfid.search_rfid');

  // get url
  Route::get('admin/rfid/rfid_per_page', 'RfidController@rfid_per_page')
    ->name('admin.rfid.rfid_per_page_url');  

  Route::get('admin/rfid/rfid_per_page/type/{type?}/per_page/{per_page?}', 'RfidController@rfid_per_page')
    ->name('admin.rfid.rfid_per_page');  

  // get url
  Route::get('admin/rfid/rfid_toOrder', 'RfidController@rfid_toOrder')
    ->name('admin.rfid.rfid_toOrder_url');

  Route::get('admin/rfid/rfid_toOrder/type/{type?}/toOrder/{toOrder?}', 'RfidController@rfid_toOrder')
    ->name('admin.rfid.rfid_toOrder');

  // get url
  Route::get('admin/rfid/rfid_orderBy', 'RfidController@rfid_orderBy')
    ->name('admin.rfid.rfid_orderBy_url');

  Route::get('admin/rfid/rfid_orderBy/type/{type?}/orderBy/{orderBy?}', 'RfidController@rfid_orderBy')
    ->name('admin.rfid.rfid_orderBy');
    
  // get url
  Route::get('admin/rfid/filter_rfid', 'ReportsController@filter_rfid')
    ->name('admin.rfid.filter_rfid_url');  
    
  Route::get('admin/rfid/filter_rfid/type/{type?}/filter/{filter?}', 'RfidController@filter_rfid')
    ->name('admin.rfid.filter_rfid');  


  // Add RFID to user

  Route::get('admin/rfid/scan_rfid/{type?}', 'RfidController@scan_rfid')
    ->name('admin.rfid.scan_rfid');

  Route::post('admin/rfid/check_rfid', 'RfidController@check_rfid')
    ->name('admin.rfid.check_rfid');

  Route::post('admin/rfid/add_rfid', 'RfidController@add_rfid')
    ->name('admin.rfid.add_rfid');

  // url
  Route::get('admin/rfid/scan_change_rfid', 'RfidController@scan_change_rfid')
    ->name('admin.rfid.scan_change_rfid_url');
    
  Route::get('admin/rfid/scan_change_rfid/type/{type?}/user_id/{user_id?}', 'RfidController@scan_change_rfid')
    ->name('admin.rfid.scan_change_rfid');

  Route::delete('admin/rfid/change_rfid/{user_id?}', 'RfidController@change_rfid')
    ->name('admin.rfid.change_rfid');



/******************Accounts/******************/

  // Admins

  Route::get('admin/accounts/admins', 'AdminsController@admins')
    ->name('admin.accounts.admins');
    
  Route::get('admin/accounts/admins/search_admin/{search?}', 'AdminsController@search_admin')
    ->name('admin.accounts.admins.search_admin');
  
  
  // Dynamic to Orderby ToOrder filter & Roles
  
  Route::get('admin/accounts/admins/admins_per_page/{per_page?}', 'AdminsController@admins_per_page')
    ->name('admin.accounts.admins.admins_per_page');
  
  Route::get('admin/accounts/admins/admins_toOrder/{ToOrder?}', 'AdminsController@admins_toOrder')
    ->name('admin.accounts.admins.admins_toOrder');
  
  Route::get('admin/accounts/admins/admins_orderBy/{orderBy?}', 'AdminsController@admins_orderBy')
    ->name('admin.accounts.admins.admins_orderBy');
  
  Route::get('admin/accounts/admins/filter_admins/{filter?}', 'AdminsController@filter_admins')
    ->name('admin.accounts.admins.filter_admins');  
  
  Route::get('admin/accounts/admins/roles_admins/{filter?}', 'AdminsController@roles_admins')
    ->name('admin.accounts.admins.roles_admins');  
  
  
  // Actions
  
  Route::get('admin/accounts/admins/add_admin', 'AdminsController@add_admin_view')
    ->name('admin.accounts.admins.add_admin_view');
  
  Route::post('admin/accounts/admins/add_new_admin', 'AdminsController@add_new_admin')
    ->name('admin.accounts.admins.add_new_admin');
  
  Route::post('admin/accounts/admins/store_admin', 'AdminsController@store_admin')
    ->name('admin.accounts.admins.store_admin');
  
  Route::get('admin/accounts/admins/edit_admin/{id?}', 'AdminsController@edit_admin_view')
    ->name('admin.accounts.admins.edit_admin_view');
  
  Route::put('admin/accounts/admins/store_admin', 'AdminsController@store_admin')
    ->name('admin.accounts.admins.store_admin');
  
  Route::delete('admin/accounts/admins/delete_admin/{id?}', 'AdminsController@delete_admin')
    ->name('admin.accounts.admins.delete_admin');



  // Staff Caoches

  Route::post('admin/accounts/import_excell_staff_coaches', 'StaffCoachController@import_excell_staff_coaches')
    ->name('admin.accounts.import_excell_staff_coaches');

  Route::get('admin/accounts/staff_coaches', 'StaffCoachController@staff_coaches_view')
    ->name('admin.accounts.staff_coaches');

  Route::get('admin/accounts/search_staff_coach/{search?}', 'StaffCoachController@search_staff_coach')
    ->name('admin.accounts.search_staff_coach');

  Route::get('admin/accounts/staff_coaches_per_page/{per_page?}', 'StaffCoachController@staff_coaches_per_page')
    ->name('admin.accounts.staff_coaches_per_page');

  Route::get('admin/accounts/staff_coaches_toOrder/{ToOrder?}', 'StaffCoachController@staff_coaches_toOrder')
    ->name('admin.accounts.staff_coaches_toOrder');

  Route::get('admin/accounts/staff_coaches_orderBy/{orderBy?}', 'StaffCoachController@staff_coaches_orderBy')
    ->name('admin.accounts.staff_coaches_orderBy');

  Route::get('admin/accounts/filter_staff_coaches/{filter?}', 'StaffCoachController@filter_staff_coaches')
    ->name('admin.accounts.filter_staff_coaches');  

  Route::get('admin/accounts/add_staff_coach', 'StaffCoachController@add_staff_coach_view')
    ->name('admin.accounts.add_staff_coach_view');

  Route::get('admin/accounts/view_staff_coach/{id?}', 'StaffCoachController@view_staff_coach')
    ->name('admin.accounts.view_staff_coach');

  Route::post('admin/accounts/add_new_staff_coach', 'StaffCoachController@add_new_staff_coach')
    ->name('admin.accounts.add_new_staff_coach');

  Route::post('admin/accounts/store_staff_coach', 'StaffCoachController@store_staff_coach')
    ->name('admin.staff_coaches.store_staff_coach');

  Route::get('admin/accounts/edit_staff_coach/{id?}', 'StaffCoachController@edit_staff_coach_view')
    ->name('admin.accounts.edit_staff_coach_view');

  Route::put('admin/accounts/store_staff_coach', 'StaffCoachController@store_staff_coach')
    ->name('admin.accounts.store_staff_coach');

  Route::delete('admin/accounts/delete_staff_coach/{id?}', 'StaffCoachController@delete_staff_coach')
    ->name('admin.accounts.delete_staff_coach');


  //Students

  Route::post('admin/accounts/import_excell_students', 'StudentController@import_excell_students')
    ->name('admin.accounts.import_excell_students');

  Route::get('admin/accounts/students', 'StudentController@students_view')
    ->name('admin.accounts.students');

  Route::get('admin/accounts/search_student/{search?}', 'StudentController@search_student')
    ->name('admin.accounts.search_student');

  Route::get('admin/accounts/students_per_page/{per_page?}', 'StudentController@students_per_page')
    ->name('admin.accounts.students_per_page');

  Route::get('admin/accounts/students_toOrder/{ToOrder?}', 'StudentController@students_toOrder')
    ->name('admin.accounts.students_toOrder');

  Route::get('admin/accounts/students_orderBy/{orderBy?}', 'StudentController@students_orderBy')
    ->name('admin.accounts.students_orderBy');

  Route::get('admin/accounts/filter_students/{filter?}', 'StudentController@filter_students')
    ->name('admin.accounts.filter_students');  

  Route::get('admin/accounts/add_student', 'StudentController@add_student_view')
    ->name('admin.accounts.add_student_view');
  
  Route::get('admin/accounts/get_data_section/{program_id?}', 'StudentController@get_data_section')
    ->name('admin.accounts.get_data_section');

  Route::get('admin/accounts/get_data_grade_year/{program_id?}', 'StudentController@get_data_grade_year')
    ->name('admin.accounts.get_data_grade_year');

  Route::get('admin/accounts/get_data_sem/{program_id?}', 'StudentController@get_data_sem')
    ->name('admin.accounts.get_data_sem');

  Route::get('admin/accounts/view_student/{id?}', 'StudentController@view_student')
    ->name('admin.accounts.view_student');

  Route::post('admin/accounts/add_new_student', 'StudentController@add_new_student')
    ->name('admin.accounts.add_new_student');

  Route::post('admin/accounts/store_student', 'StudentController@store_student')
    ->name('admin.students.store_student');

  Route::get('admin/accounts/edit_student/{id?}', 'StudentController@edit_student_view')
    ->name('admin.accounts.edit_student_view');

  Route::put('admin/accounts/store_student', 'StudentController@store_student')
    ->name('admin.accounts.store_student');

  Route::delete('admin/accounts/delete_student/{id?}', 'StudentController@delete_student')
    ->name('admin.accounts.delete_student');




  
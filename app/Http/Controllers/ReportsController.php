<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\RfidUser; 
use App\AttendanceUser; 

use App\User; 
use App\Student; 
use App\StaffCoach; 

use App\BorrowedBook;
use App\BorrowedEvent;

use App\EgamesReservation; 
use App\EgamesEvent; 
use App\EgamesSetting; 

use App\RoomsReservation; 
use App\RoomsEvent; 

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Attendance Scanner

    public function attendance_scanner()
    {
        return view('admin.reports.attendance_scanner');

    }
    
    // All Reports

    public function all_reports($report_type)
    {
        $all_report_types = $this->get_all_report_types();
        
        if(in_array($report_type, $all_report_types) == false){
            
            $report_type = 'attendance';

        }

        $reports = $this->get_all_reports($report_type);
        
        $all_count_data = $this->get_all_data_reports_count($report_type);
        
        $reports_info_data = $this->get_reports_info_data($report_type);

        return view('admin.reports.all_reports')->with('reports', $reports)
            ->with('all_count_data', $all_count_data)
            ->with('report_type', $report_type)
            ->with('reports_info_data', $reports_info_data);
            
    }
  
    public function get_all_report_types()
    {
        $all_report_types = ['attendance', 'borrowed_books', 'all_borrowed', 'egames', 'rooms', 'board_ps4_games', 'accountabilities'];
        
        return $all_report_types;
        
    }

    public function get_reports_info_data($report_type)
    {
        $all_data_toOrder = $this->get_all_toOrder($report_type, true);
            
        $all_data_filter = $this->get_all_filter($report_type, true);

        $all_user_types = $this->get_all_user_types($report_type, true);

        $all_pc_types = $this->get_all_pc_types(true);
        
        $all_users = $this->get_all_users();
        
        if($report_type == 'attendance'){

            $title = 'Attendance';

            $sidebar_nav_lev_2 = 'attendance_reports_ul';

            $all_table_columns = ['User', 'Room', 'User Type', 'Time-In']; 

            $reports_info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_toOrder' => $all_data_toOrder['all_toOrder'],
                'all_toOrder_name' => $all_data_toOrder['all_toOrder_name'],
                'all_filter' => $all_data_filter['all_filter'],
                'all_filter_name' => $all_data_filter['all_filter_name'],
                'all_user_types' => $all_user_types['all_user_types'],
                'all_user_type_name' => $all_user_types['all_user_type_name'],
                'all_table_columns' => $all_table_columns,
                'all_users' => $all_users
            ];

            
        }else if($report_type == 'borrowed_books'){

            $title = 'Borrowing Books';

            $sidebar_nav_lev_2 = 'borrowed_books_reports_ul';

            $all_table_columns = ['Trans. No.', 'Image', 'Title', 'Author', 'Acc. No.', 'User', 'Date', 'Status', 'Created']; 

            $reports_info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_toOrder' => $all_data_toOrder['all_toOrder'],
                'all_toOrder_name' => $all_data_toOrder['all_toOrder_name'],
                'all_filter' => $all_data_filter['all_filter'],
                'all_filter_name' => $all_data_filter['all_filter_name'],
                'all_table_columns' => $all_table_columns,
                'all_users' => $all_users
            ];

        }else if($report_type == 'all_borrowed'){

            $title = 'Borrowed Books';

            $sidebar_nav_lev_2 = 'borrowed_books_reports_ul';

            $all_table_columns = ['Classification', 'Type of Material', 'Quantity', 'Title', 'Client']; 

            $reports_info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_toOrder' => $all_data_toOrder['all_toOrder'],
                'all_toOrder_name' => $all_data_toOrder['all_toOrder_name'],
                'all_filter' => $all_data_filter['all_filter'],
                'all_filter_name' => $all_data_filter['all_filter_name'],
                'all_user_types' => $all_user_types['all_user_types'],
                'all_user_type_name' => $all_user_types['all_user_type_name'],
                'all_table_columns' => $all_table_columns,
                'all_users' => $all_users
            ];
            
        }else if($report_type == 'egames'){

            $title = 'E-games';

            $sidebar_nav_lev_2 = 'egames_reports_ul';

            $all_table_columns = ['PC Name', 'PC Type', 'User', 'Date', 'Time-slot', 'Status', 'Created']; 
            
            $egames_settings = EgamesSetting::where('id', 1)->first();
            
            $reports_info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_toOrder' => $all_data_toOrder['all_toOrder'],
                'all_toOrder_name' => $all_data_toOrder['all_toOrder_name'],
                'all_filter' => $all_data_filter['all_filter'],
                'all_filter_name' => $all_data_filter['all_filter_name'],
                'all_pc_types' => $all_pc_types['all_pc_types'],
                'all_pc_type_name' => $all_pc_types['all_pc_type_name'],
                'all_table_columns' => $all_table_columns,
                'egames_per_session' => $egames_settings['per_session']
            ];
            
        }else if($report_type == 'rooms'){
            
            $title = 'Rooms';

            $sidebar_nav_lev_2 = 'rooms_reports_ul';

            $all_table_columns = ['Topic/Description', 'Purpose', 'User', 'Reserve Date', 'Time-slot', 'Status', 'Created']; 
            
            $reports_info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_toOrder' => $all_data_toOrder['all_toOrder'],
                'all_toOrder_name' => $all_data_toOrder['all_toOrder_name'],
                'all_filter' => $all_data_filter['all_filter'],
                'all_filter_name' => $all_data_filter['all_filter_name'],
                'all_table_columns' => $all_table_columns,
                'all_users' => $all_users
            ];


        }

        return $reports_info_data;

    }

    public function get_all_users()
    {
        $users = User::all();
    
        $students = Student::all();
    
        $staff_coach = StaffCoach::all();
        
        $all_users = [
            'users' => $users,
            'students' => $students,
            'staff_coach' => $staff_coach,
        ];
        
        return $all_users;

    }

    public function check_session_queries($report_type)
    {
        $session_name_order = $report_type . '_reports_toOrder'; 
            
        $session_name_orderBy = $report_type . '_reports_orderBy'; 

        $session_name_per_page = $report_type . '_reports_per_page'; 
        
        if(session()->has($session_name_order) != true){

            session([$session_name_order => 'created_at' ]);

        }

        if(session()->has($session_name_orderBy) != true){

            session([$session_name_orderBy => 'desc' ]);
            
        }

        if (session()->has($session_name_per_page) != true) {

            session([$session_name_per_page => 10 ]);
            
        }
    }

    public function get_all_reports($report_type)
    {
        $this->check_session_queries($report_type);
        
        $reports_query = $this->get_reports_query($report_type);
            
        $reports_query = $this->add_order_queries($report_type, $reports_query, false); 
        
        return $reports_query;
        
    }

    public function add_order_queries($report_type, $report_query, $search_on)
    {
        $session_get_all = $report_type . '_reports_getAll';

        $session_get_user_type = $report_type . '_reports_get_user_type';

        $session_get_pc_type = $report_type . '_reports_getAll_pc_type';
        
        $session_toOrder = $report_type . '_reports_toOrder';

        $session_orderBy = $report_type . '_reports_orderBy';

        $session_per_page = $report_type . '_reports_per_page';
        
        if(session()->has($report_type . '_reports_getAll')){

            if(session()->get($report_type . '_reports_getAll') != 'all'){

                if($report_type == 'attendance'){

                    $report_query = $report_query->where('attendance_users.room_ref_no', session()->get($session_get_all));
                    
                }else if($report_type == 'borrowed_books'){

                    $report_query = $report_query->where('borrowed_events.status', session()->get($session_get_all));
                    
                }else if($report_type == 'all_borrowed'){
                    
                    $report_query = $report_query->where('borrowed_books.book_type', session()->get($session_get_all));
                    
                }else if($report_type == 'egames'){
        
                    $report_query = $report_query->where('egames_events.status', session()->get($session_get_all));
                    
                }else if($report_type == 'rooms'){
                    
                    $report_query = $report_query->where('rooms_events.status', session()->get($session_get_all));
        
                }
            }
            
        }else{
                
            session([$report_type . '_reports_getAll' => 'all' ]);

        }

        if(session()->has($report_type . '_reports_get_user_type')){

            if(session()->get($report_type . '_reports_get_user_type') != 'all'){

                if($report_type == 'attendance'){
                    
                    $report_query = $report_query->where('attendance_users.user_type', session()->get($session_get_user_type));
                    
                }else if($report_type == 'all_borrowed'){
                    
                    $report_query = $report_query->where('users.user_type', session()->get($report_type . '_reports_get_user_type'));

                }
            }

        }else{
                
            session([$report_type . '_reports_get_user_type' => 'all' ]);

        }

        if($report_type == 'egames' && session()->has($report_type . '_reports_getAll_pc_type')){

            if(session()->get($report_type . '_reports_getAll_pc_type') != 'all'){

                $report_query = $report_query->where('egames_slots.pc_type', session()->get($session_get_pc_type));

            }

        }else{
                
            session([$report_type . '_reports_getAll_pc_type' => 'all' ]);

        }

        if($search_on){

            $count = $report_query->count();
            
            session()->flash('admin_search_count', $count);
            
        }

        $reports = $report_query->orderBy(session()->get($session_toOrder), session()->get($session_orderBy))
            ->paginate(session()->get($session_per_page));
            
        
        if($reports->count() <= 0){
            
            session()->flash('error_status', 'No Data Found!');
            
        }

        return $reports;

    }

    public function get_reports_query($report_type)
    {
        if($report_type == 'attendance'){
            
            $reports_query = AttendanceUser::join('rfid_users', 'attendance_users.rfid_users_id', 'rfid_users.id')
                ->join('users', 'rfid_users.user_id', 'users.id')
                ->select(
                    'attendance_users.*',
                    'rfid_users.user_id'
                );

        }else if($report_type == 'borrowed_books'){
            
            $reports_query = BorrowedEvent::join('borrowed_books', 'borrowed_events.borrowed_book_id', 'borrowed_books.id')
                ->join('no_accessions', 'borrowed_books.accession_no_id', 'no_accessions.id')
                ->join('accessions', 'no_accessions.accession_id', 'accessions.id')
                ->join('authors', 'accessions.author_id', '=', 'authors.id')
                ->select(
                    'borrowed_events.*',
                    'borrowed_books.transaction_no',
                    'borrowed_books.id AS borrowed_books_id',
                    'borrowed_books.user_id',
                    'borrowed_books.accession_no_id',
                    'borrowed_books.book_type',
                    'no_accessions.accession_no',
                    'no_accessions.accession_id',
                    'accessions.book_title',
                    'accessions.pic_url',
                    'authors.author_name'
                );

        }else if($report_type == 'all_borrowed'){
            
            $reports_query = BorrowedEvent::join('borrowed_books', 'borrowed_events.borrowed_book_id', 'borrowed_books.id')
                ->join('no_accessions', 'borrowed_books.accession_no_id', 'no_accessions.id')
                ->join('accessions', 'no_accessions.accession_id', 'accessions.id')
                ->join('classifications', 'accessions.classification_id', 'classifications.id')
                ->join('users', 'borrowed_books.user_id', 'users.id')
                ->select(
                    'borrowed_events.status',
                    'borrowed_events.created_at',
                    'borrowed_books.user_id',
                    'borrowed_books.book_type',
                    'accessions.book_title',
                    'classifications.name AS classification_name',
                    'users.user_type',
                    DB::raw('Count(accessions.book_title) as book_count')
                )
                ->groupBy('accessions.book_title')
                ->where('borrowed_events.status', 3);

            //$reports_query->get();

        }else if($report_type == 'egames'){
            
            $reports_query = EgamesEvent::join('egames_reservations', 'egames_events.egames_reservation_id', 'egames_reservations.id')
                ->join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
                ->join('students', 'egames_reservations.user_id', 'students.user_id')
                ->select(
                    'egames_events.*',
                    'egames_reservations.reserve_date',
                    'egames_reservations.reserve_time_slot',
                    'egames_slots.pc_no',
                    'egames_slots.pc_name',
                    'egames_slots.pc_type',
                    'students.f_name',
                    'students.m_name',
                    'students.l_name',
                    'students.email_add',
                    'students.contact_no'
                );
            
        }else if($report_type == 'rooms'){
            
            $reports_query = RoomsEvent::join('rooms_reservations', 'rooms_events.rooms_reservation_id', 'rooms_reservations.id')
                ->select(
                    'rooms_events.*',
                    'rooms_reservations.reserve_date',
                    'rooms_reservations.purpose',
                    'rooms_reservations.topic_description',
                    'rooms_reservations.user_id',
                    'rooms_reservations.reserve_date',
                    'rooms_reservations.reserve_time_start',
                    'rooms_reservations.reserve_time_end',
                    'rooms_reservations.no_users'
                );
        }

        return $reports_query;

    }

    public function get_all_data_reports_count($report_type)
    {
        if($report_type == 'attendance'){

            $attendance_hall = AttendanceUser::where('room_ref_no', 1)->count();

            $cozy_room = AttendanceUser::where('room_ref_no', 2)->count();

            $reading_area = AttendanceUser::where('room_ref_no', 3)->count();

            $egames_research_room = AttendanceUser::where('room_ref_no', 4)->count();
            
            $shs = AttendanceUser::join('rfid_users', 'attendance_users.rfid_users_id', 'rfid_users.id')
                ->join('users', 'rfid_users.user_id', 'users.id')
                ->join('students', 'rfid_users.user_id', 'students.user_id')
                ->join('programs', 'students.program_id', '=', 'programs.id')
                ->where([
                    ['programs.type', 0],
                    ['room_ref_no', 1]
                ])
                ->count();
            
            $tertiary = AttendanceUser::join('rfid_users', 'attendance_users.rfid_users_id', 'rfid_users.id')
                ->join('users', 'rfid_users.user_id', 'users.id')
                ->join('students', 'rfid_users.user_id', 'students.user_id')
                ->join('programs', 'students.program_id', '=', 'programs.id')
                ->where([
                    ['programs.type', 1],
                    ['room_ref_no', 1]
                ])
                ->count();
            
            $coaches = AttendanceUser::join('rfid_users', 'attendance_users.rfid_users_id', 'rfid_users.id')
                ->join('users', 'rfid_users.user_id', 'users.id')
                ->join('staff_coaches', 'rfid_users.user_id', 'staff_coaches.user_id')
                ->where('room_ref_no', 1)
                ->count();
            
            $all = AttendanceUser::count();
            
            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'attendance_hall' => [
                    'count' => $attendance_hall,
                    'name' => 'Attendance Hall',
                    'color' => 'text-primary'
                ], 
                'cozy_room' => [
                    'count' => $cozy_room,
                    'name' => 'Cozy Room',
                    'color' => 'text-info'
                ], 
                'reading_area' => [
                    'count' => $reading_area,
                    'name' => 'Reading Area',
                    'color' => 'text-info'
                ], 
                'egames_research_room' => [
                    'count' => $egames_research_room,
                    'name' => 'E-Games/Research Room',
                    'color' => 'text-info'
                ],
                'shs' => [
                    'count' => $shs,
                    'name' => 'SeniorHigh',
                    'color' => 'text-info'
                ],
                'tertiary' => [
                    'count' => $tertiary,
                    'name' => 'Tertiary',
                    'color' => 'text-info'
                ],
                'coaches' => [
                    'count' => $coaches,
                    'name' => 'Staff/Coaches',
                    'color' => 'text-info'
                ]
            ];
            
        }else if($report_type == 'borrowed_books'){

            $pending = BorrowedEvent::where('status', 1)->count();

            $approved = BorrowedEvent::where('status', 2)->count();

            $borrowed = BorrowedEvent::where('status', 3)->count();

            $returned = BorrowedEvent::where('status', 4)->count();

            $damage_lost = BorrowedEvent::where('status', 5)->count();

            $denied = BorrowedEvent::where('status', 8)->count();

            $cancelled = BorrowedEvent::where('status', 9)->count();

            $overdue = BorrowedEvent::where('status', 10)->count();

            $returned_overdue = BorrowedEvent::where('status', 11)->count();
            
            $all = BorrowedEvent::count();

            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'pending' => [
                    'count' => $pending,
                    'name' => 'Pending',
                    'color' => 'text-warning'
                ], 
                'approved' => [
                    'count' => $approved,
                    'name' => 'Approved',
                    'color' => 'text-info'
                ], 
                'borrowed' => [
                    'count' => $borrowed,
                    'name' => 'Borrowed',
                    'color' => 'text-primary'
                ], 
                'returned' => [
                    'count' => $returned,
                    'name' => 'Returned',
                    'color' => 'text-success'
                ], 
                'damage_lost' => [
                    'count' => $damage_lost,
                    'name' => 'Damage/lost',
                    'color' => 'text-danger'
                ], 
                'denied' => [
                    'count' => $denied,
                    'name' => 'Denied',
                    'color' => 'text-danger'
                ], 
                'cancelled' => [
                    'count' => $cancelled,
                    'name' => 'Cancelled',
                    'color' => 'text-danger'
                ],
                'overdue' => [
                    'count' => $overdue,
                    'name' => 'Overdue',
                    'color' => 'text-danger'
                ],  
                'returned_overdue' => [
                    'count' => $returned_overdue,
                    'name' => 'Returned & Overdue',
                    'color' => 'text-danger'
                ]  
            ];

        }else if($report_type == 'all_borrowed'){

            $all_students = BorrowedEvent::join('borrowed_books', 'borrowed_events.borrowed_book_id', 'borrowed_books.id')
                ->join('users', 'borrowed_books.user_id', 'users.id')
                ->where([
                    ['borrowed_events.status', 3],
                    ['users.user_type', 1]
                ])->count();

            $all_coaches = BorrowedEvent::join('borrowed_books', 'borrowed_events.borrowed_book_id', 'borrowed_books.id')
                ->join('users', 'borrowed_books.user_id', 'users.id')
                ->where([
                    ['borrowed_events.status', 3],
                    ['users.user_type', 2]
                ])->count();
            
            $all = BorrowedEvent::where('status', 3)->count();

            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'all_students' => [
                    'count' => $all_students,
                    'name' => 'Students',
                    'color' => 'text-info'
                ],
                'all_coaches' => [
                    'count' => $all_coaches,
                    'name' => 'Staff/Coaches',
                    'color' => 'text-info'
                ] 
            ];
            
        }else if($report_type == 'egames'){

            $pending = EgamesEvent::where('status', 1)->count();
    
            $reserved = EgamesEvent::where('status', 2)->count();

            $playing = EgamesEvent::where('status', 3)->count();

            $finished = EgamesEvent::where('status', 4)->count();

            $denied = EgamesEvent::where('status', 8)->count();

            $cancelled = EgamesEvent::where('status', 9)->count();

            $egames = EgamesEvent::join('egames_reservations', 'egames_events.egames_reservation_id', 'egames_reservations.id')
                ->join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
                ->where('egames_slots.pc_type', 1)->count();

            $research = EgamesEvent::join('egames_reservations', 'egames_events.egames_reservation_id', 'egames_reservations.id')
                ->join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
                ->where('egames_slots.pc_type', 2)->count();
            
            $all = EgamesEvent::count();

            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'pending' => [
                    'count' => $pending,
                    'name' => 'Pending',
                    'color' => 'text-warning'
                ], 
                'reserved' => [
                    'count' => $reserved,
                    'name' => 'Reserved',
                    'color' => 'text-primary'
                ], 
                'playing' => [
                    'count' => $playing,
                    'name' => 'Playing',
                    'color' => 'text-success'
                ], 
                'finished' => [
                    'count' => $finished,
                    'name' => 'Finished',
                    'color' => 'text-info'
                ], 
                'denied' => [
                    'count' => $denied,
                    'name' => 'Denied',
                    'color' => 'text-danger'
                ], 
                'cancelled' => [
                    'count' => $cancelled,
                    'name' => 'Cancelled',
                    'color' => 'text-danger'
                ],
                'egames' => [
                    'count' => $egames,
                    'name' => 'E-games',
                    'color' => 'text-primary'
                ],
                'research' => [
                    'count' => $research,
                    'name' => 'Research',
                    'color' => 'text-info'
                ]  
            ];

            
        }else if($report_type == 'rooms'){
            
            $pending = RoomsEvent::where('status', 1)->count();
    
            $reserved = RoomsEvent::where('status', 2)->count();

            $on_use = RoomsEvent::where('status', 3)->count();

            $finished = RoomsEvent::where('status', 4)->count();

            $denied = RoomsEvent::where('status', 8)->count();

            $cancelled = RoomsEvent::where('status', 9)->count();

            $all = RoomsEvent::count();
            
            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'pending' => [
                    'count' => $pending,
                    'name' => 'Pending',
                    'color' => 'text-warning'
                ], 
                'reserved' => [
                    'count' => $reserved,
                    'name' => 'Reserved',
                    'color' => 'text-primary'
                ], 
                'playing' => [
                    'count' => $on_use,
                    'name' => 'On Use',
                    'color' => 'text-success'
                ], 
                'finished' => [
                    'count' => $finished,
                    'name' => 'Finished',
                    'color' => 'text-info'
                ], 
                'denied' => [
                    'count' => $denied,
                    'name' => 'Denied',
                    'color' => 'text-danger'
                ], 
                'cancelled' => [
                    'count' => $cancelled,
                    'name' => 'Cancelled',
                    'color' => 'text-danger'
                ]  
            ];
            
        }
        
        return $all_count_data;
        
    }

    public function reports_per_page($report_type = "attendance", $per_page = 10) 
    {
        $all_report_types = $this->get_all_report_types();
        
        if(in_array($report_type, $all_report_types)){
            
            $session_per_page = $report_type . '_reports_per_page';

        }else{

            $session_per_page = 'attendance_reports_per_page';
            
            $report_type = "attendance";
            
        }
        
        $per_page_array = [5,10,20,50,100,200,500];
        
        if(in_array($per_page, $per_page_array)){
            
            session([$session_per_page => $per_page]);
            
        }else{
            
            session([$session_per_page => 5]);

        }

        return redirect()->route('admin.reports.all_reports', $report_type);

    }

    public function reports_toOrder($report_type = "attendance", $toOrder = 'created_at') 
    {
        $all_report_types = $this->get_all_report_types();
        
        if(in_array($report_type, $all_report_types)){
            
            $session_toOrder = $report_type . '_reports_toOrder';

        }else{

            $session_toOrder = 'attendance_reports_toOrder';
            
            $report_type = "attendance";
            
        }

        $all_toOrder = $this->get_all_toOrder($report_type, false);
        
        if(in_array($toOrder, $all_toOrder)){
            
            session([$session_toOrder => $toOrder]);
            
        }else{
            
            session([$session_toOrder => 'created_at' ]);

        }

        return redirect()->route('admin.reports.all_reports', $report_type);

    }

    public function get_all_toOrder($report_type, $get_name)
    {
        if($report_type == 'attendance'){

            $all_toOrder = ['created_at'];

            if($get_name){
                
                $all_toOrder_name = ['Latest'];

                $all_data = [
                    'all_toOrder' => $all_toOrder,
                    'all_toOrder_name' => $all_toOrder_name
                ];

                return $all_data;
                
            }
        
        }else if($report_type == 'borrowed_books'){
        
            $all_toOrder = ['transaction_no','book_title','author_name','accession_no','due_date','created_at'];

            if($get_name){
                
                $all_toOrder_name = ['Transaction No.', 'Title', 'Author', 'Acc. No.', 'Date', 'Latest'];

                $all_data = [
                    'all_toOrder' => $all_toOrder,
                    'all_toOrder_name' => $all_toOrder_name
                ];

                return $all_data;
                
            }

        }else if($report_type == 'all_borrowed'){

            $all_toOrder = ['classifications.name', 'book_count','book_title', 'created_at'];

            if($get_name){
                
                $all_toOrder_name = ['Classification', 'Quantity', 'Title', 'Latest'];

                $all_data = [
                    'all_toOrder' => $all_toOrder,
                    'all_toOrder_name' => $all_toOrder_name
                ];

                return $all_data;
                
            }
            
        }else if($report_type == 'egames'){

            $all_toOrder = ['pc_name', 'l_name', 'reserve_date', 'reserve_time_slot', 'created_at'];

            if($get_name){
                
                $all_toOrder_name = ['PC Name', 'User', 'Date', 'Time-slot', 'Latest'];

                $all_data = [
                    'all_toOrder' => $all_toOrder,
                    'all_toOrder_name' => $all_toOrder_name
                ];

                return $all_data;
                
            }
            
        }else if($report_type == 'rooms'){
            
            $all_toOrder = ['topic_description', 'purpose', 'reserve_date', 'reserve_time_start', 'created_at'];

            if($get_name){
                
                $all_toOrder_name = ['Topic/Description', 'Purpose', 'Date', 'Time-slot', 'Latest'];

                $all_data = [
                    'all_toOrder' => $all_toOrder,
                    'all_toOrder_name' => $all_toOrder_name
                ];

                return $all_data;
                
            }
            
        }

        return $all_toOrder;
        
    }

    public function reports_orderBy($report_type = "attendance", $orderBy = 'desc') 
    {
        $all_report_types = $this->get_all_report_types();
        
        if(in_array($report_type, $all_report_types)){
            
            $session_orderBy = $report_type . '_reports_orderBy';

        }else{

            $session_orderBy = 'attendance_reports_orderBy';
            
            $report_type = "attendance";
            
        }

        if($orderBy == 'asc' || $orderBy == 'desc' ){
            
            session([$session_orderBy => $orderBy ]);
            
        }else{
            
            session([$session_orderBy => 'desc' ]);

        }

        return redirect()->route('admin.reports.all_reports', $report_type);

    }

    public function filter_reports($report_type = "attendance", $filter = 'all') 
    {
        $all_report_types = $this->get_all_report_types();
        
        if(in_array($report_type, $all_report_types)){
            
            $session_getAll = $report_type . '_reports_getAll';

        }else{

            $session_getAll = 'attendance_reports_getAll';
            
            $report_type = "attendance";
            
        }

        $all_filter = $this->get_all_filter($report_type, false);
        
        if(in_array($filter, $all_filter)){
            
            session([$session_getAll => $filter]);
            
        }else{
            
            session([$session_getAll => 'all']);

        }

        return redirect()->route('admin.reports.all_reports', $report_type);

    }

    public function filter_user_type_reports($report_type = "attendance", $user_type = 'all') 
    {
        $all_report_types = $this->get_all_report_types();
        
        if(in_array($report_type, $all_report_types)){
            
            $session_get_user_type = $report_type . '_reports_get_user_type';

        }else{

            $session_get_user_type = 'attendance_reports_get_user_type';
            
            $report_type = "attendance";
            
        }

        $all_user_types = $this->get_all_user_types($report_type, false);
        
        if(in_array($user_type, $all_user_types)){
            
            session([$session_get_user_type => $user_type]);
            
        }else{
            
            session([$session_get_user_type => 'all']);

        }

        return redirect()->route('admin.reports.all_reports', $report_type);

    }

    public function filter_pc_type_reports($report_type = "attendance", $pc_type = 'all') 
    {
        if($report_type == 'egames'){
            
            $session_get_pc_type = $report_type . '_reports_getAll_pc_type';

        }else{

            $session_get_pc_type = 'attendance_reports_getAll_pc_type';
            
            $report_type = "attendance";
            
        }

        $all_pc_types = $this->get_all_pc_types(false);
        
        if(in_array($pc_type, $all_pc_types)){
            
            session([$session_get_pc_type => $pc_type]);
            
        }else{
            
            session([$session_get_pc_type => 'all']);

        }

        return redirect()->route('admin.reports.all_reports', $report_type);

    }

    public function get_all_filter($report_type, $get_name)
    {
        if($report_type == 'attendance'){

            $all_filter = ['all', 1, 2, 3, 4];
            
            if($get_name){
                
                $all_filter_name = ['All', 'Attendance Hall', 'Cozy Room', 'Reading Area', 'E-games/Research Room'];

                $all_filter_data = [
                    'all_filter' => $all_filter,
                    'all_filter_name' => $all_filter_name
                ];
                
                return $all_filter_data;
                
            }
            
        }else if($report_type == 'borrowed_books'){            
    
            $all_filter = ['all', 1, 2, 3, 4, 5, 8, 9, 10, 11];
            
            if($get_name){
                
                $all_filter_name = ['All', 'Pending', 'Reserved', 'Borrowed', 'Returned',  'Damage/lost', 'Denied', 'Cancelled', 'Overdue', 'Returned & Overdue'];

                $all_filter_data = [
                    'all_filter' => $all_filter,
                    'all_filter_name' => $all_filter_name
                ];
                
                return $all_filter_data;
                
            }
            
        }else if($report_type == 'all_borrowed'){
            
            $all_filter = ['all', 1, 2];
            
            if($get_name){
                
                $all_filter_name = ['All', 'Book', 'Thesis'];

                $all_filter_data = [
                    'all_filter' => $all_filter,
                    'all_filter_name' => $all_filter_name
                ];
                
                return $all_filter_data;
                
            }
            
        }else if($report_type == 'egames'){

            $all_filter = ['all', 1, 2, 3, 4, 8, 9];
            
            if($get_name){
                
                $all_filter_name = ['All', 'Pending', 'Reserved', 'Playing', 'Finished', 'Denied', 'Cancelled'];

                $all_filter_data = [
                    'all_filter' => $all_filter,
                    'all_filter_name' => $all_filter_name
                ];
                
                return $all_filter_data;
                
            }
            
        }else if($report_type == 'rooms'){

            $all_filter = ['all', 1, 2, 3, 4, 8, 9];
            
            if($get_name){
                
                $all_filter_name = ['All', 'Pending', 'Reserved', 'On Use', 'Finished', 'Denied', 'Cancelled'];

                $all_filter_data = [
                    'all_filter' => $all_filter,
                    'all_filter_name' => $all_filter_name
                ];
                
                return $all_filter_data;
                
            }
            
        }

        return $all_filter;
        
    }

    public function get_all_user_types($report_type, $get_name)
    {
        if($report_type == 'attendance'){
            
            $all_user_types = ['all', 1, 2, 3];
            
            if($get_name){
                
                $all_user_type_name = ['All', 'SeniorHigh', 'Tertary', 'Faculty/Coach'];
        
                $all_user_type_data = [
                    'all_user_types' => $all_user_types,
                    'all_user_type_name' => $all_user_type_name
                ];
    
                return $all_user_type_data;
    
            }
            
            return $all_user_types;
            
        }else if($report_type == 'all_borrowed'){
            
            $all_user_types = ['all', 1, 2];
            
            if($get_name){
                
                $all_user_type_name = ['All', 'Students', 'Faculty/Coach'];
        
                $all_user_type_data = [
                    'all_user_types' => $all_user_types,
                    'all_user_type_name' => $all_user_type_name
                ];
    
                return $all_user_type_data;
    
            }
            
            return $all_user_types;

        }
    }

    public function get_all_pc_types($get_name)
    {
        $all_pc_types = ['all', 1, 2];
        
        if($get_name){
            
            $all_pc_type_name = ['All', 'E-games', 'Research'];
    
            $all_pc_type_data = [
                'all_pc_types' => $all_pc_types,
                'all_pc_type_name' => $all_pc_type_name
            ];

            return $all_pc_type_data;

        }
        
        return $all_pc_types;
        
    }

    public function search_reports($report_type, $search = '')
    {
        $all_report_types = $this->get_all_report_types();
        
        if(in_array($report_type, $all_report_types) == false){
            
            $report_type = 'attendance';

        }
        
        $this->check_session_queries($report_type);
        
        $reports_query = $this->get_reports_query($report_type);
        
        $reports_query = $this->add_reports_search_query($report_type, $reports_query, $search);

        session()->flash('admin_search', $search);
        session([$report_type . '_reports_getAll' => 'all' ]);
        session([$report_type . '_reports_get_user_type' => 'all' ]);
        session([$report_type . '_reports_getAll_pc_type' => 'all' ]);

        $reports = $this->add_order_queries($report_type, $reports_query, true); 
        
        $all_count_data = $this->get_all_data_reports_count($report_type);
        
        $reports_info_data = $this->get_reports_info_data($report_type);
        
        $count = $reports->count();

        if($count <= 0){

            session()->flash('error_status', 'No data found!');
            
        }
            
        return view('admin.reports.all_reports')->with('reports', $reports)
            ->with('all_count_data', $all_count_data)
            ->with('report_type', $report_type)
            ->with('reports_info_data', $reports_info_data);
    }

    public function add_reports_search_query($report_type, $reports_query, $search)
    {
        if($report_type == 'attendance'){
            
            $all_users_id = $this->search_users($search);
            
            $reports_query->where('attendance_users.created_at', 'like', '%'.$search.'%')
                ->orWhereIn('rfid_users.user_id', $all_users_id['student_user_ids'])
                ->orWhereIn('rfid_users.user_id', $all_users_id['staff_coach_ids']);
        
        }else if($report_type == 'borrowed_books'){
            
            $all_users_id = $this->search_users($search);
            
            $reports_query->where('borrowed_books.transaction_no', 'like', '%'.$search.'%')
                ->orWhereIn('borrowed_books.user_id', $all_users_id['student_user_ids'])
                ->orWhereIn('borrowed_books.user_id', $all_users_id['staff_coach_ids'])
                ->orWhere('accessions.book_title', 'like', '%'.$search.'%')
                ->orWhere('authors.author_name', 'like', '%'.$search.'%')
                ->orWhere('no_accessions.accession_no', 'like', '%'.$search.'%')
                ->orWhere('borrowed_books.start_date', 'like', '%'.$search.'%')
                ->orWhere('borrowed_books.due_date', 'like', '%'.$search.'%')
                ->orWhere('borrowed_events.created_at', 'like', '%'.$search.'%');
                
        }else if($report_type == 'all_borrowed'){
                
            $reports_query->Where('accessions.book_title', 'like', '%'.$search.'%');
            
        }else if($report_type == 'egames'){

            $reports_query->where('egames_slots.pc_name', 'like', '%'.$search.'%')
                ->orWhere('egames_reservations.reserve_date', 'like', '%'.$search.'%')
                ->orWhere('egames_reservations.reserve_time_slot', 'like', '%'.$search.'%')
                ->orWhere('egames_events.created_at', 'like', '%'.$search.'%')
                ->orWhere('students.f_name', 'like', '%'.$search.'%')
                ->orWhere('students.m_name', 'like', '%'.$search.'%')
                ->orWhere('students.l_name', 'like', '%'.$search.'%')
                ->orWhere('students.email_add', 'like', '%'.$search.'%');
            
        }else if($report_type == 'rooms'){
            
            $all_users_id = $this->search_users($search);

            $reports_query->where('rooms_reservations.purpose', 'like', '%'.$search.'%')
                ->orWhere('rooms_reservations.topic_description', 'like', '%'.$search.'%')
                ->orWhereIn('rooms_reservations.user_id', $all_users_id['student_user_ids'])
                ->orWhereIn('rooms_reservations.user_id', $all_users_id['staff_coach_ids'])
                ->orWhere('rooms_reservations.reserve_date', 'like', '%'.$search.'%')
                ->orWhere('rooms_reservations.reserve_time_start', 'like', '%'.$search.'%')
                ->orWhere('rooms_reservations.reserve_time_end', 'like', '%'.$search.'%')
                ->orWhere('rooms_reservations.no_users', 'like', '%'.$search.'%');
            
        }

        return $reports_query;

    }

    public function search_users($search)
    {
        // Students
        $search_student = Student::where('f_name' ,'like', '%'.$search.'%')
            ->orWhere('f_name', 'like', '%'.$search.'%')
            ->orWhere('m_name', 'like', '%'.$search.'%')
            ->orWhere('l_name', 'like', '%'.$search.'%')
            ->orWhere('email_add', 'like', '%'.$search.'%')
            ->distinct()->get();

        $student_ids = [];

        foreach ($search_student as $student) {
            
            array_push($student_ids, $student->id);

        }

        $student_users = User::whereIn('user_ref_id', $student_ids)
            ->where('user_type', 1)->distinct()->get();

        $student_user_ids = [];
    
        foreach ($student_users as $student_user) {
        
            array_push($student_user_ids, $student_user->id);

        }
        
        // Staff/Coach
        $search_staff_coach = StaffCoach::where('f_name' ,'like', '%'.$search.'%')
            ->orWhere('f_name', 'like', '%'.$search.'%')
            ->orWhere('m_name', 'like', '%'.$search.'%')
            ->orWhere('l_name', 'like', '%'.$search.'%')
            ->orWhere('email_add', 'like', '%'.$search.'%')
            ->distinct()->get();

        $staff_coach_ids = [];
    
        foreach ($search_staff_coach as $staff_coach) {
            
            array_push($staff_coach_ids, $staff_coach->id);

        }

        $staff_coach_users = User::whereIn('user_ref_id', $staff_coach_ids)
            ->where('user_type', 2)->distinct()->get();
        
        $staff_coach_ids = [];

        foreach ($staff_coach_users as $staff_coach_user) {
        
            array_push($staff_coach_ids, $staff_coach_user->id);

        }

        $all_users_id = [
            'student_user_ids' => $student_user_ids,
            'staff_coach_ids' => $staff_coach_ids
        ];

        return $all_users_id;
        
    }

    public function reports_filter_by_date($report_type, $calendar_type, $search_date = null)
    {
        if($calendar_type == 'date' || $calendar_type == 'week' || $calendar_type == 'month' || $calendar_type == 'year'){
            
            if($search_date != null){

                $all_report_types = $this->get_all_report_types();
                
                $all_usage_report_types = $this->get_all_usage_report_types();
        
                session([$report_type . '_reports_calendar_type' => $calendar_type ]);
                session([$report_type . '_reports_search_date' => $search_date ]);

                if(in_array($report_type, $all_report_types)){
                    
                    $this->check_session_queries($report_type);
                
                    $reports_query = $reports_query = $this->get_reports_query($report_type);

                    if($calendar_type == 'date'){

                        $reports_query = $this->get_reports_search_by_date($report_type, $reports_query, $search_date);

                        $search_to_date = date('Y M d', strtotime($search_date));

                    }else if($calendar_type == 'week' ){

                        $year = substr($search_date,0, -4);
                        $week_number = substr($search_date,6);

                        $first_day = "";
                        $last_day = "";

                        for($day=1; $day<=7; $day++){
                            $days = date('Y-m-d', strtotime($year."W".$week_number.$day));
                            if($day == 1){
                                $first_day = $days;
                            }else if($day == 7){
                                $last_day = $days . " 23:59:59.999";
                            }
                        }

                        $reports_query = $this->get_reports_search_by_week($report_type, $reports_query, $first_day, $last_day);
                        
                        $search_to_date = $search_date;
                        
                    }else if($calendar_type == 'month'){
                        
                        $month = substr($search_date,5);
                        
                        $year = substr($search_date,0, -3);
                        
                        $reports_query = $this->get_reports_search_by_month($report_type, $reports_query, $month, $year);
                        
                        $search_to_date = date('Y M', strtotime($search_date));
                        
                    }else if($calendar_type == 'year'){
                        
                        $reports_query = $this->get_reports_search_by_year($report_type, $reports_query, $search_date);
                        
                        $search_to_date = date('Y', strtotime($search_date));

                    }

                    session()->flash('admin_search', $search_to_date);
                    
                    $reports = $this->add_order_queries($report_type, $reports_query, true); 
                    
                    $all_count_data = $this->get_all_data_reports_count($report_type);
                    
                    $reports_info_data = $this->get_reports_info_data($report_type);
                    
                    $count = $reports->count();

                    if($count <= 0){

                        session()->flash('error_status', 'No data found!');
            
                    }
                        
                    return view('admin.reports.all_reports')->with('reports', $reports)
                        ->with('all_count_data', $all_count_data)
                        ->with('report_type', $report_type)
                        ->with('reports_info_data', $reports_info_data);
                    

                }else if(in_array($report_type, $all_usage_report_types)){
            
                    if($calendar_type == 'date'){

                        $all_dates = $this->get_all_dates($report_type, null, null, $search_date, null, null);

                        $search_to_date = date('Y M d', strtotime($search_date));

                    }else if($calendar_type == 'week' ){

                        $year = substr($search_date,0, -4);
                        $week_number = substr($search_date,6);

                        $first_day = "";
                        $last_day = "";

                        for($day=1; $day<=7; $day++){
                            $days = date('Y-m-d', strtotime($year."W".$week_number.$day));
                            if($day == 1){
                                $first_day = $days;
                            }else if($day == 7){
                                $last_day = $days . " 23:59:59.999";
                            }
                        }

                        $all_dates = $this->get_all_dates($report_type, null, null, null, $first_day, $last_day);
                        
                        $search_to_date = $search_date;
                        
                    }else if($calendar_type == 'month'){
                        
                        $month = substr($search_date,5);
                        
                        $year = substr($search_date,0, -3);
                        
                        $all_dates = $this->get_all_dates($report_type, $year, $month, null, null, null);
                        
                        $search_to_date = date('Y M', strtotime($search_date));
                        
                    }else if($calendar_type == 'year'){
                        
                        $all_dates = $this->get_all_dates($report_type, $search_date, null, null, null, null);
                        
                        $search_to_date = date('Y', strtotime($search_date));

                    }

                    session()->flash('admin_search', $search_to_date);

                    $usage_collection = $this->get_all_usage_reports_collection($report_type, $all_dates);
        
                    $usage_reports_info_data = $this->get_usage_reports_info_data($report_type);
                    
                    $all_usage_count_data = $this->get_all_data_usage_reports_collection_count($report_type, $usage_collection);

                    session()->flash('admin_search_count', $all_usage_count_data['all']['count']);
                    
                    if($all_usage_count_data['all']['count'] <= 0){
                        
                        session()->flash('error_status', 'No Data Found!');
                        
                    }

                    return view('admin.reports.all_usage_reports')->with('usage_reports', $usage_collection->all())
                        ->with('report_type', $report_type)
                        ->with('usage_reports_info_data', $usage_reports_info_data)
                        ->with('all_usage_count_data', $all_usage_count_data);
        
                }else{

                    return redirect()->route('admin.reports.all_reports', 'attendance');
                    
                }
                
            }else{

                return redirect()->route('admin.reports.all_reports', 'attendance');
                
            }
            
        }else{
            
            return redirect()->route('admin.reports.all_reports', 'attendance');
            
        }
    }

    public function get_reports_search_by_date($report_type, $reports_query, $search_date)
    {
        if($report_type == 'attendance'){

            $reports_query->whereDate('attendance_users.created_at', $search_date)->get();
            
        }else if($report_type == 'borrowed_books'){

            $reports_query->whereDate('borrowed_events.created_at', $search_date)->get();
            
        }else if($report_type == 'all_borrowed'){
            
            $reports_query->whereDate('borrowed_events.created_at', $search_date)->get();
            
        }else if($report_type == 'egames'){

            $reports_query->whereDate('egames_events.created_at', $search_date)->get();
            
        }else if($report_type == 'rooms'){
            
            $reports_query->whereDate('rooms_events.created_at', $search_date)->get();
            
        }
        
        return $reports_query;
        
    }

    public function get_reports_search_by_week($report_type, $reports_query, $first_day, $last_day)
    {   
        if($report_type == 'attendance'){

            $reports_query->whereBetween('attendance_users.created_at', [$first_day, $last_day])->get();
            
        }else if($report_type == 'borrowed_books'){

            $reports_query->whereBetween('borrowed_events.created_at', [$first_day, $last_day])->get();

        }else if($report_type == 'all_borrowed'){

            $reports_query->whereBetween('borrowed_events.created_at', [$first_day, $last_day])->get();

        }else if($report_type == 'egames'){

            $reports_query->whereBetween('egames_events.created_at', [$first_day, $last_day])->get();
            
        }else if($report_type == 'rooms'){
            
            $reports_query->whereBetween('rooms_events.created_at', [$first_day, $last_day])->get();
            
        }

        return $reports_query;

    }

    public function get_reports_search_by_month($report_type, $reports_query, $month, $year)
    {   
        if($report_type == 'attendance'){

            $reports_query->whereMonth('attendance_users.created_at', $month)
                ->whereYear('attendance_users.created_at', $year)->get();
            
        }else if($report_type == 'borrowed_books'){

            $reports_query->whereMonth('borrowed_events.created_at', $month)
                ->whereYear('borrowed_events.created_at', $year)->get();

        }else if($report_type == 'all_borrowed'){

            $reports_query->whereMonth('borrowed_events.created_at', $month)
                ->whereYear('borrowed_events.created_at', $year)->get();
            
        }else if($report_type == 'egames'){

            $reports_query->whereMonth('egames_events.created_at', $month)
                ->whereYear('egames_events.created_at', $year)->get();
                
        }else if($report_type == 'rooms'){
            
            $reports_query->whereMonth('rooms_events.created_at', $month)
                ->whereYear('rooms_events.created_at', $year)->get();

        }

        return $reports_query;
        
    }

    public function get_reports_search_by_year($report_type, $reports_query, $search_date)
    {   
        if($report_type == 'attendance'){

            $reports_query->whereYear('attendance_users.created_at', $search_date)->get();
            
        }else if($report_type == 'borrowed_books'){
                   
            $reports_query->whereYear('borrowed_events.created_at', $search_date)->get();

        }else if($report_type == 'all_borrowed'){
                   
            $reports_query->whereYear('borrowed_events.created_at', $search_date)->get();

        }else if($report_type == 'egames'){

            $reports_query->whereYear('egames_events.created_at', $search_date)->get();
            
        }else if($report_type == 'rooms'){
            
            $reports_query->whereYear('rooms_events.created_at', $search_date)->get();
            
        }

        return $reports_query;
        
    }


    public function reports_filter_start_end_date($report_type, $start_date = null, $end_date = null)
    {
        if($start_date != null && $end_date != null){
            
            $all_report_types = $this->get_all_report_types();

            $all_usage_report_types = $this->get_all_usage_report_types();
        
            session([$report_type . '_reports_calendar_type' => 'start_end' ]);
            session([$report_type . '_reports_search_date_start' => $start_date ]);
            session([$report_type . '_reports_search_date_end' => $end_date ]);

            if(in_array($report_type, $all_report_types)){
                
                $this->check_session_queries($report_type);
                
                $reports_query =  $reports_query = $this->get_reports_query($report_type);
    
                $reports_query = $this->get_reports_search_by_start_end_date($report_type, $reports_query, $start_date, $end_date);
    
                $start_date = date('Y M d', strtotime($start_date));
    
                $end_date = date('Y M d', strtotime($end_date));
                
                session()->flash('admin_search', 'Start: ' . $start_date . ' - ' . 'End : ' . $end_date);
                
                $reports = $this->add_order_queries($report_type, $reports_query, true); 
                
                $all_count_data = $this->get_all_data_reports_count($report_type);
                
                $reports_info_data = $this->get_reports_info_data($report_type);
                
                $count = $reports->count();
    
                if($count <= 0){
    
                    session()->flash('error_status', 'No data found!');
        
                }
    
                return view('admin.reports.all_reports')->with('reports', $reports)
                        ->with('all_count_data', $all_count_data)
                        ->with('report_type', $report_type)
                        ->with('reports_info_data', $reports_info_data);

            }else if(in_array($report_type, $all_usage_report_types)){

                $all_dates = $this->get_all_dates($report_type, null, null, null, $start_date, $end_date);

                $start_date = date('Y M d', strtotime($start_date));
    
                $end_date = date('Y M d', strtotime($end_date));
                
                session()->flash('admin_search', 'Start: ' . $start_date . ' - ' . 'End : ' . $end_date);
                
                $usage_collection = $this->get_all_usage_reports_collection($report_type, $all_dates);
                
                $usage_reports_info_data = $this->get_usage_reports_info_data($report_type);
                
                $all_usage_count_data = $this->get_all_data_usage_reports_collection_count($report_type, $usage_collection);

                session()->flash('admin_search_count', $all_usage_count_data['all']['count']);
                
                if($all_usage_count_data['all']['count'] <= 0){
                    
                    session()->flash('error_status', 'No Data Found!');
                    
                }

                return view('admin.reports.all_usage_reports')->with('usage_reports', $usage_collection->all())
                    ->with('report_type', $report_type)
                    ->with('usage_reports_info_data', $usage_reports_info_data)
                    ->with('all_usage_count_data', $all_usage_count_data);
                
            }else{

                return redirect()->route('admin.reports.all_reports', 'attendance');
                
            }
            
        }else{
                
            return redirect()->route('admin.reports.all_reports', 'attendance');

        }
    }

    public function get_reports_search_by_start_end_date($report_type, $reports_query, $start_date, $end_date)
    {   
        $end_date = date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes',strtotime($end_date)));
        
        if($report_type == 'attendance'){
            
            $reports_query->whereBetween('attendance_users.created_at', [$start_date, $end_date])->get();
            
        }else if($report_type == 'borrowed_books'){

            $reports_query->whereBetween('borrowed_events.created_at', [$start_date, $end_date])->get();

        }else if($report_type == 'all_borrowed'){

            $reports_query->whereBetween('borrowed_events.created_at', [$start_date, $end_date])->get();

        }else if($report_type == 'egames'){

            $reports_query->whereBetween('egames_events.created_at', [$start_date, $end_date])->get();
            
        }else if($report_type == 'rooms'){
            
            $reports_query->whereBetween('rooms_events.created_at', [$start_date, $end_date])->get();
            
        }

        return $reports_query;
        
    }


    // Usage Reports

    public function get_all_usage_report_types()
    {
        $all_report_types = ['attendance_usage', 'egames_usage', 'rooms_usage'];
        
        return $all_report_types;
    }

    public function all_usage_reports($report_type)
    {
        $all_usage_report_types = $this->get_all_usage_report_types();
        
        if(in_array($report_type, $all_usage_report_types) == false){
            
            $report_type = 'attendance_usage';

        }

        $current_year = date('Y');
        $current_month = date('m');

        $search_to_date = date('Y M');

        session()->flash('admin_search', $search_to_date);

        $all_dates = $this->get_all_dates($report_type, $current_year, $current_month, null, null, null);
        
        $usage_collection = $this->get_all_usage_reports_collection($report_type, $all_dates);
        
        $usage_reports_info_data = $this->get_usage_reports_info_data($report_type);
        
        $all_usage_count_data = $this->get_all_data_usage_reports_collection_count($report_type, $usage_collection);

        session()->flash('admin_search_count', $all_usage_count_data['all']['count']);
        
        if($all_usage_count_data['all']['count'] <= 0){
            
            session()->flash('error_status', 'No Data Found!');
            
        }

        return view('admin.reports.all_usage_reports')->with('usage_reports', $usage_collection->all())
            ->with('report_type', $report_type)
            ->with('usage_reports_info_data', $usage_reports_info_data)
            ->with('all_usage_count_data', $all_usage_count_data);
            
    }

    public function get_all_dates($report_type, $year, $month, $date, $start_date, $end_date)
    {
        if($report_type == 'attendance_usage'){

            $all_events_dates_query = AttendanceUser::select(DB::raw('DATE(created_at) as date'))
                ->groupBy('date')->distinct();
                
            if($year != null && $month == null){

                $all_events_dates_query = $all_events_dates_query->whereYear('created_at', $year);
                
            }else if ($month != null && $year != null){
                
                $all_events_dates_query = $all_events_dates_query->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
                
            }else if ($date != null){
                
                $all_events_dates_query = $all_events_dates_query->whereDate('created_at', $date);

            }else if ($start_date != null && $end_date != null){

                $end_date = date('Y-m-d h:m:s',strtotime('+23 hour +59 minutes',strtotime($end_date)));

                $all_events_dates_query = $all_events_dates_query->whereBetween('created_at', [$start_date, $end_date]);
                
            }
            
        }else if($report_type == 'egames_usage'){

            $all_events_dates_query = EgamesEvent::join('egames_reservations', 'egames_events.egames_reservation_id', 'egames_reservations.id')
                ->select(DB::raw('DATE(reserve_date) as date'))
                ->groupBy('date')->distinct()
                ->where('egames_events.status', 4); // 3 or 4 playing or finished
                
            if($year != null && $month == null){

                $all_events_dates_query = $all_events_dates_query->whereYear('reserve_date', $year);
                
            }else if ($month != null && $year != null){
                
                $all_events_dates_query = $all_events_dates_query->whereMonth('reserve_date', $month)
                    ->whereYear('reserve_date', $year);
                
            }else if ($date != null){
                
                $all_events_dates_query = $all_events_dates_query->whereDate('reserve_date', $date);

            }else if ($start_date != null && $end_date != null){

                $all_events_dates_query = $all_events_dates_query->whereBetween('reserve_date', [$start_date, $end_date]);
                
            }

        }else if ($report_type == 'rooms_usage'){

            $all_events_dates_query = RoomsEvent::join('rooms_reservations', 'rooms_events.rooms_reservation_id', 'rooms_reservations.id')
                ->select(DB::raw('DATE(reserve_date) as date'))
                ->groupBy('date')->distinct()
                ->where('rooms_events.status', 4); // 3 or 4 playing or finished
                
            if($year != null && $month == null){

                $all_events_dates_query = $all_events_dates_query->whereYear('reserve_date', $year);
                
            }else if ($month != null && $year != null){
                
                $all_events_dates_query = $all_events_dates_query->whereMonth('reserve_date', $month)
                    ->whereYear('reserve_date', $year);
                
            }else if ($date != null){
                
                $all_events_dates_query = $all_events_dates_query->whereDate('reserve_date', $date);

            }else if ($start_date != null && $end_date != null){

                $all_events_dates_query = $all_events_dates_query->whereBetween('reserve_date', [$start_date, $end_date]);
                
            }
            
        }
        
        return $all_events_dates_query->get();
            
    }
    
    public function get_all_usage_reports_collection($report_type, $all_dates)
    {
        $usage_collection = collect([]);

        if($report_type == 'attendance_usage'){
            
            foreach ($all_dates as $date) {

                $count_shs = AttendanceUser::where([
                    ['user_type', 1],
                    ['room_ref_no', 1]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_tertiary = AttendanceUser::where([
                    ['user_type', 2],
                    ['room_ref_no', 1]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_coaches = AttendanceUser::where([
                    ['user_type', 3],
                    ['room_ref_no', 1]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_shs_room_2 = AttendanceUser::where([
                    ['user_type', 1],
                    ['room_ref_no', 2]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_tertiary_room_2 = AttendanceUser::where([
                    ['user_type', 2],
                    ['room_ref_no', 2]
                ])
                ->whereDate('created_at', $date->date)
                ->count();
                
                $count_coaches_room_2 = AttendanceUser::where([
                    ['user_type', 3],
                    ['room_ref_no', 2]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_shs_room_3 = AttendanceUser::where([
                    ['user_type', 1],
                    ['room_ref_no', 3]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_tertiary_room_3 = AttendanceUser::where([
                    ['user_type', 2],
                    ['room_ref_no', 3]
                ])
                ->whereDate('created_at', $date->date)
                ->count();
                
                $count_coaches_room_3 = AttendanceUser::where([
                    ['user_type', 3],
                    ['room_ref_no', 3]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_shs_room_4 = AttendanceUser::where([
                    ['user_type', 1],
                    ['room_ref_no', 4]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_tertiary_room_4 = AttendanceUser::where([
                    ['user_type', 2],
                    ['room_ref_no', 4]
                ])
                ->whereDate('created_at', $date->date)
                ->count();
                
                $count_coaches_room_4 = AttendanceUser::where([
                    ['user_type', 3],
                    ['room_ref_no', 4]
                ])
                ->whereDate('created_at', $date->date)
                ->count();

                $count_total_room_1 = $count_shs + $count_tertiary + $count_coaches; 
                
                $count_total_room_2 = $count_shs_room_2 + $count_tertiary_room_2 + $count_coaches_room_2; 

                $count_total_room_3 = $count_shs_room_3 + $count_tertiary_room_3 + $count_coaches_room_3; 

                $count_total_room_4 = $count_shs_room_4 + $count_tertiary_room_4; 

                $count_total = $count_total_room_1 + $count_total_room_2 + $count_total_room_3 + $count_total_room_4; 

                $usage_collection->push([
                    'date' => $date->date, 
                    'count_shs' => $count_shs, 
                    'count_tertiary' => $count_tertiary, 
                    'count_coaches' => $count_coaches, 
                    'count_shs_room_2' => $count_shs_room_2, 
                    'count_tertiary_room_2' => $count_tertiary_room_2, 
                    'count_coaches_room_2' => $count_coaches_room_2, 
                    'count_shs_room_3' => $count_shs_room_3, 
                    'count_tertiary_room_3' => $count_tertiary_room_3, 
                    'count_coaches_room_3' => $count_coaches_room_3, 
                    'count_shs_room_4' => $count_shs_room_4, 
                    'count_tertiary_room_4' => $count_tertiary_room_4, 
                    'count_coaches_room_4' => $count_coaches_room_4, 
                    'count_total_room_1' => $count_total_room_1, 
                    'count_total_room_2' => $count_total_room_2, 
                    'count_total_room_3' => $count_total_room_3, 
                    'count_total_room_4' => $count_total_room_4, 
                    'count_total' => $count_total 
                ]);
                
            }

        }else if($report_type == 'egames_usage'){

            foreach ($all_dates as $date) {

                $count_shs = EgamesEvent::join('egames_reservations', 'egames_events.egames_reservation_id', 'egames_reservations.id')
                    ->join('students', 'egames_reservations.user_id', 'students.user_id')
                    ->join('programs', 'students.program_id', 'programs.id')
                    ->whereDate('reserve_date', $date->date)
                    ->where([
                        ['egames_events.status', 4],
                        ['programs.type', 0]
                    ])
                    ->count();
                
                $count_tertiary = EgamesEvent::join('egames_reservations', 'egames_events.egames_reservation_id', 'egames_reservations.id')
                    ->join('students', 'egames_reservations.user_id', 'students.user_id')
                    ->join('programs', 'students.program_id', 'programs.id')
                    ->whereDate('reserve_date', $date->date)
                    ->where([
                        ['egames_events.status', 4],
                        ['programs.type', 1]
                    ])
                    ->count();

                $count_total = $count_shs + $count_tertiary; 

                $usage_collection->push([
                    'date' => $date->date, 
                    'count_shs' => $count_shs, 
                    'count_tertiary' => $count_tertiary, 
                    'count_total' => $count_total 
                ]);
                
            }
            
        }if($report_type == 'rooms_usage'){

            foreach ($all_dates as $date) {

                $count_shs = RoomsEvent::join('rooms_reservations', 'rooms_events.rooms_reservation_id', 'rooms_reservations.id')
                    ->join('students', 'rooms_reservations.user_id', 'students.user_id')
                    ->join('programs', 'students.program_id', 'programs.id')
                    ->whereDate('reserve_date', $date->date)
                    ->where([
                        ['rooms_events.status', 4],
                        ['programs.type', 0]
                    ])
                    ->count();

                $count_tertiary = RoomsEvent::join('rooms_reservations', 'rooms_events.rooms_reservation_id', 'rooms_reservations.id')
                    ->join('students', 'rooms_reservations.user_id', 'students.user_id')
                    ->join('programs', 'students.program_id', 'programs.id')
                    ->whereDate('reserve_date', $date->date)
                    ->where([
                        ['rooms_events.status', 4],
                        ['programs.type', 1]
                    ])
                    ->count();
                
                $count_coach = RoomsEvent::join('rooms_reservations', 'rooms_events.rooms_reservation_id', 'rooms_reservations.id')
                    ->join('staff_coaches', 'rooms_reservations.user_id', 'staff_coaches.user_id')
                    ->whereDate('reserve_date', $date->date)
                    ->where([
                        ['rooms_events.status', 4],
                    ])
                    ->count();

                $count_total = $count_shs + $count_tertiary + $count_coach; 

                $usage_collection->push([
                    'date' => $date->date, 
                    'count_shs' => $count_shs, 
                    'count_tertiary' => $count_tertiary, 
                    'count_coach' => $count_coach, 
                    'count_total' => $count_total 
                ]);
                
            }
            
        }
        
        return $usage_collection;
        
    }

    public function get_all_data_usage_reports_collection_count($report_type, $usage_collection)
    {
        if($report_type == 'attendance_usage'){

            $count_shs = $usage_collection->sum('count_shs');
            $average_shs = round($usage_collection->avg('count_shs'), 2);
            
            $count_tertiary = $usage_collection->sum('count_tertiary');
            $average_tertiary = round($usage_collection->avg('count_tertiary'), 2);

            $count_coaches = $usage_collection->sum('count_coaches');
            $average_coaches = round($usage_collection->avg('count_coaches'), 2);

            
            $count_shs_room_2 = $usage_collection->sum('count_shs_room_2');
            $count_tertiary_room_2 = $usage_collection->sum('count_tertiary_room_2');
            $count_coaches_room_2 = $usage_collection->sum('count_coaches_room_2');
            
            $count_shs_room_3 = $usage_collection->sum('count_shs_room_3');
            $count_tertiary_room_3 = $usage_collection->sum('count_tertiary_room_3');
            $count_coaches_room_3 = $usage_collection->sum('count_coaches_room_3');
            
            $count_shs_room_4 = $usage_collection->sum('count_shs_room_4');
            $count_tertiary_room_4 = $usage_collection->sum('count_tertiary_room_4');
            
            $count_total_room_1 = $usage_collection->sum('count_total_room_1'); 
            $count_total_room_2 = $usage_collection->sum('count_total_room_2'); 
            $count_total_room_3 = $usage_collection->sum('count_total_room_3'); 
            $count_total_room_4 = $usage_collection->sum('count_total_room_4'); 
            
            $all = $usage_collection->sum('count_total');          

            $average_all = round($usage_collection->avg('count_total'), 2);            

            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'average_all' => [
                    'count' => $average_all,
                    'name' => 'Average Users',
                    'color' => 'text-secondary'
                ],
                'shs' => [
                    'count' => $count_shs,
                    'name' => 'SHS',
                    'color' => 'text-info'
                ], 
                'average_shs' => [
                    'count' => $average_shs,
                    'name' => 'Average SHS',
                    'color' => 'text-secondary'
                ], 
                'tertiary' => [
                    'count' => $count_tertiary,
                    'name' => 'Tertiary',
                    'color' => 'text-info'
                ], 
                'average_tertiary' => [
                    'count' => $average_tertiary,
                    'name' => 'Average Tertiary',
                    'color' => 'text-secondary'
                ], 
                'coaches' => [
                    'count' => $count_coaches,
                    'name' => 'Staff/Coach',
                    'color' => 'text-info'
                ], 
                'average_coaches' => [
                    'count' => $average_coaches,
                    'name' => 'Average Staff/Coach',
                    'color' => 'text-secondary'
                ], 
                'count_shs_room_2' => [
                    'count' => $count_shs_room_2,
                    'name' => 'SeniorHigh Cozy Room',
                    'color' => 'text-info'
                ], 
                'count_tertiary_room_2' => [
                    'count' => $count_tertiary_room_2,
                    'name' => 'Tertiary Cozy Room',
                    'color' => 'text-info'
                ], 
                'count_coaches_room_2' => [
                    'count' => $count_coaches_room_2,
                    'name' => 'Staff/Coaches Cozy Room',
                    'color' => 'text-info'
                ], 
                'count_shs_room_3' => [
                    'count' => $count_shs_room_3,
                    'name' => 'SeniorHigh Reading Area',
                    'color' => 'text-info'
                ], 
                'count_tertiary_room_3' => [
                    'count' => $count_tertiary_room_3,
                    'name' => 'Tertiary Reading Area',
                    'color' => 'text-info'
                ], 
                'count_coaches_room_3' => [
                    'count' => $count_coaches_room_3,
                    'name' => 'Staff/Coaches Reading Area',
                    'color' => 'text-info'
                ], 
                'count_shs_room_4' => [
                    'count' => $count_shs_room_4,
                    'name' => 'SeniorHigh E-games/Research Room',
                    'color' => 'text-info'
                ], 
                'count_tertiary_room_4' => [
                    'count' => $count_tertiary_room_4,
                    'name' => 'Tertiary E-games/Research Room',
                    'color' => 'text-info'
                ], 
                'count_total_room_1' => [
                    'count' => $count_total_room_1,
                    'name' => 'Attendance Hall',
                    'color' => 'text-primary'
                ], 
                'count_total_room_2' => [
                    'count' => $count_total_room_2,
                    'name' => 'Cozy Room',
                    'color' => 'text-primary'
                ], 
                'count_total_room_3' => [
                    'count' => $count_total_room_3,
                    'name' => 'Reading Area',
                    'color' => 'text-primary'
                ], 
                'count_total_room_4' => [
                    'count' => $count_total_room_4,
                    'name' => 'E-games/Research Room',
                    'color' => 'text-primary'
                ]
            ];
            
        }else if($report_type == 'egames_usage'){

            $count_shs = $usage_collection->sum('count_shs');

            $average_shs = round($usage_collection->avg('count_shs'), 2);
            
            $count_tertiary = $usage_collection->sum('count_tertiary');

            $average_tertiary = round($usage_collection->avg('count_tertiary'), 2);

            $all = $usage_collection->sum('count_total');            

            $average_all = round($usage_collection->avg('count_total'), 2);            

            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'average_all' => [
                    'count' => $average_all,
                    'name' => 'Average Users',
                    'color' => 'text-secondary'
                ],
                'shs' => [
                    'count' => $count_shs,
                    'name' => 'SHS',
                    'color' => 'main_green_blue_bg'
                ], 
                'average_shs' => [
                    'count' => $average_shs,
                    'name' => 'Average SHS',
                    'color' => 'text-secondary'
                ], 
                'tertiary' => [
                    'count' => $count_tertiary,
                    'name' => 'Tertiary',
                    'color' => 'main_sky_blue_bg'
                ], 
                'average_tertiary' => [
                    'count' => $average_tertiary,
                    'name' => 'Average Tertiary',
                    'color' => 'text-secondary'
                ] 
            ];

        }else if($report_type == 'rooms_usage'){

            $count_shs = $usage_collection->sum('count_shs');

            $average_shs = round($usage_collection->avg('count_shs'), 2);
            
            $count_tertiary = $usage_collection->sum('count_tertiary');

            $average_tertiary = round($usage_collection->avg('count_tertiary'), 2);

            $count_coach = $usage_collection->sum('count_coach');

            $average_coach = round($usage_collection->avg('count_coach'), 2);

            $all = $usage_collection->sum('count_total');            

            $average_all = round($usage_collection->avg('count_total'), 2);            

            $all_count_data = [
                'all' => [
                    'count' => $all,
                    'name' => 'All',
                    'color' => 'text-primary'
                ],
                'average_all' => [
                    'count' => $average_all,
                    'name' => 'Users Average',
                    'color' => 'text-secondary'
                ],
                'shs' => [
                    'count' => $count_shs,
                    'name' => 'SHS',
                    'color' => 'main_green_blue_bg'
                ], 
                'average_shs' => [
                    'count' => $average_shs,
                    'name' => 'Average SHS',
                    'color' => 'text-secondary'
                ], 
                'tertiary' => [
                    'count' => $count_tertiary,
                    'name' => 'Tertiary',
                    'color' => 'main_sky_blue_bg'
                ], 
                'average_tertiary' => [
                    'count' => $average_tertiary,
                    'name' => 'Average Tertiary',
                    'color' => 'text-secondary'
                ], 
                'staff_coach' => [
                    'count' => $count_coach,
                    'name' => 'Staff/Coach',
                    'color' => 'main_green_blue_bg'
                ],
                'average_coach' => [
                    'count' => $average_coach,
                    'name' => 'Average Staff/Coach',
                    'color' => 'text-secondary'
                ] 
            ];

        }
        
        return $all_count_data;
        
    }
    
    public function get_usage_reports_info_data($report_type)
    {
        if($report_type == 'attendance_usage'){

            $title = 'Attendance Usage';
            
            $sidebar_nav_lev_2 = 'attendance_reports_ul';
            
            $all_table_columns = ['Attendance Hall', 'Cozy Room', 'Reading Area', 'E-games/Research Room']; 
            
            $reports_info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_table_columns' => $all_table_columns,
            ];
            
        }else if($report_type == 'egames_usage'){

            $title = 'E-Games Usage';
            
            $sidebar_nav_lev_2 = 'egames_reports_ul';
            
            $all_table_columns = ['Date', 'SHS', 'Tertiary', 'Total']; 
            
            $reports_info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_table_columns' => $all_table_columns,
            ];

        }else if($report_type == 'rooms_usage'){
            
            $title = 'Rooms Usage';
            
            $sidebar_nav_lev_2 = 'rooms_reports_ul';
            
            $all_table_columns = ['Date', 'SHS', 'Tertiary', 'Coach/Staff','Total']; 
            
            $reports_info_data = [
                'title' => $title,
                'sidebar_nav_lev_2' => $sidebar_nav_lev_2,
                'all_table_columns' => $all_table_columns,
            ];

        }
        
        return $reports_info_data;
        
    }
    

}


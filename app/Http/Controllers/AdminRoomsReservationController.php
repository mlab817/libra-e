<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; 
use App\Student; 
use App\StaffCoach; 

use App\RoomsReservation; 
use App\RoomsEvent; 
use App\EgamesSetting; 


class AdminRoomsReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    // Rooms Reserve Now
    
    public function reserve_now()
    {
        $reservations = $this->get_date_reservation(null);
        
        $all_purpose = $this->get_all_purpose();
        
        $time_schedules = $this->create_schedules('12a');
        
        $search_date = date("Y-m-d");

        $students = $this->get_all_students();

        $coaches = $this->get_all_coaches();

        return view('admin.rooms.reservations.reserve_now')->with('reservations', $reservations)
            ->with('all_purpose', $all_purpose)
            ->with('time_schedules', $time_schedules)
            ->with('search_date', $search_date)    
            ->with('students', $students)
            ->with('coaches', $coaches);    

    }

    public function get_all_purpose()
    {
        $all_purpose = [
            'class_use' => 'Class Use', 
            'photo_video_shoot' => "Photo/Video shoot",
            'personalize_asst' => "Personalized Assistance",
            'meeting' => "Meeting",
            'research' => "Research",
            'lib_orient' => "Library Orientation",
            'lib_facility' => "Library Facility Use",
            'audio_visual' => "Audio-visual Materials",
            'document_delivery' => "Document Delivery"
          ];

        return $all_purpose;

    }

    public function create_schedules($format)
    {
        $rooms_settings = EgamesSetting::find(1);
        
        if($rooms_settings->exists()){

            $startTime = $rooms_settings->start_time;
            $endTime = $rooms_settings->end_time;

            $minutes_per_session = 30;
            
            $all_sched = [];
    
            $first = true;
            $continue = true;
    
            while ($continue) {
    
                if($first){
                    
                    $sched = strtotime("+".$minutes_per_session." minutes", strtotime($startTime));
                    
                    $sched = date('H:i:s', $sched);
    
                    $start_time = $startTime;
    
                    $end_time = $sched;
    
                    
                }else{
                    
                    $start_time = $sched;
    
                    $sched = strtotime("+".$minutes_per_session." minutes", strtotime($sched));
                    
                    $sched = date('H:i:s', $sched);
    
                    $end_time = $sched;
                    
                }
    
                $first = false;
    
                $end_hour = substr($endTime,0,2);
                $sched_hour = substr($end_time,0,2);
    
                if($end_hour <= $sched_hour){
    
                    $continue = false;
    
                    $end_time = $endTime;
                    
                }

                if($format == '12'){
                    
                    $start_time = date('h:i:s', strtotime($start_time));
                    $end_time = date('h:i:s', strtotime($end_time));
                    
                }else if($format == '12a'){
                    
                    $start_time = date('h:i:s a', strtotime($start_time));
                    $end_time = date('h:i:s a', strtotime($end_time));

                }
    
                $time_start_end = [
                    'start_time' => $start_time,
                    'end_time' => $end_time
                ];
                
                array_push($all_sched, $time_start_end);        
                
            }
    
            return $all_sched;

        }
    }

    public function get_all_students()
    {
        $all_enrolled_students = Student::join('programs', 'students.program_id', 'programs.id')
            ->select(
                'students.*',
                'programs.code'
            )
            ->where('students.status', 1)->orderBy('l_name', 'asc')->get();

        return $all_enrolled_students;
    }

    public function get_all_coaches()
    {
        $all_employed_coaches = StaffCoach::join('departments', 'staff_coaches.department_id', 'departments.id')
            ->select(
                'staff_coaches.*',
                'departments.name'
            )
            ->where('staff_coaches.status', 1)->orderBy('l_name', 'asc')->get();

        return $all_employed_coaches;
    }

    public function search_date($search_date = null)
    {
        if($search_date != null){
            
            $reservations = $this->get_date_reservation($search_date);

            $all_purpose = $this->get_all_purpose();

            $time_schedules = $this->create_schedules('12a');

            $check_date = $this->check_date($search_date);

            $students = $this->get_all_students();

            $coaches = $this->get_all_coaches();

            if($check_date != 'ok'){

                session()->flash('error_status', $check_date);
                
            }
            
            return view('admin.rooms.reservations.reserve_now')->with('reservations', $reservations)
                ->with('time_schedules', $time_schedules)
                ->with('all_purpose', $all_purpose)
                ->with('search_date', $search_date)
                ->with('students', $students)
                ->with('coaches', $coaches);    

        }else{
            
            return redirect()->route('admin.rooms.reservation.reserve_now');
            
        }
    }

    public function check_date($reserve_date)
    {
        $given_date = strtotime($reserve_date);

        if(date('D', $given_date) == 'Sat' || date('D', $given_date) == 'Sun') { 
            
            return 'No Reservations on Weekends!';
            
        }else{

            return 'ok';
            
        }
    }
    
    // Reserve

    public function reserve(Request $request)
    {
        $request->validate([
            'reserve_date' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'topic_description' => 'required|string',
            'no_users' => 'required|numeric',
            'user_radio' => 'required|numeric',
        ]);
        
        if($request->user_radio == 1){
            
            $request->validate([
                'select_student' => 'required'
            ]);
            
            $user_id = $request->select_student;

        }else if($request->user_radio == 2){
            
            $request->validate([
                'select_coach' => 'required'
            ]);
            
            $user_id = $request->select_coach;
            
        }

        $user_requests_too_many = $this->check_if_user_has_too_many_request($request->reserve_date, $user_id);
        
        if($user_requests_too_many != 'ok'){
            
            session()->flash('error_status', $user_requests_too_many);
            
            return redirect()->route('admin.rooms.reservation.reserve_now.search_date', $request->reserve_date);

        }

        if($request->others == 'Others'){
            
            $request->validate([
                'others_text' => 'required|string',
            ]);
            
        }
        
        $all_purpose_data = $this->get_all_purpose_data($request);

        if(count($all_purpose_data) == 0){
        
            session()->flash('error_status', 'Please indicate purpose of reservation!');
            
            return redirect()->route('admin.rooms.reservation.reserve_now.search_date', $request->reserve_date);
            
        }else{

            $all_purpose_data_json = json_encode($all_purpose_data);
            
            $check_date = $this->check_date($request->reserve_date);

            if($check_date != 'ok'){

                session()->flash('error_status', $check_date);
                
                return redirect()->route('admin.rooms.reservation.reserve_now.search_date', $request->reserve_date);
                
            }else{

                $select_time = date('H:i:s', strtotime($request->select_time));
                
                $check_reservation = $this->check_reservation($request->reserve_date, $request->start_time, $request->end_time);
                
                if($check_reservation != 'ok'){
                    
                    session()->flash('error_status', $check_reservation);

                    return redirect()->route('admin.rooms.reservation.reserve_now.search_date', $request->reserve_date);

                }else{

                    $this->add_reservation($request, $all_purpose_data_json, $user_id);

                    session()->flash('success_status', 'Reserved!');

                    return redirect()->route('admin.rooms.reservation.rooms_reservation');

                }
            }
            
        }
    }

    public function check_if_user_has_too_many_request($reserve_date, $user_id)
    {
        $rooms_query = RoomsReservation::where('user_id', $user_id)
            ->whereIn('status', [1, 2]);

        $count_request = $rooms_query->count();

        $count_reserve_date = $rooms_query->where('reserve_date', $reserve_date)->count();

        if($count_request >= 3){
            
            return 'You already had too many requests! Please allow others to use the facilities as well.';
            
        }else if($count_reserve_date >= 1){
            
            return 'You already have a request for reservation in this date! Please allow others to use the facilities as well.';
            
        }else{

            return 'ok';
            
        }
    }
    

    public function get_all_purpose_data($request)
    {
        $all_purpose = $this->get_all_purpose();

        $all_purpose_data = [];

        if($request->class_use == $all_purpose['class_use']){
            
            array_push($all_purpose_data, $request->class_use);
            
        }

        if($request->photo_video_shoot == $all_purpose['photo_video_shoot']){
            
            array_push($all_purpose_data, $request->photo_video_shoot);
            
        }

        if($request->personalize_asst == $all_purpose['personalize_asst']){
            
            array_push($all_purpose_data, $request->personalize_asst);
            
        }

        if($request->meeting == $all_purpose['meeting']){
            
            array_push($all_purpose_data, $request->meeting);
            
        }

        if($request->research == $all_purpose['research']){
            
            array_push($all_purpose_data, $request->research);
            
        }

        if($request->lib_orient == $all_purpose['lib_orient']){
            
            array_push($all_purpose_data, $request->lib_orient);
            
        }

        if($request->lib_facility == $all_purpose['lib_facility']){
            
            array_push($all_purpose_data, $request->lib_facility);
            
        }

        if($request->audio_visual == $all_purpose['audio_visual']){
            
            array_push($all_purpose_data, $request->audio_visual);
            
        }

        if($request->document_delivery == $all_purpose['document_delivery']){
            
            array_push($all_purpose_data, $request->document_delivery);
            
        }

        if($request->others == 'Others'){
            
            array_push($all_purpose_data, $request->others_text);
            
        }
        
        return $all_purpose_data;

    }

    public function check_reservation($reserve_date, $start_time, $end_time)
    {
        $start_time = date('H:i:s', strtotime($start_time));
        $end_time = date('H:i:s', strtotime($end_time));
        
        if($start_time == $end_time){
            
            return 'Invalid Time Slot!';
            
        }else if($end_time < $start_time ){
            
            return 'Invalid Time Slot!';
            
        }

        $reservations = RoomsReservation::whereDate('reserve_date', $reserve_date)
            ->WhereIn('status', [2,3,4])
            ->get();

        $check_reservation = false;

        foreach ($reservations as $reservation) {

            if($reservation->reserve_time_start <= $start_time && $reservation->reserve_time_end > $start_time){
                
                $check_reservation = true;
                
            }else if($reservation->reserve_time_start < $end_time && $reservation->reserve_time_end >= $end_time){
                
                $check_reservation = true;
                
            }else if($start_time <= $reservation->reserve_time_start && $reservation->reserve_time_end <= $end_time){
                
                $check_reservation = true;
                
            }
            
        }

        $current_date = date('Y-m-d H:i:s'); 

        $reserve_date_time = $reserve_date . ' ' . $start_time;

        $reserve_date_time = date('Y-m-d H:i:s', strtotime($reserve_date_time));

        if($check_reservation){

            return 'Slot Already Reserved!';
            
        }else if($reserve_date_time < $current_date){
            
            return 'Slot Already Elapsed!';
            
        }else{

            return 'ok';
            
        }
    }

    public function add_reservation($request, $all_purpose_data_json, $user_id)
    {
        $rooms_reservation = new RoomsReservation;
        
        $rooms_reservation->purpose = $all_purpose_data_json;
        
        $rooms_reservation->topic_description = $request->topic_description;
        
        $rooms_reservation->user_id = $user_id;

        $rooms_reservation->reserve_date = $request->reserve_date;

        $start_time = date('H:i:s', strtotime($request->start_time));
        
        $rooms_reservation->reserve_time_start = $start_time;
        
        $end_time = date('H:i:s', strtotime($request->end_time));

        $rooms_reservation->reserve_time_end = $end_time;

        $rooms_reservation->no_users = $request->no_users;

        $rooms_reservation->status = 1;

        $rooms_reservation->save();

        $this->add_reservation_event($rooms_reservation->id, null, 1);
        
    }


    
    // Rooms Reservations by status

    public function rooms_reservation($status_type)
    {
        $all_types = $this->get_all_types($status_type);
        
        if(in_array($status_type, $all_types)){
            
            $status_array_data = $this->check_status_type($status_type);
            
        }else{
            
            $status_type = 'all_transactions';

            $status_array_data = [
                'status' => 'all',
                'get_all' => true,
                'get_all_count' => 'all_transactions',
                'title' => 'All Transactions',
            ];

        }
        
        $reservations = $this->get_admin_rooms_reservations($status_array_data['status'], $status_type, $status_array_data['get_all']);
        
        $get_all_count = $this->get_all_count($status_array_data['get_all_count']);

        $all_users = $this->get_all_users('all', 0);

        return view('admin.rooms.reservations.rooms_reservations')->with('reservations' ,$reservations)
            ->with('count', $get_all_count)
            ->with('title', $status_array_data['title'])
            ->with('status_type', $status_type)
            ->with('all_users', $all_users);

            
    }

    public function get_all_types()
    {
        $all_types = ['pending', 'reserved', 'on_use', 'finished', 'denied', 'cancelled', 'all_transaction', 'all_events', 'pc_slots'];
        
        return $all_types;

    }

    public function check_status_type($status_type)
    {
        switch ($status_type) {
            case 'pending':
                $status = 1;
                $get_all = false;
                $get_all_count = 1;
                $title = 'Pending';
                break;

            case 'reserved':
                $status = 2;
                $get_all = false;
                $get_all_count = 2;
                $title = 'Reserved';
                break;

            case 'on_use':
                $status = 3;
                $get_all = false;
                $get_all_count = 3;
                $title = 'On Use';
                break;

            case 'finished':
                $status = 4;
                $get_all = false;
                $get_all_count = 4;
                $title = 'Finished';
                break;

            case 'denied':
                $status = 8;
                $get_all = false;
                $get_all_count = 8;
                $title = 'Denied';
                break;

            case 'cancelled':
                $status = 9;
                $get_all = false;
                $get_all_count = 9;
                $title = 'Cancelled';
                break;

            case 'overdue':
                $status = 10;
                $get_all = false;
                $get_all_count = 10;
                $title = 'Overdue';
                break;

            case 'all_transactions':
                $status = 'all';
                $get_all = true;
                $get_all_count = 'all_transactions';
                $title = 'All Transactions';
            break;
            
            case 'all_events':
                $status = 'all_events';
                $get_all = true;
                $get_all_count = 'all_events';
                $title = 'All Events';
                break;
            
            default:
                $status = 'all';
                $get_all = true;
                $get_all_count = 'all_transactions';
                $title = 'All Transactions';
                break;
        }
        
        $status_array_data = [
            'status' => $status,
            'get_all' => $get_all,
            'get_all_count' => $get_all_count,
            'title' => $title 
        ];
        
        return $status_array_data;
                
    }
    
    public function get_all_count($type)
    {

        if($type == 'all_transactions' || $type == 'all_events'){
            
            if($type == 'all_transactions'){
            
                $pending = RoomsReservation::where('status', 1)->count();
    
                $reserved = RoomsReservation::where('status', 2)->count();
    
                $on_use = RoomsReservation::where('status', 3)->count();
    
                $finished = RoomsReservation::where('status', 4)->count();
    
                $denied = RoomsReservation::where('status', 8)->count();
    
                $cancelled = RoomsReservation::where('status', 9)->count();
    
                $all = RoomsReservation::count();
                
    
            }else if($type == 'all_events'){
                
                $pending = RoomsEvent::where('status', 1)->count();
    
                $reserved = RoomsEvent::where('status', 2)->count();
    
                $on_use = RoomsEvent::where('status', 3)->count();
    
                $finished = RoomsEvent::where('status', 4)->count();
    
                $denied = RoomsEvent::where('status', 8)->count();
    
                $cancelled = RoomsEvent::where('status', 9)->count();
    
                $all = RoomsEvent::count();
    
            }
            
            $all_count = [
                'pending' => $pending, 
                'reserved' => $reserved, 
                'on_use' => $on_use, 
                'finished' => $finished, 
                'denied' => $denied, 
                'cancelled' => $cancelled, 
                'all' => $all 
            ];
    
            return $all_count;
            

        }else{
            
            if($type == 'all'){
            
                $count = RoomsReservation::count();
    
            }else if($type == 'all_events'){
    
                $count = RoomsEvent::count();
                
            }else{
                
                $count = RoomsReservation::where('status', $type)->count();
                
            }
    
            return $count; 

        }

    }

    public function get_all_users($get, $id)
    {
        if($get == 'all'){
            
            $users = User::all();

            $students = Student::all();
    
            $staff_coach = StaffCoach::all();
            
            $all_users = [
                'users' => $users,
                'students' => $students,
                'staff_coach' => $staff_coach,
            ];
            
            return $all_users;

        }else if ($get == 'single'){
            
            $reservation = RoomsReservation::where('id', $id)->first();
            
            $user_id = $reservation['user_id'];

            $user_ref = User::where('id', $user_id)->first();

            $user_type = $user_ref->user_type;
            
            if($user_type == 1){
                
                $user = Student::where('id', $user_ref->user_ref_id)->first();

            }else if($user_type == 2){
                
                $user = StaffCoach::where('id', $user_ref->user_ref_id)->first();

            }

            $user_data = [
                'user_type' => $user_type,
                'user' => $user
            ];

            return $user_data;

        }
    }
    
    public function check_session_queries($type)
    {
        $session_name_order = $type . '_rooms_toOrder'; 
            
        $session_name_orderBy = $type . '_rooms_orderBy'; 

        $session_name_per_page = $type . '_rooms_per_page'; 
        
        if(session()->has($session_name_order) != true){

            session([$session_name_order => 'updated_at' ]);

        }

        if(session()->has($session_name_orderBy) != true){

            session([$session_name_orderBy => 'desc' ]);
            
        }

        if (session()->has($session_name_per_page) != true) {

            session([$session_name_per_page => 10 ]);
            
        }
    }

    public function get_admin_rooms_reservations($status, $status_type, $get_all)
    {
        $this->check_session_queries($status_type);
        
        $admin_rooms_reservations_query = $this->get_admin_rooms_reservations_query($status, $get_all);
        
        $all_user_admin_rooms_reservations = $this->add_order_queries($admin_rooms_reservations_query, $get_all, $status_type);
            
        return $all_user_admin_rooms_reservations;
        
    }

    public function get_admin_rooms_reservations_query($status, $get_all)
    {
        $this->check_reservations();

        if($status == 'all_events'){
            
            $admin_rooms_reservations_query = RoomsEvent::join('rooms_reservations', 'rooms_events.rooms_reservation_id', 'rooms_reservations.id')
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

        }else{

            $admin_rooms_reservations_query = RoomsReservation::select('rooms_reservations.*');

        }
        
            
        if($get_all == false){

            $admin_rooms_reservations_query->where('rooms_reservations.status', $status);

        }

        return $admin_rooms_reservations_query;
            
    }

    public function add_order_queries($query, $get_all, $type)
    {
        $session_get_all_status = $type . '_rooms_getAll_status';
        
        $session_toOrder = $type . '_rooms_toOrder';

        $session_orderBy = $type . '_rooms_orderBy';

        $session_per_page = $type . '_rooms_per_page';
        
        if($get_all){
            
            if(session()->has($session_get_all_status)){
    
                if(session()->get($session_get_all_status) != 'all'){
                    
                    if($type == 'all_reservations'){
                    
                        $query = $query->where('rooms_reservations.status', session()->get($session_get_all_status));
                        
                    }else if($type == 'all_events'){
                        
                        $$query = $query->where('rooms_events.status', session()->get($session_get_all_status));

                    }
    
                }else{
                        
                    session([$session_get_all_status => 'all' ]);
                }
                
            }else{
    
                session([$session_get_all_status => 'all' ]);
                
            }
        }
        
        $all_query = $query->orderBy(session()->get($session_toOrder), session()->get($session_orderBy))
            ->paginate(session()->get($session_per_page));

        if($all_query->count() > 0){
            
            return $all_query;
            
        }else{

            session()->flash('error_status', 'No Reservations Yet!');
            
            return $all_query;

        }
    }


    // Rooms Dynamic toOrder, orderBy, filter by status
    
    public function rooms_per_page($type = 'all_transactions', $per_page = 10) 
    {
        $all_types = $this->get_all_types();

        if(in_array($type, $all_types)){
            
            $session_per_page = $type . '_rooms_per_page';
            
        }else{
            
            $session_per_page = 'all_transactions_rooms_per_page';
            
        }

        $all_per_page = [5, 10, 20, 50, 100, 200, 500];
        
        if(in_array($per_page, $all_per_page)){
            
            session([$session_per_page => $per_page]);
            
        }else{
            
            session([$session_per_page => 10]);

        }
        
        if(in_array($type, $all_types)){

            return redirect()->route('admin.rooms.reservation.rooms_reservation', $type);
            
        }else{
            
            return redirect()->route('admin.rooms.reservation.rooms_reservation', 'all_transactions');

        }
    }

    public function rooms_toOrder($type = 'all_transactions', $toOrder = 'updated_at') 
    {
        $all_types = $this->get_all_types();
        
        if(in_array($type, $all_types)){
            
            $session_toOrder = $type . '_rooms_toOrder';

        }else{
            
            $session_toOrder = 'all_transactions_rooms_toOrder';
            
        }

        $all_toOrder = ['topic_description', 'purpose', 'reserve_date','reserve_time_start', 'updated_at'];
        
        if(in_array($toOrder, $all_toOrder)){
            
            session([$session_toOrder => $toOrder]);
            
        }else{
            
            session([$session_toOrder => 'updated_at']);
            
        }

        if(in_array($type, $all_types)){

            return redirect()->route('admin.rooms.reservation.rooms_reservation', $type);
            
        }else{
            
             return redirect()->route('admin.rooms.reservation.rooms_reservation', 'all_transactions');

        }

    }

    public function rooms_orderBy($type = 'all_transactions', $orderBy = 'desc') 
    {
        $all_types = $this->get_all_types();
        
        if(in_array($type, $all_types)){
            
            $session_orderBy = $type . '_rooms_orderBy';
            
        }else{
            
            $session_orderBy = 'all_transactions_rooms_orderBy';
            
        }
        
        if($orderBy == 'asc' || $orderBy == 'desc' ){
            
            session([$session_orderBy => $orderBy]);
            
        }else{
            
            session([$session_orderBy => 'desc']);

        }

        if(in_array($type, $all_types)){

            return redirect()->route('admin.rooms.reservation.rooms_reservation', $type);
            
        }else{
            
             return redirect()->route('admin.rooms.reservation.rooms_reservation', 'all_transactions');

        }
    }

    public function filter_status_rooms($type = 'all_transactions', $filter = 'all') 
    {
        $all_types = $this->get_all_types();
        
        if(in_array($type, $all_types)){
            
            $session_getAll_status = $type . '_rooms_getAll_status';
            
        }else{
            
            $session_getAll_status = 'all_transactions_rooms_getAll_status';

        }

        $all_filter = ['all', 1, 2, 3, 4, 8, 9];
        
        if(in_array($filter, all_filter)){
            
            session([$session_getAll_status => $filter ]);
            
        }else{
            
            session([$session_getAll_status => 'all' ]);

        }

        if(in_array($type, $all_types)){

            return redirect()->route('admin.rooms.reservation.rooms_reservation', $type);
            
        }else{
            
            return redirect()->route('admin.rooms.reservation.rooms_reservation', 'all_transactions');

        }
    }
    
    // Search By status

    public function search_all_rooms_reservations($status_type, $search = '')
    {
        $all_types = $this->get_all_types($status_type);
        
        if(in_array($status_type, $all_types)){

            if($status_type != 'all_events'){

                $status_type = 'all_transactions';
    
            }

            $status_array_data = $this->check_status_type($status_type);
            
        }else{
            
            $status_type = 'all_transactions';

            $status_array_data = [
                'status' => 'all',
                'get_all' => true,
                'get_all_count' => 'all_transactions',
                'title' => 'All Transactions',
            ];

        }
        
        $reservations = $this->search_rooms_reservations($search, $status_array_data['status'], $status_array_data['get_all'], $status_type);
        
        $get_all_count = $this->get_all_count($status_array_data['get_all_count']);

        $all_users = $this->get_all_users('all', 0);

        return view('admin.rooms.reservations.rooms_reservations')->with('reservations' ,$reservations)
            ->with('count', $get_all_count)
            ->with('title', $status_array_data['title'])
            ->with('status_type', $status_type)
            ->with('all_users', $all_users);

    }

    // Search queries

    public function search_rooms_reservations($search, $type, $get_all, $status_type)
    {
        $this->check_session_queries($status_type);
        
        $rooms_reservations_query = $this->get_admin_rooms_reservations_query($type, $get_all);
        
        $rooms_reservations_query = $this->get_search_query($rooms_reservations_query, $search);
        
        $count = $rooms_reservations_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);

        if($type == 'all'){

            session([$status_type . '_rooms_reservations_getAll' => 'all' ]);

        }

        $all_user_rooms_reservations = $this->add_order_queries($rooms_reservations_query, $get_all, $status_type);
        
        $reservations = $all_user_rooms_reservations;
        
        if($count <= 0){

            session()->flash('error_status', 'No Reservation/s found!');
            
        }

        return $reservations;

    }
    
    public function get_search_query($rooms_reservations_query, $search)
    {
        $all_users_id = $this->search_users($search);

        $rooms_reservations_query->where('rooms_reservations.purpose', 'like', '%'.$search.'%')
            ->orWhere('rooms_reservations.topic_description', 'like', '%'.$search.'%')
            ->orWhereIn('rooms_reservations.user_id', $all_users_id['student_user_ids'])
            ->orWhereIn('rooms_reservations.user_id', $all_users_id['staff_coach_ids'])
            ->orWhere('rooms_reservations.reserve_date', 'like', '%'.$search.'%')
            ->orWhere('rooms_reservations.reserve_time_start', 'like', '%'.$search.'%')
            ->orWhere('rooms_reservations.reserve_time_end', 'like', '%'.$search.'%')
            ->orWhere('rooms_reservations.no_users', 'like', '%'.$search.'%');
        
        return $rooms_reservations_query;

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

    // View Single Reservation

    public function view_reservation($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $reservation = $this->get_reservation($id);
            
            if($reservation->count() == 1){
                
                $reservation_data = $this->get_reservation_dynamic_data($reservation);
                
                $reservation = $reservation->first();

                $reservations = $this->get_date_reservation($reservation['reserve_date']);

                $user_data = $this->get_all_users('single', $id);

                return view('admin.rooms.reservations.view_reservation')->with('reservation', $reservation)
                    ->with('reservations', $reservations)
                    ->with('reservation_data', $reservation_data)
                    ->with('user_data', $user_data);

            }else{
                
                return redirect()->route('admin.rooms.reservation.rooms_reservation', 'all_transactions');
                
            }
            
        }else{
            
            return redirect()->route('admin.rooms.reservation.rooms_reservation', 'all_transactions');

        }
    }

    public function get_reservation($id)
    {
        $reservation_query = $this->get_admin_rooms_reservations_query('all_transactions', true);

        $reservation = $reservation_query->where('rooms_reservations.id', $id);

        return $reservation;

    }

    public function get_reservation_dynamic_data($reservation)
    {
        $get_reservation = $reservation->first();

        $status = $get_reservation['status'];
        
        if($status == 1){
    
            $point_arrow = 'pending';
            $color_class = 'text-warning';
            $url_back = route('admin.rooms.reservation.rooms_reservation') . '/pending';
            $form_url = route('admin.rooms.reservation.approve_reservation');
            
          }else if($status == 2){
            
            $point_arrow = 'reserved';
            $color_class = 'text-primary';
            $url_back = route('admin.rooms.reservation.rooms_reservation') . '/reserved';
            $form_url = route('admin.rooms.reservation.start_reservation');
      
          }else if($status == 3){
            
            $point_arrow = 'on_use';
            $color_class = 'text-success';
            $url_back = route('admin.rooms.reservation.rooms_reservation') . '/on_use';
            $form_url = route('admin.rooms.reservation.finish_reservation');
            
          }else if($status == 4){
            
            $point_arrow = 'finished';
            $color_class = 'text-success';
            $url_back = route('admin.rooms.reservation.rooms_reservation') . '/finished';
            $form_url = route('admin.rooms.reservation.finish_reservation');
            
          }else if($status == 8){
      
            $point_arrow = 'denied';
            $color_class = 'text-danger';
            $url_back = route('admin.rooms.reservation.rooms_reservation') . '/denied';
            $form_url = route('admin.rooms.reservation.finish_reservation');
            
          }else if($status == 9){
            
            $point_arrow = 'cancelled';
            $color_class = 'text-danger';
            $url_back = route('admin.rooms.reservation.rooms_reservation') . '/cancelled';
            $form_url = route('admin.rooms.reservation.finish_reservation');
            
          }

          $reservation_data = [
              'point_arrow' => $point_arrow,
              'color_class' => $color_class,
              'url_back' => $url_back,
              'form_url' => $form_url
          ];
          
          return $reservation_data;
        
    }

    public function get_date_reservation($search_date)
    {
        if($search_date == null){
            
            $search_date = date('Y-m-d'); 
            
        }
        
        $reservations = RoomsReservation::WhereIn('rooms_reservations.status', [2,3,4])
            ->where('reserve_date', $search_date);

        if($reservations->count() > 0){
            
            return $reservations->get();

        }else{

            return 'No Reservation yet!';
            
        }
    } 

    // Checking Reservations 

    public function check_reservations()
    {
        $current_date_time = date('Y-m-d H:i:s'); 
        
        $approved_reservations = RoomsReservation::whereIn('status', [1, 2])->get();
        
        if($approved_reservations->count() > 0){
            
            foreach ($approved_reservations as $approved_reservation) {
                
                $date = $approved_reservation->reserve_date;

                $time = $approved_reservation->reserve_time_end;

                $date = date('Y-m-d', strtotime($date));
        
                $combi = $date . ' ' . $time; 

                $combi = date('Y-m-d H:i:s', strtotime($combi));
                
                if($combi <= $current_date_time){

                    $update = RoomsReservation::findOrFail($approved_reservation->id);

                    $update->notes = 'Denied!';
                
                    $update->status = 8;

                    $update->save();
                    
                    $this->add_reservation_event($update->id, 'Denied!', 8);
                    
                }
            }
        }
    }

    public function add_reservation_event($rooms_reservation_id, $notes, $status)
    {
        $rooms_event = new RoomsEvent;
        
        $rooms_event->rooms_reservation_id = $rooms_reservation_id;

        $rooms_event->notes = $notes;

        $rooms_event->status = $status;

        $rooms_event->save();

    }

    // Handle Reservations

    public function approve_reservation(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|numeric',
        ]);

        $reservation = $this->get_reservation($request->reservation_id);
        
        $date_available = $this->check_if_date_available($reservation);

        if($date_available == 'ok'){
            
            $this->change_reservation_status($request->reservation_id, 'reserved');

            session()->flash('success_status', 'Request Approved!');
            return redirect()->route('admin.rooms.reservation.rooms_reservation', 'reserved');

        }else{

            session()->flash('error_status', $date_available);
            return redirect()->route('admin.rooms.reservation.view_reservation', $request->reservation_id);
            
        }
    }

    public function check_if_date_available($reservation)
    {
        $reservation = $reservation->first();

        $start_time = $reservation['reserve_time_start'];

        $end_time = $reservation['reserve_time_start'];

        $reserve_date = $reservation['reserve_date'];
        
        $check_reservation = RoomsReservation::whereDate('reserve_date', $reserve_date)
            ->whereBetween('reserve_time_start', [$start_time, $end_time])
            ->whereBetween('reserve_time_end', [$start_time, $end_time])
            ->WhereIn('status', [2,3,4])
            ->exists();

        $current_date = date('Y-m-d H:i:s'); 

        $reserve_date = date('Y-m-d', strtotime($reserve_date)); 

        $reserve_date_time = $reserve_date . ' ' . $start_time;

        $reserve_date_time = date('Y-m-d H:i:s', strtotime($reserve_date_time));

        if($check_reservation){

            return 'Slot Already Reserved!';
            
        }else if($reserve_date_time < $current_date){
            
            return 'Slot Already Elapsed!';
            
        }else{

            return 'ok';
            
        }
    }

    public function change_reservation_status($reservation_id, $type_status)
    {
        if($type_status == 'reserved'){
            
            RoomsReservation::where('id', $reservation_id)
                ->update(['status' => 2]);

            $this->add_reservation_event($reservation_id, null, 2);
            
        }else if($type_status == 'on_use'){
            
            $current_date = date('Y-m-d H:i:s'); 

            RoomsReservation::where('id', $reservation_id)
                ->update(
                    ['status' => 3],
                    ['time_in' => $current_date]
                );

            $this->add_reservation_event($reservation_id, null, 3);

        }else if($type_status == 'finished'){

            $current_date = date('Y-m-d H:i:s'); 

            RoomsReservation::where('id', $reservation_id)
                ->update(
                    ['status' => 4],
                    ['time_out' => $current_date]
                );

            $this->add_reservation_event($reservation_id, null, 4);
            
        }
    }

    public function start_reservation(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|numeric',
        ]);
        
        $reservation = $this->get_reservation($request->reservation_id);

        $check = $this->check_if_now_slot($reservation);
        
        if($check){

            $this->change_reservation_status($request->reservation_id, 'on_use');
            
            session()->flash('success_status', 'Now on Use!');
            return redirect()->route('admin.rooms.reservation.rooms_reservation', 'on_use');
            
        }else{
            
            session()->flash('error_status', 'Not Time Yet!');
            return redirect()->route('admin.rooms.reservation.view_reservation', $request->reservation_id);

        }
    }

    public function check_if_now_slot($reservation)
    {
        $reservation = $reservation->first();
        
        $start_time = $reservation['reserve_time_start'];
        $end_time = $reservation['reserve_time_end'];;
        
        $reserve_date = date('Y-m-d', strtotime($reservation['reserve_date']));
        
        $reserve_date_time_start = $reserve_date . ' ' . $start_time;
        $reserve_date_time_end = $reserve_date . ' ' . $end_time;

        $reserve_date_time_start = date('Y-m-d H:i:s', strtotime($reserve_date_time_start));
        $reserve_date_time_end = date('Y-m-d H:i:s', strtotime($reserve_date_time_end));
        
        if($reserve_date_time_start <= date('Y-m-d H:i:s') && $reserve_date_time_end >= date('Y-m-d H:i:s')){
            
            return true;
            
        }else{
            
            return false;

        }
    }

    public function finish_reservation(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|numeric',
        ]);
        
        $exists = RoomsReservation::where([
            ['id', $request->reservation_id],
            ['status', 3]
        ])->exists();
        
        if($exists){

            $this->change_reservation_status($request->reservation_id, 'finished');
            
            session()->flash('success_status', 'Reservation finished!');
            return redirect()->route('admin.rooms.reservation.rooms_reservation', 'finished');
            
        }else{
            
            session()->flash('error_status', 'Error!');
            return redirect()->route('admin.rooms.reservation.view_reservation', $request->reservation_id);

        }
    }
    
    public function deny_reservation(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
            'type' => 'required'
        ]);

        $reserve = RoomsReservation::findOrFail($id);

        $reserve->notes = $request->notes;

        $reserve->status = 8;
        
        $reserve->save();

        $this->add_reservation_event($id, $request->notes, 8);

        session()->flash('success_status', 'Reservation Denied!');
        return redirect()->route('admin.rooms.reservation.rooms_reservation', 'denied');

    }
    
}

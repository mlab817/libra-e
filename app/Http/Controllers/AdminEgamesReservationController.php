<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; 
use App\Student; 
use App\StaffCoach; 

use App\EgamesSlot; 
use App\EgamesReservation; 
use App\EgamesEvent; 
use App\EgamesSetting; 


class AdminEgamesReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Egames Reserve Now
    
    public function reserve_now()
    {
        $pc_slots = $this->get_all_slots_select();

        $reservations = $this->get_date_reservation(null);

        $egames_settings = EgamesSetting::where('id', 1)->first();

        $time_schedules = $this->create_schedules('12a');

        $search_date = date("Y-m-d");

        $students = $this->get_all_students();
        
        return view('admin.egames.reservations.reserve_now')->with('pc_slots', $pc_slots->get())
            ->with('reservations', $reservations)
            ->with('egames_settings', $egames_settings)
            ->with('time_schedules', $time_schedules)
            ->with('search_date', $search_date)
            ->with('students', $students);
    }
    
    public function get_all_slots_select()
    {
        $pc_slots_query = EgamesSlot::select('egames_slots.*');
        
        return $pc_slots_query;

    }

    public function get_all_students()
    {
        $all_enrolled_students = Student::join('programs', 'students.program_id', 'programs.id')
            ->select('students.*', 'programs.code')
            ->where('students.status', 1)
            ->orderBy('l_name', 'asc')
            ->get();

        return $all_enrolled_students;
    }

    public function search_date($search_date = null)
    {
        if($search_date != null){
            
            $pc_slots = $this->get_all_slots_select();

            $reservations = $this->get_date_reservation($search_date);

            $egames_settings = EgamesSetting::where('id', 1)->first();

            $time_schedules = $this->create_schedules('12a');

            $check_date = $this->check_date($search_date);
            
            $students = $this->get_all_students();

            if($check_date != 'ok'){

                session()->flash('error_status', $check_date);
                
            }
            
            return view('admin.egames.reservations.reserve_now')->with('pc_slots', $pc_slots->get())
                ->with('reservations', $reservations)
                ->with('egames_settings', $egames_settings)
                ->with('time_schedules', $time_schedules)
                ->with('search_date', $search_date)
                ->with('students', $students);

        }else{
            
            return redirect()->route('admin.egames.reservation.reserve_now');
            
        }
    }

    public function check_date($reserve_date)
    {
        $given_date = strtotime($reserve_date);

        if(date('D', $given_date) == 'Sat' || date('D', $given_date) == 'Sun') { 
            
            return 'No Egames & Reservations on Weekends!';
            
        }else{

            return 'ok';
            
        }
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'reserve_date' => 'required|string',
            'select_pc' => 'required|numeric',
            'select_time' => 'required|string',
            'select_student' => 'required|numeric',
        ]);

        $user_requests_too_many = $this->check_if_user_has_too_many_request($request->reserve_date, $request->select_student);

        if($user_requests_too_many != 'ok'){
            
            session()->flash('error_status', $user_requests_too_many);
            
            return redirect()->route('admin.egames.reservation.reserve_now.search_date', $request->reserve_date);

        }

        $check_date = $this->check_date($request->reserve_date);

        if($check_date != 'ok'){

            session()->flash('error_status', $check_date);
            
            return redirect()->route('admin.egames.reservation.reserve_now.search_date', $request->reserve_date);
            
        }else{

            $select_time = date('H:i:s', strtotime($request->select_time));
            
            $check_reservation = $this->check_reservation($request->reserve_date, $request->select_pc, $select_time);
            
            if($check_reservation != 'ok'){
                
                session()->flash('error_status', $check_reservation);

                return redirect()->route('admin.egames.reservation.reserve_now.search_date', $request->reserve_date);

            }else{

                $this->add_reservation($request->reserve_date, $request->select_pc, $select_time, $request->select_student);

                session()->flash('success_status', 'Request Sent!');

                return redirect()->route('admin.egames.reservation.egames_reservation', 'pending');

            }
        }
    }

    public function check_if_user_has_too_many_request($reserve_date, $user_id)
    {
        $egames_query = EgamesReservation::where('user_id', $user_id)
            ->whereIn('status', [1, 2]);

        $count_request = $egames_query->count();

        $count_reserve_date = $egames_query->where('reserve_date', $reserve_date)->count();

        if($count_request >= 3){
            
            return 'The user already had too many requests! Please allow others to use the E-games facilities as well.';
            
        }else if($count_reserve_date >= 1){
            
            return 'The user already has a request for reservation in this date! Please allow others to use the E-games facilities as well.';
            
        }else{

            return 'ok';
            
        }
    }

    public function check_reservation($reserve_date, $select_pc, $select_time)
    {
        $egames_settings = EgamesSetting::where('id', 1)->first();
        
        $end_time = strtotime("+".$egames_settings['per_session']." minutes", strtotime($select_time));
                    
        $end_time = date('H:i:s', $end_time);

        $check_reservation = EgamesReservation::whereDate('reserve_date', $reserve_date)
            ->where('egames_slot_id', $select_pc)
            ->whereBetween('reserve_time_slot', [$select_time, $end_time])
            ->WhereIn('status', [2,3,4])
            ->exists();

        $current_date = date('Y-m-d H:i:s'); 

        $reserve_date_time = $reserve_date . ' ' . $select_time;

        $reserve_date_time = date('Y-m-d H:i:s', strtotime($reserve_date_time));

        if($check_reservation){

            return 'Slot Already Reserved!';
            
        }else if($reserve_date_time < $current_date){
            
            return 'Slot Already Elapsed!';
            
        }else{

            return 'ok';
            
        }
    }

    public function add_reservation($reserve_date, $select_pc, $select_time, $student_id)
    {
        $egames_reservation = new EgamesReservation;
        
        $egames_reservation->user_id = $student_id;

        $egames_reservation->egames_slot_id = $select_pc;

        $egames_reservation->reserve_date = $reserve_date;

        $egames_reservation->reserve_time_slot = $select_time;

        $egames_reservation->status = 1;

        $egames_reservation->save();

        $this->add_reservation_event($egames_reservation->id, null, 1);
        
    }


    // Egames Reservations by status

    public function egames_reservation($status_type)
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
        
        $reservations = $this->get_admin_egames_reservations($status_array_data['status'], $status_type, $status_array_data['get_all']);

        $get_all_count = $this->get_all_count($status_array_data['get_all_count']);

        $egames_settings = EgamesSetting::where('id', 1)->first();

        return view('admin.egames.reservations.egames_reservations')->with('reservations' ,$reservations)
            ->with('count', $get_all_count)
            ->with('title', $status_array_data['title'])
            ->with('status_type', $status_type)
            ->with('egames_settings', $egames_settings);
    }

    public function get_all_types($type)
    {
        if($type == 'pc_slots'){
            
            $all_types = ['pc_slots'];

        }else{

            $all_types = ['pending', 'reserved', 'playing', 'finished', 'denied', 'cancelled', 'all_transaction', 'all_events', 'pc_slots'];

        }
        
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

            case 'playing':
                $status = 3;
                $get_all = false;
                $get_all_count = 3;
                $title = 'Playing';
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

        if($type == 'pc_slots'){

            $active = EgamesSlot::where('status', 1)->count();

            $inactive = EgamesSlot::where('status', 0)->count();

            $egames = EgamesSlot::where('pc_type', 1)->count();

            $research = EgamesSlot::where('pc_type', 2)->count();

            $all = EgamesSlot::count();

            $all_count = [
                'active' => $active,
                'inactive' => $inactive,
                'egames' => $egames,
                'research' => $research,
                'all' => $all
            ];

            return $all_count;
            
        }else if($type == 'all_transactions' || $type == 'all_events'){
            
            if($type == 'all_transactions'){
            
                $pending = EgamesReservation::where('status', 1)->count();
    
                $reserved = EgamesReservation::where('status', 2)->count();
    
                $playing = EgamesReservation::where('status', 3)->count();
    
                $finished = EgamesReservation::where('status', 4)->count();
    
                $denied = EgamesReservation::where('status', 8)->count();
    
                $cancelled = EgamesReservation::where('status', 9)->count();

                $egames = EgamesReservation::join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
                    ->where('egames_slots.pc_type', 1)->count();

                $research = EgamesReservation::join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
                    ->where('egames_slots.pc_type', 2)->count();
    
                $all = EgamesReservation::count();
                
    
            }else if($type == 'all_events'){
                
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
    
            }
            
            $all_count = [
                'pending' => $pending, 
                'reserved' => $reserved, 
                'playing' => $playing, 
                'finished' => $finished, 
                'denied' => $denied, 
                'cancelled' => $cancelled, 
                'egames' => $egames, 
                'research' => $research, 
                'all' => $all 
            ];
    
            return $all_count;
            

        }else{
            
            if($type == 'all'){
            
                $count = EgamesReservation::count();
    
            }else if($type == 'all_events'){
    
                $count = EgamesEvent::count();
                
            }else{
                
                $count = EgamesReservation::where('status', $type)->count();
                
            }
    
            return $count; 

        }

    }
    
    public function check_session_queries($type)
    {
        $session_name_order = $type . '_egames_toOrder'; 
            
        $session_name_orderBy = $type . '_egames_orderBy'; 

        $session_name_per_page = $type . '_egames_per_page'; 
        
        if(session()->has($session_name_order) != true){

            if($type == 'pc_slots'){
                
                session([$session_name_order => 'pc_no' ]);

            }else{

                session([$session_name_order => 'updated_at' ]);

            }
        }

        if(session()->has($session_name_orderBy) != true){

            if($type == 'pc_slots'){
                
                session([$session_name_orderBy => 'asc' ]);

            }else{

                session([$session_name_orderBy => 'desc' ]);

            }
            
        }

        if (session()->has($session_name_per_page) != true) {

            session([$session_name_per_page => 10 ]);
            
        }
    }

    public function get_admin_egames_reservations($status, $status_type, $get_all)
    {
        $this->check_session_queries($status_type);
        
        $admin_egames_reservations_query = $this->get_admin_egames_reservations_query($status, $get_all);
        
        $all_user_admin_egames_reservations = $this->add_order_queries($admin_egames_reservations_query, $get_all, $status_type);
            
        return $all_user_admin_egames_reservations;
        
    }

    public function get_admin_egames_reservations_query($status, $get_all)
    {
        $this->check_reservations();

        if($status == 'all_events'){
            
            $admin_egames_reservations_query = EgamesEvent::join('egames_reservations', 'egames_events.egames_reservation_id', 'egames_reservations.id')
                ->join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
                ->join('students', 'egames_reservations.user_id', 'students.user_id')
                ->select(
                    'egames_events.*',
                    'egames_reservations.reserve_date',
                    'egames_reservations.reserve_time_slot',
                    'egames_slots.pc_no',
                    'egames_slots.pc_type',
                    'egames_slots.pc_name',
                    'students.f_name',
                    'students.m_name',
                    'students.l_name',
                    'students.email_add',
                    'students.contact_no'
                );

        }else{

            $admin_egames_reservations_query = EgamesReservation::join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
                ->join('students', 'egames_reservations.user_id', 'students.user_id')
                ->select(
                    'egames_reservations.*',
                    'egames_slots.pc_no',
                    'egames_slots.pc_name',
                    'egames_slots.pc_type',
                    'students.f_name',
                    'students.m_name',
                    'students.l_name',
                    'students.email_add',
                    'students.contact_no'
                );

        }
        
            
        if($get_all == false){

            $admin_egames_reservations_query->where('egames_reservations.status', $status);

        }

        return $admin_egames_reservations_query;
            
    }

    public function add_order_queries($query, $get_all, $type)
    {
        $session_get_all_status = $type . '_egames_getAll_status';

        $session_get_all_pc_type = $type . '_egames_getAll_pc_type';
        
        $session_toOrder = $type . '_egames_toOrder';

        $session_orderBy = $type . '_egames_orderBy';

        $session_per_page = $type . '_egames_per_page';
        
        if($get_all){
            
            if(session()->has($session_get_all_status)){
    
                if(session()->get($session_get_all_status) != 'all'){
    
                    if($type == 'pc_slots'){
    
                        $query = $query->where('egames_slots.status', session()->get($session_get_all_status));
                        
                    }else{

                        if($type == 'all_transactions'){
                        
                            $query = $query->where('egames_reservations.status', session()->get($session_get_all_status));
                            
                        }else if($type == 'all_events'){
                            
                            $query = $query->where('egames_events.status', session()->get($session_get_all_status));
    
                        }
                    }
    
                }else{
                        
                    session([$session_get_all_status => 'all' ]);
                }
                
            }else{
    
                session([$session_get_all_status => 'all' ]);
                
            }

            if(session()->has($session_get_all_pc_type)){
    
                if(session()->get($session_get_all_pc_type) != 'all'){
    
                    $query = $query->where('egames_slots.pc_type', session()->get($session_get_all_pc_type));
    
                }else{
                        
                    session([$session_get_all_pc_type => 'all' ]);
                }
                
            }else{
    
                session([$session_get_all_pc_type => 'all' ]);
                
            }


        }
        
        $all_query = $query->orderBy(session()->get($session_toOrder), session()->get($session_orderBy))
            ->paginate(session()->get($session_per_page));

        if($all_query->count() > 0){
            
            return $all_query;
            
        }else{

            if($type == 'pc_slots'){

                session()->flash('error_status', 'No PC Slots Yet!');

            }else{

                session()->flash('error_status', 'No Reservations Yet!');
                
            }
            
            return $all_query;

        }
    }


    // Egames Dynamic toOrder, orderBy, filter by status
    
    public function egames_per_page($type = 'all_transactions', $per_page = 10) 
    {
        $all_types = $this->get_all_types($type);

        if(in_array($type, $all_types)){
            
            $session_per_page = $type . '_egames_per_page';
            
        }else{
            
            $session_per_page = 'all_transactions_egames_per_page';
            
        }

        $all_per_page = [5, 10, 20, 50, 100, 200, 500];
        
        if(in_array($per_page, $all_per_page)){
            
            session([$session_per_page => $per_page]);
            
        }else{
            
            session([$session_per_page => 10]);

        }
        
        if(in_array($type, $all_types)){

            if($type == 'pc_slots'){
                
                return redirect()->route('admin.egames.slots_settings.' . $type );

            }else{

                return redirect()->route('admin.egames.reservation.egames_reservation', $type);
                
            }
            
        }else{
            
            return redirect()->route('admin.egames.reservation.egames_reservation', 'all_transactions');

        }
    }

    public function egames_toOrder($type = 'all_transactions', $toOrder = 'updated_at') 
    {
        $all_types = $this->get_all_types($type);
        
        if(in_array($type, $all_types)){
            
            $session_toOrder = $type . '_egames_toOrder';

        }else{
            
            $session_toOrder = 'all_transactions_egames_toOrder';
            
        }

        if($type == 'pc_slots'){
            
            $all_toOrder = ['pc_no', 'pc_name'];
            
        }else{
            
            $all_toOrder = ['pc_name', 'l_name', 'reserve_date', 'reserve_time_slot', 'updated_at'];

        }
        
        if(in_array($toOrder, $all_toOrder)){
            
            session([$session_toOrder => $toOrder]);
            
        }else{
            
            if($type = 'pc_slots'){
                
                session([$session_toOrder => 'pc_no']);

            }else{
                
                session([$session_toOrder => 'updated_at']);

            }
            
        }

        if(in_array($type, $all_types)){

            if($type == 'pc_slots'){
                
                return redirect()->route('admin.egames.slots_settings.' . $type );

            }else{

                return redirect()->route('admin.egames.reservation.egames_reservation', $type);
                
            }
            
        }else{
            
             return redirect()->route('admin.egames.reservation.egames_reservation', 'all_transactions');

        }

    }

    public function egames_orderBy($type = 'all_transactions', $orderBy = 'desc') 
    {
        $all_types = $this->get_all_types($type);
        
        if(in_array($type, $all_types)){
            
            $session_orderBy = $type . '_egames_orderBy';
            
        }else{
            
            $session_orderBy = 'all_transactions_egames_orderBy';
            
        }
        
        if($orderBy == 'asc' || $orderBy == 'desc' ){
            
            session([$session_orderBy => $orderBy]);
            
        }else{
            
            session([$session_orderBy => 'desc']);

        }

        if(in_array($type, $all_types)){

            if($type == 'pc_slots'){
                
                return redirect()->route('admin.egames.slots_settings.' . $type );

            }else{

                return redirect()->route('admin.egames.reservation.egames_reservation', $type);
                
            }
            
        }else{
            
             return redirect()->route('admin.egames.reservation.egames_reservation', 'all_transactions');

        }
    }

    public function filter_status_egames($type = 'all_transactions', $filter = 'all') 
    {
        $all_types = $this->get_all_types($type);
        
        if(in_array($type, $all_types)){
            
            $session_getAll_status = $type . '_egames_getAll_status';
            
        }else{
            
            $session_getAll_status = 'all_transactions_egames_getAll_status';

        }

        if($type == 'pc_slots'){

            $all_filter = ['all', 1, 0];
            
        }else{
            
            $all_filter = ['all', 1, 2, 3, 4, 8,9];
            
        }
        
        if(in_array($filter, $all_filter)){
            
            session([$session_getAll_status => $filter ]);
            
        }else{
            
            session([$session_getAll_status => 'all' ]);

        }

        if(in_array($type, $all_types)){

            if($type == 'pc_slots'){
                
                return redirect()->route('admin.egames.slots_settings.' . $type );

            }else{

                return redirect()->route('admin.egames.reservation.egames_reservation', $type);
                
            }
            
        }else{
            
            return redirect()->route('admin.egames.reservation.egames_reservation', 'all_transactions');

        }
    }

    public function filter_pc_type_egames($type = 'all_transactions', $filter = 'all') 
    {
        $all_types = $this->get_all_types($type);
        
        if(in_array($type, $all_types)){
            
            $session_getAll_pc_type = $type . '_egames_getAll_pc_type';
            
        }else{
            
            $session_getAll_pc_type = 'all_transactions_egames_getAll_pc_type';

        }
        
        $all_filter = ['all', 1, 2];

        if(in_array($filter, $all_filter)){
            
            session([$session_getAll_pc_type => $filter ]);
            
        }else{
            
            session([$session_getAll_pc_type => 'all' ]);

        }

        if(in_array($type, $all_types)){

            if($type == 'pc_slots'){
                
                return redirect()->route('admin.egames.slots_settings.' . $type );

            }else{

                return redirect()->route('admin.egames.reservation.egames_reservation', $type);
                
            }
            
        }else{
            
            return redirect()->route('admin.egames.reservation.egames_reservation', 'all_transactions');

        }
    }
    
    // Search By status

    public function search_all_egames_reservations($status_type, $search = '')
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
        
        $reservations = $this->search_egames_reservations($search, $status_array_data['status'], $status_array_data['get_all'], $status_type);
        
        $get_all_count = $this->get_all_count($status_array_data['get_all_count']);

        $egames_settings = EgamesSetting::where('id', 1)->first();

        return view('admin.egames.reservations.egames_reservations')->with('reservations' ,$reservations)
            ->with('count', $get_all_count)
            ->with('title', $status_array_data['title'])
            ->with('status_type', $status_type)
            ->with('egames_settings', $egames_settings);

    }

    
    // Search queries

    public function search_egames_reservations($search, $type, $get_all, $status_type)
    {
        $this->check_session_queries($status_type);
        
        $egames_reservations_query = $this->get_admin_egames_reservations_query($type, $get_all);
        
        $egames_reservations_query = $this->get_search_query($egames_reservations_query, $search);
        
        $count = $egames_reservations_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);

        if($type == 'all'){

            session([$status_type . '_egames_reservations_getAll' => 'all' ]);

        }

        $all_user_egames_reservations = $this->add_order_queries($egames_reservations_query, $get_all, $status_type);
        
        $reservations = $all_user_egames_reservations;
        
        if($count <= 0){

            session()->flash('error_status', 'No Reservation/s found!');
            
        }

        return $reservations;

    }
    
    public function get_search_query($egames_reservations_query, $search)
    {
        //$all_users_id = $this->search_users($search);

        $egames_reservations_query->where('egames_slots.pc_name', 'like', '%'.$search.'%')
            ->orWhere('egames_reservations.reserve_date', 'like', '%'.$search.'%')
            ->orWhere('egames_reservations.reserve_time_slot', 'like', '%'.$search.'%')
            ->orWhere('students.f_name', 'like', '%'.$search.'%')
            ->orWhere('students.m_name', 'like', '%'.$search.'%')
            ->orWhere('students.l_name', 'like', '%'.$search.'%')
            ->orWhere('students.email_add', 'like', '%'.$search.'%');
        
        return $egames_reservations_query;
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

                $egames_settings = EgamesSetting::where('id', 1)->first();

                $pc_slots = $this->get_all_slots();

                $reservations = $this->get_date_reservation($reservation['reserve_date']);

                return view('admin.egames.reservations.view_reservation')->with('reservation', $reservation)
                    ->with('reservations', $reservations)
                    ->with('reservation_data', $reservation_data)
                    ->with('egames_settings', $egames_settings)
                    ->with('pc_slots', $pc_slots);

            }else{
                
                return redirect()->route('admin.egames.reservation.egames_reservation', 'all_transactions');
                
            }
            
        }else{
            
            return redirect()->route('admin.egames.reservation.egames_reservation', 'all_transactions');

        }
    }

    public function get_reservation($id)
    {
        $reservation_query = $this->get_admin_egames_reservations_query('all_transactions', true);

        $reservation = $reservation_query->where('egames_reservations.id', $id);

        return $reservation;

    }

    public function get_reservation_dynamic_data($reservation)
    {
        $get_reservation = $reservation->first();

        $status = $get_reservation['status'];
        
        if($status == 1){
    
            $point_arrow = 'pending';
            $color_class = 'text-warning';
            $url_back = route('admin.egames.reservation.egames_reservation') . '/pending';
            $form_url = route('admin.egames.reservation.approve_reservation');
            
          }else if($status == 2){
            
            $point_arrow = 'reserved';
            $color_class = 'text-primary';
            $url_back = route('admin.egames.reservation.egames_reservation') . '/reserved';
            $form_url = route('admin.egames.reservation.play_reservation');
      
          }else if($status == 3){
            
            $point_arrow = 'playing';
            $color_class = 'text-success';
            $url_back = route('admin.egames.reservation.egames_reservation') . '/playing';
            $form_url = route('admin.egames.reservation.finish_reservation');
            
          }else if($status == 4){
            
            $point_arrow = 'finished';
            $color_class = 'text-success';
            $url_back = route('admin.egames.reservation.egames_reservation') . '/finished';
            $form_url = route('admin.egames.reservation.finish_reservation');
            
          }else if($status == 8){
      
            $point_arrow = 'denied';
            $color_class = 'text-danger';
            $url_back = route('admin.egames.reservation.egames_reservation') . '/denied';
            $form_url = route('admin.egames.reservation.finish_reservation');
            
          }else if($status == 9){
            
            $point_arrow = 'cancelled';
            $color_class = 'text-danger';
            $url_back = route('admin.egames.reservation.egames_reservation') . '/cancelled';
            $form_url = route('admin.egames.reservation.finish_reservation');
            
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
        
        $reservations = EgamesReservation::join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
            ->select(
                'egames_reservations.*',
                'egames_slots.pc_no',
                'egames_slots.pc_name'
            )
            ->WhereIn('egames_reservations.status', [2,3,4])
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
        $egames_settings = EgamesSetting::where('id', 1)->first();

        $current_date_time = date('Y-m-d H:i:s'); 
        
        $current_date_time = date('Y-m-d H:i:s', strtotime("-".$egames_settings['per_session']." minutes", strtotime($current_date_time)));
        
        $approved_reservations = EgamesReservation::whereIn('status', [1, 2])->get();
        
        if($approved_reservations->count() > 0){
            
            foreach ($approved_reservations as $approved_reservation) {
                
                $date = $approved_reservation->reserve_date;

                $time = $approved_reservation->reserve_time_slot;

                $date = date('Y-m-d', strtotime($date));
        
                $combi = $date . ' ' . $time; 

                $combi = date('Y-m-d H:i:s', strtotime($combi));
                
                if($combi <= $current_date_time){

                    $update = EgamesReservation::findOrFail($approved_reservation->id);

                    $update->notes = 'Denied!';
                
                    $update->status = 8;

                    $update->save();
                    
                    $this->add_reservation_event($update->id, 'Denied!', 8);
                    
                }
            }
        }
    }

    public function add_reservation_event($egames_reservation_id, $notes, $status)
    {
        $egames_event = new EgamesEvent;
        
        $egames_event->egames_reservation_id = $egames_reservation_id;

        $egames_event->notes = $notes;

        $egames_event->status = $status;

        $egames_event->save();
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
            return redirect()->route('admin.egames.reservation.egames_reservation', 'reserved');

        }else{

            session()->flash('error_status', $date_available);
            return redirect()->route('admin.egames.reservation.view_reservation', $request->reservation_id);
            
        }
    }

    public function check_if_date_available($reservation)
    {
        $reservation = $reservation->first();

        $egames_settings = EgamesSetting::where('id', 1)->first();

        $select_time = $reservation['reserve_time_slot'];

        $reserve_date = $reservation['reserve_date'];
        
        $end_time = strtotime("+".$egames_settings['per_session']." minutes", strtotime($select_time));
                    
        $end_time = date('H:i:s', $end_time);

        $check_reservation = EgamesReservation::whereDate('reserve_date', $reserve_date)
            ->where('egames_slot_id', $reservation['egames_slot_id'])
            ->whereBetween('reserve_time_slot', [$select_time, $end_time])
            ->WhereIn('status', [2,3,4])
            ->exists();

        $current_date = date('Y-m-d H:i:s'); 

        $reserve_date = date('Y-m-d', strtotime($reserve_date)); 

        $reserve_date_time = $reserve_date . ' ' . $select_time;

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
            
            EgamesReservation::where('id', $reservation_id)
                ->update(['status' => 2]);

            $this->add_reservation_event($reservation_id, null, 2);
            
        }else if($type_status == 'playing'){
            
            $current_date = date('Y-m-d H:i:s'); 

            EgamesReservation::where('id', $reservation_id)
                ->update(
                    ['status' => 3],
                    ['time_in' => $current_date]
                );

            $this->add_reservation_event($reservation_id, null, 3);

        }else if($type_status == 'finished'){

            $current_date = date('Y-m-d H:i:s'); 

            EgamesReservation::where('id', $reservation_id)
                ->update(
                    ['status' => 4],
                    ['time_out' => $current_date]
                );

            $this->add_reservation_event($reservation_id, null, 4);
            
        }
    }

    public function play_reservation(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|numeric',
        ]);
        
        $reservation = $this->get_reservation($request->reservation_id);

        $check = $this->check_if_now_slot($reservation);
        
        if($check){

            $this->change_reservation_status($request->reservation_id, 'playing');
            
            session()->flash('success_status', 'Now Playing!');
            return redirect()->route('admin.egames.reservation.egames_reservation', 'playing');
            
        }else{
            
            session()->flash('error_status', 'Not Time Yet!');
            return redirect()->route('admin.egames.reservation.view_reservation', $request->reservation_id);

        }
    }

    public function check_if_now_slot($reservation)
    {
        $egames_settings = EgamesSetting::where('id', 1)->first();
        
        $reservation = $reservation->first();
        
        $start_time = strtotime($reservation['reserve_time_slot']);
        $end_time = strtotime("+".$egames_settings['per_session']." minutes", strtotime($reservation['reserve_time_slot']));
        
        $start_time = date('H:i:s', $start_time);
        $end_time = date('H:i:s', $end_time);
        
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
        
        $exists = EgamesReservation::where([
            ['id', $request->reservation_id],
            ['status', 3]
        ])->exists();
        
        if($exists){

            $this->change_reservation_status($request->reservation_id, 'finished');
            
            session()->flash('success_status', 'Reservation finished!');
            return redirect()->route('admin.egames.reservation.egames_reservation', 'finished');
            
        }else{
            
            session()->flash('error_status', 'Error!');
            return redirect()->route('admin.egames.reservation.view_reservation', $request->reservation_id);

        }
    }
    
    
    public function deny_reservation(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
            'type' => 'required'
        ]);

        $reserve = EgamesReservation::findOrFail($id);

        $reserve->notes = $request->notes;

        $reserve->status = 8;
        
        $reserve->save();

        $this->add_reservation_event($id, $request->notes, 8);

        session()->flash('success_status', 'Reservation Denied!');
        return redirect()->route('admin.egames.reservation.egames_reservation', 'denied');

    }
    
    
    // Slots & Settings

    //Slots
    
    public function pc_slots()
    {
        $pc_slots = $this->get_all_slots();

        $get_all_count = $this->get_all_count('pc_slots');

        $last_pc_no = $this->get_last_pc_no();
        
        return view('admin.egames.slots_settings.pc_slots')->with('pc_slots', $pc_slots)
            ->with('type', 'pc_slots')
            ->with('count', $get_all_count)
            ->with('last_pc_no', $last_pc_no);
        
    }
    
    public function get_all_slots()
    {
        $this->check_session_queries('pc_slots');

        $pc_slots_query = $this->get_pc_slots_query();
        
        $all_pc_slots_query = $this->add_order_queries($pc_slots_query, true, 'pc_slots');
            
        return $all_pc_slots_query;

    }

    public function get_pc_slots_query()
    {
        $pc_slots_query = EgamesSlot::select('egames_slots.*');
        
        return $pc_slots_query;

    }

    public function get_last_pc_no()
    {
        $count = EgamesSlot::count();
        
        if($count == 0){
            
            return 1;

        }else{
            
            $last_pc_no = EgamesSlot::orderBy('pc_no', 'desc')->select('pc_no')->first();
            
            $last_pc_no = 1 + $last_pc_no->pc_no;

            return $last_pc_no;
            
        }
    }
    
    public function add_pc_slot(Request $request)
    {
        
        if($request->isMethod('put')){

            $request->validate([
                'id' => 'required|numeric',
                'pc_no' => 'required|numeric',
                'pc_type' => 'required|numeric',
                'pc_name' => 'required|string',
                'notes' => 'nullable|string',
                'description' => 'nullable|string',
                'status' => 'nullable|digits:1',
            ]);

        }else{

            $request->validate([
                'pc_no' => 'required|unique:egames_slots,pc_no|numeric',
                'pc_name' => 'required|unique:egames_slots,pc_name|string',
                'pc_type' => 'required|numeric',
                'notes' => 'nullable|string',
                'description' => 'nullable|string',
                'status' => 'nullable|digits:1',
            ]);
            
        }
        
        $pc_slot = $request->isMethod('put') ? EgamesSlot::findOrFail($request->id) : new EgamesSlot;

        $pc_slot->pc_no = $request->pc_no;

        $pc_slot->pc_name = ltrim(ucfirst($request->pc_name));

        $pc_slot->pc_type = $request->pc_type;

        $pc_slot->notes = $request->notes;
        
        $pc_slot->description = $request->description;

        if($request->status == '' || null){

            $pc_slot->status = 0;

        }else{

            $pc_slot->status = 1;
            
        }
        
        $pc_slot->save();

        if($request->isMethod('put')){
            
            session()->flash('success_status', 'PC Slot updated!');

        }else{
            
            session()->flash('success_status', 'PC Slot added!');

        }
        
        return redirect()->route('admin.egames.slots_settings.pc_slots');

    }

    public function view_pc_slot($id = 0)
    {
        if(is_numeric($id) && $id > 0){

            $pc_slot = $this->get_pc_slot($id);
            
            if($pc_slot == false){
                
                return redirect()->route('admin.egames.slots_settings.pc_slots');

            }else{

                if($pc_slot->count() != 0){
    
                    return view('admin.egames.slots_settings.view_pc_slot')->with('pc_slot', $pc_slot);

                }else{
                    
                    return redirect()->route('admin.egames.slots_settings.pc_slots');
                    
                }
            }

        }else{
            
            return redirect()->route('admin.egames.slots_settings.pc_slots');
            
        }
    }

    public function get_pc_slot($id)
    {
        $exists = EgamesSlot::where('id', $id)->exists();
        
        if($exists){

            $pc_slots_query = $this->get_pc_slots_query();

            $pc_slots = $pc_slots_query->where('id', $id)->first();

            return $pc_slots;
            
        }else{
            
            return false;
            
        }
    }

    public function search_pc_slots($search = '')
    {
        $pc_slots_query = $this->get_pc_slots_query();
    
        $pc_slots_query = $this->get_pc_slots_search_query($pc_slots_query, $search);

        $count = $pc_slots_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);
        
        $all_pc_slots_query = $this->add_order_queries($pc_slots_query, true, 'pc_slots');
        
        $pc_slots = $all_pc_slots_query;

        if($count <= 0){

            session()->flash('error_status', 'No PC Slot/s found!');
            
        }

        $get_all_count = $this->get_all_count('pc_slots');

        $last_pc_no = $this->get_last_pc_no();
        
        return view('admin.egames.slots_settings.pc_slots')->with('pc_slots', $pc_slots)
            ->with('type', 'pc_slots')
            ->with('count', $get_all_count)
            ->with('last_pc_no', $last_pc_no);

    }
    
    public function get_pc_slots_search_query($pc_slots_query, $search)
    {
        $pc_slots_query->where('pc_no', 'like', '%'.$search.'%')
            ->orWhere('pc_name', 'like', '%'.$search.'%');
        
        return $pc_slots_query;

    }

    
    // Settings

    public function egames_settings()
    {
        $egames_settings = EgamesSetting::find(1);

        if($egames_settings->count() == 0){
            
            session()->flash('error_no_settings_yet', 'No Settings Yet!');
            
            $time_schedules = null;

        }else{

            $time_schedules = $this->create_schedules('12a');
            
        }

        //return $time_sched;
        
        return view('admin.egames.slots_settings.egames_settings')->with('egames_settings', $egames_settings)
            ->with('time_schedules', $time_schedules)
            ->with('type', 'egames_settings');
    }

    public function store_settings(Request $request)
    {
        $request->validate([
            'per_session' => 'required|numeric',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);
        
        $egames_settings = $request->isMethod('put') ? EgamesSetting::findOrFail(1) : new EgamesSetting;

        $egames_settings->per_session = $request->per_session;
        
        $start_timestamp = strtotime($request->start_time);

        $start_time = date('H:i:s', $start_timestamp);
        
        $end_timestamp = strtotime($request->end_time);

        $end_time = date('H:i:s', $end_timestamp);

        $egames_settings->start_time = $start_time;

        $egames_settings->end_time = $end_time;

        $egames_settings->save();
        
        if($request->isMethod('put')){
            
            session()->flash('success_status', 'Settings updated!');

        }else{
            
            session()->flash('success_status', 'Settings added!');

        }
        
        return redirect()->route('admin.egames.slots_settings.egames_settings');
        
    }

    public function create_schedules($format)
    {
        $egames_settings = EgamesSetting::find(1);
        
        if($egames_settings->exists()){

            $startTime = $egames_settings->start_time;
            $endTime = $egames_settings->end_time;

            $minutes_per_session = $egames_settings->per_session;
            
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
                    
                }else if ($format == '12a'){
                    
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
    
}

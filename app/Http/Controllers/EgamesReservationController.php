<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Student; 
use App\StaffCoach; 

use App\EgamesSlot; 
use App\EgamesReservation; 
use App\EgamesEvent; 
use App\EgamesSetting; 


class EgamesReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function get_loggedIn_user_data()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user_id  = $user->id;
            $user_ref_id  = $user->user_ref_id;
            $user_type  = $user->user_type;

            if($user_type == 1){

                $user = Student::where('id', $user_ref_id)->first();
                
            }else if($user_type == 2){
                
                $user = StaffCoach::where('id', $user_ref_id)->first();

            }

            session(['loggedIn_user_data' => $user ]);
            session(['loggedIn_user_id' => $user_id ]);
            session(['loggedIn_user_ref_id' => $user_ref_id ]);
            session(['loggedIn_user_type' => $user_type ]);
            
        }
    }

    public function egames()
    {
        $this->get_loggedIn_user_data();
        
        $pc_slots = $this->get_all_slots();

        $reservations = $this->get_date_reservation(null);

        $egames_settings = EgamesSetting::where('id', 1)->first();

        $time_schedules = $this->create_schedules('12a');

        $search_date = date("Y-m-d");
        
        return view('libraE.gaming_rooms.egames')->with('pc_slots', $pc_slots->get())
            ->with('reservations', $reservations)
            ->with('egames_settings', $egames_settings)
            ->with('time_schedules', $time_schedules)
            ->with('search_date', $search_date);
        
    }

    public function get_all_slots()
    {
        $all_pc_slots_query = $this->get_pc_slots_query();
        
        return $all_pc_slots_query;

    }

    public function get_pc_slots_query()
    {
        $pc_slots_query = EgamesSlot::select('egames_slots.*');
        
        return $pc_slots_query;

    }

    public function get_date_reservation($search_date)
    {
        $this->check_reservations();
        
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
    
    public function search_date($search_date = null)
    {
        if($search_date != null){
            
            $this->get_loggedIn_user_data();
        
            $pc_slots = $this->get_all_slots();

            $reservations = $this->get_date_reservation($search_date);

            $egames_settings = EgamesSetting::where('id', 1)->first();

            $time_schedules = $this->create_schedules('12a');

            $check_date = $this->check_date($search_date);

            if($check_date != 'ok'){

                session()->flash('error_status', $check_date);
                
            }
            
            return view('libraE.gaming_rooms.egames')->with('pc_slots', $pc_slots->get())
                ->with('reservations', $reservations)
                ->with('egames_settings', $egames_settings)
                ->with('time_schedules', $time_schedules)
                ->with('search_date', $search_date);

        }else{
            
            return redirect()->route('libraE.reservations.egames');
            
        }
    }

    public function reserve(Request $request)
    {
        $this->get_loggedIn_user_data();

        $request->validate([
            'reserve_date' => 'required|string',
            'select_pc' => 'required|numeric',
            'select_time' => 'required|string',
        ]);

        $user_requests_too_many = $this->check_if_user_has_too_many_request($request->reserve_date);

        if($user_requests_too_many != 'ok'){
            
            session()->flash('error_status', $user_requests_too_many);
            
            return redirect()->route('libraE.reservations.egames.search_date', $request->reserve_date);

        }

        $check_date = $this->check_date($request->reserve_date);

        if($check_date != 'ok'){

            session()->flash('error_status', $check_date);
            
            return redirect()->route('libraE.reservations.egames.search_date', $request->reserve_date);
            
        }else{

            $select_time = date('H:i:s', strtotime($request->select_time));
            
            $check_reservation = $this->check_reservation($request->reserve_date, $request->select_pc, $select_time);
            
            if($check_reservation != 'ok'){
                
                session()->flash('error_status', $check_reservation);

                return redirect()->route('libraE.reservations.egames.search_date', $request->reserve_date);

            }else{

                $this->add_reservation($request->reserve_date, $request->select_pc, $select_time);

                session()->flash('success_status', 'Reserved!');

                return redirect()->route('libraE.my_reservations.egames');

            }
        }
    }
    
    public function check_if_user_has_too_many_request($reserve_date)
    {
        $this->get_loggedIn_user_data();

        $user_id = session()->get('loggedIn_user_id');

        $egames_query = EgamesReservation::where('user_id', $user_id)
            ->whereIn('status', [1, 2]);

        $count_request = $egames_query->count();

        $count_reserve_date = $egames_query->where('reserve_date', $reserve_date)->count();

        if($count_request >= 3){
            
            return 'You already had too many requests! Please allow others to use the E-games facilities as well.';
            
        }else if($count_reserve_date >= 1){
            
            return 'You already have a request for reservation in this date! Please allow others to use the E-games facilities as well.';
            
        }else{

            return 'ok';
            
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
    
    public function add_reservation($reserve_date, $select_pc, $select_time)
    {
        $egames_reservation = new EgamesReservation;
        
        $egames_reservation->user_id = session()->get('loggedIn_user_id');

        $egames_reservation->egames_slot_id = $select_pc;

        $egames_reservation->reserve_date = $reserve_date;

        $egames_reservation->reserve_time_slot = $select_time;

        $egames_reservation->status = 1;

        $egames_reservation->save();

        $this->add_reservation_event($egames_reservation->id, null, 1);
        
    }

    public function add_reservation_event($egames_reservation_id, $notes, $status)
    {
        $egames_event = new EgamesEvent;
        
        $egames_event->egames_reservation_id = $egames_reservation_id;

        $egames_event->notes = $notes;

        $egames_event->status = $status;

        $egames_event->save();
    }


    
    // My Egames Reservations

    public function my_egames_reservation()
    {
        $this->get_loggedIn_user_data();
        
        $my_egames_reservations = $this->get_user_reservation();

        $egames_settings = EgamesSetting::where('id', 1)->first();
        
        return view('libraE.my_reservations.egames')->with('my_egames_reservations', $my_egames_reservations)
            ->with('egames_settings', $egames_settings);
        
    }
    
    public function check_session_queries($type)
    {
        $session_name_order = $type.'_toOrder'; 
            
        $session_name_orderBy = $type.'_orderBy'; 

        $session_name_per_page = $type.'_per_page'; 
        
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
    

    public function get_user_reservation()
    {
        $this->check_session_queries('my_egames');
        
        $my_egames_reservation_query = $this->get_my_egames_reservation_query();
        
        $my_egames_reservation_query = $this->add_order_queries($my_egames_reservation_query, 'my_egames');
            
        return $my_egames_reservation_query;
        
    }

    public function get_my_egames_reservation_query()
    {
        $my_egames_reservation_query = EgamesReservation::join('egames_slots', 'egames_reservations.egames_slot_id', 'egames_slots.id')
            ->select(
                'egames_reservations.*',
                'egames_slots.pc_no',
                'egames_slots.pc_name'
            )
            ->where('user_id', session()->get('loggedIn_user_id'));

        return $my_egames_reservation_query;
        
    }

    public function add_order_queries($my_egames_reservation_query, $type)
    {
        $session_get_all_status = $type . '_getAll_status';

        $session_toOrder = $type . '_toOrder';

        $session_orderBy = $type . '_orderBy';

        $session_per_page = $type . '_per_page';
        
        if(session()->has($session_get_all_status)){

            if(session()->get($session_get_all_status) != 'all'){

                $my_egames_reservation_query = $my_egames_reservation_query->where('egames_reservations.status', session()->get($session_get_all_status));

            }else{
                    
                session([$session_get_all_status => 'all' ]);
            }
            
        }else{

            session([$session_get_all_status => 'all' ]);
            
        }

        $all_query = $my_egames_reservation_query->orderBy(session()->get($session_toOrder), session()->get($session_orderBy))
            ->paginate(session()->get($session_per_page));


        if($all_query->count() > 0){
            
            return $all_query;
            
        }else{

            session()->flash('error_status', 'No Reservation/s Yet!');
            return $all_query;

        }
    }

    public function my_egames_per_page($per_page = 10) 
    {
        $per_page_array = [5,10,20,50,100,200,500];
        
        if(in_array($per_page, $per_page_array)){
            
            session(['my_egames_per_page' => $per_page ]);
            
        }else{

            session(['my_egames_per_page' => 10 ]);
        }
        
        return redirect()->route('libraE.my_reservations.egames');
        
    }

    public function my_egames_toOrder($ToOrder = 'reserve_date') 
    {
        $to_order_array = ['pc_name','reserve_date','reserve_time_slot', 'updated_at'];

        if(in_array($ToOrder, $to_order_array)){
            
            session(['my_egames_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['my_egames_toOrder' => 'updated_at' ]);

        }

        return redirect()->route('libraE.my_reservations.egames');

    }

    public function my_egames_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || $orderBy ==  'desc' ){
            
            session(['my_egames_orderBy' => $orderBy ]);
            
        }else{
            
            session(['my_egames_orderBy' => 'desc' ]);

        }

        return redirect()->route('libraE.my_reservations.egames');

    }

    public function filter_my_egames_reservations($filter = 'all') 
    {
        $filter_array = ['all',1,2,3,4,8,9];
        
        if(in_array($filter, $filter_array)){
            
            session(['my_egames_getAll_status' => $filter ]);
            
        }else{
            
            session(['my_egames_getAll_status' => 'all' ]);

        }

        return redirect()->route('libraE.my_reservations.egames');

    }

    public function search_my_egames_reservations($search = '')
    {
        $this->check_session_queries('my_egames');

        $my_egames_reservation_query = $this->get_my_egames_reservation_query();
        
        $my_egames_reservation_query->where('egames_slots.pc_name', 'like', '%'.$search.'%')
            ->orWhere('egames_reservations.reserve_date', 'like', '%'.$search.'%')
            ->orWhere('egames_reservations.reserve_time_slot', 'like', '%'.$search.'%');
        
        $count = $my_egames_reservation_query->count();

        session()->flash('libraE_search', $search);
        session()->flash('libraE_search_count', $count);
        session(['my_egames_getAll_status' => 'all' ]);

        $my_egames_reservation_query = $this->add_order_queries($my_egames_reservation_query, 'my_egames');
        
        $my_egames_reservations = $my_egames_reservation_query;
        
        if($count <= 0){

            session()->flash('error_status', 'No Reservation/s found!');
            
        }
        
        $egames_settings = EgamesSetting::where('id', 1)->first();
        
        return view('libraE.my_reservations.egames')->with('my_egames_reservations', $my_egames_reservations)
            ->with('egames_settings', $egames_settings);

    }

    public function delete_my_egames_reservations($id)
    {
        $egames_reservation = EgamesReservation::findOrFail($id);
        
        if($egames_reservation->status == 1){
            
            EgamesEvent::where('egames_reservation_id', $id)
                ->delete();
            
            if($egames_reservation->delete()){
                
                session()->flash('success_status', 'Reservation Deleted!');
                return redirect()->route('libraE.my_reservations.egames');

            }

        }else if($egames_reservation->status == 2){

            EgamesReservation::where('id', $id)
                ->update(['status' => 9]);

            $this->add_reservation_event($id, null, 9);

            session()->flash('success_status', 'Reservation Cancelled!');
            return redirect()->route('libraE.my_reservations.egames');
            
        }else{
            
            session()->flash('error_status', 'Reservation Cannot be Removed!');
            return redirect()->route('libraE.my_reservations.egames');
            
        }
    }

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
    
    
}

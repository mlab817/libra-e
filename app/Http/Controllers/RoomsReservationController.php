<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Student; 
use App\StaffCoach; 

use App\RoomsReservation; 
use App\RoomsEvent; 
use App\EgamesSetting; 

class RoomsReservationController extends Controller
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

    public function rooms_reservations()
    {
        $this->get_loggedIn_user_data();
        
        $reservations = $this->get_date_reservation(null);
        
        $all_purpose = $this->get_all_purpose();
        
        $time_schedules = $this->create_schedules('12a');
        
        $search_date = date("Y-m-d");

        return view('libraE.rooms.rooms_reservations')->with('reservations', $reservations)
            ->with('all_purpose', $all_purpose)
            ->with('time_schedules', $time_schedules)
            ->with('search_date', $search_date);    

    }

    public function get_date_reservation($search_date)
    {
        $this->check_reservations();
        
        if($search_date == null){
            
            $search_date = date('Y-m-d'); 
            
        }
        
        $reservations = RoomsReservation::WhereIn('status', [2,3,4])
            ->where('reserve_date', $search_date);

        if($reservations->count() > 0){
            
            return $reservations->get();

        }else{

            return 'No Reservation yet!';
            
        }
        
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

    public function search_date($search_date = null)
    {
        if($search_date != null){
            
            $this->get_loggedIn_user_data();
        
            $reservations = $this->get_date_reservation($search_date);

            $all_purpose = $this->get_all_purpose();

            $time_schedules = $this->create_schedules('12a');

            $check_date = $this->check_date($search_date);

            if($check_date != 'ok'){

                session()->flash('error_status', $check_date);
                
            }
            
            return view('libraE.rooms.rooms_reservations')->with('reservations', $reservations)
                ->with('time_schedules', $time_schedules)
                ->with('all_purpose', $all_purpose)
                ->with('search_date', $search_date);    

        }else{
            
            return redirect()->route('libraE.rooms.rooms_reservations');
            
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
        $this->get_loggedIn_user_data();

        $request->validate([
            'reserve_date' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'topic_description' => 'required|string',
            'no_users' => 'required|numeric',
        ]);

        $user_requests_too_many = $this->check_if_user_has_too_many_request($request->reserve_date);
        
        if($user_requests_too_many != 'ok'){
            
            session()->flash('error_status', $user_requests_too_many);
            
            return redirect()->route('libraE.reservations.rooms.search_date', $request->reserve_date);

        }

        if($request->others == 'Others'){
            
            $request->validate([
                'others_text' => 'required|string',
            ]);
            
        }
        
        $all_purpose_data = $this->get_all_purpose_data($request);

        if(count($all_purpose_data) == 0){
        
            session()->flash('error_status', 'Please indicate purpose of reservation!');
            
            return redirect()->route('libraE.reservations.rooms.search_date', $request->reserve_date);
            
        }else{

            $all_purpose_data_json = json_encode($all_purpose_data);
            
            $check_date = $this->check_date($request->reserve_date);

            if($check_date != 'ok'){

                session()->flash('error_status', $check_date);
                
                return redirect()->route('libraE.reservations.rooms.search_date', $request->reserve_date);
                
            }else{

                $select_time = date('H:i:s', strtotime($request->select_time));
                
                $check_reservation = $this->check_reservation($request->reserve_date, $request->start_time, $request->end_time);
                
                if($check_reservation != 'ok'){
                    
                    session()->flash('error_status', $check_reservation);

                    return redirect()->route('libraE.reservations.rooms.search_date', $request->reserve_date);

                }else{

                    $this->add_reservation($request, $all_purpose_data_json);

                    session()->flash('success_status', 'Reserved!');

                    return redirect()->route('libraE.my_reservations.rooms');

                }
            }
            
        }
    }

    public function check_if_user_has_too_many_request($reserve_date)
    {
        $this->get_loggedIn_user_data();

        $user_id = session()->get('loggedIn_user_id');

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

        /*
        $check_reservation = RoomsReservation::whereDate('reserve_date', $reserve_date)
            //->orWhereBetween('reserve_time_start', [$start_time, $end_time])
            //->orWhereBetween('reserve_time_end', [$start_time, $end_time])
            ->where([
                ['reserve_time_start', '<=' ,$start_time],
                ['reserve_time_end', '>=' ,$start_time],
                ['reserve_time_start', '<=' ,$end_time],
                ['reserve_time_end', '>=' ,$end_time],
            ])
            ->orWhereTime('reserve_time_start', '<=', $start_time)
            ->orWhereTime('reserve_time_end', '>=', $start_time)
            ->orWhereTime('reserve_time_start', '<=', $end_time)
            ->orWhereTime('reserve_time_end', '>=', $end_time)
            ->WhereIn('status', [2,3,4])
            ->exists();
        */

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

    public function add_reservation($request, $all_purpose_data_json)
    {
        $rooms_reservation = new RoomsReservation;
        
        $rooms_reservation->purpose = $all_purpose_data_json;
        
        $rooms_reservation->topic_description = $request->topic_description;
        
        $rooms_reservation->user_id = session()->get('loggedIn_user_id');

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

    public function add_reservation_event($rooms_reservation_id, $notes, $status)
    {
        $rooms_event = new RoomsEvent;
        
        $rooms_event->rooms_reservation_id = $rooms_reservation_id;

        $rooms_event->notes = $notes;

        $rooms_event->status = $status;

        $rooms_event->save();

    }


    
    // My Rooms Reservations

    public function my_rooms_reservation()
    {
        $this->get_loggedIn_user_data();
        
        $my_rooms_reservations = $this->get_user_reservation();

        return view('libraE.my_reservations.rooms')->with('my_rooms_reservations', $my_rooms_reservations);

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
        $this->check_session_queries('my_rooms');
        
        $my_rooms_reservation_query = $this->get_my_rooms_reservation_query();
        
        $my_rooms_reservation_query = $this->add_order_queries($my_rooms_reservation_query, 'my_rooms');
            
        return $my_rooms_reservation_query;
        
    }

    public function get_my_rooms_reservation_query()
    {
        $my_rooms_reservation_query = RoomsReservation::where('user_id', session()->get('loggedIn_user_id'));

        return $my_rooms_reservation_query;
        
    }

    public function add_order_queries($my_rooms_reservation_query, $type)
    {
        $session_get_all_status = $type . '_getAll_status';

        $session_toOrder = $type . '_toOrder';

        $session_orderBy = $type . '_orderBy';

        $session_per_page = $type . '_per_page';
        
        if(session()->has($session_get_all_status)){

            if(session()->get($session_get_all_status) != 'all'){

                $my_rooms_reservation_query = $my_rooms_reservation_query->where('rooms_reservations.status', session()->get($session_get_all_status));

            }else{
                    
                session([$session_get_all_status => 'all' ]);
            }
            
        }else{

            session([$session_get_all_status => 'all' ]);
            
        }

        $all_query = $my_rooms_reservation_query->orderBy(session()->get($session_toOrder), session()->get($session_orderBy))
            ->paginate(session()->get($session_per_page));


        if($all_query->count() > 0){
            
            return $all_query;
            
        }else{

            session()->flash('error_status', 'No Reservation/s Yet!');
            return $all_query;

        }
    }

    public function my_rooms_per_page($per_page = 10) 
    {
        $per_page_array = [5,10,20,50,100,200,500];
        
        if(in_array($per_page, $per_page_array)){
            
            session(['my_rooms_per_page' => $per_page ]);
            
        }else{

            session(['my_rooms_per_page' => 10 ]);
        }
        
        return redirect()->route('libraE.my_reservations.rooms');
        
    }

    public function my_rooms_toOrder($ToOrder = 'reserve_date') 
    {
        $to_order_array = ['topic_description', 'purpose', 'reserve_date','reserve_time_start', 'updated_at'];

        if(in_array($ToOrder, $to_order_array)){
            
            session(['my_rooms_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['my_rooms_toOrder' => 'updated_at' ]);

        }

        return redirect()->route('libraE.my_reservations.rooms');

    }

    public function my_rooms_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || $orderBy ==  'desc' ){
            
            session(['my_rooms_orderBy' => $orderBy ]);
            
        }else{
            
            session(['my_rooms_orderBy' => 'desc' ]);

        }

        return redirect()->route('libraE.my_reservations.rooms');

    }

    public function filter_my_rooms_reservations($filter = 'all') 
    {
        $filter_array = ['all',1,2,3,4,8,9];
        
        if(in_array($filter, $filter_array)){
            
            session(['my_rooms_getAll_status' => $filter ]);
            
        }else{
            
            session(['my_rooms_getAll_status' => 'all' ]);

        }

        return redirect()->route('libraE.my_reservations.rooms');

    }

    public function search_my_rooms_reservations($search = '')
    {
        $this->check_session_queries('my_rooms');

        $my_rooms_reservation_query = $this->get_my_rooms_reservation_query();
        
        $my_rooms_reservation_query->Where('rooms_reservations.reserve_date', 'like', '%'.$search.'%')
            ->orWhere('rooms_reservations.reserve_time_start', 'like', '%'.$search.'%')
            ->orWhere('rooms_reservations.reserve_time_end', 'like', '%'.$search.'%')
            ->orWhere('rooms_reservations.topic_description', 'like', '%'.$search.'%')
            ->orWhere('rooms_reservations.purpose', 'like', '%'.$search.'%');
        
        $count = $my_rooms_reservation_query->count();

        session()->flash('libraE_search', $search);
        session()->flash('libraE_search_count', $count);
        session(['my_rooms_getAll_status' => 'all' ]);

        $my_rooms_reservation_query = $this->add_order_queries($my_rooms_reservation_query, 'my_rooms');
        
        $my_rooms_reservations = $my_rooms_reservation_query;
        
        if($count <= 0){

            session()->flash('error_status', 'No Reservation/s found!');
            
        }
        
        return view('libraE.my_reservations.rooms')->with('my_rooms_reservations', $my_rooms_reservations);

    }

    public function delete_my_rooms_reservations($id)
    {
        $rooms_reservation = RoomsReservation::findOrFail($id);
        
        if($rooms_reservation->status == 1){
            
            RoomsEvent::where('rooms_reservation_id', $id)
                ->delete();
            
            if($rooms_reservation->delete()){
                
                session()->flash('success_status', 'Reservation Deleted!');
                return redirect()->route('libraE.my_reservations.rooms');

            }

        }else if($rooms_reservation->status == 2){

            RoomsReservation::where('id', $id)
                ->update(['status' => 9]);

            $this->add_reservation_event($id, null, 9);

            session()->flash('success_status', 'Reservation Cancelled!');
            return redirect()->route('libraE.my_reservations.rooms');
            
        }else{
            
            session()->flash('error_status', 'Reservation Cannot be Removed!');
            return redirect()->route('libraE.my_reservations.rooms');
            
        }
    }

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
    
    

}

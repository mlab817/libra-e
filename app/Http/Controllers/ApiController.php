<?php

namespace App\Http\Controllers;

use App\User; 
use App\Student; 
use App\StaffCoach; 

use App\Accession;
use App\NoAccession;

use App\BorrowedBook;
use App\BorrowedEvent;

use App\EgamesSlot; 
use App\EgamesReservation; 
use App\EgamesSetting; 

use App\Category;
use App\Author;
use App\Publisher;
use App\Illustration;
use App\Tag;

use App\RfidUser; 
use App\AttendanceUser; 

use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Error json return 

    public function create_json_error_return($error_type, $error_message){
         
        $error_info = array(
            "error_type" => $error_type,
            'error_message' => $error_message
        );
        
        return json_encode($error_info);
    
    }
    
    // Attendance Scanner

     public function attendance_scanner(Request $request)
     {
         $rfid_id = $request->rfid_id;
         
         $room_ref_no = $request->room_ref_no;
         
         $all_room_ref_no = $this->get_all_room_ref_no();

         if(in_array($room_ref_no,$all_room_ref_no)){
             
            $exists = RfidUser::where([
                ['rfid_id', $rfid_id],
                ['status', 1]
            ])->exists();
            
            if($exists){
                
                $rfid_user = RfidUser::where([
                    ['rfid_id', $rfid_id],
                    ['status', 1]
                ])->first();

                $user_data = $this->get_user_data($rfid_user['user_id']);
                
                if($user_data == 'un-enrolled'){

                    return $this->create_json_error_return('un-enrolled', 'User is not Enrolled Yet! Please update you account in the library!');
                    
                }else if ($user_data == 'un-employed'){
                    
                    return $this->create_json_error_return('un-employed', 'User is not Employed Yet! Please update you account in the library!');
                    
                }else{

                    $check = $this->check_user_attendance($rfid_user['id'], $room_ref_no);
                    
                    if($check == false || $check == 0){

                        $user_type = $this->get_user_type($rfid_user['user_id']);    

                        if($user_type == 3 && $room_ref_no == 4){

                            return json_encode($user_data->all());
                            
                        }
                        
                        $this->add_attendance_user($rfid_user['id'], $user_type, $room_ref_no);

                    }
                    
                    return json_encode($user_data->all());
                    
                }
                
            }else{

                return $this->create_json_error_return('rfid_id', 'Invalid RFID!');
                
            }
             
         }else{
             
            return $this->create_json_error_return('room_ref_no', 'Invalid Room Ref No!');
            
         }
     }

     public function get_all_room_ref_no()
     {
        $all_room_ref_no = [1,2,3,4];
        
        return $all_room_ref_no;
        
     }
    
     public function get_user_data($user_id)
     {
        $user = User::where('id', $user_id)->first();

        $user_type = $user['user_type'];
        
        if($user_type == 1){
            
            $student = Student::where('user_id', $user_id)->first();

            if($student['status'] == 1 || ($student['status'] == 2)){

                $student_data = Student::join('programs', 'students.program_id', 'programs.id')
                    ->join('sections', 'programs.id', 'sections.program_id')
                    ->select(
                        'students.*',
                        'programs.code AS program_code',
                        'programs.section_code AS program_section_code',
                        'programs.name AS program_name',
                        'programs.type AS program_type',
                        'sections.code AS section_no'
                    )
                    ->where('students.user_id', $user_id)->first();
                    
                $lib_card_no = "ST" . $student_data['lib_card_no'];
                    
                if($student_data['gender'] == 1){

                    $gender = "Male";

                }else if($student_data['gender'] == 2){
        
                    $gender = "Female";

                }
                
                if($student_data['program_type'] == 0){
                    
                    $user_type = "SeniorHigh";
                    
                }else if($student_data['program_type'] == 1){
                    
                    $user_type = "Tertiary";
                    
                }
                
                $pic_url = asset('storage/images/student_images/' . $student_data['pic_url']);
                
                $section = $student_data['program_section_code'] . $student_data['section_no'];
                    
                $user_collection = collect([]);

                $user_collection->push([
                    'user_type' => $user_type, 
                    'lib_card_no' => $lib_card_no, 
                    'stud_id_no' => $student_data['stud_id_no'], 
                    'f_name' => $student_data['f_name'], 
                    'm_name' => $student_data['m_name'], 
                    'l_name' => $student_data['l_name'], 
                    'gender' => $gender, 
                    'address' => $student_data['address'], 
                    'email_add' => $student_data['email_add'], 
                    'contact_no' => $student_data['contact_no'], 
                    'pic_url' => $pic_url, 
                    'program_code' => $student_data['program_code'], 
                    'section' => $section,
                    'program_name' => $student_data['program_name'],
                    'grade_year' => $student_data['grade_year'],
                    'sem' => $student_data['sem'],
                ]);
                    
                return $user_collection;

            }else{

                return 'un-enrolled';

            }

        }else if ($user_type == 2){
            
            $coach = StaffCoach::where('user_id', $user_id)->first();
            
            if($coach['status'] == 1 || ($coach['status'] == 2)){

                $coach_data = StaffCoach::join('departments', 'staff_coaches.department_id', 'departments.id')
                    ->select(
                        'staff_coaches.*',
                        'departments.name AS department_name'
                    )
                    ->where('staff_coaches.user_id', $user_id)->first();
                    
                
                $lib_card_no = "FC" . $coach_data['lib_card_no'];
                
                if($coach_data['gender'] == 1){

                    $gender = "Male";

                }else if($coach_data['gender'] == 2){
        
                    $gender = "Female";

                }

                $pic_url = asset('storage/images/staff_coach_images/' . $coach_data['pic_url']);
                
                $user_collection = collect([]);

                $user_collection->push([
                    'user_type' => 'Staff/Coach', 
                    'lib_card_no' => $lib_card_no, 
                    'emp_id_no' => $coach_data['emp_id_no'], 
                    'f_name' => $coach_data['f_name'], 
                    'm_name' => $coach_data['m_name'], 
                    'l_name' => $coach_data['l_name'], 
                    'gender' => $gender, 
                    'address' => $coach_data['address'], 
                    'email_add' => $coach_data['email_add'], 
                    'contact_no' => $coach_data['contact_no'], 
                    'pic_url' => $pic_url, 
                    'department_name' => $coach_data['department_name'], 
                    'school_year' => $coach_data['school_year'], 
                ]);
                    
                return $user_collection;

            }else{

                return 'un-employed';

            }
        }

     }

     public function check_user_attendance($rdid_user_id, $room_ref_no)
     {
        $current_date = date('Y-m-d');
         
        $check = AttendanceUser::where([
            ['rfid_users_id', $rdid_user_id],
            ['room_ref_no', $room_ref_no]
        ])
        ->whereDate('created_at', $current_date)
        ->exists();

        return $check;

     }

     public function get_user_type($user_id)
     {
        $user = User::where('id', $user_id)->first();

        if($user['user_type'] == 1){
            
            $student = Student::join('programs', 'students.program_id', '=', 'programs.id')
                ->where('user_id', $user_id)
                ->select('programs.type AS program_type')
                ->first();

            if($student['program_type'] == 0){
                
                return 1;

            }else if($student['program_type'] == 1){

                return 2;
                
            }

        }else if ($user['user_type'] == 2){
            
            return 3;
            
        }
     }
     
     public function add_attendance_user($rdid_user_id, $user_type, $room_ref_no)
     {
        $attendance = new AttendanceUser;

        $attendance->rfid_users_id = $rdid_user_id; 

        $attendance->user_type = $user_type; 

        $attendance->room_ref_no = $room_ref_no; 
        
        $attendance->status = 1; 
        
        $attendance->save();
        
     }
     
    
    // Book Reservations
    public function fetch_no_accession_availability($id)
    {
        $book = BorrowedBook::where('accession_no_id', $id)
            ->whereIn('status', [2, 3]);

        if($book->count() > 0){

            return json_decode($book->get());
            
        }else{

            return 0;
            
        }
    }

    public function try_api()
    {
        $egames_reservation = EgamesReservation::get();
        
        return json_decode($egames_reservation);
        
    }

    public function fetch_month_egames_usage()
    {
        $egames_reservation = $this->egames_month_query();
            
        if($egames_reservation->count() > 0){
            
            $egames_reservation_tertiary = $this->egames_month_query();

            $egames_reservation_shs = $this->egames_month_query();
                
            $shs = $egames_reservation_shs->where('programs.type', 0)->count();
            
            $tertiary = $egames_reservation_tertiary->where('programs.type', 1)->count();

            $egames_month_usage = [
                'shs' => $shs,
                'tertiary' => $tertiary
            ];
            
            return json_encode($egames_month_usage);
            
        }else{
            
            $error = [
                'error' => "No Data Yet!",
            ];
            
            return json_encode($error);
            
        }
    }

    public function egames_month_query()
    {
        $current_date = date('Y-m-d H:i:s'); 
        
        $month = substr($current_date,5);

        $year = substr($current_date,0, -3);
        
        $query = EgamesReservation::join('students', 'egames_reservations.user_id', 'students.user_id')
            ->join('programs', 'students.program_id', 'programs.id')
            ->select(
                'egames_reservations.*',
                'programs.type'
            )
            ->whereMonth('reserve_date', $month)
            ->whereYear('reserve_date', $year)
            ->whereIn('egames_reservations.status', [3, 4]);
        
        return $query;

    }
    
}



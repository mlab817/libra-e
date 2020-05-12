<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;

use App\Student;
use App\Program;
use App\Section;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function students_view()
    {
        $students = $this->get_all_students();
        
        $all_count = $this->get_all_count();
        
        return view('admin.accounts.students.students')->with('students', $students)
            ->with('all_count', $all_count);

    }
    
    public function get_all_count()
    {
        $enrolled = Student::where('status', 1)->count();
        
        $un_enrolled = Student::where('status', 2)->count();

        $alumni = Student::where('status', 3)->count();

        $all = Student::count();

        $all_count = [
            'un_enrolled' => $un_enrolled,
            'enrolled' => $enrolled,
            'alumni' => $alumni,
            'all' => $all
        ];

        return $all_count;
        
    }

    public function check_session_queries()
    {
        if(session()->has('students_toOrder') != true){

            session(['students_toOrder' => 'created_at' ]);
            
        }

        if(session()->has('students_orderBy') != true){

            session(['students_orderBy' => 'desc' ]);
            
        }

        if (session()->has('students_per_page') != true) {

            session(['students_per_page' => 5 ]);
            
        }
    }
    
    public function get_all_students()
    {
        $this->check_session_queries();

        $students_query = $this->get_student_query();

        if(session()->has('students_getAll')){

            if(session()->get('students_getAll') != 'all'){

                $students_query = $students_query->where('students.status', session()->get('students_getAll'));

            }else{
                    
                session(['students_getAll' => 'all' ]);
            }
            
        }else{

            session(['students_getAll' => 'all' ]);
            
        }
        
        $students = $students_query->orderBy(session()->get('students_toOrder'), session()->get('students_orderBy'))
            ->paginate(session()->get('students_per_page'));

        if($students->count() > 0){
            
            return $students;
            
        }else{
            
            session()->flash('error_status', 'No Students Yet!');
            return $students;

        }
    }

    public function students_per_page($per_page = 10) 
    {
        if($per_page == 5 || 10 || 20 || 50 || 100 || 200 || 500){
            session(['students_per_page' => $per_page ]);
        }else{
            session(['students_per_page' => 5 ]);
        }

        return redirect()->route('admin.accounts.students');

    }

    public function students_toOrder($ToOrder = 'created_at') 
    {

        if($ToOrder == 'lib_card_no' || 'stud_id_no' || 'name' || 'program_code' || 'grade_year' || 'created_at' || 'updated_at'){
            
            session(['students_toOrder' => $ToOrder ]);
            
        }else{
            
            session(['students_toOrder' => 'created_at' ]);

        }

        return redirect()->route('admin.accounts.students');

    }

    public function students_orderBy($orderBy = 'desc') 
    {

        if($orderBy == 'asc' || 'desc' ){
            
            session(['students_orderBy' => $orderBy ]);
            
        }else{
            
            session(['students_orderBy' => 'desc' ]);

        }

        return redirect()->route('admin.accounts.students');

    }

    public function filter_students($filter = 'all') 
    {
        
        if($filter == 'all' || $filter == 0 || $filter == 1 || $filter == 2 || $filter == 3){
            
            session(['students_getAll' => $filter ]);
            
        }else{
            
            session(['students_getAll' => 'all' ]);

        }

        return redirect()->route('admin.accounts.students');

    }

    public function get_student_query()
    {
        $students_query = Student::join('programs', 'students.program_id', '=', 'programs.id')
            ->join('sections', 'students.section_id', '=', 'sections.id')
            ->select(
                'students.*', 
                'students.id AS student_id', 
                'programs.code AS program_code', 
                'programs.section_code AS program_section_code', 
                'programs.type AS program_type', 
                'programs.name AS program_name', 
                'sections.code AS section_code', 
                'students.status AS student_status'
            )
            ->selectRaw('CONCAT(f_name, " " , m_name, " " , l_name) AS full_name');

        return $students_query;

    }

    public function search_student($search = '')
    {
        $this->check_session_queries();
        
        $students_query = $this->get_student_query();

        $students_query->where('students.lib_card_no', 'like', '%'.$search.'%')
            ->orWhere('students.stud_id_no', 'like', '%'.$search.'%')
            ->orWhere('students.f_name', 'like', '%'.$search.'%')
            ->orWhere('students.m_name', 'like', '%'.$search.'%')
            ->orWhere('students.l_name', 'like', '%'.$search.'%')
            ->orWhere('programs.code', 'like', '%'.$search.'%')
            ->orWhere('sections.code', 'like', '%'.$search.'%')
            ->orWhere('students.grade_year', 'like', '%'.$search.'%');
            
        $count = $students_query->count();

        session()->flash('admin_search', $search);
        session()->flash('admin_search_count', $count);
        session(['students_getAll' => 'all' ]);
        
        if($count > 0){

            $students = $students_query->orderBy(session()->get('students_toOrder'), session()->get('students_orderBy'))
                ->paginate(session()->get('students_per_page'));

            $all_count = $this->get_all_count();
    
            return view('admin.accounts.students.students')->with('students', $students)
                ->with('all_count', $all_count);

        }else{
            
            session()->flash('error_status', 'No data found!');
            
            $students = $students_query->paginate(session()->get('students_per_page'));
            
            $all_count = $this->get_all_count();
        
            return view('admin.accounts.students.students')->with('students', $students)
                ->with('all_count', $all_count);
            
        }
    }


    public function view_student($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $student = $this->get_student_data($id);
            
            if($student){
                
                return view('admin.accounts.students.view_student')->with('student', $student);
                
            }else{
                
                return redirect()->route('admin.accounts.students');
                
            }
            
        }else{
            
            return redirect()->route('admin.accounts.students');

        }
    }
    
    public function add_student_view()
    {
        $file_maintenance = $this->get_file_maintenance();
        return view('admin.accounts.students.add_student')->with('file_maintenance', $file_maintenance);
    }

    public function get_file_maintenance()
    {
        $last_lib_card_no = $this->get_last_lib_card_no();
        
        $new_lib_card_no = $this->to_string_add_lib_card_no($last_lib_card_no);

        $programs = Program::orderBy('code', 'asc')->get();

        $sections = Section::orderBy('code', 'asc')->get();
        
        return $file_maintenance = [
            'new_lib_card_no' => $new_lib_card_no,
            'programs' => $programs,
            'sections' => $sections
        ];

    }

    public function to_string_add_lib_card_no($student_no)
    {
        $add = true;
        
        while($add){
            
            $student_no = (string)$student_no;

            $student_no++;
            
            $exist = Student::where('lib_card_no', $student_no)->exists();
            
            if($exist != true){
                
                $add = false;
                
            }
            
        }

        return $this->to_string_lib_card_no($student_no);
        
    }

    public function to_string_lib_card_no($student_no)
    {
        $num = $student_no;
        $num = (string)$num;
        $num_length = strlen($num);


        for($i = $num_length; $i < 6; $i++){
            $num = "0" . $num;
        }

        return $num;

    }

    public function get_last_lib_card_no()
    {
        $count = Student::count();
        
        if($count == 0){
            
            return 0;

        }else{
            
            $last_student_no = Student::orderBy('lib_card_no', 'desc')->select('lib_card_no')->first();
            return $last_student_no->lib_card_no;
            
        }
    }

    public function get_data_section($program_id = 0)
    {
        if($program_id > 0){

            $program = Program::select('section_code')->where('id', $program_id)->first();

            $sections = Section::where([
                ['program_id', $program_id],
                ['status', 1]
            ]);

            if($sections->count() > 0){
                
                echo '<option value="">---Select Section---</option>';
                
                foreach ($sections->get() as $section) {
                    
                    echo '<option value="'.$section->id.'">'.$program['section_code'].$section->code.'</option>';
                    
                }
                
            }else{
                
                echo '<option value="">---No Sections Yet!---</option>';
                
            }
            
        }else{
            
            echo '<option value="">---Select Program First---</option>';
            
        }
    }

    public function get_data_grade_year($program_id = 0)
    {
        if($program_id > 0){

            $program = Program::select('type')->where('id', $program_id)->first();

            if($program['type'] == 1){
                
                echo '<option value="">---Select Year---</option>';
                
                echo '
                    <option value="1">
                        1st Year
                    </option>
                    <option value="2">
                        2nd Year
                    </option>
                    <option value="3">
                        3rd Year
                    </option>
                    <option value="4">
                        4th Year
                    </option>
                ';
                
            }else if($program['type'] == 0){
                
                echo '<option value="">---Select Grade---</option>';

                echo '
                    <option value="11">
                        Grade 11 
                    </option>
                    <option value="12">
                        Grade 12
                    </option>
                ';

            }else{

                echo '<option value="">---Select Program First---</option>';
                
            }
            
        }else{
            
            echo '<option value="">---Select Program First---</option>';
            
        }
    }

    public function get_data_sem($program_id = 0)
    {
        if($program_id > 0){

            $program = Program::select('type')->where('id', $program_id)->first();

            if($program['type'] == 1){
                
                return 1;
                
            }else if($program['type'] == 0){
                
                return 0;
                
            }else{

               return 0;
                
            }
            
        }else{
            
            return 0;
            
        }
    }

    public function store_student(Request $request)
    {
        //return $request->all();
        
        if($request->isMethod('put')){
            
            $request->validate([
                'lib_card_no' => 'required|numeric',
                'stud_id_no' => 'required|digits:11',
                'pic_file' => 'nullable|image|mimes:jpeg,bmp,png',
                'email_add' => 'required',
                'section' => 'nullable|numeric',
                'grade_year' => 'nullable|numeric'
            ]);
            
        }else{
            
            $request->validate([
                'lib_card_no' => 'required|unique:students,stud_id_no',
                'stud_id_no' => 'required|unique:students,stud_id_no|digits:11',
                'email_add' => 'required|unique:users,email',
                'pic_file' => 'required|image|mimes:jpeg,bmp,png',
                'section' => 'required|numeric',
                'grade_year' => 'required|numeric'
            ]);
            
        }
        
        $request->validate([
            'f_name' => 'required|regex:/^[a-z ñ\-]+$/i',
            'm_name' => 'required|regex:/^[a-z ñ\-]+$/i',
            'l_name' => 'required|regex:/^[a-z ñ\-]+$/i',
            'gender' => 'required|numeric',
            'address' => 'required',
            'contact_no' => 'required|digits:10',
            'program' => 'required|numeric',
            'school_year' => 'required|digits:4',
            'status' => 'required|numeric'
        ]);
        
        $student_old_program = Student::select('program_id')->where('id', $request->id)->first();

        if($request->isMethod('put')){
            
            if($student_old_program['program_id'] != $request->program){

                $request->validate([
                    'section' => 'required|numeric',
                    'grade_year' => 'required|numeric'
                ]);
            }
        }
        

        $program = Program::select('type')->where('id', $request->program)->first();

        if($program['type'] == 1){
            
            $request->validate([
                'sem' => 'required|numeric',
            ]);
            
        }

        if($request->isMethod('put')){

            $stud_id_exist = $this->check_student_id_exist($request->id, $request->stud_id_no);
    
            if($stud_id_exist){
                
                session()->flash('error_status', 'Student ID No Already Exist!');
                return redirect()->route('admin.accounts.edit_student', [$request->id]);
    
            }
            
            $student_email = $request->email_add;
            
            $email_exists = $this->check_user_email_exist($request->id, $student_email);

            if($email_exists){
                
                session()->flash('error_status', 'Student Email Already Exist!');
                return redirect()->route('admin.accounts.edit_student', [$request->id]);
    
            }
        }
        

        $student = $request->isMethod('put') ? Student::findOrFail($request->id) : new Student;
        
        $student->lib_card_no = $request->lib_card_no;

        $student->stud_id_no = $request->stud_id_no;

        $student->f_name = ltrim(ucfirst($request->f_name));

        $student->m_name = ltrim(ucfirst($request->m_name));

        $student->l_name = ltrim(ucfirst($request->l_name));

        $student->gender = $request->gender;

        $student->address = ltrim(ucfirst($request->address));

        $student_email = $request->email_add;
        
        if($request->isMethod('put')){
            
            $student->email_add = $student_email;
            
            $user = User::where([
                ['user_ref_id', $request->id],
                ['user_type', 1]
            ])->update(['email' => $student_email]);
            
        }else{
            
            $student->email_add = $student_email;
            
        }

        $student->contact_no = $request->contact_no;

        // Handle File Upload
        if($request->hasFile('pic_file')){
            // Get Filename with the extension
            $filenameWithExt = $request->file('pic_file')->getClientOriginalName();
            // Get Just Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get Just Ext
            $extension = $request->file('pic_file')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('pic_file')->storeAs('public/images/student_images', $fileNameToStore);
        }else{
            
            if($request->isMethod('put')){

                $fileNameToStore = $request->pic_name;

            }else{

                $fileNameToStore = 'noimage.png';
                
            }
        }

        $student->pic_url = $fileNameToStore; 

        $student->program_id = $request->program;

        if($request->isMethod('put')){
            
            if($request->section != null){

                $student->section_id = $request->section;
                
            } 

            if($request->grade_year != null){

                $student->grade_year = $request->grade_year;
                
            } 
            
        }else{
            
            $student->section_id = $request->section;
            
            $student->grade_year = $request->grade_year;

        }

        $student->school_year = $request->school_year;

        if($program['type'] == 1){

            $student->sem = $request->sem;
            
        }else{
            
            $student->sem = 0;
            
        }
        
        $student->status = $request->status;

        $student->save();

        $inserted_id = $student->id;

        if($request->isMethod('put')){
            
            $request->session()->flash('success_status', 'Student Updated!');
            
        }else{
            
            $this->add_student_to_users($inserted_id, $student_email);
            $request->session()->flash('success_status', 'Student Added!');

        }
            
        return redirect()->route('admin.accounts.students');
        
    }

    public function check_student_id_exist($student_id, $stud_id_no)
    {
        $same_student = Student::where([
            ['id', $student_id],
            ['stud_id_no', $stud_id_no]
        ])->exists();
        
        if($same_student){
            
            return false;
            
        }else{
            
            $existing_student = Student::where('stud_id_no', $stud_id_no)->exists();

            if($existing_student){
                
                return true;

            }else{
                
                return false;
                
            }
        }
    }

    public function check_user_email_exist($user_ref_id, $email)
    {
        $same_student = Student::where([
            ['id', $user_ref_id],
            ['email_add', $email]
        ])->exists();
        
        if($same_student){
            
            return false;
            
        }else{
            
            $existing_email = User::where('email', $email)->exists();

            if($existing_email){
                
                return true;

            }else{
                
                return false;
                
            }
        }
    }

    public function edit_student_view($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            
            $student = $this->get_student_data($id);
            
            if($student){

                $file_maintenance = $this->get_file_maintenance();
                
                return view('admin.accounts.students.edit_student')->with('student', $student)
                    ->with('file_maintenance', $file_maintenance);

            }else{
                
                return redirect()->route('admin.accounts.students');

            }
            
        }else{
            
            return redirect()->route('admin.accounts.students');

        }
    }

    public function get_student_data($student_id)
    {

        $exist = Student::where('students.id', $student_id)->exists();

        if($exist == false){
            return 0;
        }
        
        $student_query = $this->get_student_query();
        
        $student = $student_query->where('students.id', $student_id)->first();
            
        return $student;
                
    }

    public function add_student_to_users($student_id, $student_email)
    {
        $user = new User;
        
        $user->email = $student_email;
        
        $user->password = Hash::make("pass1234");

        $user->user_ref_id = $student_id;

        $user->user_type = 1;

        $user->save();

        $inserted_id = $user->id;

        Student::where('id', $student_id)->update(['user_id' => $inserted_id]);
        
    }

    public function import_excell_students(Request $request) 
    {
        $request->validate([
            'excell_students' => 'required|file',
        ]);

        $extension = $request->file('excell_students')->getClientOriginalExtension();
        
        if($extension == 'xlsx' || $extension == 'csv'){
            
            Excel::import(new studentImport, request()->file('excell_students'));

        }else{
            
            session()->flash('error_status', 'Invalid Input! Must be xlsx or csv file only!');

        }
        
        session()->flash('success_status', 'Student Added!');

        return redirect()->route('admin.accounts.students');

    }

    public function delete_student($id)
    {
        $student = Student::findOrFail($id);

        if($student->delete()){
            
            session()->flash('success_status', 'Student Deleted!');
            
            return redirect()->route('admin.accounts.students');

        }
    }
}

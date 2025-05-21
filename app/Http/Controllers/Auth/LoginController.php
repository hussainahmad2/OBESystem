<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Models\Faculty;
use App\Models\Role;
use \App\Models\Stu;
use \App\Models\User;
use \App\Models\Course;
use \App\Models\CourseAllocation;
use \App\Models\CourseRegistration;


class LoginController extends Controller
{
    public function showAdminLoginForm(){
        return view('auth.admin_login');
    }
    public function showStudentLoginForm(){
        return view('auth.student_login');
    }
    public function showFacultyLoginForm(){
        return view('auth.faculty_login');      
    }

    public function showhomepage(){
        return view('welcome');      
    }

    public function showQecLoginForm(){
        return view('auth.qec_login');
    }

     // Validate the admin request
    public function adminAuthenticate(Request $request)
    {
        // Validate the incoming request
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        // Attempt login with credentials
        if (Auth::attempt($data)) {
            // Redirect based on user role
            if (Auth::user()->role_id === 1) {  
                return redirect()->route('admin.dashboard'); 
            } elseif (Auth::user()->role_id === 4) {
                return redirect()->route('Qualityenhancementcell.dashboard');
            }else {
                return back()->withErrors(['password' => 'Access Denied. Admins only!']);
            }
        } else {
            // If authentication fails, return back with an error
            return back()->withErrors(['password' => 'Incorrect email or password.']);
        }
    }

    // Validate the faculty request
    public function facultyAuthenticate(Request $request)
    {
        // Validate the input data
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'designation' => 'required'
        ]);
        // dd($request->all());
        // Attempt login using only email and password
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            
            $faculty = Auth::user(); // Get authenticated user

            // Check if user exists in the faculty table
            $facultydata = Faculty::where('user_id', $faculty->id)->first();

            if (!$facultydata) {
                Auth::logout();
                return back()->withErrors(['designation' => 'No faculty record found. Contact admin!']);
            }
            
            // Verify if the selected designation matches the one in the database
            // if ($facultydata->designation !== $data['designation']) {
            //     Auth::logout();
            //     return back()->withErrors(['designation' => 'Incorrect designation selected. Please try again!']);
            // }

            if ($faculty) {
                return redirect()->route('lecturar.dashboard'); 
            } else {
                Auth::logout();
                return back()->withErrors(['password' => 'Access Denied. Unauthorized User!']);
            }
            
            // Redirect based on role_id
            // if ($faculty->role_id === 2) {
            //     return redirect()->route('lab_engineer.dashboard'); 
            // } elseif ($faculty->role_id === 5) {
            //     return redirect()->route('lecturer.dashboard'); 
            // } elseif ($faculty->role_id === 6) {
            //     return redirect()->route('assistant_professor.dashboard'); 
            // } elseif ($faculty->role_id === 7) {
            //     return redirect()->route('associate_professor.dashboard'); 
            // } elseif ($faculty->role_id === 8) {
            //     return redirect()->route('professor.dashboard'); 
            // } else {
            //     Auth::logout();
            //     return back()->withErrors(['password' => 'Access Denied. Unauthorized User!']);
            // }
        } else {
            // If authentication fails, return back with an error
            return back()->withErrors(['password' => 'Incorrect email or password.']);
        }
    }




    public function studentAuthenticate(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'rollno'     => 'required|string',
            'batch'      => 'required|string',
            'department' => 'required|string',
            'password'   => 'required'
        ]);

        // Find student by Roll Number
        $student = Stu::where('roll_number', $data['rollno'])->first();

        // Check if student exists
        if (!$student) {
            return back()->withErrors(['rollno' => 'Roll Number not found.']);
        }

        // Fetch user associated with student
        $user = User::find($student->user_id);

        // Verify password
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        // 🔹 Convert both to uppercase for consistent comparison
        if (strtoupper(trim($student->batch)) !== strtoupper(trim($data['batch']))) {
            return back()->withErrors(['batch' => 'Incorrect batch selected.']);
        }

        if (strtoupper(trim($student->department)) !== strtoupper(trim($data['department']))) {
            return back()->withErrors(['department' => 'Incorrect department selected.']);
        }
        // Login user
        Auth::login($user);

        return redirect()->route('student.dashboard');
    }




    public function adminDashboard(){
        return view('admin.dashboard');    
    }
 
    public function studentDashboard(){
        $user = Auth::user()->student;
        $coreuser = Auth::user();
        // dd($coreuser);
        
        $courses = CourseAllocation::with(['faculty.user', 'course'])
        ->where('batch', $user->batch)
        ->where('section', $user->section)
        ->get();

        // dd($courses);



        // $courseRegistrations = CourseRegistration::first();

        // $student = User::where('id' , $courseRegistrations->student_id)->get();
        // $teacher = User::where('id' , $courseRegistrations->teacher_id)->get();
        // $CourseAllocation = CourseAllocation::with(['faculty' ,'Course'])->where('id' , $courseRegistrations->cource_allocation_id)->get();
        // $course = Course::where('id' , $courseRegistrations->course_id)->get();

        $courseRegistration = CourseRegistration::with([
            'student',               // Fetch student information (User)
            'teacher',               // Fetch teacher information (User)
            'course',                // Fetch course details
            'CourseAllocation.faculty',  // Fetch course allocation and related faculty with user details
            'courseAllocation.Course'    // Fetch course allocation and related faculty with user details
        ])->where('student_id' , $coreuser->id)->get();
        

            // dd($courseRegistration);


    
        // dd($courses);
        return view('student.dashboard', compact('courses' , 'user' , 'coreuser' , 'courseRegistration'));   
    }
    public function QualityenhancementcellDashboard(){
        return view('Qualityenhancementcell.dashboard');      
    }
    // public function lab_engineerDashboard(){
    //     return view('lab_engineer.dashboard');      
    // }
    // public function lecturerDashboard(){
    //     return view('lecturer.dashboard');      
    // }

    // public function associate_professorDashboard(){
    //     return view('associate_professor.dashboard');      
    // }
    // public function professorDashboard(){
    //     return view('professor.dashboard');      
    // }

    // public function logout(Request $request)
    // {
    //     Auth::logout(); // Logs out the user

    //     $request->session()->invalidate(); // Invalidate session
    //     $request->session()->regenerateToken(); // Regenerate CSRF token

    //     return redirect()->route('showAdminLoginForm'); // Redirect to login page
    // }
    
    public function qecAuthenticate(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt login with credentials
        if (Auth::attempt($data)) {
            if (Auth::user()->role_id === 4) {
                return redirect()->route('Qualityenhancementcell.dashboard');
            } else {
                Auth::logout();
                return back()->with('error', 'Access Denied. QEC only!');
            }
        } else {
            return back()->with('error', 'Incorrect email or password.');
        }
    }
}

<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Models\Faculty;
use App\Models\Role;
use \App\Models\Stu;
use \App\Models\Course;
use \App\Models\course_content_point;
use \App\Models\course_content;
use \App\Models\course_outcome;
use \App\Models\course_detail;
use \App\Models\practical_outcome;
use \App\Models\CourseAllocation;
use \App\Models\AdvisorClassAssignment;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
 
    public function courselist(){   
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;
        $courses = Course::whereIn('semester', [1])->get();
        // dd($courses);
        return view('lecturar.program_manager.course_list' , compact('primaryTasks', 'duties', 'dutyTasks' ,'designation' , 'courses' ));      
    }

    public function courselist_detail($id){
        
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;
        $courses_detail = Course::with(  'course_detail','course_outcome','course_content','course_content_point','practical_outcome')->where('id', $id)->first();
        // dd($courses_detail);
        return view('lecturar.program_manager.course_detail' , compact('primaryTasks', 'duties', 'dutyTasks' ,'designation' ,'courses_detail'));      
    }

    public function getCoursesBySemester(Request $request) {
        $selectedSemesters = $request->input('semesters', []);
        
        $courses = empty($selectedSemesters) 
            ? Course::all() 
            : Course::whereIn('semester', $selectedSemesters)->get();
    
            // dd($courses);
        return response()->json($courses); // Direct return, no data wrapper
    }

    public function facultylist(){
        
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $facultylist = Faculty::with('user')->get();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;   
        return view('lecturar.program_manager.faculty' , compact('primaryTasks', 'duties', 'dutyTasks' ,'designation'  , 'facultylist' ));      
    }

    public function courseallocate()
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $facultylist = Faculty::all();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;
        $courses = Course::all();
        $faculties = Faculty::with('user')->get();

        return view('lecturar.program_manager.assign_faculty', compact('courses', 'faculties' , 'primaryTasks', 'duties', 'dutyTasks' ,'designation' ,'facultylist'));
    }

    public function Assignadvisor()
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $facultylist = Faculty::all();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;
        $courses = Course::all();

        // Step 1: Get all advisor_ids from AdvisorClassAssignment
        $assignedAdvisorIds = AdvisorClassAssignment::pluck('advisor_id');

        // Step 2: Get faculties with duty 11, and exclude the ones already assigned
        $faculties = Faculty::with('user')
            ->where('duties', 'like', '%\\\"11\\\"%')  // Escaped JSON string match
            ->whereNotIn('user_id', $assignedAdvisorIds)   // Filter out already assigned
            ->get();

            // dd($assignedAdvisorIds);
        // $faculties = Faculty::with('user')
        // ->where('duties', 'like', '%\\\"11\\\"%')
        // ->get();


        return view('lecturar.program_manager.AssignCourceAdvisor', compact('courses', 'faculties' , 'primaryTasks', 'duties', 'dutyTasks' ,'designation' ,'facultylist'));
    }


    public function courseAllocateStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'faculty_id' => 'required|exists:faculties,id',
            'batch' => 'required|string',
            'section' => 'required|string'
        ]);

        // Check if already assigned
        $exists = \App\Models\CourseAllocation::where('course_id', $request->course_id)
            ->where('batch', $request->batch)
            ->where('section', $request->section)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This course is already assigned to a faculty for the selected batch and section. Please choose another course or batch.');
        }

        \App\Models\CourseAllocation::create([
            'course_id' => $request->course_id,
            'faculty_id' => $request->faculty_id,
            'batch' => $request->batch,
            'section' => $request->section,
        ]);

        return redirect()->back()->with('success', 'Course allocated successfully.');
    }
   
   
    public function Assignadvisorstore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'faculty_id' => 'required',
            'batch' => 'required|string',
            'section' => 'required|string'
        ]);

        AdvisorClassAssignment::create([
            'advisor_id' => $request->faculty_id,
            'batch' => $request->batch,
            'section' => $request->section,
        ]);

        return redirect()->back()->with('success', 'Course allocated successfully.');
    }


    public function create()
    {

        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $facultylist = Faculty::all();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;
        $courses = Course::all();
        $faculties = Faculty::with('user')->get();
        return view('lecturar.program_manager.create_course' , compact('courses', 'faculties' , 'primaryTasks', 'duties', 'dutyTasks' ,'designation' ,'facultylist'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'semester' => 'required|string|max:50',
            'batch' => 'required|string|max:50',
            'section' => 'required|string|max:10',
        ]);

        

        Course::create([
            'name' => $request->name,
            'code' => $request->code,
            'semester' => $request->semester,
            'pre_req' => $request->pre_req,
            'Credit_Hours' => $request->Credit_Hours,
            'Status' => $request->Status,
        ]);

        return redirect()->back()->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;
        $course = Course::findOrFail($id);
        $courses = Course::all();
        return view('lecturar.program_manager.edit_course', compact('primaryTasks', 'duties', 'dutyTasks', 'designation', 'course', 'courses'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $course = Course::findOrFail($id);
            
            // Delete related records first
            $course->course_detail()->delete();
            $course->course_outcome()->delete();
            $course->course_content()->delete();
            $course->course_content_point()->delete();
            $course->practical_outcome()->delete();
            $course->courseAllocations()->delete();
            
            // Delete the course
            $course->delete();
            
            DB::commit();
            
            if(request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Course deleted successfully!'
                ]);
            }
            
            return redirect()->route('course.list')->with('success', 'Course deleted successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            if(request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting course: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('course.list')->with('error', 'Error deleting course: ' . $e->getMessage());
        }
    }

    public function setCourseSession($course_id)
    {
        session(['course_id' => $course_id]);
        return redirect()->back();
    }

    public function delete_student_marks($id)
    {
        $assessment = \App\Models\assessment::findOrFail($id);
        $assessment->delete();
        return back()->with('success', 'Assessment deleted successfully.');
    }
}
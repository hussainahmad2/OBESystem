<?php

// namespace App\Http\Controllers\Faculty;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use \App\Models\Faculty;
// use App\Models\Role;


// use PhpOffice\PhpWord\PhpWord;
// use PhpOffice\PhpWord\IOFactory;
// use PhpOffice\PhpWord\Shared\Converter;


// use \App\Models\Course;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Response;
// use \App\Models\CourseAllocation;
// use \App\Models\CourseRegistration;
// use \App\Models\assessment;
// use \App\Models\assessment_clo_detail;
// use App\Models\User;
// use App\Models\LabAssessmentRubricDetail;
// use App\Models\LabAssessment;



// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Style\Fill;
// use PhpOffice\PhpSpreadsheet\Style\Alignment;


namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Models\Faculty;
use App\Models\Role;


use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;


use \App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \App\Models\CourseAllocation;
use \App\Models\CourseRegistration;
use \App\Models\assessment;
use \App\Models\assessment_clo_detail;
use App\Models\User;
use App\Models\LabAssessmentRubricDetail;
use App\Models\LabAssessment;
use App\Models\AssessmentQuestion;



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class CourseAllocationController extends Controller
{

    public function SingleStuCources($id)
    {


        $user = Auth::user();
        $courses = Course::all();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;

        
        $ProgramCourseAllocations = CourseAllocation::with(['course', 'faculty'])->get();

        // dd($request->all());
        $CourseRegistration = CourseRegistration::with([
            'studentDetails',
            'student',
            'teacher',
            'course',
            'CourseAllocation.faculty',
            'courseAllocation.Course'
        ])->where('student_id' , $id )->get();

        // dd($ProgramCourseAllocations);

        return view('lecturar.advisor.single_stu_cources' , compact('primaryTasks', 'duties', 'dutyTasks' ,'designation' ,'courses' ,'ProgramCourseAllocations' ,'CourseRegistration')); 
    }

    public function plo_counseling($id)
    {
       $user = Auth::user();
        $courses = Course::all();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;

        
        $ProgramCourseAllocations = CourseAllocation::with(['course', 'faculty'])->get();

        // dd($request->all());
        $CourseRegistration = CourseRegistration::with([
            'studentDetails',
            'student',
            'teacher',
            'course',
            'CourseAllocation.faculty',
            'courseAllocation.Course'
        ])->where('student_id' , $id )->get();

        // dd($CourseRegistration);

        return view('lecturar.advisor.plo_counseling' , compact('primaryTasks', 'duties', 'dutyTasks' ,'designation' ,'courses' ,'CourseRegistration')); 
    }
    public function updateStatus(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'status' => 'required|string|in:approved,rejected,pending' // Update status options as needed
        ]);

        // Find the course registration by ID
        $courseRegistration = CourseRegistration::findOrFail($id);

        // Update the status field
        $courseRegistration->status = ucfirst($validated['status']); // Ensure the status is capitalized
        $courseRegistration->save(); // Save the changes

        // Return a JSON response
        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully.',
            'new_status' => $courseRegistration->status // Returning the updated status
        ]);
    }

    public function AllRegisteredStudent($id)
    {
        $course_id = $id;
        $user = Auth::user();
        $faculty_id = $user->id;
        $courses = Course::all();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;
        
        $ProgramCourseAllocations = CourseAllocation::with(['course', 'faculty'])->get();

        $CourseRegistration = CourseRegistration::with([
            'studentDetails',
            'student',
            'teacher',
            'course',
            'CourseAllocation.faculty',
            'courseAllocation.Course'
        ])->where('course_id', $course_id)
          ->where('teacher_id', $faculty_id)
          ->where('status', 'approved')
          ->get();

        return view('lecturar.All_registered_student', compact(
            'primaryTasks',
            'duties',
            'dutyTasks',
            'designation',
            'courses',
            'ProgramCourseAllocations',
            'CourseRegistration'
        ));
    }

    public function student_marks($id)
    {
        $user = Auth::user();
        $faculty_id = $user->id;
        $courses = Course::all();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;
        $ProgramCourseAllocations = CourseAllocation::with(['course', 'faculty'])->get();
        
        // Load assessments with their marks for the student
        $marksDetail = assessment::with('marks')
            ->where('student_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('lecturar.show_student_marks', compact(
            'primaryTasks',
            'id',
            'duties',
            'dutyTasks',
            'designation',
            'courses',
            'ProgramCourseAllocations',
            'marksDetail'
        )); 
    }

    public function add_student_marks($id)
    {
        // $course_id = $id;
        $course_id = $id;
        $user = Auth::user();
        $faculty_id =  $user->id;
        $courses = Course::all();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;

        
        $ProgramCourseAllocations = CourseAllocation::with(['course', 'faculty'])->get();

        // dd($request->all());
        $CourseRegistration = CourseRegistration::with([
            'studentDetails',
            'student',
            'teacher',
            'course',
            'CourseAllocation.faculty',
            'courseAllocation.Course'
        ])->where('course_id' , $course_id)->where('teacher_id',$faculty_id)->where('status' , 'approved')->get();

        // Fetch CLOs for the course from course_outcome
        $courseCLOs = \App\Models\course_outcome::where('course_id', $course_id)->get(['clo', 'description']);

        return view('lecturar.add_marks_copy' , compact('CourseRegistration' ,'primaryTasks','id', 'duties', 'dutyTasks' ,'designation' ,'courses' ,'ProgramCourseAllocations', 'courseCLOs')); 
    }
    public function add_studentlab_marks($id)
    {
        // $course_id = $id;
        $course_id = $id;
        $user = Auth::user();
        $faculty_id =  $user->id;
        $courses = Course::all();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        $primaryTasks = Role::find($user->role_id)->tasks;
        $dutyTasks = $duties->flatMap->tasks;

        
        $ProgramCourseAllocations = CourseAllocation::with(['course', 'faculty'])->get();

        // dd($request->all());
        $CourseRegistration = CourseRegistration::with([
            'studentDetails',
            'student',
            'teacher',
            'course',
            'CourseAllocation.faculty',
            'courseAllocation.Course'
        ])->where('course_id' , $course_id)->where('teacher_id',$faculty_id)->where('status' , 'approved')->get();

     
        return view('lecturar.add_marks_lab' , compact( 'CourseRegistration' ,'primaryTasks','id', 'duties', 'dutyTasks' ,'designation' ,'courses' ,'ProgramCourseAllocations')); 
    }

    public function store_student_marks(Request $request)
    {
        try {
            // Validate required fields
            $request->validate([
                'course_id' => 'required|integer',
                'type' => 'required|string',
                'title' => 'required|string',
                'student_ids' => 'required|array|min:1',
                'student_ids.*' => 'required|integer',
                'questions' => 'required|array|min:1',
                'questions.*.title' => 'required|string',
                'questions.*.clos' => 'required|array|min:1',
                'questions.*.clos.*' => 'required',
                'questions.*.total_marks' => 'required|numeric|min:0',
                'obtained_marks_single' => 'required|array',
                'obtained_marks_single.*' => 'required|numeric|min:0'
            ], [
                'questions.required' => 'At least one question is required',
                'questions.*.title.required' => 'Question title is required',
                'questions.*.clos.required' => 'At least one CLO must be selected',
                'questions.*.total_marks.required' => 'Total marks are required',
                'questions.*.total_marks.min' => 'Total marks cannot be negative',
                'obtained_marks_single.required' => 'Marks for all students are required',
                'obtained_marks_single.*.required' => 'Marks for each student are required',
                'obtained_marks_single.*.min' => 'Marks cannot be negative'
            ]);

            DB::beginTransaction();

            // Create assessment
            $assessment = assessment::create([
                'course_id' => $request->course_id,
                'teacher_id' => Auth::id(),
                'student_id' => $request->student_ids[0],
                'type' => $request->type,
                'assessment_title' => $request->title
            ]);

            // Save questions and CLOs
            foreach ($request->questions as $question) {
                foreach ($question['clos'] as $clo) {
                    assessment_clo_detail::create([
                        'assessment_id' => $assessment->id,
                        'question_title' => $question['title'],
                        'clo_number' => $clo,
                        'total_marks' => $question['total_marks'],
                        'obtained_marks' => $request->obtained_marks_single[$request->student_ids[0]] ?? 0
                    ]);
                }
            }

            DB::commit();

            // Get the student ID from the request
            $studentId = $request->student_ids[0];

            // Redirect to view marks with success message
            return redirect()
                ->route('student.marks', ['id' => $studentId])
                ->with('success', 'Marks saved successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'An error occurred while saving marks: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function store_student_marks_lab(Request $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->student_ids as $studentId) {
                // Create lab assessment
                $labAssessment = LabAssessment::create([
                    'course_id' => $request->course_id,
                    'teacher_id' => Auth::id(),
                    'student_id' => $studentId,
                    'type' => $request->type,
                    'assessment_title' => $request->title,
                ]);

                // Create rubric detail
                LabAssessmentRubricDetail::create([
                    'lab_assessment_id' => $labAssessment->id,
                    'rubric_number' => $request->R1_number_single,
                    'total_marks' => $request->total_marks_single,
                    'obtained_marks' => $request->obtained_marks_single[$studentId],
                ]);
            }

            DB::commit();
            return redirect()->route('courses.AllRegisteredStudent', $request->course_id)
                ->with('success', 'Lab assessment saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save lab assessment. Error: '.$e->getMessage())
                ->withInput();
        }
    }
    
    
        public function setCourseSession($course_id)
        {
            // Clear any existing course_id
            Session::forget('course_id');

            // Store the new course_id
            Session::put('course_id', $course_id);

            // Redirect to the page that shows registered students
            return redirect()->route('courses.AllRegisteredStudent' ,  $course_id);
        }
        public function delete_student_marks($id)
        {
             // Use a transaction to ensure data integrity
            DB::beginTransaction();
            try {
                // Step 1: Delete CLO details
                assessment_clo_detail::where('assessment_id', $id)->delete();

                // Step 2: Delete the assessment
                $assessment = assessment::findOrFail($id);
                $assessment->delete();
                DB::commit();
                return redirect()->back()->with('success', 'Assessment deleted successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Error deleting assessment: ' . $e->getMessage());
            }
        }



        public function exportToExcel(Request $request) {
            // ... [Your existing data fetching code] ...

          

            $greenStyle = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF00FF00'] // Green
                ]
            ];
            $PLO = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'd4d9dc'] // Green
                ]
            ];
            $FinaltotalColor = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'b28c14'] // blue for assignment
                ]
            ];
            $assignmentColor = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'b8cce4'] // blue for assignment
                ]
            ];
            $quizColor = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'd7e4bc'] // Greenish  for quiz
                ]
            ];
            $MidColor = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'fac090'] // skin for Mid 
                ]
            ];
            $finalColor = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'ccc0da'] // purpul for final 
                ]
            ];



            $CLO1Color = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'ffff99'] // CLO1
                ]
            ];
            $CLO2Color = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'ff65d7'] // CLO2
                ]
            ];
            $CLO3Color = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'b6dde8'] // CLO3
                ]
            ];
            $CLO4Color = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '99ff66'] // CLO4
                ]
            ];
        
            $course_id = $request->course_id;
            $faculty_id = $request->faculty_id;

            // Fetch related data
            $assessments = Assessment::with(['marks', 'student' ,'studentdetail'])
                ->where('course_id', $course_id)
                ->where('teacher_id', $faculty_id)
                ->get();




            $studentsData = assessment_clo_detail::with(['assessment.student', 'assessment.studentdetail'])
            ->whereHas('assessment', function($query) use ($course_id, $faculty_id) {
                $query->where('course_id', $course_id)
                        ->where('teacher_id', $faculty_id);
            })
            ->get();

            $batchstudent = ($studentsData->first());
                
                // dd($assessments);
                $course = Course::find($course_id);
                $faculty = User::find($faculty_id);
                // dd($faculty);
                $courseName = $course->name ?? 'empty';
                $facultyName = $faculty->name ?? 'empty';
                $batch = $batchstudent->assessment->studentdetail->batch ?? 'empty';
                $section = $batchstudent->assessment->studentdetail->section ?? 'empty';
                // dd($section , $batch);
                // Group assessments by student
                $groupedAssessments = $assessments->groupBy('student_id');
            // Create a new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
        
            // Merge cells for Institute Name (Row 1)
            $sheet->mergeCells('A1:AD1'); // Merges 29 columns (A to AD)
            $sheet->setCellValue('A1', 'Foundation University Islamabad, Rawalpindi Campus');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
            // Merge cells for Department (Row 2)
            $sheet->mergeCells('A2:AD2');
            $sheet->setCellValue('A2', 'Department of Engineering Technology');
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
            // Merge cells for Course Info (Row 3)
            $sheet->mergeCells('A3:AD3');
            $sheet->setCellValue('A3', "Course: {$courseName} - Instructor: {$facultyName} - Class and Section: {$batch} - {$section} ");
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        

           

            // Add CLO Numbers (Row 4)
            $sheet->mergeCells('A4:C4');
            $sheet->setCellValue('A4', 'CLO No');
            $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->fromArray(
                ['CLO1', 'CLO2', 'CLO3', 'CLO4','CLO1', 'CLO2', 'CLO3', 'CLO4', '', 'CLO1', 'CLO2', 'CLO3', 'CLO4', '', 'CLO1', 'CLO2', 'CLO3', 'CLO4', ''],
                null,
                'D4'
            );

            $sheet->getStyle('D4')->applyFromArray($CLO1Color);
            $sheet->getStyle('E4')->applyFromArray($CLO2Color);
            $sheet->getStyle('F4')->applyFromArray($CLO3Color);
            $sheet->getStyle('G4')->applyFromArray($CLO4Color);

            $sheet->getStyle('H4')->applyFromArray($CLO1Color); // Assignment CLO1
            $sheet->getStyle('I4')->applyFromArray($CLO2Color); // Assignment CLO1
            $sheet->getStyle('J4')->applyFromArray($CLO3Color); // Assignment CLO1
            $sheet->getStyle('K4')->applyFromArray($CLO4Color);
             // Assignment CLO1
            $sheet->getStyle('M4')->applyFromArray($CLO1Color); // Assignment CLO1
            $sheet->getStyle('N4')->applyFromArray($CLO2Color); // Assignment CLO1
            $sheet->getStyle('O4')->applyFromArray($CLO3Color); // Assignment CLO1
            $sheet->getStyle('P4')->applyFromArray($CLO4Color); // Assignment CLO1
            
            $sheet->getStyle('R4')->applyFromArray($CLO1Color); // Assignment CLO1
            $sheet->getStyle('S4')->applyFromArray($CLO2Color); // Assignment CLO1
            $sheet->getStyle('T4')->applyFromArray($CLO3Color); // Assignment CLO1
            $sheet->getStyle('U4')->applyFromArray($CLO4Color); // Assignment CLO1



            function colorBlockRange($sheet, $startCol, $endCol, $startRow, $endRow, $styleArray) {
                for ($row = $startRow; $row <= $endRow; $row++) {
                    $range = "{$startCol}{$row}:{$endCol}{$row}";
                    $sheet->getStyle($range)->applyFromArray($styleArray);
                }
            }

            colorBlockRange($sheet, 'B', 'F', 5, $sheet->getHighestRow(), $assignmentColor);
           
             // Add CLO Numbers (Row 4)
             $sheet->mergeCells('A5:C5');
             $sheet->setCellValue('A5', 'Marks Catagory');
             $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            
            $sheet->mergeCells('D5:G5'); // Assignment (5 columns)
            $sheet->setCellValue('D5', 'Assignment');
            $sheet->getStyle('D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D5:G5')->applyFromArray($assignmentColor);
            $sheet->getStyle('D6:G6')->applyFromArray($assignmentColor);
            $sheet->getStyle('D7:G7')->applyFromArray($assignmentColor);
            $sheet->getStyle('D8:G8')->applyFromArray($assignmentColor);
            

            $sheet->mergeCells('H5:L5'); // Quiz (5 columns)
            $sheet->setCellValue('H5', 'Quiz');
            $sheet->getStyle('H5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H5:L5')->applyFromArray($quizColor);  
            $sheet->getStyle('H6:L6')->applyFromArray($quizColor);  
            $sheet->getStyle('H7:L7')->applyFromArray($quizColor);  
            $sheet->getStyle('H8:L8')->applyFromArray($quizColor);  
            
            $sheet->mergeCells('M5:Q5'); // Midterm (5 columns)
            $sheet->setCellValue('M5', 'Mid term Exam');
            $sheet->getStyle('M5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M5:Q5')->applyFromArray($MidColor);
            $sheet->getStyle('M6:Q6')->applyFromArray($MidColor);
            $sheet->getStyle('M7:Q7')->applyFromArray($MidColor);
            $sheet->getStyle('M8:Q8')->applyFromArray($MidColor);

            
            $sheet->mergeCells('R5:U5'); // Final (5 columns)
            $sheet->setCellValue('R5', 'Terminal Exam');
            $sheet->getStyle('R5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('R5:V5')->applyFromArray($finalColor);
            $sheet->getStyle('R6:V6')->applyFromArray($finalColor);
            $sheet->getStyle('R7:V7')->applyFromArray($finalColor);
            $sheet->getStyle('R8:V8')->applyFromArray($finalColor);
            $sheet->getStyle('W8')->applyFromArray($finalColor);

            $sheet->getStyle('X8')->applyFromArray($CLO1Color);
            $sheet->getStyle('Y8')->applyFromArray($CLO2Color);
            $sheet->getStyle('Z8')->applyFromArray($CLO3Color);
            $sheet->getStyle('AA8')->applyFromArray($CLO4Color);

            $sheet->getStyle('AC8:AE8')->applyFromArray($PLO);


        

            $sheet->mergeCells('A6:C6');
            $sheet->setCellValue('A6', 'Marks Weightage');
            $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->fromArray(
                ['15', '15', '15', '15','10', '10', '10', '10', '', '25', '25', '25', '25', '', '50', '50', '50', '50', ''],
                null,
                'D6'
            );


            $sheet->mergeCells('A7:C7');
            $sheet->setCellValue('A7', 'Maximum Marks');
            $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->fromArray(
                ['10', '10', '10', '10','5', '5', '5', '5', '25', '5', '5', '5', '10', '25', '10', '10', '15', '15', 'Total'],
                null,
                'D7'
            );
 
            $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->fromArray(
                ['S.No.','Registration No','Name','A1','A2','A3', 'A4','Q1', 'Q2', 'Q3', 'Q4', 'Total', 'Mid-1', 'Mid-2', 'Mid-3', 'Mid-4', 'Total', 'Final-1', 'Final-2', 'Final-3', 'Final-4', 'Total','Final','CLO1','CLO2','CLO3','CLO4','','PLO1','PLO2','PLO3'],
                null,
                'A8'
            );
            $sheet->getColumnDimension('A')->setWidth(6);   // S.No.
            $sheet->getColumnDimension('B')->setWidth(30);  // Registration No
            $sheet->getColumnDimension('C')->setWidth(25);  // Name
            

            $rowIndex = 9;  
            $sno = 1;  

            $groupedStudents = [];

            foreach ($studentsData as $assessment) {
                $student_id = $assessment->assessment->student_id ?? null;
                if ($student_id) {
                    $groupedStudents[$student_id][] = $assessment;
                }
            }

            foreach ($groupedStudents as $student_id => $assessments) {
                $firstAssessment = $assessments[0];
                $student = $firstAssessment->assessment->student ?? null;
                $studentdetail = $firstAssessment->assessment->studentdetail ?? null;

                if (!$student || !$studentdetail) continue;

                $reg_no = 'FUI/FURC/' . $studentdetail->batch . '-BSET-' . $studentdetail->roll_number;
                $name = $student->name ?? 'N/A';

                $assignments = array_fill(1, 4, 0); // A1 to A4
                $quizzes     = array_fill(1, 4, 0); // Q1 to Q4
                $midterms    = array_fill(1, 4, 0); // Mid-1 to Mid-4
                $finals      = array_fill(1, 4, 0); // Final-1 to Final-4

                foreach ($assessments as $assessment) {
                    $clo = $assessment->clo_number ?? "N/A";
                    $obtained = $assessment->obtained_marks ?? 0;
                    $type = strtolower($assessment->assessment->type ?? '');

                    $cloNumber = (int)str_replace('CLO', '', $clo);

                    switch ($type) {
                        case 'assignment':
                            if (isset($assignments[$cloNumber])) {
                                $assignments[$cloNumber] = $obtained;
                            }
                            break;

                        case 'quiz':
                            if (isset($quizzes[$cloNumber])) {
                                $quizzes[$cloNumber] = $obtained;
                            }
                            break;

                        case 'mid':
                            if (isset($midterms[$cloNumber])) {
                                $midterms[$cloNumber] = $obtained;
                            }
                            break;

                        case 'final':
                            if (isset($finals[$cloNumber])) {
                                $finals[$cloNumber] = $obtained;
                            }
                            break;
                    }
                }



                    $assignmentCount = count(array_filter($assignments, fn($v) => $v > 0));
                    $quizCount       = count(array_filter($quizzes, fn($v) => $v > 0));

                    // Sum of obtained marks
                    $totalAssignment = array_sum($assignments);
                    $totalQuiz       = array_sum($quizzes);

                    // Average (%) calculation
                    $assignmentAvg = $assignmentCount > 0 ? ($totalAssignment / ($assignmentCount))  : 0;
                    $quizAvg       = $quizCount > 0 ? ($totalQuiz / ($quizCount)) : 0;

                    // Total AQ average (simple average of both)
                    $totalAQAvg    = $assignmentAvg + $quizAvg;

                    $totalMid        = array_sum($midterms);
                    $totalFinal      = array_sum($finals);

                    $grandTotal      = $totalAQAvg + $totalMid + $totalFinal;


                    $clos = [];

                    for ($i = 1; $i <= 4; $i++) {
                        $assignmentMarks = $assignments[$i] ?? 0;
                        $quizMarks       = $quizzes[$i] ?? 0;
                        $midMarks        = $midterms[$i] ?? 0;
                        $finalMarks      = $finals[$i] ?? 0;

                        $obtained = $assignmentMarks + $quizMarks + $midMarks + $finalMarks;
                        $totalPossible = 15 + 10 + 5 + 10; // 100

                        $cloPercentage = $obtained > 0 ? ($obtained / $totalPossible) * 100 : 0;

                        $clos[$i] = round($cloPercentage, 2); // Round to 2 decimal places
                    }

                    
                    $plo1 = round(($clos[1] + $clos[2]) / 2, 1); // Assuming CLO1 & CLO2 map to PLO1
                    $plo2 = round(($clos[2] + $clos[3]) / 2, 1); // For example
                    $plo3 = round(($clos[3] + $clos[4]) / 2, 1); // Adjust mapping as needed


                    $rowData = [
                        $sno++, // S.No.
                        $reg_no,
                        $name,
                    
                        // A1–A4
                        $assignments[1] ?? 0,
                        $assignments[2] ?? 0,
                        $assignments[3] ?? 0,
                        $assignments[4] ?? 0,
                    
                        // Q1–Q4
                        $quizzes[1] ?? 0,
                        $quizzes[2] ?? 0,
                        $quizzes[3] ?? 0,
                        $quizzes[4] ?? 0,
                    
                        $totalAQAvg, // A+Q Total
                    
                        // Mid-1 to Mid-4
                        $midterms[1] ?? 0,
                        $midterms[2] ?? 0,
                        $midterms[3] ?? 0,
                        $midterms[4] ?? 0,
                    
                        $totalMid, // Mid Total
                    
                        // Final-1 to Final-4
                        $finals[1] ?? 0,
                        $finals[2] ?? 0,
                        $finals[3] ?? 0,
                        $finals[4] ?? 0,
                    
                        $totalFinal, // Final Total
                    
                        $grandTotal, // Grand Total
                    
                        $clos[1] ?? 0,
                        $clos[2] ?? 0,
                        $clos[3] ?? 0,
                        $clos[4] ?? 0, '', // CLO1–CLO4 + blank


                        $plo1,
                        $plo2,
                        $plo3 // PLO1–PLO3
                    ];

                $sheet->fromArray($rowData, null, 'A' . $rowIndex);

                // Apply styling
                $sheet->getStyle('D' . $rowIndex . ':G' . $rowIndex)->applyFromArray($assignmentColor);
                $sheet->getStyle('H' . $rowIndex . ':K' . $rowIndex)->applyFromArray($quizColor);
                $sheet->getStyle('M' . $rowIndex . ':P' . $rowIndex)->applyFromArray($MidColor);
                $sheet->getStyle('R' . $rowIndex . ':U' . $rowIndex)->applyFromArray($finalColor);
                $sheet->getStyle('W' . $rowIndex)->applyFromArray($FinaltotalColor);
                $sheet->getStyle('AC' . $rowIndex . ':AE' . $rowIndex)->applyFromArray($PLO);

                $rowIndex++;
            }
                    // Center-align all content in the sheet
            $sheet->getStyle($sheet->calculateWorksheetDimension())
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $sheet->getStyle($sheet->calculateWorksheetDimension())
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // You can use BORDER_MEDIUM for thicker border
                        'color' => ['argb' => 'FF000000'], // Solid Black
                    ],
                ],
            ]);
            // Save as Excel (XLSX)
            $filename = "OBE_report_" . now()->format('Y-m-d') . ".xlsx";
            $writer = new Xlsx($spreadsheet);

            return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            );
        }

        public function exportToWord(Request $request)
        {
            $course_id = $request->course_id;
            $faculty_id = $request->faculty_id;

            $studentsData = assessment_clo_detail::with(['assessment.student', 'assessment.studentdetail','assessment.Cource'])
                ->whereHas('assessment', function ($query) use ($course_id, $faculty_id) {
                    $query->where('course_id', $course_id)
                        ->where('teacher_id', $faculty_id);
                })
                ->get();

            if ($studentsData->isEmpty()) {
                return redirect()->back()->with('error', 'No assessment data found for the selected course and faculty.');
            }

            $courcesdetail = $studentsData->first();
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            $headerStyle = ['alignment' => 'center', 'spaceAfter' => Converter::pointToTwip(12)];
            $boldStyle = ['bold' => true];
            $section->addText('Course Review Report', $boldStyle, $headerStyle);
            $section->addText('Course Title:' . $courcesdetail->assessment->Cource->name ?? 'N/A');
            $section->addText('Pre-requisite: ' . $courcesdetail->assessment->Cource->pre_req ?? 'N/A');
            $section->addText('No of Students Registered: ' . $studentsData->pluck('assessment.student_id')->unique()->count());
            $section->addTextBreak(1);

            $gradeCounts = [
                'A+' => 0, 'A' => 0, 'B+' => 0, 'B' => 0,
                'C+' => 0, 'C' => 0, 'D+' => 0, 'D' => 0, 'F' => 0
            ];

            $cloTotals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $cloCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $notAchievedPLO = [];

            $groupedStudents = [];
            foreach ($studentsData as $assessment) {
                $student_id = $assessment->assessment->student_id ?? null;
                if ($student_id) {
                    $groupedStudents[$student_id][] = $assessment;
                }
            }

            foreach ($groupedStudents as $student_id => $assessments) {
                $firstAssessment = $assessments[0];
                $student = $firstAssessment->assessment->student ?? null;
                $studentdetail = $firstAssessment->assessment->studentdetail ?? null;
                if (!$student || !$studentdetail) continue;

                $assignments = array_fill(1, 4, 0);
                $quizzes     = array_fill(1, 4, 0);
                $midterms    = array_fill(1, 4, 0);
                $finals      = array_fill(1, 4, 0);

                foreach ($assessments as $assessment) {
                    $clo = $assessment->clo_number ?? "N/A";
                    $obtained = $assessment->obtained_marks ?? 0;
                    $type = strtolower($assessment->assessment->type ?? '');
                    $cloNumber = (int)str_replace('CLO', '', $clo);

                    switch ($type) {
                        case 'assignment':
                            if (isset($assignments[$cloNumber])) $assignments[$cloNumber] = $obtained;
                            break;
                        case 'quiz':
                            if (isset($quizzes[$cloNumber])) $quizzes[$cloNumber] = $obtained;
                            break;
                        case 'mid':
                            if (isset($midterms[$cloNumber])) $midterms[$cloNumber] = $obtained;
                            break;
                        case 'final':
                            if (isset($finals[$cloNumber])) $finals[$cloNumber] = $obtained;
                            break;
                    }
                }

                // $assignmentCount = count(array_filter($assignments, fn($v) => $v > 0));
                // $quizCount       = count(array_filter($quizzes, fn($v) => $v > 0));
                // $totalAssignment = array_sum($assignments);
                // $totalQuiz       = array_sum($quizzes);
                // $assignmentAvg = $assignmentCount > 0 ? ($totalAssignment / ($assignmentCount * 15)) * 100 : 0;
                // $quizAvg       = $quizCount > 0 ? ($totalQuiz / ($quizCount * 10)) * 100 : 0;
                // $totalAQAvg    = ($assignmentAvg + $quizAvg) / 2;
                // $totalMid      = array_sum($midterms);
                // $totalFinal    = array_sum($finals);
                // $grandTotal    = $totalAQAvg + $totalMid + $totalFinal;

                $assignmentCount = count(array_filter($assignments, fn($v) => $v > 0));
                    $quizCount       = count(array_filter($quizzes, fn($v) => $v > 0));

                    // Sum of obtained marks
                    $totalAssignment = array_sum($assignments);
                    $totalQuiz       = array_sum($quizzes);

                    // Average (%) calculation
                    $assignmentAvg = $assignmentCount > 0 ? ($totalAssignment / ($assignmentCount))  : 0;
                    $quizAvg       = $quizCount > 0 ? ($totalQuiz / ($quizCount)) : 0;

                    // Total AQ average (simple average of both)
                    $totalAQAvg    = $assignmentAvg + $quizAvg;

                    $totalMid        = array_sum($midterms);
                    $totalFinal      = array_sum($finals);

                    $grandTotal      = $totalAQAvg + $totalMid + $totalFinal;


                $clos = [];
                for ($i = 1; $i <= 4; $i++) {
                    $assignmentMarks = $assignments[$i] ?? 0;
                    $quizMarks       = $quizzes[$i] ?? 0;
                    $midMarks        = $midterms[$i] ?? 0;
                    $finalMarks      = $finals[$i] ?? 0;
                    $obtained = $assignmentMarks + $quizMarks + $midMarks + $finalMarks;
                    $totalPossible = 15 + 10 + 5 + 10;
                    $cloPercentage = $obtained > 0 ? ($obtained / $totalPossible) * 100 : 0;
                    $clos[$i] = round($cloPercentage, 2);
                    $cloTotals[$i] += $clos[$i];
                    $cloCounts[$i]++;
                }

                $plo1 = round(($clos[1] + $clos[2]) / 2, 1);
                $plo2 = round(($clos[2] + $clos[3]) / 2, 1);
                $plo3 = round(($clos[3] + $clos[4]) / 2, 1);

                if ($grandTotal >= 50 && ($plo1 < 50 || $plo2 < 50 || $plo3 < 50)) {
                    $notAchievedPLO[] = [
                        'name' => $student->name,
                        'plos' => implode(',', array_filter([
                            $plo1 < 50 ? 1 : null,
                            $plo2 < 50 ? 2 : null,
                            $plo3 < 50 ? 3 : null,
                        ]))
                    ];
                }

                $grade = match (true) {
                    $grandTotal >= 85 => 'A+',
                    $grandTotal >= 80 => 'A',
                    $grandTotal >= 75 => 'B+',
                    $grandTotal >= 70 => 'B',
                    $grandTotal >= 65 => 'C+',
                    $grandTotal >= 60 => 'C',
                    $grandTotal >= 55 => 'D+',
                    $grandTotal >= 50 => 'D',
                    default => 'F'
                };

                $gradeCounts[$grade]++;
            }

            // Add Grades Distribution Section
            // $section->addTextBreak(1);
            // $section->addText('1) Grades Distribution:', $boldStyle);

            // $gradeTable = $section->addTable([
            //     'borderSize' => 6,
            //     'borderColor' => '000000',
            //     'alignment' => 'left'
            // ]);

            // $gradeTable->addRow();
            // $gradeTable->addCell(2000)->addText('Grade', $boldStyle);
            // $gradeTable->addCell(2000)->addText('Count', $boldStyle);

            // foreach ($gradeCounts as $grade => $count) {
            //     $gradeTable->addRow();
            //     $gradeTable->addCell(2000)->addText($grade);
            //     $gradeTable->addCell(2000)->addText($count);
            // }

            $section->addTextBreak(1);
            $section->addText('1) Grades Distribution:', $boldStyle);

            $gradeTable = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'alignment' => 'left'
            ]);

            // Add header row with grade labels
            $gradeTable->addRow();
            foreach (array_keys($gradeCounts) as $grade) {
                $gradeTable->addCell(1000)->addText($grade, $boldStyle);
            }

            // Add row with count values
            $gradeTable->addRow();
            foreach ($gradeCounts as $count) {
                $gradeTable->addCell(1000)->addText($count);
            }



            // Add CLOs Achievement
            $section->addTextBreak(1);
            $section->addText('2) CLOs Achievement:', $boldStyle);
            for ($i = 1; $i <= 4; $i++) {
                $avg = $cloCounts[$i] > 0 ? round($cloTotals[$i] / $cloCounts[$i], 2) : 0;
                $section->addText("CLO{$i}: {$avg}%");
            }

            // Add PLO issues
            $section->addTextBreak(1);
            $section->addText('3) List of Students that did not achieve a PLO but passed the course:', $boldStyle);
            foreach ($notAchievedPLO as $item) {
                $section->addText($item['name'] . " - PLO: " . $item['plos']);
            }


            $section->addTextBreak(1);
            $section->addText('4) Instructor Comments:', ['bold' => true]);

            $commentTable = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'alignment' => 'left',
                'cellMargin' => 80
            ]);

            $bold = ['bold' => true];

            // Header row
            $commentTable->addRow();
            $commentTable->addCell(1000)->addText('S No.', $bold);
            $commentTable->addCell(4000)->addText('Question', $bold);
            $commentTable->addCell(8000)->addText('Comment', $bold);

            // Row 1
            $commentTable->addRow();
            $commentTable->addCell(1000)->addText('1.');
            $commentTable->addCell(4000)->addText("Comments on the achievement of CLO's");
            $commentTable->addCell(8000)->addText("All CLOs except CLO-2 have been successfully achieved.");

            // Row 2
            $commentTable->addRow();
            $commentTable->addCell(1000)->addText('2.');
            $commentTable->addCell(4000)->addText("Steps taken to address last years QEC observations");
            $commentTable->addCell(8000)->addText("CLOs added along with Complexity level for Mid/Final Paper Questions. Rubrics/reasons in marking added in best, average, worst assignment samples.");

            // Row 3
            $commentTable->addRow();
            $commentTable->addCell(1000)->addText('3.');
            $commentTable->addCell(4000)->addText("Preparedness of students for your class");
            $commentTable->addCell(8000)->addText("Students had some basic concepts required. But they lack Database concepts.");

            // Row 4
            $commentTable->addRow();
            $commentTable->addCell(1000)->addText('4.');
            $commentTable->addCell(4000)->addText("Engineering Design Problem");
            $commentTable->addCell(8000)->addText("Project of Cryptography Algorithms Implementation in Programming languages was assigned in Lab.");

            // Row 5
            $commentTable->addRow();
            $commentTable->addCell(1000)->addText('5.');
            $commentTable->addCell(4000)->addText("Any suggestion for improving the student learning process next time");
            $commentTable->addCell(8000)->addText("Database and Programming should be pre-requisite to this course.");


         


            $section->addTextBreak(1);
            $section->addText('4) Departmental Review:', ['bold' => true]);

            $reviewTable = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'alignment' => 'left',
                'cellMargin' => 80,
            ]);

            $bold = ['bold' => true];

            // Header Row
            $reviewTable->addRow();
            $reviewTable->addCell(700)->addText('S No.', $bold);
            $reviewTable->addCell(5000)->addText('Review Point', $bold);
            $reviewTable->addCell(800)->addText('Yes', $bold);
            $reviewTable->addCell(800)->addText('No', $bold);
            $reviewTable->addCell(3000)->addText('Remarks', $bold);

            // Rows (1–9)
            $reviewPoints = [
                'Course coverage (CLOs > 90 %)',
                'CLO achievement Satisfactory',
                'Cohort Failure if any',
                'Students feedback Satisfactory (> 50%)',
                'CLO Clearly Define',
                'Appropriate CLO to PLO Mapping',
                'Assessment carried out at desired taxonomy level or not',
                'Observation of QEC, if any, addressed',
                'Comments regarding Problem based learning',
            ];

            foreach ($reviewPoints as $i => $point) {
                $reviewTable->addRow();
                $reviewTable->addCell(700)->addText(($i + 1) . '.');
                $reviewTable->addCell(5000)->addText($point);
                $reviewTable->addCell(800)->addText(''); // Yes (blank)
                $reviewTable->addCell(800)->addText(''); // No (blank)
                $reviewTable->addCell(3000)->addText(''); // Remarks (blank)
            }


            $filename = "course_review_" . date('Y-m-d') . ".docx";
            $tempFile = tempnam(sys_get_temp_dir(), 'word');
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        }

    public function destroy($id)
    {
        $allocation = \App\Models\CourseAllocation::findOrFail($id);
        $allocation->delete();
        return redirect()->back()->with('success', 'Course allocation deleted successfully.');
    }
}

{{-- All_registered_student.blade --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/FUSSTLogo.jpg') }}">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Add Bootstrap Select for better multi-select UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>
    <style>
        /* Sidebar Styles */
        .sidebar {
            height: auto;
            background: linear-gradient(to bottom, #3C9AA5, #23546B);
            color: white;
            padding-top: 70px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border: none;
            text-align: left;
            transition: background-color 0.3s, transform 0.2s;
        }
        .sidebar a:hover {
            text-decoration: underline;
            transform: scale(1.05);
        }
        .btn-sidebar {
            width: 100%;
            margin-bottom: 10px;
            text-align: left;
            background: #23546B;
            padding-top: 10px;
        }
        .btn-sidebar.active {
            background: linear-gradient(to right, #3C9AA5, #23546B);
        }
        .mainbar{

        }

        /* Navbar and Page Layout */
        body {
            background-color: #E2ECF2 ;
        }
        .navbar {
            background: linear-gradient(to bottom, #23546B, #3C9AA5);
        }
        .logo {
            max-width: 300px;
            border-radius: 30px;
            mix-blend-mode: color-burn;
        }
        form {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px; /* Rounded corners for the form */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            margin-top: 50px;
        }
        label {
            font-weight: bold;
            color: black;
        }
        input[type="text"], input[type="email"], 
        input[type="password"],  select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            background-color: #3C9AA5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #23546B;
        }
        .icon-container {
            display: flex;
            flex-direction: row;
            align-items: flex-end; /* Aligns icons to the right */
            margin-left: auto; /* Pushes the icons to the far right */
        }
        .icon-container a {
            color: white;
            font-size: 24px;
            margin: 0 10px;
        }
        .mobilemenu{
                display: none;
        }  .faculty-table {
            width: 100%;
            margin-top: 20px;
            background-color: #ffffff;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .faculty-table th, .faculty-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .faculty-table th {
            background-color: #23546B;
            color: #ffffff;
            font-weight: bold;
        }
        .faculty-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .faculty-table tr:hover {
            background-color: #d3eaf2;
            cursor: pointer;
        }

        select.form-control {
            min-width: 250px;
            max-width: 450px;
            font-size: 1rem;
            padding: 8px 12px;
        }

          @media (max-width: 768px) {
            .logo {
            max-width: 200px;
            border-radius: 30px;
            mix-blend-mode: color-burn;
            }
            .sidebar{
                padding-top: 0;
                height: auto;
            }
            .text-center{
                font-size: 1.5rem;
            }
        }
        .question-card { background: #f8f9fa; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
        .question-card label { font-weight: 600; }
        .selectpicker { background: #fff !important; border: 1px solid #ced4da !important; }
        /* Fix Bootstrap Select dropdown width and wrap CLO descriptions */
        .bootstrap-select .dropdown-menu {
            min-width: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        .bootstrap-select .dropdown-menu .dropdown-item {
            white-space: normal !important;
            word-break: break-word;
        }
        /* Align all question card fields in a single row */
        .question-card .row.align-items-center > [class^='col-'] {
            display: flex;
            align-items: center;
        }
        .question-card .btn-link.text-danger {
            color: #dc3545 !important;
            text-decoration: none;
        }
        .question-card .btn-link.text-danger:hover {
            color: #a71d2a !important;
            background: none;
            text-decoration: none;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<div class="container-fluid p-0">
    <nav class="col-md-12 col-lg-12 navbar ">
        <div class="container-fluid">
            <img src="{{ asset('img/logo_wn.png') }}" alt="FUI Logo" class="logo img-fluid" >
            <div class="icon-container">
                <a href="admin_main.php">
                    <i class="fas fa-home"></i>
                </a>
                <a href="https://fusst.fui.edu.pk/" title="Information">
                    <i class="fas fa-info-circle"></i>
                </a>
                <a href="#" title="fusst@fui.edu.pk" data-toggle="tooltip" data-placement="left">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>
        </div>
    </nav>
    {{-- {{dd($dutyTasks)}} --}}

    <div class="row m-0" >
        <!-- Sidebar Section -->
        <div class="col-md-3 col-lg-2 sidebar">
            <a href="{{ route('lecturar.dashboard') }}" class="d-block btn btn-sidebar font-weight-bold" style="color: white">Home</a>

            {{-- <h3>{{ ucfirst($user->role->name) }} Dashboard</h3> --}}

            <!-- Show duties -->
            @if($duties->isNotEmpty())
            <div class="duties-section">
                @foreach($duties as $duty)
                    <a href="{{ route('duty.dashboard', $duty->name) }}" class="btn btn-sidebar font-weight-bold duty-link" style="color: white">
                        {{$duty->name }}
                    </a>
                @endforeach
            </div>
            @endif

            <!-- Student Management Section -->
            <button class="btn btn-sidebar font-weight-bold" data-toggle="collapse" style="color: white" data-target="#studentMenu">OBE Sheet</button>
            <div id="studentMenu" class="collapse">
                <a href="{{ route('add.QualityEnhancementCell') }}" class="d-block pl-4 py-1">Marks</a>
               <a href="{{ route('QualityEnhancementCell.list') }}" class="d-block pl-4 py-1">Generate CRRC</a>
            </div>

            <form id="logout-form" action="{{ route('faculty.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            
            <button class="btn btn-sidebar font-weight-bold" style="color: white" onclick="document.getElementById('logout-form').submit();">
                Sign out
            </button>
        </div>

        <!-- Main Content Section -->
            <div class="col-md-9 col-lg-10 mainbar">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(isset(
                    $errors
                ) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(trim($__env->yieldContent('content')))
                    <div id="dynamicContent">
                        @yield('content')
                    </div>
                @else
                <div id="defaultContent">
                    <h1 class="mt-4 text-center">Welcome to the {{ $designation }} Dashboard</h1>
                
                    @php
                        $id = session('course_id'); 
                        $cource = $CourseRegistration->first();    
                    @endphp
                
                <form method="POST" style="max-width: 100%;" action="{{ route('student.store_marks') }}" id="marksForm" onsubmit="return validateForm()">
                    @csrf
                
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">Add Marks</h3>
                    </div>
                
                    <div class="d-flex flex-column gap-4 mb-4">
                        <!-- Course Field -->
                        <div class="d-flex align-items-center" style="width: 48%; gap: 30px;">
                            <label for="course" style="font-size:22px; font-weight:600; width:300px;" class="form-label">Course</label>
                            <input type="text" id="course" value="{{ $cource->course->code }} - {{ $cource->course->name }}" class="form-control flex-grow-1" readonly>
                            <input type="hidden" name="course_id" value="{{ $cource->course_id }}" required>
                        </div>
                
                        <!-- Marks Type Field -->
                        <div class="d-flex align-items-center" style="width: 48%; gap: 30px;">
                            <label for="type" style="font-size:22px; font-weight:600; width:300px;" class="form-label">Select Marks Type</label>
                            <select id="type" name="type" class="form-control flex-grow-1" required>
                                <option value="">Select Assessment Type</option>
                                <option value="Quiz">Quiz</option>
                                <option value="Assignment">Assignment</option>
                                <option value="Mid">Mid</option>
                                <option value="Final">Final</option>
                            </select>
                        </div>

                        <div class="d-flex align-items-center" style="width: 48%; gap: 30px;">
                            <label for="title" style="font-size:22px; font-weight:600; width:300px;" class="form-label">Marks Title</label>
                            <input type="text" id="title" name="title" class="form-control flex-grow-1" placeholder="Enter Assessment Title" required>
                        </div>
                
                        <!-- CLO for Quiz/Assignment -->
                        <!-- Removed the single CLO dropdown for Quiz/Assignment -->
                        
                        <div id="single_total_marks" class="d-flex align-items-center" style="width: 48%; gap: 30px; display:none;">
                            <label class="form-label" style="font-size:22px; font-weight:600; width:300px;">Total Marks</label>
                            <input type="number" name="total_marks_single" class="form-control">
                        </div>
                
                        <!-- CLOs for Mid/Final -->
                        <div id="multiple_clo_section" style="display:none;">
                            <div class="mb-3">
                                <label style="font-size:22px; font-weight:600;">Select CLO(s)</label>
                                <select id="multi_clo_select" name="clo_number[]" class="form-control" multiple style="max-width: 450px;">
                                    @foreach($courseCLOs as $clo)
                                        @php
                                            $desc = $clo->description ?? '';
                                            $shortDesc = strlen($desc) > 60 ? substr($desc, 0, 60) . '...' : $desc;
                                        @endphp
                                        <option value="{{ $clo->clo }}" title="{{ $desc }}"><strong>{{ $clo->clo }}</strong>: {{ $shortDesc }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <div id="multi_total_marks_fields" class="d-flex flex-column gap-2">
                                <!-- Total marks inputs will be generated here -->
                            </div>
                        </div>
                    </div>
                
                    <!-- Questions Section (no obtained marks per question) -->
                    <div id="questions_container" class="mb-4"></div>
                    <button type="button" class="btn btn-success mb-3" onclick="addQuestionCard()">+ Add Question</button>

                    <table id="students_table" class="faculty-table table table-bordered">
                        <thead>
                            <tr id="table_head_row">
                                <th>S.NO.</th>
                                <th>Registration NO</th>
                                <th>Student Name</th>
                                <th>Obtained Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($CourseRegistration as $index => $Course)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>FUI/FURC/{{ $Course->courseAllocation->batch }}-BSET-{{ $Course->studentDetails->roll_number }}</td>
                                    <td>{{ $Course->student->name }}</td>
                                    <td class="marks_input_cell">
                                        <input type="number" class="form-control obtained-marks" 
                                               name="obtained_marks_single[{{ $Course->student_id }}]" 
                                               required min="0" step="0.01"
                                               onchange="validateMarks(this)">
                                        <input type="hidden" name="student_ids[]" value="{{ $Course->student_id }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Course Assigned.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Save All Marks</button>
                    </div>
                </form>
            </div>
            
            @endif
        </div>
        
</div>
<script>
    $(document).ready(function() {
        $(".mobilemenu").click(function() {
            $(".sidebar").toggleClass("active"); // Toggle sidebar visibility
            $(this).toggleClass("fa-bars fa-times"); // Toggle menu icon (bars ↔ close)
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assessmentTypeSelect = document.getElementById('type');
        const titleSelect = document.getElementById('title');

        const optionsData = {
            'Quiz': ['Quiz 1', 'Quiz 2', 'Quiz 3', 'Quiz 4'],
            'Assignment': ['Assignment 1', 'Assignment 2', 'Assignment 3', 'Assignment 4'],
            'Mid': ['Question 1', 'Question 2', 'Question 3', 'Question 4', 'Question 5'],
            'Final': ['Question 1', 'Question 2', 'Question 3', 'Question 4', 'Question 5'],
            'Lab': Array.from({length: 12}, (_, i) => `Lab ${i + 1}`),
        };

        assessmentTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            titleSelect.innerHTML = '<option value="">Select Assessment Title</option>'; // Reset

            if (optionsData[selectedType]) {
                optionsData[selectedType].forEach(function(title) {
                    const option = document.createElement('option');
                    option.value = title;
                    option.text = title;
                    titleSelect.appendChild(option);
                });
            }
        });
    });
</script>
<script>
function validateForm() {
    // Check if at least one question is added
    const questions = document.querySelectorAll('.question-card');
    if (questions.length === 0) {
        alert('Please add at least one question');
        return false;
    }

    // Check if all questions have required fields
    let isValid = true;
    questions.forEach((question, index) => {
        const title = question.querySelector(`input[name="questions[${index}][title]"]`);
        const clos = question.querySelector(`select[name="questions[${index}][clos][]"]`);
        const totalMarks = question.querySelector(`input[name="questions[${index}][total_marks]"]`);

        if (!title.value || !clos.value || !totalMarks.value) {
            alert(`Please fill all fields for Question ${index + 1}`);
            isValid = false;
        }
    });

    // Check if all students have obtained marks
    const obtainedMarks = document.querySelectorAll('.obtained-marks');
    obtainedMarks.forEach((input, index) => {
        if (!input.value) {
            alert(`Please enter obtained marks for student ${index + 1}`);
            isValid = false;
        }
    });

    return isValid;
}

function validateMarks(input) {
    const value = parseFloat(input.value);
    if (value < 0) {
        alert('Marks cannot be negative');
        input.value = 0;
    }
}

let questionIndex = 0;
function addQuestionCard() {
    const container = document.getElementById('questions_container');
    const cloOptions = document.getElementById('clo-options-template').innerHTML;
    const card = document.createElement('div');
    card.className = 'card mb-3 p-3 question-card';
    card.innerHTML = `
        <div class="row mb-2 font-weight-bold align-items-center">
            <div class="col-md-4 mb-1">Question Title/Number</div>
            <div class="col-md-3 mb-1">CLO(s)</div>
            <div class="col-md-2 mb-1">Total Marks</div>
            <div class="col-md-1 mb-1"></div>
        </div>
        <div class="row align-items-center mb-2 gx-2">
            <div class="col-md-4 mb-2">
                <input type="text" name="questions[${questionIndex}][title]" class="form-control" placeholder="Question Title/Number" required>
            </div>
            <div class="col-md-3 mb-2">
                <select name="questions[${questionIndex}][clos][]" class="form-control selectpicker" multiple data-actions-box="true" title="Select CLO(s)" required>
                    ${cloOptions}
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <input type="number" name="questions[${questionIndex}][total_marks]" class="form-control" placeholder="Total Marks" min="0" step="0.01" required>
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('.question-card').remove();">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(card);
    $(card).find('.selectpicker').selectpicker();
    questionIndex++;
}

// Initialize first question card on page load
window.onload = function() { 
    addQuestionCard();
    // Initialize Bootstrap Select
    $('.selectpicker').selectpicker();
};
</script>
<!-- Hidden CLO options for JS -->
<div id="clo-options-template" style="display:none;">
    @foreach($courseCLOs as $clo)
        @php
            $desc = $clo->description ?? '';
            $shortDesc = strlen($desc) > 40 ? substr($desc, 0, 40) . '...' : $desc;
        @endphp
        <option value="{{ $clo->clo }}" title="{{ $desc }}">CLO {{ $clo->clo }}: {{ $shortDesc }}</option>
    @endforeach
</div>
</body>
</html>


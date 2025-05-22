<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marks</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/FUSSTLogo.jpg') }}">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Existing styles */
        .sidebar {
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

        /* New styles for marks display */
        .marks-container {
            padding: 20px;
        }
        .assessment-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            overflow: hidden;
        }
        .assessment-header {
            background: linear-gradient(135deg, #23546B, #3C9AA5);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .assessment-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }
        .assessment-content {
            padding: 20px;
        }
        .marks-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 15px;
        }
        .marks-table th {
            background: #f8f9fa;
            color: #23546B;
            font-weight: 600;
            padding: 12px;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }
        .marks-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        .marks-table tr:hover {
            background-color: #f8f9fa;
        }
        .clo-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            margin: 2px;
            display: inline-block;
        }
        .total-marks {
            font-weight: 600;
            color: #23546B;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .marks-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .summary-label {
            font-weight: 600;
            color: #23546B;
        }
        .summary-value {
            color: #28a745;
            font-weight: 600;
        }
        .no-marks {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .no-marks i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #dee2e6;
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
        {{-- <div class="col-md-3 col-lg-2 sidebar" style="height: {{ empty($marksDetail) ? '100vh' : 'auto' }};"> --}}
            <div class="col-md-3 col-lg-2 sidebar" style="height: {{ $marksDetail->isEmpty() ? '100vh !important' : 'auto' }};">
            {{-- {{ dd($marksDetail); }} --}}
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

            <!-- OBE Sheet Section -->
            <button class="btn btn-sidebar font-weight-bold" data-toggle="collapse" style="color: white" data-target="#obeMenu">OBE Sheet</button>
            <div id="obeMenu" class="collapse show">
                <a href="#" class="d-block pl-4 py-1" data-toggle="modal" data-target="#generateObeModal">Generate OBE</a>
            </div>

            <!-- Modal for OBE Generation -->
            <div class="modal fade" id="generateObeModal" tabindex="-1" role="dialog" aria-labelledby="generateObeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="generateObeModalLabel">Generate OBE Sheet</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="GET" action="{{ route('obe.generate') }}">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="course_id">Select Course</label>
                                    <select class="form-control" id="course_id" name="course_id" required>
                                        <option value="">-- Select Course --</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Download OBE</button>
                            </div>
                        </form>
                    </div>
                </div>
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
            <div class="marks-container">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Student Assessment Marks</h3>
                </div>

                    @forelse($marksDetail as $index => $assessment)
                    <div class="assessment-card">
                        <div class="assessment-header">
                            <h5 class="assessment-title">
                                <i class="fas fa-file-alt mr-2"></i>
                                {{ $assessment->assessment_title ?? 'Untitled Assessment' }}
                                <span class="badge badge-light ml-2">{{ $assessment->type }}</span>
                            </h5>
                            </div>
                        
                        <div class="assessment-content">
                            <table class="marks-table">
                                <thead>
                                    <tr>
                                        <th>CLO</th>
                                        <th>Marks Obtained</th>
                                            <th>Total Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php 
                                        $totalMarks = 0;
                                        $obtainedMarks = 0;
                                    @endphp
                                    
                                    @foreach($assessment->marks as $mark)
                                        @php
                                            $totalMarks += $mark->total_marks;
                                            $obtainedMarks += $mark->obtained_marks;
                                        @endphp
                                        <tr>
                                            <td>{{ $mark->clo_number }}</td>
                                            <td class="total-marks">{{ $mark->obtained_marks }}</td>
                                            <td>{{ $mark->total_marks }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            <div class="marks-summary">
                                <div class="summary-item">
                                    <span class="summary-label">Total Marks Obtained:</span>
                                    <span class="summary-value">{{ $obtainedMarks }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Total Possible Marks:</span>
                                    <span class="summary-value">{{ $totalMarks }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Percentage:</span>
                                    <span class="summary-value">
                                        {{ $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0 }}%
                                    </span>
                                </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    <div class="no-marks">
                        <i class="fas fa-clipboard-list"></i>
                        <h4>No Assessment Marks Found</h4>
                        <p>No marks have been recorded for this student yet.</p>
                    </div>
                    @endforelse
                </div>
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




</body>
</html>






























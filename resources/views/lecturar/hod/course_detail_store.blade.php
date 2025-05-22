@extends('lecturar.dashboard')
@section('content')
<!-- Download Icon Dropdown OUTSIDE the container -->
<div style="position: absolute; top: 30px; right: 60px; z-index: 1000;">
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" id="downloadDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Download CDF">
            <i class="fas fa-download"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="downloadDropdown">
            <a class="dropdown-item" href="{{ route('program_manager.cdf.download', ['id' => $courses_detail->id, 'format' => 'word']) }}">Download as Word</a>
            <a class="dropdown-item" href="{{ route('program_manager.cdf.download', ['id' => $courses_detail->id, 'format' => 'pdf']) }}">Download as PDF</a>
        </div>
    </div>
</div>
<div class="container position-relative">
    <div class="position-absolute top-0 end-0 mt-3 me-3" style="z-index:10;">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Download CDF">
                <i class="bi bi-download"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="#" onclick="downloadCDF('pdf')">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Download as PDF
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="downloadCDF('docx')">
                        <i class="bi bi-file-earmark-word me-2"></i>Download as Word
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <form action="{{ route('lecturer.course.save') }}" method="POST" style="margin-top:20px; width: 100%; max-width: 900px; height: 700px; overflow-y: auto; padding: 20px; border: 1px solid #ccc; background-color: #fff; border-radius: 8px;">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6">
            <label for="course_id" class="form-label">Select Course</label>
            <select name="course_id" id="course_id" class="form-control" required onchange="updateCourseName()">
                <option value="" disabled selected>-- Select a Course --</option>
                @foreach($Course as $course)
                    <option value="{{ $course->id }}" data-name="{{ $course->name }}">{{ $course->code }} - {{ $course->name }}</option>
                @endforeach
            </select>
                </div>
                <div class="col-md-6">
                    <label for="course_code" class="form-label">Course Code</label>
                    <input type="text" class="form-control" id="course_code" name="course_code" required>
                </div>
            </div>
            
            <!-- Hidden field for course name -->
            <input type="hidden" name="course_name" id="course_name">
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="credit_hours" class="form-label">Credit Hours</label>
                    <input type="number" class="form-control" id="credit_hours" name="credit_hours" required>
            </div>
                    <div class="col-md-3">
                    <label for="theory_hours" class="form-label">Theory Hours</label>
                    <input type="number" class="form-control" id="theory_hours" name="theory_hours" required>
                    </div>
                <div class="col-md-3">
                    <label for="lab_hours" class="form-label">Lab Hours</label>
                    <input type="number" class="form-control" id="lab_hours" name="lab_hours" required>
                </div>
                <div class="col-md-3">
                    <label for="pre_req" class="form-label">Pre-Requisites</label>
                    <input type="text" class="form-control" id="pre_req" name="pre_req">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="co_req" class="form-label">Co-Requisites</label>
                    <input type="text" class="form-control" id="co_req" name="co_req">
                </div>
            </div>

            <!-- Course Introduction -->
            <div class="mb-4">
                <label for="course_intro" class="form-label">Course Introduction</label>
                <textarea name="course_intro" id="course_intro" class="form-control" rows="3" required></textarea>
            </div>

            <!-- Course Contents (Paragraph) -->
            <div class="mb-4">
                <label for="course_contents_paragraph" class="form-label">Course Contents (Paragraph)</label>
                <textarea name="course_contents_paragraph" id="course_contents_paragraph" class="form-control" rows="4"></textarea>
            </div>

            <!-- Course Learning Outcomes (CLOs) -->
            <h4>Course Learning Outcomes (CLOs)</h4>
            <table class="table table-bordered" id="clo-table">
                <thead class="table-light">
                    <tr>
                        <th>CLO</th>
                        <th>Description</th>
                        <th>Bloom's Level</th>
                        <th>PLO</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="clos[0][clo]" class="form-control" placeholder="e.g., CLO1"></td>
                        <td><input type="text" name="clos[0][description]" class="form-control" placeholder="Description"></td>
                        <td>
                            <select name="clos[0][bloom]" class="form-control">
                                <option value="">Select Level</option>
                                <option value="Remember">Remember</option>
                                <option value="Understand">Understand</option>
                                <option value="Apply">Apply</option>
                                <option value="Analyze">Analyze</option>
                                <option value="Evaluate">Evaluate</option>
                                <option value="Create">Create</option>
                            </select>
                        </td>
                        <td><input type="text" name="clos[0][plo]" class="form-control" placeholder="e.g., PLO1"></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm mb-3" id="add-clo">Add New CLO</button>

            <!-- Course Contents -->
            <h4>Topics Covered in the Course (Week-wise)</h4>
            <table class="table table-bordered" id="content-table">
                <thead class="table-light">
                    <tr>
                        <th>Week #</th>
                        <th>Topics</th>
                        <th>CLOs</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="number" name="contents[0][week]" class="form-control" min="1" max="16"></td>
                        <td><input type="text" name="contents[0][topic]" class="form-control" placeholder="Topic description"></td>
                        <td><input type="text" name="contents[0][clos]" class="form-control" placeholder="e.g., CLO1, CLO2"></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm mb-3" id="add-content">Add New Content</button>

            <!-- CLO-PLO Mapping -->
            <h4>Mapping of CLOs to PLOs</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>PLO</th>
                            <th>CLO1</th>
                            <th>CLO2</th>
                            <th>CLO3</th>
                            <th>CLO4</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=1; $i<=12; $i++)
                        <tr>
                            <td>PLO-{{ $i }}</td>
                            @for($j=1; $j<=4; $j++)
                            <td>
                                <select name="clo_plo[{{ $i }}][{{ $j }}]" class="form-control">
                                    <option value="">-</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </td>
                            @endfor
                        </tr>
                    @endfor
                    </tbody>
                </table>
            </div>

            <!-- Assessment Methods -->
            <h4>Assessment Mechanism</h4>
            <table class="table table-bordered" id="assessment-table">
                <thead class="table-light">
                    <tr>
                        <th>Assessment Type</th>
                        <th>Weightage (%)</th>
                        <th>CLOs</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="assessments[0][type]" class="form-control">
                                <option value="">Select Type</option>
                                <option value="Quizzes">Quizzes</option>
                                <option value="Assignments">Assignments</option>
                                <option value="Mid Term">Mid Term</option>
                                <option value="Final Term">Final Term</option>
                                <option value="Project">Project</option>
                            </select>
                        </td>
                        <td><input type="number" name="assessments[0][weightage]" class="form-control" min="0" max="100"></td>
                        <td><input type="text" name="assessments[0][clos]" class="form-control" placeholder="e.g., CLO1, CLO2"></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm mb-3" id="add-assessment">Add Assessment Method</button>

            <!-- References -->
            <div class="mb-4">
                <label for="references" class="form-label">Text and Reference Books</label>
                <textarea name="references" id="references" class="form-control" rows="3" placeholder="Enter references in APA format"></textarea>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success">Save Course Details</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Add new CLO row
    let cloIndex = 0;
    document.getElementById('add-clo').onclick = function() {
        const table = document.getElementById('clo-table').getElementsByTagName('tbody')[0];
        const row = table.insertRow();
        cloIndex++;
        row.innerHTML = `
            <td><input type='text' name='clos[${cloIndex}][clo]' class='form-control' placeholder='e.g., CLO1'></td>
            <td><input type='text' name='clos[${cloIndex}][description]' class='form-control' placeholder='Description'></td>
            <td>
                <select name='clos[${cloIndex}][bloom]' class='form-control'>
                    <option value=''>Select Level</option>
                    <option value='Remember'>Remember</option>
                    <option value='Understand'>Understand</option>
                    <option value='Apply'>Apply</option>
                    <option value='Analyze'>Analyze</option>
                    <option value='Evaluate'>Evaluate</option>
                    <option value='Create'>Create</option>
                </select>
            </td>
            <td><input type='text' name='clos[${cloIndex}][plo]' class='form-control' placeholder='e.g., PLO1'></td>
            <td><button type='button' class='btn btn-danger btn-sm remove-row'>Remove</button></td>`;
    };

    // Add new Content row
    let contentIndex = 0;
    document.getElementById('add-content').onclick = function() {
        const table = document.getElementById('content-table').getElementsByTagName('tbody')[0];
        const row = table.insertRow();
        contentIndex++;
        row.innerHTML = `
            <td><input type='number' name='contents[${contentIndex}][week]' class='form-control' min='1' max='16'></td>
            <td><input type='text' name='contents[${contentIndex}][topic]' class='form-control' placeholder='Topic description'></td>
            <td><input type='text' name='contents[${contentIndex}][clos]' class='form-control' placeholder='e.g., CLO1, CLO2'></td>
            <td><button type='button' class='btn btn-danger btn-sm remove-row'>Remove</button></td>`;
    };

    // Add new Assessment row
    let assessmentIndex = 0;
    document.getElementById('add-assessment').onclick = function() {
        const table = document.getElementById('assessment-table').getElementsByTagName('tbody')[0];
        const row = table.insertRow();
        assessmentIndex++;
        row.innerHTML = `
            <td>
                <select name='assessments[${assessmentIndex}][type]' class='form-control'>
                    <option value=''>Select Type</option>
                    <option value='Quizzes'>Quizzes</option>
                    <option value='Assignments'>Assignments</option>
                    <option value='Mid Term'>Mid Term</option>
                    <option value='Final Term'>Final Term</option>
                    <option value='Project'>Project</option>
                </select>
            </td>
            <td><input type='number' name='assessments[${assessmentIndex}][weightage]' class='form-control' min='0' max='100'></td>
            <td><input type='text' name='assessments[${assessmentIndex}][clos]' class='form-control' placeholder='e.g., CLO1, CLO2'></td>
            <td><button type='button' class='btn btn-danger btn-sm remove-row'>Remove</button></td>`;
    };

    // Remove row
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    // Update course name
    function updateCourseName() {
        const select = document.getElementById('course_id');
        const selectedOption = select.options[select.selectedIndex];
        const courseName = selectedOption.getAttribute('data-name');
        document.getElementById('course_name').value = courseName;
    }

    // Download CDF function
    function downloadCDF(format) {
        const courseId = document.getElementById('course_id')?.value;
        if (!courseId) {
            alert('Please select a course first');
            return;
        }
        window.location.href = `/lecturer/course/${courseId}/download-cdf?format=${format}`;
    }
</script>
@endsection

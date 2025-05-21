@extends('lecturar.dashboard')

@section('title', 'HOD Dashboard')

@section('content')
    <div class="topbar">
        <h2 class="heading">Available Courses</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('hod.plo.list') }}" class="btn innerbuttons" style="background: #317c8c; color: white; border: none;">View PLOs</a>
        </div>
    </div>
    <!-- Semester checkboxes -->
    <div class="semester-checkboxes">
        @for($i = 1; $i <= 8; $i++)
            <label>
                <input type="checkbox" name="semester[]" value="{{ $i }}" class="semester-checkbox" @if($i == 1) checked @endif>
                Semester {{ $i }}
            </label>
        @endfor
    </div>
    <!-- Courses Table -->
    <table class="faculty-table">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Semester</th>
                <th>Pre-req</th>
                <th>Credit-Hours</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody id="courses-body">
            <!-- Initial static content removed -->
        </tbody>
    </table>
    <script>
        $(document).ready(function () {
            function loadCourses() {
                var selectedSemesters = [];
                $('input[name="semester[]"]:checked').each(function () {
                    selectedSemesters.push($(this).val());
                });
                $.ajax({
                    url: '{{ route('get.hod.courses') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        semesters: selectedSemesters
                    },
                    success: function (response) {
                        var coursesBody = $('#courses-body');
                        coursesBody.empty();
                        if (response.length > 0) {
                            response.forEach(function (courseslist) {
                                var courseUrl = courseDetailUrlTemplate.replace('course_id', courseslist.id);
                                coursesBody.append(`
                                    <tr>
                                        <td>${courseslist.code}</td>
                                        <td>${courseslist.name}</td>
                                        <td>${courseslist.semester}</td>
                                        <td>${courseslist.pre_req || 'N/A'}</td>
                                        <td>${courseslist.Credit_Hours || 'N/A'}</td>
                                        <td>${courseslist.Status || 'N/A'}</td>
                                       <td><a href="${courseUrl}" class="btn btn-primary btn-sm">View Details</a></td>
                                    </tr>
                                `);
                            });
                        } else {
                            coursesBody.append('<tr><td colspan="7">No courses found.</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            }
            $('input[name="semester[]"]').on('change', loadCourses);
            loadCourses();
        });
    </script>
    <script>
        const courseDetailUrlTemplate = "{{ route('course_detail.edit', ['id' => 'course_id']) }}";
    </script>
@endsection

<style>
    .heading{
        margin:40px;
    }
    .topbar{
        display: flex;
        align-items: center;
        justify-content:space-between;
        margin-bottom: 10px;
    } 
    .innerbuttons {
    background: #317c8c !important;
    color: white !important; /* Ensures text is visible */
    border: none !important; /* Removes default Bootstrap border */
}
.faculty-table {
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
</style>



@extends('lecturar.dashboard')

@section('title', 'View Courses')
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
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}
.action-buttons form {
    display: inline;
    margin: 0;
    padding: 0;
    box-shadow: none !important;
    background: none !important;
}
.action-buttons .btn-warning {
    min-width: 60px;
}
.action-buttons .btn-danger {
    min-width: 60px;
}
</style>


@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="topbar">
        <h2 class="heading">Available Courses</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="/" title="Home" class="btn btn-light" style="font-size: 20px;"><i class="fas fa-home"></i></a>
            <a href="/contact" title="Contact" class="btn btn-light" style="font-size: 20px;"><i class="fas fa-envelope"></i></a>
            <a href="{{ route('newcourse.create') }}" class="btn innerbuttons">Add Course</a>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="courses-body"> <!-- Add ID here -->
            <!-- Initial static content removed -->
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            // Function to load courses
            function loadCourses() {
                var selectedSemesters = [];
                $('input[name="semester[]"]:checked').each(function () {
                    selectedSemesters.push($(this).val());
                });

                $.ajax({
                    url: '{{ route('get.courses') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        semesters: selectedSemesters
                    },
                    success: function (response) {
                        console.log('Response:', response);
                        var coursesBody = $('#courses-body');
                        coursesBody.empty();

                        if (response.length > 0) {
                            response.forEach(function (course) {
                                var courseUrl = courseDetailUrlTemplate.replace('course_id', course.id);
                                var editUrl = editCourseUrlTemplate.replace('course_id', course.id);
                                var deleteForm = `
                                    <form method="POST" action="${deleteCourseUrlTemplate.replace('course_id', course.id)}" style="display:inline;" class="delete-form">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                `;
                                coursesBody.append(`
                                    <tr id="course-row-${course.id}">
                                        <td>${course.code}</td>
                                        <td>${course.name}</td>
                                        <td>${course.semester}</td>
                                        <td>${course.pre_req ? (response.find(c => c.id == course.pre_req)?.name || 'N/A') : 'N/A'}</td>
                                        <td>${course.Credit_Hours || 'N/A'}</td>
                                        <td>${course.Status || 'N/A'}</td>
                                        <td>
                                            <a href="${courseUrl}" class="btn btn-primary btn-sm">View Details</a>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="${editUrl}" class="btn btn-warning btn-sm">Edit</a>
                                                ${deleteForm}
                                            </div>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            coursesBody.append('<tr><td colspan="8">No courses found.</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            }

            // Handle delete form submission
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                var form = $(this);
                var row = form.closest('tr');
                
                if(confirm('Are you sure you want to delete this course?')) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if(response.success) {
                                // Remove the row from the table
                                row.remove();
                                // Show success message
                                alert(response.message);
                                // Reload the courses list
                                loadCourses();
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting course: ' + (xhr.responseJSON?.message || 'Unknown error'));
                        }
                    });
                }
            });

            // Event listener for checkboxes
            $('input[name="semester[]"]').on('change', loadCourses);

            // Initial load
            loadCourses();
        });
    </script>
    <script>
        const courseDetailUrlTemplate = "{{ route('course_detail', ['id' => 'course_id']) }}";
        const editCourseUrlTemplate = "{{ route('editcourse', ['id' => 'course_id']) }}";
        const deleteCourseUrlTemplate = "{{ route('deletecourse', ['id' => 'course_id']) }}";
    </script>
    <script>
        function downloadCDF(courseId, format) {
            window.location.href = `/lecturer/course/${courseId}/download-cdf?format=${format}`;
        }
    </script>
@endsection



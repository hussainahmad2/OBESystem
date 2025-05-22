{{-- @extends('lecturar.dashboard')
@section('content')
<div class="container">
    @php
        $details = $courses_detail;
        $intro = $details->course_detail->first();
    @endphp
    <h2 class="mb-4">{{ $details->title ?? 'Course Title' }}</h2>

    <h4>Course Introduction & Objectives:</h4>
    <p>{{ $intro->intro_objectives ?? 'No introduction available.' }}</p>

    <h4 class="mt-5">Course Outcomes:</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>CLO</th>
                <th>Description</th>
                <th>Bloom's Level</th>
                <th>PLO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses_detail->course_outcome as $outcome)
                <tr>
                    <td>{{ $outcome->clo }}</td>
                    <td>{{ $outcome->description }}</td>
                    <td>{{ $outcome->Bloom'sLevel }}</td>
                    <td>{{ $outcome->PLO }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="mt-5">Course Contents:</h4>
    @foreach($courses_detail->course_content as $content)
        <h5>{{ $courses_detail->heading_number }}. {{ $content->heading_title }}</h5>
        <ul>
            @foreach($courses_detail->course_content_point->where('course_contents_id', $content->id) as $point)
                <li>{{ $point->description }}</li>
            @endforeach
        </ul>
    @endforeach






    <h4 class="mt-5">Practical Outcomes:</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>CLO</th>
                <th>Description</th>
                <th>Bloom's Level</th>
                <th>PLO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses_detail->practical_outcome as $practical)
                <tr>
                    <td>{{ $practical->clo }}</td>
                    <td>{{ $practical->description }}</td>
                    <td>{{ $practical->Bloom'sLevel }}</td>
                    <td>{{ $practical->PLO }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection --}}




@extends('lecturar.dashboard')
@section('content')
<!-- Download Icon Dropdown OUTSIDE the container -->
<div style="position: absolute; top: 30px; right: 60px; z-index: 1000;">
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" id="downloadDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Download CDF">
            <i class="fas fa-download"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="downloadDropdown">
            <a class="dropdown-item" href="{{ route('hod.cdf.download', ['id' => $courses_detail->id, 'format' => 'word']) }}">Download as Word</a>
            <a class="dropdown-item" href="{{ route('hod.cdf.download', ['id' => $courses_detail->id, 'format' => 'pdf']) }}">Download as PDF</a>
        </div>
    </div>
</div>
<div class="container position-relative">
    {{-- Download button removed as per request --}}
    <div class="d-flex justify-content-center">
        <form action="{{ route('lecturer.course.update', $courses_detail->id) }}" method="POST" style="margin-top:20px; width: 100%; max-width: 900px; height: 700px; overflow-y: auto; padding: 20px; border: 1px solid #ccc; background-color: #fff; border-radius: 8px;">
            @csrf
            {{-- Course Title and Intro --}}
            @php
                $details = $courses_detail;
                $intro = $details->course_detail->first();
            @endphp
            <div class="mb-4">
                <label for="title"><strong>Course Title:</strong></label>
                <input type="text" class="form-control" name="title" value="{{ $intro->title ?? '' }}">
            </div>

            <div class="mb-4">
                <label for="intro_objectives"><strong>Course Introduction & Objectives:</strong></label>
                <textarea class="form-control" name="intro_objectives" rows="4">{{ $intro->intro_objectives ?? '' }}</textarea>
            </div>

            {{-- Course Outcomes --}}
            <h4 class="mt-5">Course Outcomes:</h4>
            <table class="table table-bordered" id="outcomes-table">
                <thead>
                    <tr>
                        <th>CLO</th>
                        <th>Description</th>
                        <th>Bloom's Level</th>
                        <th>PLO</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses_detail->course_outcome as $index => $outcome)
                        <tr>
                            <td><input type="text" name="course_outcomes[{{ $index }}][clo]" value="{{ $outcome->clo }}" class="form-control"></td>
                            <td><input type="text" name="course_outcomes[{{ $index }}][description]" value="{{ $outcome->description }}" class="form-control"></td>
                            <td><input type="text" name="course_outcomes[{{ $index }}][bloom]" value="{{ $outcome->{"Bloom'sLevel"} }}" class="form-control"></td>
                            <td><input type="text" name="course_outcomes[{{ $index }}][plo]" value="{{ $outcome->PLO }}" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td><input type="text" name="course_outcomes[0][clo]" class="form-control"></td>
                            <td><input type="text" name="course_outcomes[0][description]" class="form-control"></td>
                            <td><input type="text" name="course_outcomes[0][bloom]" class="form-control"></td>
                            <td><input type="text" name="course_outcomes[0][plo]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm mb-3" id="add-outcome">Add New Outcome</button>

            {{-- Course Content --}}
            <h4 class="mt-5">Course Contents:</h4>
            <div id="contents-section">
                @forelse($courses_detail->course_content as $cIndex => $content)
                    <div class="mb-3 content-block">
                        <input type="text" name="course_contents[{{ $cIndex }}][heading_number]" value="{{ $content->heading_number }}" class="form-control mb-2" placeholder="Heading Number">
                        <input type="text" name="course_contents[{{ $cIndex }}][heading_title]" value="{{ $content->heading_title }}" class="form-control mb-2" placeholder="Heading Title">
                        @foreach($courses_detail->course_content_point->where('course_contents_id', $content->id) as $pIndex => $point)
                            <textarea name="course_contents[{{ $cIndex }}][points][{{ $pIndex }}]" class="form-control mb-2" rows="2">{{ $point->description }}</textarea>
                        @endforeach
                        <button type="button" class="btn btn-danger btn-sm remove-content">Remove</button>
                    </div>
                @empty
                    <div class="mb-3 content-block">
                        <input type="text" name="course_contents[0][heading_number]" class="form-control mb-2" placeholder="Heading Number">
                        <input type="text" name="course_contents[0][heading_title]" class="form-control mb-2" placeholder="Heading Title">
                        <textarea name="course_contents[0][points][0]" class="form-control mb-2" rows="2" placeholder="Content Point"></textarea>
                        <button type="button" class="btn btn-danger btn-sm remove-content">Remove</button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="btn btn-primary btn-sm mb-3" id="add-content">Add New Content</button>

            {{-- Practical Outcomes --}}
            <h4 class="mt-5">Practical Outcomes:</h4>
            <table class="table table-bordered" id="practicals-table">
                <thead>
                    <tr>
                        <th>CLO</th>
                        <th>Description</th>
                        <th>Bloom's Level</th>
                        <th>PLO</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses_detail->practical_outcome as $pIndex => $practical)
                        <tr>
                            <td><input type="text" name="practical_outcomes[{{ $pIndex }}][clo]" value="{{ $practical->clo }}" class="form-control"></td>
                            <td><input type="text" name="practical_outcomes[{{ $pIndex }}][description]" value="{{ $practical->description }}" class="form-control"></td>
                            <td><input type="text" name="practical_outcomes[{{ $pIndex }}][bloom]" value="{{ $practical->{"Bloom'sLevel"} }}" class="form-control"></td>
                            <td><input type="text" name="practical_outcomes[{{ $pIndex }}][plo]" value="{{ $practical->PLO }}" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td><input type="text" name="practical_outcomes[0][clo]" class="form-control"></td>
                            <td><input type="text" name="practical_outcomes[0][description]" class="form-control"></td>
                            <td><input type="text" name="practical_outcomes[0][bloom]" class="form-control"></td>
                            <td><input type="text" name="practical_outcomes[0][plo]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm mb-3" id="add-practical">Add New Practical Outcome</button>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
// Add new outcome row
let outcomeIndex = {{ max(0, ($courses_detail->course_outcome->count() ?? 0)) }};
document.getElementById('add-outcome').onclick = function() {
    const table = document.getElementById('outcomes-table').getElementsByTagName('tbody')[0];
    const row = table.insertRow();
    row.innerHTML = `<td><input type='text' name='course_outcomes[${++outcomeIndex}][clo]' class='form-control'></td>
        <td><input type='text' name='course_outcomes[${outcomeIndex}][description]' class='form-control'></td>
        <td><input type='text' name='course_outcomes[${outcomeIndex}][bloom]' class='form-control'></td>
        <td><input type='text' name='course_outcomes[${outcomeIndex}][plo]' class='form-control'></td>
        <td><button type='button' class='btn btn-danger btn-sm remove-row'>Remove</button></td>`;
};
// Remove outcome row
$(document).on('click', '.remove-row', function() {
    $(this).closest('tr').remove();
});
// Add new content block
let contentIndex = {{ max(0, ($courses_detail->course_content->count() ?? 0)) }};
document.getElementById('add-content').onclick = function() {
    const section = document.getElementById('contents-section');
    const div = document.createElement('div');
    div.className = 'mb-3 content-block';
    div.innerHTML = `<input type='text' name='course_contents[${++contentIndex}][heading_number]' class='form-control mb-2' placeholder='Heading Number'>
        <input type='text' name='course_contents[${contentIndex}][heading_title]' class='form-control mb-2' placeholder='Heading Title'>
        <textarea name='course_contents[${contentIndex}][points][0]' class='form-control mb-2' rows='2' placeholder='Content Point'></textarea>
        <button type='button' class='btn btn-danger btn-sm remove-content'>Remove</button>`;
    section.appendChild(div);
};
// Remove content block
$(document).on('click', '.remove-content', function() {
    $(this).closest('.content-block').remove();
});
// Add new practical row
let practicalIndex = {{ max(0, ($courses_detail->practical_outcome->count() ?? 0)) }};
document.getElementById('add-practical').onclick = function() {
    const table = document.getElementById('practicals-table').getElementsByTagName('tbody')[0];
    const row = table.insertRow();
    row.innerHTML = `<td><input type='text' name='practical_outcomes[${++practicalIndex}][clo]' class='form-control'></td>
        <td><input type='text' name='practical_outcomes[${practicalIndex}][description]' class='form-control'></td>
        <td><input type='text' name='practical_outcomes[${practicalIndex}][bloom]' class='form-control'></td>
        <td><input type='text' name='practical_outcomes[${practicalIndex}][plo]' class='form-control'></td>
        <td><button type='button' class='btn btn-danger btn-sm remove-row'>Remove</button></td>`;
};

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

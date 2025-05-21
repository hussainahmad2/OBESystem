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
<div class="container d-flex justify-content-center">
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
                @foreach($courses_detail->course_outcome as $index => $outcome)
                    <tr>
                        <td style="width: 15%;"><input type="text" name="course_outcomes[{{ $index }}][clo]" value="{{ $outcome->clo }}" class="form-control"></td>
                        <td style="width: 55%;"><input type="text" name="course_outcomes[{{ $index }}][description]" value="{{ $outcome->description }}" class="form-control"></td>
                        <td style="width: 15%;"><input type="text" name="course_outcomes[{{ $index }}][bloom]" value="{{ $outcome->{"Bloom'sLevel"} }}" class="form-control"></td>
                        <td style="width: 15%;"><input type="text" name="course_outcomes[{{ $index }}][plo]" value="{{ $outcome->PLO }}" class="form-control"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Course Content --}}
        <h4 class="mt-5">Course Contents:</h4>
        @foreach($courses_detail->course_content as $cIndex => $content)
            <div class="mb-3">
                <input type="text" name="course_contents[{{ $cIndex }}][heading_number]" value="{{ $content->heading_number }}" class="form-control mb-2" placeholder="Heading Number">
                <input type="text" name="course_contents[{{ $cIndex }}][heading_title]" value="{{ $content->heading_title }}" class="form-control mb-2" placeholder="Heading Title">

                @foreach($courses_detail->course_content_point->where('course_contents_id', $content->id) as $pIndex => $point)
                    <textarea name="course_contents[{{ $cIndex }}][points][{{ $pIndex }}]" class="form-control mb-2" rows="2">{{ $point->description }}</textarea>
                @endforeach
            </div>
        @endforeach

        {{-- Practical Outcomes --}}
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
                @if(
                    $courses_detail->practical_outcome &&
                    $courses_detail->practical_outcome->isNotEmpty() &&
                    !empty($courses_detail->practical_outcome[0]->description)
                )
                @foreach($courses_detail->practical_outcome as $pIndex => $practical)
                    <tr>
                        <td style="width: 15%;"><input type="text" name="practical_outcomes[{{ $pIndex }}][clo]" value="{{ $practical->clo }}" class="form-control"></td>
                        <td style="width: 55%;"><input type="text" name="practical_outcomes[{{ $pIndex }}][description]" value="{{ $practical->description }}" class="form-control"></td>
                        <td style="width: 15%;"><input type="text" name="practical_outcomes[{{ $pIndex }}][bloom]" value="{{ $practical->{"Bloom'sLevel"} }}" class="form-control"></td>
                        <td style="width: 15%;"><input type="text" name="practical_outcomes[{{ $pIndex }}][plo]" value="{{ $practical->PLO }}" class="form-control"></td>
                    </tr>
                @endforeach
                @endif
            </tbody>
        </table>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </form>
</div>
@endsection

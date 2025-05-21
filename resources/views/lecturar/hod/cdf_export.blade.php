<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CDF Export</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f8fb; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #ccc; }
        h2, h4, h5 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #888; padding: 8px; text-align: left; }
        th { background: #23546B; color: #fff; }
        ul { margin: 0 0 10px 20px; }
    </style>
</head>
<body>
<div class="container">
    <h2>{{ $intro->title ?? 'Course Title' }}</h2>
    <h4>Course Introduction & Objectives:</h4>
    <p>{{ $intro->intro_objectives ?? 'No introduction available.' }}</p>

    <h4>Course Outcomes:</h4>
    <table>
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
                <td>{{ $outcome->{"Bloom'sLevel"} }}</td>
                <td>{{ $outcome->PLO }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h4>Course Contents:</h4>
    @foreach($courses_detail->course_content as $content)
        <h5>{{ $content->heading_number }}. {{ $content->heading_title }}</h5>
        <ul>
            @foreach($courses_detail->course_content_point->where('course_contents_id', $content->id) as $point)
                <li>{{ $point->description }}</li>
            @endforeach
        </ul>
    @endforeach

    <h4>Practical Outcomes:</h4>
    <table>
        <thead>
            <tr>
                <th>CLO</th>
                <th>Description</th>
                <th>Bloom's Level</th>
                <th>PLO</th>
            </tr>
        </thead>
        <tbody>
        @if($courses_detail->practical_outcome && $courses_detail->practical_outcome->isNotEmpty() && !empty($courses_detail->practical_outcome[0]->description))
            @foreach($courses_detail->practical_outcome as $practical)
                <tr>
                    <td>{{ $practical->clo }}</td>
                    <td>{{ $practical->description }}</td>
                    <td>{{ $practical->{"Bloom'sLevel"} }}</td>
                    <td>{{ $practical->PLO }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
</body>
</html> 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .info-item {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
    </div>

    <!-- Course Information -->
    <div class="section">
        <div class="section-title">Course Information</div>
        <div class="info-item">
            <span class="info-label">Course Code:</span>
            <span>{{ $course->code }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Course Name:</span>
            <span>{{ $course->name }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Credit Hours:</span>
            <span>{{ $course->credit_hours }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Theory Hours:</span>
            <span>{{ $course->theory_hours }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Lab Hours:</span>
            <span>{{ $course->lab_hours }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Pre-requisites:</span>
            <span>{{ $course->pre_req }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Co-requisites:</span>
            <span>{{ $course->co_req }}</span>
        </div>
    </div>

    <!-- Course Introduction -->
    <div class="section">
        <div class="section-title">Course Introduction</div>
        <p>{{ $course->course_intro }}</p>
    </div>

    <!-- Course Objectives -->
    <div class="section">
        <div class="section-title">Course Objectives</div>
        <p>{{ $course->objectives }}</p>
    </div>

    <!-- Course Learning Outcomes -->
    <div class="section">
        <div class="section-title">Course Learning Outcomes (CLOs)</div>
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
                @foreach($course->clos as $clo)
                <tr>
                    <td>{{ $clo->clo }}</td>
                    <td>{{ $clo->description }}</td>
                    <td>{{ $clo->bloom }}</td>
                    <td>{{ $clo->plo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Course Contents -->
    <div class="section">
        <div class="section-title">Course Contents</div>
        <table>
            <thead>
                <tr>
                    <th>Week #</th>
                    <th>Topics</th>
                    <th>CLOs</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->contents as $content)
                <tr>
                    <td>{{ $content->week }}</td>
                    <td>{{ $content->topic }}</td>
                    <td>{{ $content->clos }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Assessment Methods -->
    <div class="section">
        <div class="section-title">Assessment Methods</div>
        <table>
            <thead>
                <tr>
                    <th>Assessment Type</th>
                    <th>Weightage (%)</th>
                    <th>CLOs</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->assessments as $assessment)
                <tr>
                    <td>{{ $assessment->type }}</td>
                    <td>{{ $assessment->weightage }}</td>
                    <td>{{ $assessment->clos }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- References -->
    <div class="section">
        <div class="section-title">References</div>
        <p>{{ $course->references }}</p>
    </div>
</body>
</html> 
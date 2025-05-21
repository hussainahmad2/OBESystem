@extends('lecturar.dashboard')

@section('title', 'Edit Course')

@section('content')
<div class="container mt-4">
    <h2>Edit Course</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('lecturer.course.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Course Name:</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $course->name }}" required>
        </div>

        <div class="form-group">
            <label for="code">Course Code:</label>
            <input type="text" class="form-control" name="code" id="code" value="{{ $course->code }}" required>
        </div>

        <div class="form-group">
            <label for="semester">Semester:</label>
            <select class="form-control" name="semester" id="semester" required>
                <option value="">-- Select Semester --</option>
                @for ($i = 1; $i <= 8; $i++)
                    <option value="{{ $i }}" {{ $course->semester == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label for="pre_req">Pre-Req:</label>
            <select class="form-control" name="pre_req" id="pre_req">
                <option value="">-- Choose Course --</option>
                <option value="0" {{ $course->pre_req == 0 ? 'selected' : '' }}>None</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ $course->pre_req == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Credit_Hours">Credit Hours:</label>
            <input type="number" class="form-control" name="Credit_Hours" id="Credit_Hours" value="{{ $course->Credit_Hours }}" required>
        </div>

        <div class="form-group">
            <label for="Status">Status:</label>
            <select class="form-control" name="Status" id="Status" required>
                <option value="Core" {{ $course->Status == 'Core' ? 'selected' : '' }}>Core</option>
                <option value="General Knowledge" {{ $course->Status == 'General Knowledge' ? 'selected' : '' }}>General Knowledge</option>
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Course</button>
            <a href="{{ route('course.list') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection 
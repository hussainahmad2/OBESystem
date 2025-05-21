@extends('lecturar.dashboard')

@section('title', 'Add Course')
<style>
   .topbar{
    display: flex;
    align-items: center;
    justify-content:space-between;
    margin-bottom: 10px;
   } 
   .innerbuttons{
    display: flex;
    align-items: center;
    justify-content:end;
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

@section('content')
    <h1></h1>
    <div id="defaultContent">
        <h1 class="mt-4 text-center">Welcome to the Cource Advisor Dashboard</h1>
        
        
       
        <table class="faculty-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cource</th>
                    <th>Batch</th>
                    {{-- <th>course Title</th> --}}
                    <th>Faculty</th>
                    <th>CLO Sheet</th>
                    <th>PLO Sheet</th>
                </tr>
            </thead>

            <tbody>
                @forelse($CourseRegistration as $index => $registration)
                    <tr>
                        <?php
                            // $studentData = \App\Models\Stu::where('user_id', $registration->student->id)->first();
                        ?>
                        <td>{{ $index + 1 }}</td> <!-- Serial Number -->
                        {{-- <td>{{ $registration->student->name }}</td> <!-- Course Title --> --}}
                        {{-- <td>{{ $registration->studentDetails->roll_number }}</td> <!-- Roll number --> --}}
                        <td>{{ $registration->course->name }} ({{ $registration->course->code }})</td> <!-- Course Title -->
                        <td>
                            @if(empty($registration->courseAllocation->section))
                                -
                            @else
                                {{ $registration->courseAllocation->batch }} - {{ $registration->courseAllocation->section }}
                            @endif
                        </td>
                        <td>{{ optional($registration->courseAllocation->faculty->user)->name ?? 'N/A' }}</td> <!-- Faculty Name -->
                        <td style="text-align: center;">
                            <form method="get" action="{{ route('marks.export') }}"
                                style="background-color: #00556c; padding:0; border-radius:20px; box-shadow:0; max-width:400px; width:80px; margin:0 auto;">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $registration->course_id }}">
                                <input type="hidden" name="faculty_id" value="{{ $registration->teacher_id ?? '' }}">
                                <button type="submit" style="color:#ffffff;" class="btn btn-sm">OBE</button>
                            </form>
                        </td>
                        <td style="text-align: center;">
                            <form method="get" action="{{ route('marks.export.word') }}"
                                style="background-color: #00556c; padding:0; border-radius:20px; box-shadow:0; max-width:400px; width:80px; margin:0 auto;">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $registration->course_id }}">
                                <input type="hidden" name="faculty_id" value="{{ $registration->teacher_id ?? '' }}">
                                <button type="submit" style="color:#ffffff;" class="btn btn-sm">PLO</button>
                            </form>
                        </td>
                        {{-- <td>
                            <a href="{{ route('courses.getStudentsWithLowPLO', ['course_id' => $registration->course_id, 'faculty_id' => $registration->teacher_id ?? '' ]) }}"
                            class="btn btn-sm"
                            style="color:#ffffff; background-color: #00556c; border-radius:20px; padding: 5px 10px; display: inline-block; text-align: center;">
                                Fail PLO
                            </a>
                        </td> --}}
                        {{-- <td style="text-align: center;">
                            <form method="get" action="{{ route('courses.getStudentsWithLowPLO') }}"
                                style="background-color: #00556c; padding:0; border-radius:20px; box-shadow:0; max-width:400px; width:80px; margin:0 auto;">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $registration->course_id }}">
                                <input type="hidden" name="faculty_id" value="{{ $registration->teacher_id ?? '' }}">
                                <button type="submit" style="color:#ffffff;" class="btn btn-sm">Fail PLO</button>
                            </form>
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-danger">No Cources found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
    </div>
@endsection
